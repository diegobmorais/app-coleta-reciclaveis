import { defineStore } from 'pinia'
import axios from 'axios'

export const useAppointmentStore = defineStore('appointments', {
  state: () => ({
    appointments: [],
    appointment: '',
    lastAppointment: [],
    lastSuccess: false,
    loading: false,
    error: null,
    filters: {
      search: '',
      status: '',
      suggested_date: '',
      protocol: ''
    },
    pagination: {
      currentPage: 1,
      lastPage: 1,
      perPage: 10,
      total: 0
    }
  }),
  actions: {
    async fetchAppointments() {
      this.loading = true
      this.error = null
      try {
        const params = {}
        if (this.filters.search) {
          params.search = this.filters.search
        }
        if (this.filters.status) {
          params.status = this.filters.status
        }
        if (this.filters.suggested_date) {
          params.suggested_date = this.filters.suggested_date
        }
        if (this.filters.protocol) {
          params.protocol = this.filters.protocol
        }
        params.page = this.pagination.currentPage
        params.per_page = this.pagination.perPage

        const response = await axios.get('/api/appointments', { params })
        this.appointments = response.data.data.map(app => ({
          ...app,
          status_logs: app.status_logs || []
        }))

        this.pagination = {
          currentPage: response.data.current_page,
          lastPage: response.data.last_page,
          perPage: response.data.per_page,
          total: response.data.total
        }
      } catch (err) {
        this.error = err.response?.data?.message || 'Erro ao carregar agendamentos'
      } finally {
        this.loading = false
      }
    },

    async fetchAppointmentById(id) {
      this.loading = true
      this.error = null
      try {
        const response = await axios.get(`/api/appointments/${id}`)
        this.appointment = response.data.data
        return response.data.data
      } catch (err) {
        if (err.response?.status === 404) {
          this.error = 'Agendamento não encontrado'
        } else {
          this.error = err.response?.data?.message || 'Erro ao carregar detalhes do agendamento'
        }
        throw err
      } finally {
        this.loading = false
      }
    },
    clearCurrentAppointment() {
      this.appointment = null
    },

    async createAppointment(data) {
      this.loading = true
      this.error = null
      try {
        const response = await axios.post('/api/appointments', data)
        this.lastAppointment = response.data.appointment
        this.lastSuccess = true
        return response.data
      } catch (err) {
        this.error = err.response?.data?.message || 'Erro ao agendar coleta'
        throw err
      } finally {
        this.loading = false
      }
    },

    clearSuccess() {
      this.lastSuccess = false
      this.lastAppointment = null
    },

    async updateStatus(id, status, observation = '') {
      this.loading = true
      this.error = null
      try {
        const response = await axios.patch(`/api/appointments/${id}/status`, { status, observation })

        if (this.appointment && this.appointment.id === id) {
          this.appointment = response.data.appointment
        }
        
        return response.data.appointment
      } catch (err) {
        this.error = err.response?.data?.message || 'Erro ao atualizar status'
        throw err 
      } finally {
        this.loading = false
      }
    },

    resetFiltersAndReload() {
      this.filters = {
        search: '',
        status: '',
        date: '',
        protocol: ''
      }
      this.pagination.currentPage = 1
    },

    setPage(page) {
      this.pagination.currentPage = page
      this.fetchAppointments()
    }
  },

})