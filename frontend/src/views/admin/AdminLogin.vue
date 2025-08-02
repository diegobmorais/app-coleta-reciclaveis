<template>
    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 to-emerald-100 px-4 py-12">
        <!-- Card de Login -->
        <div
            class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden transition-all duration-300 transform hover:shadow-2xl">
            <!-- Cabeçalho -->
            <div class="bg-gradient-to-r from-green-600 to-emerald-500 px-8 py-6 text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 bg-white bg-opacity-20 rounded-full mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-white">Área Administrativa</h1>
                <p class="text-green-100 opacity-90 mt-1">Acesso restrito</p>
            </div>
            <!-- Formulário -->
            <form @submit.prevent="handleSubmit" class="p-8 space-y-6">
                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">E-mail Corporativo</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.209a2.496 2.496 0 01-3 0" />
                            </svg>
                        </div>
                        <input v-model="form.email" type="email" placeholder="admin@coleta.com"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition" />
                    </div>
                </div>
                <!-- Senha -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Senha</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input v-model="form.password" type="password" placeholder="••••••••"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition" />
                    </div>
                </div>
                <!-- Mensagem de erro -->
                <div v-if="error"
                    class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ error }}</span>
                </div>
                <!-- Botão de login -->
                <button type="submit" :disabled="loading"
                    class="w-full bg-gradient-to-r from-green-600 to-emerald-500 hover:from-green-700 hover:to-emerald-600 disabled:from-gray-400 disabled:to-gray-500 text-white font-semibold py-3 rounded-lg shadow transition transform hover:scale-105 disabled:scale-100 disabled:cursor-not-allowed flex items-center justify-center space-x-2">
                    <span v-if="loading" class="animate-spin">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </span>
                    <span>{{ loading ? 'Entrando...' : 'Acessar Sistema' }}</span>
                </button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/store/useAuthStore'
import axios from 'axios'

const router = useRouter()
const authStore = useAuthStore()

const form = reactive({
    email: '',
    password: ''
})

const error = ref('')

const handleSubmit = async () => {
    error.value = ''
    try {
        const response = await axios.post('/api/login', form)
        authStore.setToken(response.data.token)
        router.push('/admin')
    } catch (err) {
        error.value = 'Credenciais inválidas'
    }
}
</script>