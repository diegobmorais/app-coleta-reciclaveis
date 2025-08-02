<script setup>
import { useRouter } from 'vue-router'
const router = useRouter()
</script>

<template>
  <div class="p-6 max-w-md mx-auto text-center">
    <h1 class="text-2xl font-bold text-green-600 mb-4">Agendamento Realizado!</h1>
    <p class="mb-6">Sua coleta foi agendada com sucesso. Entraremos em contato em breve.</p>
    <button @click="router.push('/')" class="bg-blue-600 text-white px-4 py-2 rounded">
      Voltar para Início
    </button>
  </div>
</template>