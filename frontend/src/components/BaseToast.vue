<template>
    <Transition name="toast-fade">
      <div v-if="visible" class="fixed top-4 right-4 z-50 bg-gray-800 text-white px-4 py-2 rounded shadow-lg">
        {{ message }}
      </div>
    </Transition>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  
  const visible = ref(false)
  const message = ref('')
  
  function showToast(msg, duration = 3000) {
    message.value = msg
    visible.value = true
  
    setTimeout(() => {
      visible.value = false
    }, duration)
  }
  
  defineExpose({ showToast })
  </script>
  
  <style scoped>
  .toast-fade-enter-active, .toast-fade-leave-active {
    transition: opacity 0.3s ease;
  }
  .toast-fade-enter-from, .toast-fade-leave-to {
    opacity: 0;
  }
  </style>
  