<template>
  <div v-if="appointment" class="max-w-lg mx-auto bg-white shadow-xl rounded-lg p-8 border border-gray-100 pt-20">
    <!-- Ícone de sucesso -->
    <div class="text-center mb-6">
      <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24"
          stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
      </div>
      <h1 class="text-3xl font-bold text-gray-800">Agendamento Realizado!</h1>
      <p class="text-gray-600 mt-2">Seu pedido de coleta foi registrado com sucesso.</p>
    </div>
    <!-- Protocolo -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded">
      <p class="text-blue-800">
        <strong>Protocolo:</strong> <span class="font-mono">{{ appointment.protocol || 'Não gerado' }}</span>
      </p>
    </div>
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded">
      <p class="text-green-800">
        <strong>Status:</strong> <span class="font-mono">{{ appointment.status || 'Não gerado' }}</span>
      </p>
    </div>
    <!-- Dados do Agendamento -->
    <section class="space-y-4 text-sm text-gray-700">
      <h2 class="text-lg font-semibold text-gray-800 border-b pb-2">Detalhes do Agendamento</h2>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <strong>Nome:</strong> {{ appointment.full_name }}
        </div>
        <div>
          <strong>Telefone:</strong> {{ appointment.phone }}
        </div>
        <div>
          <strong>E-mail:</strong> {{ appointment.email || 'Não informado' }}
        </div>
        <div>
          <strong>Data:</strong> {{ formatDate(appointment.suggested_date) }}
        </div>
        <div class="md:col-span-2">
          <strong>Endereço:</strong>
          {{ appointment.street }}, {{ appointment.number }}
          <template v-if="appointment.neighborhood"> - {{ appointment.neighborhood }}</template>
          <template v-if="appointment.city">, {{ appointment.city }}</template>
        </div>
      </div>
      <!-- Materiais -->
      <div>
        <strong>Materiais para coleta:</strong>
        <ul class="list-disc list-inside mt-1 space-y-1">
          <li v-for="material in appointment.materials" :key="material">
            {{ material.name }}
          </li>
        </ul>
      </div>
      <!-- Observações -->
      <div v-if="appointment.observation">
        <strong>Observações:</strong>
        <p class="bg-gray-50 p-3 rounded mt-1 text-gray-800 italic">
          {{ appointment.observation }}
        </p>
      </div>
    </section>
    <div class="text-center mt-8">
      <button @click="router.push('/')"
        class="bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-3 rounded-lg shadow transition transform hover:scale-105">
        Voltar para Início
      </button>
    </div>
  </div>
  <!-- Fallback se não houver dados -->
  <div v-else class="p-6 max-w-md mx-auto text-center">
    <p class="text-red-600">Dados do agendamento não encontrados.</p>
    <button @click="router.push('/')" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">
      Voltar
    </button>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAppointmentStore } from '@/store/useAppointmentStore'
import { useMaterialStore } from '@/store/useMaterialStore'

const router = useRouter()
const appointmentStore = useAppointmentStore()
const materialStore = useMaterialStore()
const appointment = ref(null)


onMounted(() => {
  const lastAppointment = appointmentStore.lastAppointment 
  if (lastAppointment) {
    appointment.value = lastAppointment
  } else {   
    router.push('/')
  }

  if (materialStore.materials.length === 0) {
    materialStore.fetchMaterials()
  }
  appointmentStore.clearSuccess()
})

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('pt-BR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  })
}

const formatTime = (timeString) => {
  return timeString || 'Não informado'
}

const selectedMaterials = computed(() => {
  if (!appointment.value?.material_ids || materialStore.materials.length === 0) return []
  return materialStore.materials
    .filter(m => appointment.value.material_ids.includes(m.id))
    .map(m => `${m.name} (${m.category})`)
})
</script>

<style scoped>
.font-mono {
  font-family: 'Courier New', monospace;
  background: #f3f4f6;
  padding: 2px 6px;
  border-radius: 6px;
  font-size: 0.95em;
}
</style>