import { defineStore } from 'pinia'
import { axios } from 'axios'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: localStorage.getItem('authToken') || null,
    user: null
  }),
  actions: {
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
      router.push('/login')
    }
  },
  getters: {
    isAuthenticated: (state) => !!state.token
  }
})