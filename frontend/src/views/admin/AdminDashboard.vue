<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Sidebar -->
        <aside class="bg-white shadow-sm w-64 min-h-screen fixed left-0 top-0 pt-20 border-r border-gray-200">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Administração</h2>
                <nav class="space-y-2">
                    <RouterLink to="/admin"
                        class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-green-50 hover:text-green-700 transition"
                        active-class="bg-green-100 text-green-700 font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span>Agendamentos</span>
                    </RouterLink>
                    <RouterLink to="/admin/materials"
                        class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-green-50 hover:text-green-700 transition"
                        active-class="bg-green-100 text-green-700 font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2" />
                        </svg>
                        <span>Materiais</span>
                    </RouterLink>
                </nav>
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <button @click="logout"
                        class="flex items-center space-x-3 w-full px-4 py-3 text-red-600 hover:bg-red-50 rounded-lg transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m-6 9h16a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Sair</span>
                    </button>
                </div>
            </div>
        </aside>

        <!-- Conteúdo Principal -->
        <main class="ml-64 p-8 pt-20">
            <header class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Dashboard de Agendamentos</h1>
                <p class="text-gray-600 mt-1">Gerencie as coletas agendadas pelos usuários.</p>
            </header>

            <!-- Filtros -->
            <div class="bg-white p-6 rounded-lg shadow mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Filtrar Agendamentos</h2>
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <!-- Busca -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                        <input v-model="appointmentStore.filters.search" @keyup.enter="applyFilters"
                            placeholder="Nome, protocolo..."
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" />
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select v-model="appointmentStore.filters.status" @change="applyFilters"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Todos</option>
                            <option value="Pendente">Pendente</option>
                            <option value="Agendado">Agendado</option>
                            <option value="Concluído">Concluído</option>
                            <option value="Cancelado">Cancelado</option>
                        </select>
                    </div>

                    <!-- Data -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Data</label>
                        <input v-model="appointmentStore.filters.suggested_date" type="date" @change="applyFilters"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" />
                    </div>
                    <!-- Ações -->
                    <div class="flex items-end space-x-2">
                        <button @click="applyFilters"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                            Filtrar
                        </button>
                        <button @click="resetFilters"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                            Limpar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Loader -->
            <div v-if="appointmentStore.loading" class="flex justify-center py-8">
                <div class="animate-spin rounded-full h-10 w-10 border-t-2 border-b-2 border-green-500"></div>
            </div>

            <!-- Erro -->
            <div v-else-if="appointmentStore.error"
                class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6">
                {{ appointmentStore.error }}
            </div>

            <!-- Tabela -->
            <div v-else class="bg-white shadow rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Protocolo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nome</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tipo de Materiais</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Data</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="app in appointmentStore.appointments" :key="app.id" class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ app.protocol || '–' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{
                                app.full_name
                                }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{app.materials.map(m => m.name).join(', ')}}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{
                                formatDate(app.suggested_date) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    :class="`px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusColor(app.status)}`">
                                    {{ app.status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <RouterLink :to="`/admin/appointments/${app.id}`"
                                    class="text-green-600 hover:text-green-900 transition">
                                    Detalhes
                                </RouterLink>
                            </td>
                        </tr>
                        <tr v-if="appointmentStore.appointments.length === 0">
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">Nenhum agendamento
                                encontrado.
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- Paginação -->
                <div v-if="appointmentStore.appointments.length > 0"
                    class="bg-white px-6 py-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-700">
                            Mostrando <span class="font-medium">{{ appointmentStore.pagination.perPage }}</span> de
                            <span class="font-medium">{{ appointmentStore.pagination.total }}</span> resultados
                        </p>
                        <div class="flex space-x-1">
                            <button v-for="page in appointmentStore.pagination.lastPage" :key="page"
                                @click="appointmentStore.setPage(page)" :class="[
                                    'px-3 py-1 text-sm rounded',
                                    page === appointmentStore.pagination.currentPage
                                        ? 'bg-green-600 text-white'
                                        : 'bg-gray-100 hover:bg-gray-200 text-gray-700'
                                ]">
                                {{ page }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAppointmentStore } from '@/store/useAppointmentStore'
import { useAuthStore } from '@/store/useAuthStore'

const router = useRouter()
const appointmentStore = useAppointmentStore()
const authStore = useAuthStore()

onMounted(() => {
    appointmentStore.fetchAppointments()
})

const logout = () => {
    authStore.logout()   
    router.push('/')
}

const getStatusColor = (status) => {
    switch (status) {
        case 'pendente': return 'bg-yellow-100 text-yellow-800'
        case 'confirmado': return 'bg-blue-100 text-blue-800'
        case 'coletado': return 'bg-green-100 text-green-800'
        case 'cancelado': return 'bg-red-100 text-red-800'
        default: return 'bg-gray-100 text-gray-800'
    }
}

// Formata data
const formatDate = (dateString) => {
    const date = new Date(dateString)
    return date.toLocaleDateString('pt-BR', { timeZone: 'UTC' })
}

// Aplica filtros
const applyFilters = () => {
    appointmentStore.pagination.currentPage = 1
    appointmentStore.fetchAppointments()
}

// Reseta filtros
const resetFilters = () => {
    appointmentStore.resetFiltersAndReload()
    appointmentStore.fetchAppointments()
}
</script>