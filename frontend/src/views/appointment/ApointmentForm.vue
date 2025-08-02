<template>
  <div class="max-w-4xl mx-auto bg-white shadow-xl rounded-lg overflow-hidden border border-gray-200 pt-20">
    <!-- Cabeçalho -->
    <div class="bg-gradient-to-r from-green-600 to-emerald-500 text-white px-8 py-6">
      <h1 class="text-3xl font-bold">Agende sua Coleta de Recicláveis</h1>
      <p class="opacity-90 mt-2">Preencha os dados abaixo para agendar a coleta em sua residência.</p>
    </div>
    <p v-if="errors.form" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6 text-sm">
      {{ errors.form }}
    </p>
    <form @submit.prevent="handleSubmit" class="p-8 space-y-8">
      <!-- Protocolo -->
      <div v-if="form.protocol" class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
        <p class="text-blue-800"><strong>Protocolo:</strong> {{ form.protocol }}</p>
      </div>
      <!-- Dados Pessoais -->
      <section>
        <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
          <span class="bg-green-100 p-2 rounded-full mr-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
          </span>
          Dados Pessoais
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nome Completo *</label>
            <input v-model="form.full_name"
              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500"
              placeholder="Seu nome completo" />
            <p v-if="errors.full_name" class="text-red-500 text-sm mt-1">{{ errors.full_name }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Telefone *</label>
            <input v-model="form.phone" @input="formatPhone"
              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500"
              placeholder="(11) 98765-4321" />
            <p v-if="errors.phone" class="text-red-500 text-sm mt-1">{{ errors.phone }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
            <input v-model="form.email" type="email"
              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500"
              placeholder="seu@email.com" />
          </div>
        </div>
      </section>
      <!-- Endereço -->
      <section>
        <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
          <span class="bg-blue-100 p-2 rounded-full mr-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
          </span>
          Endereço
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Logradouro *</label>
            <input v-model="form.street"
              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500"
              placeholder="Ex: Rua das Flores" />
            <p v-if="errors.street" class="text-red-500 text-sm mt-1">{{ errors.street }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Número *</label>
            <input v-model="form.number" type="text"
              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500"
              placeholder="123" />
            <p v-if="errors.number" class="text-red-500 text-sm mt-1">{{ errors.number }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Bairro *</label>
            <input v-model="form.neighborhood"
              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500"
              placeholder="Jardim Primavera" />
            <p v-if="errors.neighborhood" class="text-red-500 text-sm mt-1">{{ errors.neighborhood }}</p>
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Cidade *</label>
            <input v-model="form.city"
              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500"
              placeholder="São Paulo" />
            <p v-if="errors.city" class="text-red-500 text-sm mt-1">{{ errors.city }}</p>
          </div>
        </div>
      </section>
      <!-- Coleta -->
      <section>
        <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
          <span class="bg-purple-100 p-2 rounded-full mr-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
            </svg>
          </span>
          Detalhes da Coleta
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Data Sugerida *</label>
            <input v-model="form.suggested_date" type="date"
              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500" />
            <p v-if="errors.suggested_date" class="text-red-500 text-sm mt-1">{{ errors.suggested_date }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              Materiais para Coleta *
            </label>
            <!-- Dropdown Controlado -->
            <div ref="dropdownRef" class="relative">
              <button type="button" @click="toggleDropdown"
                class="w-full text-left px-4 py-3 border border-gray-300 rounded-lg bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 transition">
                <span v-if="form.material_id.length === 0" class="text-gray-500">Selecione os materiais...</span>
                <span v-else>
                  {{ selectedLabels }}
                </span>
              </button>
              <!-- Painel de opções -->
              <div v-show="isOpen"
                class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto"
                @click.stop>
                <!-- Carregando -->
                <div v-if="materialStore.loading" class="p-4 text-center text-sm text-gray-500">
                  Carregando materiais...
                </div>
                <!-- Erro -->
                <div v-else-if="materialStore.error" class="p-4 text-sm text-red-600 bg-red-50">
                  {{ materialStore.error }}
                </div>
                <!-- Lista de materiais -->
                <template v-else>
                  <div class="p-2 space-y-1">
                    <label v-for="material in materialStore.materials" :key="material.id"
                      class="flex items-center space-x-3 px-3 py-2 cursor-pointer hover:bg-green-50 rounded text-sm">
                      <input type="checkbox" :value="material.id" v-model="form.material_id"
                        class="h-4 w-4 text-green-600 rounded focus:ring-green-500" @change.stop />
                      <span class="flex-1">
                        {{ material.name }}
                        <em class="text-gray-500 text-xs">({{ material.category }})</em>
                      </span>
                    </label>
                    <p v-if="materialStore.materials.length === 0" class="p-4 text-center text-gray-500 text-sm">
                      Nenhum material cadastrado.
                    </p>
                  </div>
                </template>
              </div>
            </div>
            <!-- Mensagem de erro -->
            <p v-if="errors.material_id" class="text-red-500 text-sm mt-1">{{ errors.material_id }}</p>
          </div>
        </div>
        <div class="mt-6">
          <label class="block text-sm font-medium text-gray-700 mb-1">Observações (opcional)</label>
          <textarea v-model="form.observation" rows="3"
            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500"
            placeholder="Ex: Deixar na portaria, horário comercial, etc."></textarea>
        </div>
      </section>
      <div class="text-center pt-4">
        <button type="submit" :disabled="loading"
          class="bg-gradient-to-r from-green-600 to-emerald-500 hover:from-green-700 hover:to-emerald-600 text-white font-bold py-3 px-10 rounded-lg shadow transition transform hover:scale-105 disabled:opacity-60 disabled:transform-none">
          {{ loading ? 'Enviando...' : 'Agendar Coleta' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAppointmentStore } from '@/store/useAppointmentStore'
import { useMaterialStore } from '@/store/useMaterialStore'

const router = useRouter()
const appointmentStore = useAppointmentStore()
const materialStore = useMaterialStore()

materialStore.fetchMaterials()

const form = reactive({
  protocol: '',
  full_name: '',
  street: '',
  number: '',
  neighborhood: '',
  city: '',
  suggested_date: '',
  phone: '',
  email: '',
  observation: '',
  material_id: []
})

const errors = ref({})
const loading = ref(false)
const isOpen = ref(false)
const dropdownRef = ref()

const toggleDropdown = () => {
  isOpen.value = !isOpen.value
  if (isOpen.value && materialStore.materials.length === 0 && !materialStore.loading) {
    materialStore.fetchMaterials()
  }
}

const selectedLabels = computed(() => {
  const selected = materialStore.materials.filter(m => form.material_id.includes(m.id))
  if (selected.length === 0) return ''
  if (selected.length === 1) return selected[0].name
  return `${selected.length} - Materiais Selecionados`
})

const handleClickOutside = (event) => {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
    isOpen.value = false
  }
}

const validate = () => {
  errors.value = {}
  let isValid = true

  const requiredFields = {
    full_name: 'Nome completo é obrigatório.',
    street: 'Logradouro é obrigatório.',
    number: 'Número é obrigatório.',
    neighborhood: 'Bairro é obrigatório.',
    city: 'Cidade é obrigatória.',
    suggested_date: 'Data sugerida é obrigatória.',
    phone: 'Telefone é obrigatório.'
  }

  Object.keys(requiredFields).forEach(field => {
    const value = form[field]
    if (!value || (typeof value === 'string' && !value.trim())) {
      errors.value[field] = requiredFields[field]
      isValid = false
    }
  })

  if (form.phone && !/^\(\d{2}\) \d{5}-\d{4}$/.test(form.phone)) {
    errors.value.phone = 'Telefone inválido. Use o formato (11) 98765-4321.'
    isValid = false
  }

  if (form.suggested_date) {
    const selectedDate = new Date(form.suggested_date)
    const today = new Date()
    today.setHours(0, 0, 0, 0)

    const minDate = new Date(today)
    let daysAdded = 0
    while (daysAdded < 2) {
      minDate.setDate(minDate.getDate() + 1)
      const dayOfWeek = minDate.getDay()
      if (dayOfWeek !== 0 && dayOfWeek !== 6) {
        daysAdded++
      }
    }

    if (selectedDate < minDate) {
      const minDateStr = minDate.toLocaleDateString('pt-BR')
      errors.value.suggested_date = `A data deve ser pelo menos ${minDateStr}.`
      isValid = false
    }
  }

  if (!form.material_ids || form.material_ids.length === 0) {
    errors.value.material_ids = 'Selecione ao menos um material.'
    isValid = false
  }

  return isValid
}

const handleSubmit = async () => {
  if (!validate()) return

  loading.value = true
  try {
    const payload = {
      ...form,
      date: form.suggested_date,
    }
    await appointmentStore.createAppointment(payload)
    router.push('/success')
  } catch (err) {
    if (err.response?.status === 422) {
      const validationErrors = err.response.data.errors

      Object.keys(validationErrors).forEach(field => {

        const formField = {
          full_name: 'full_name',
          street: 'street',
          number: 'number',
          neighborhood: 'neighborhood',
          city: 'city',
          suggested_date: 'suggested_date',
          phone: 'phone',
          email: 'email',
          observation: 'observation',
          material_ids: 'material_id'
        }[field]

        if (formField) {
          errors.value[formField] = validationErrors[field][0]
        }
      })
    }
    else {
      errors.value.form = err.response?.data?.message || 'Erro ao enviar o formulário. Tente novamente.'
    }
  } finally {
    loading.value = false
  }
}

const formatPhone = (e) => {
  let value = e.target.value.replace(/\D/g, '')
  if (value.length <= 11) {
    value = value.replace(/^(\d{2})(\d)/g, '($1) $2')
    value = value.replace(/(\d{5})(\d)/, '$1-$2')
  }
  form.phone = value
}
onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script>