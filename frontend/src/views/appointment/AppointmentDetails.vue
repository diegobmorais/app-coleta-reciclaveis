<template>
    <div class="min-h-screen bg-gray-50 px-6 py-8 pt-20">
        <div class="max-w-4xl mx-auto">
            <!-- Cabeçalho Fixo -->
            <div
                class="sticky top-0 z-40 bg-white shadow-md rounded-b-lg mb-6 px-6 py-4 transition-transform duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Detalhes do Agendamento</h1>
                        <p class="text-gray-600 text-sm mt-1">Gerencie o status e observações da coleta.</p>
                    </div>
                    <button @click="router.push('/admin')"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Voltar</span>
                    </button>
                </div>
            </div>
            <!-- Loader -->
            <div v-if="!appointment" class="flex justify-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-green-500"></div>
            </div>
            <!-- Dados do Agendamento -->
            <div v-else class="bg-white shadow-lg rounded-lg overflow-hidden">
                <!-- Cabeçalho com protocolo e status -->
                <div class="bg-gradient-to-r from-green-600 to-emerald-500 px-8 py-6 text-white">
                    <div class="flex flex-wrap justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-bold">Protocolo: {{ appointment.protocol }}</h2>
                            <p class="opacity-90">Solicitado em {{ formatDate(appointment.created_at) }}</p>
                        </div>
                        <span
                            :class="`px-4 py-2 rounded-full text-sm font-semibold ${getStatusColor(appointment.status)}`">
                            {{ appointment.status }}
                        </span>
                    </div>
                </div>
                <!-- Conteúdo rolável -->
                <div class="p-8 space-y-6 max-h-screen overflow-y-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Dados do Solicitante</h3>
                            <div class="space-y-3 text-sm">
                                <p><strong>Nome:</strong> {{ appointment.full_name }}</p>
                                <p><strong>Telefone:</strong> {{ appointment.phone }}</p>
                                <p><strong>E-mail:</strong> {{ appointment.email || '–' }}</p>
                                <p><strong>Observações:</strong> {{ appointment.observation || '–' }}</p>
                            </div>
                        </div>
                        <!-- Endereço -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Endereço</h3>
                            <div class="space-y-3 text-sm">
                                <p>
                                    {{ appointment.street }}, {{ appointment.number }}<br>
                                    {{ appointment.neighborhood }}<br>
                                    {{ appointment.city }}
                                </p>
                                <p><strong>Data Sugerida:</strong> {{ formatDate(appointment.suggested_date) }}</p>
                            </div>
                        </div>
                    </div>
                    <!-- Materiais -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Materiais para Coleta</h3>
                        <div class="flex flex-wrap gap-2">
                            <span v-for="material in appointment.materials" :key="material.id"
                                class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs">
                                {{ material.name }} ({{ material.category }})
                            </span>
                        </div>
                    </div>
                    <!-- Status Atual -->
                    <div v-if="appointment.status_updated_at || appointment.status_observation"
                        class="bg-gray-50 p-4 rounded-lg">
                        <p><strong>Status Atual:</strong> {{ appointment.status }}</p>
                        <p><strong>Última atualização:</strong> {{ formatDate(appointment.status_updated_at) }} às {{
                            formatTime(appointment.status_updated_at) }}</p>
                        <StatusLogs :logs="appointment.status_logs" />
                    </div>
                </div>
                <!-- Ações -->
                <div class="px-8 py-6 bg-gray-50 border-t border-gray-200 text-right">
                    <button @click="openModal"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-lg shadow transition transform hover:scale-105">
                        Atualizar Status
                    </button>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div v-if="isModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Atualizar Status da Coleta</h3>
                </div>
                <form @submit.prevent="updateStatus" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Novo Status *</label>
                        <select v-model="status"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="Pendente">Pendente</option>
                            <option value="Agendado">Agendado</option>
                            <option value="Concluído">Concluído</option>
                            <option value="Cancelado">Cancelado</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Observação {{ isObservationRequired ? '*' : '(opcional)' }}
                        </label>
                        <textarea v-model="observation" rows="3" :class="[
                            'w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2',
                            errors.observation ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-green-500'
                        ]" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                            placeholder="Ex: Coleta agendada para terça-feira, cliente avisado por SMS">
                        </textarea>
                        <!-- Mensagem de erro -->
                        <p v-if="errors.observation" class="text-red-500 text-sm mt-1">{{ errors.observation }}</p>
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="closeModal"
                            class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAppointmentStore } from '@/store/useAppointmentStore'
import StatusLogs from '../../components/StatusLogs.vue'
import axios from 'axios'

const route = useRoute()
const router = useRouter()
const appointmentStore = useAppointmentStore()

const appointment = ref(null)
const isModalOpen = ref(false)
const status = ref('')
const observation = ref('')
const errors = ref({})

const isObservationRequired = computed(() => {
    return ['Concluído', 'Cancelado'].includes(status.value)
})

onMounted(() => fetchAppointment())

const fetchAppointment = async () => {
    try {
        const response = await axios.get(`/api/appointments/${route.params.id}`)
        appointment.value = response.data.data

    } catch (err) {
        if (err.response?.status === 404) {
            console.error('Agendamento não encontrado', err)
        } else {
            console.error('Erro ao carregar detalhes', err)
        }
    }
}

const openModal = (currentStatus = '', currentObservation = '') => {
    status.value = currentStatus
    observation.value = currentObservation || ''
    errors.value = {}
    isModalOpen.value = true
}

const closeModal = () => {
    isModalOpen.value = false
    errors.value = {}
}

const updateStatus = async () => {
    errors.value = {}
    if (isObservationRequired.value && !observation.value?.trim()) {
        errors.value.observation = 'A observação é obrigatória para este status.'
        return
    }
    try {
        await appointmentStore.updateStatus(appointment.value.id, status.value, observation.value)
        appointment.value.status = status.value
        appointment.value.status_observation = observation.value
        appointment.value.status_updated_at = new Date().toISOString()

        isModalOpen.value = false
    } catch (err) {
        alert('Erro ao atualizar status')
    }
}

const getStatusColor = (status) => {
    switch (status) {
        case 'Pendente': return 'bg-yellow-100 text-yellow-800'
        case 'Agendado': return 'bg-blue-100 text-blue-800'
        case 'Concluído': return 'bg-green-100 text-green-800'
        case 'Cancelado': return 'bg-red-100 text-red-800'
        default: return 'bg-gray-100 text-gray-800'
    }
}

const formatDate = (date) => {
    if (!date) return '–'
    return new Date(date).toLocaleDateString('pt-BR')
}

const formatTime = (date) => {
    if (!date) return '–'
    return new Date(date).toLocaleTimeString('pt-BR')
}
</script>
