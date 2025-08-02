import { defineStore } from 'pinia'
import axios from 'axios'

export const useAppointmentStore = defineStore('appointments', {
  state: () => ({
    appointments: [],
    lastAppointment: [],
    lastSuccess: false,
    loading: false,
    error: null
  }),
  actions: {
    async fetchAppointments() {
      this.loading = true
      this.error = null
      try {
        const response = await axios.get('/api/appointments')
        this.appointments = response.data.data
        this.lastSuccess = true
      } catch (err) {
        this.error = err.response?.data?.message || 'Erro ao carregar agendamentos'
      } finally {
        this.loading = false
      }
    },
    
    clearSuccess() {
      this.lastSuccess = false
      this.lastAppointment = null
    },

    async createAppointment(data) {
      this.loading = true
      this.error = null
      try {
        const response = await axios.post('/api/appointments', data)
        this.lastAppointment = response.data.appointment
        return response.data
      } catch (err) {
        this.error = err.response?.data?.message || 'Erro ao agendar coleta'
        throw err
      } finally {
        this.loading = false
      }
    },
    async updateStatus(id, status, observations = '') {
      this.loading = true
      this.error = null
      try {
        await axios.patch(`/api/appointments/${id}/status`, { status, observations })
        await this.fetchAppointments()
      } catch (err) {
        this.error = err.response?.data?.message || 'Erro ao atualizar status'
      } finally {
        this.loading = false
      }
    }
  }
})