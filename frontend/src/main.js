import './style.css'
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import pinia from './store'

// Configurar axios
import axios from 'axios'
axios.defaults.baseURL = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/'

const token = localStorage.getItem('authToken')
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
}

createApp(App).use(router).use(pinia).mount('#app')