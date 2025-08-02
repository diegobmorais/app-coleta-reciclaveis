<template>
    <div class="p-6 max-w-2xl">
        <h1 class="text-2xl font-bold mb-6">Detalhes do Agendamento</h1>
        <div class="bg-white border p-4 rounded shadow mb-6">
            <p><strong>Nome:</strong> {{ $route.params.id }}</p>
            <p><strong>Telefone:</strong> 3#</p>
            <p><strong>Endereço:</strong> 3#</p>
            <p><strong>Data:</strong> 3#</p>
            <p><strong>Materiais:</strong> 3#</p>
        </div>

        <form @submit.prevent="updateStatus" class="space-y-4">
            <div>
                <label>Status</label>
                <select v-model="status" class="w-full border p-2 rounded">
                    <option value="pendente">Pendente</option>
                    <option value="confirmado">Confirmado</option>
                    <option value="coletado">Coletado</option>
                    <option value="cancelado">Cancelado</option>
                </select>
            </div>
            <div>
                <label>Observações</label>
                <textarea v-model="observations" rows="3" class="w-full border p-2 rounded"></textarea>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                Atualizar Status
            </button>
        </form>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAppointmentStore } from '@/store/appointments'

const route = useRoute()
const router = useRouter()
const appointmentStore = useAppointmentStore()

const status = ref('')
const observations = ref('')

onMounted(async () => {
    await appointmentStore.fetchAppointments()
    const app = appointmentStore.appointments.find(a => a.id == route.params.id)
    if (!app) return router.push('/admin')
    status.value = app.status
    observations.value = app.observations || ''
})

const updateStatus = async () => {
    await appointmentStore.updateStatus(route.params.id, status.value, observations.value)
    router.push('/admin')
}
</script>