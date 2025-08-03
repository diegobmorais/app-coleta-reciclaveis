<template>
    <div>
        <button type="button" @click="isModalOpen = true"
            class="text-blue-600 hover:text-blue-800 text-sm font-medium underline">
            Histórico de Atualizações
        </button>
    </div>
    <!-- Modal -->
    <div v-if="isModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-96 flex flex-col">
            <div
                class="sticky top-0 z-20 bg-white px-6 py-4 border-b border-gray-200 flex justify-between items-center rounded-t-lg">
                <h3 class="text-lg font-semibold text-gray-800">Histórico de Status</h3>
                <button @click="isModalOpen = false"
                    class="text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-full p-1 transition"
                    aria-label="Fechar modal">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-6 space-y-4 overflow-y-auto flex-1">
                <div v-for="log in logs" :key="log.id" class="border-l-4 pl-4 py-3 rounded relative bg-gray-50">
                    <div :class="[
                        'absolute left-0 top-0 bottom-0 w-1 rounded-l',
                        log.status === 'Pendente' ? 'bg-yellow-500' :
                            log.status === 'Agendado' ? 'bg-blue-500' :
                                log.status === 'Concluído' ? 'bg-green-500' :
                                    log.status === 'Cancelado' ? 'bg-red-500' : 'bg-gray-500'
                    ]"></div>

                    <!-- Conteúdo do log -->
                    <div class="ml-2">
                        <div class="flex flex-wrap justify-between gap-2">
                            <strong :class="[
                                'px-2 py-0.5 text-xs rounded-full font-medium',
                                log.status === 'Pendente' ? 'bg-yellow-100 text-yellow-800' :
                                    log.status === 'Agendado' ? 'bg-blue-100 text-blue-800' :
                                        log.status === 'Concluído' ? 'bg-green-100 text-green-800' :
                                            log.status === 'Cancelado' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'
                            ]">
                                {{ log.status }}
                            </strong>
                            <span class="text-xs text-gray-500 whitespace-nowrap">
                                {{ formatDate(log.created_at) }} às {{ formatTime(log.created_at) }}
                            </span>
                        </div>

                        <p class="text-sm text-gray-700 mt-1">
                            <span class="font-medium">{{ getAuthorName(log) }}</span>
                            {{ getActionDescription(log) }}
                        </p>

                        <p v-if="log.observation"
                            class="text-sm mt-1 italic text-gray-600 bg-white text-xs p-2 rounded border-l-2 border-blue-200">
                            "{{ log.observation }}"
                        </p>
                    </div>
                </div>
                <p v-if="logs.length === 0" class="text-gray-500 text-center py-4">
                    Nenhum log registrado.
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
    logs: {
        type: Array,
        default: () => []
    }
})

const getAuthorName = (log) => {
    if (log.user) {
        return log.user.name
    }
    if (log.status === 'Pendente' && !log.user) {
        return "Cidadão"
    }
}

const getActionDescription = (log) => {
    if (log.status === 'Pendente' && !log.user) {
        return 'solicitou coleta'
    }
    return 'atualizou status'
}

const isModalOpen = ref(false)
const formatDate = (date) => new Date(date).toLocaleDateString('pt-BR')
const formatTime = (date) => new Date(date).toLocaleTimeString('pt-BR')
</script>