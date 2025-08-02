import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: localStorage.getItem('authToken') || null,
    user: null
  }),
  actions: {
    setToken(token) {
      this.token = token
      localStorage.setItem('authToken', token)
    },
    setUser(user) {
      this.user = user
    },
    logout() {
      this.token = null
      this.user = null
      localStorage.removeItem('authToken')
    }
  },
  getters: {
    isAuthenticated: (state) => !!state.token
  }
})