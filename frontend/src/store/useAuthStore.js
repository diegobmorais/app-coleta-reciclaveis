import { defineStore } from 'pinia'
import axios from 'axios'


export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: localStorage.getItem('authToken') || null,
    user: null,
    isLoading: true,
  }),
  actions: {
    async login(credentials) {
      try {
        const response = await axios.post('/api/login', credentials)

        const token = response.data.token
        const user = response.data.user

        this.setToken(token)
        this.setUser(user)

        return true
      } catch (error) {
        return false
      }
    },

    async initializeAuth() {
      const token = localStorage.getItem('authToken')
      if (!token) {
        this.logout()
        return
      }

      this.token = token
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`

      try {
        const response = await axios.get('/api/user')
        this.setUser(response.data)
      } catch (e) {
        this.logout()
      } finally {
        this.isLoading = false
      }
    },

    setToken(token) {
      this.token = token
      localStorage.setItem('authToken', token)
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
    },
    setUser(user) {
      this.user = user
    },
    logout() {
      this.token = null
      this.user = null
      localStorage.removeItem('authToken')
      delete axios.defaults.headers.common['Authorization']
    }
  },
  getters: {
    isAuthenticated: (state) => !!state.token && !!state.user
  }
})