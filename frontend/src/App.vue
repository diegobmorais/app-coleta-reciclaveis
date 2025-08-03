<template>
  <!-- Cabeçalho -->
  <header class="bg-gradient-to-r from-green-800 to-emerald-700 text-white shadow-lg fixed top-0 left-0 w-full z-50">
    <div class="container mx-auto px-6 py-5 flex flex-wrap md:flex-nowrap items-center justify-between">    
      <RouterLink to="/">
        <div class="flex items-center space-x-3">
          <div class="bg-white p-2 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-700" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
          </div>
          <div>
            <h1 class="text-2xl font-bold">Coleta Sustentável</h1>
            <p class="text-green-100 text-sm opacity-90">Reciclagem que transforma</p>
          </div>
        </div>
      </RouterLink>
      <!-- Botão de Login -->
      <button @click="goToAdminArea"
        class="group bg-white text-green-800 font-semibold px-6 py-2.5 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105 flex items-center space-x-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-80 group-hover:rotate-12 transition-transform"
          fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
        </svg>
        <span>Área Administrativa</span>
      </button>
    </div>
  </header>
  <!-- Conteúdo Principal -->
  <main class="container mx-auto px-6 py-10">
    <RouterView />
  </main>
  <!-- Rodapé -->
  <footer class="bg-gray-900 text-gray-300 shadow-inner mt-auto border-t border-gray-800">
    <!-- ... conteúdo do footer ... -->
  </footer>
</template>

<script setup>
import { RouterLink, useRouter } from 'vue-router'
import { onMounted } from 'vue'
import { useAuthStore } from '@/store/useAuthStore'

const router = useRouter()

const goToAdminArea = () => {
  const isAuthenticated = !!localStorage.getItem('authToken')
  if (isAuthenticated) {
    router.push('/admin')
  } else {
    router.push('/login')
  }

  onMounted(async () => {
    const auth = useAuthStore()
    await auth.initializeAuth()
  })
}
</script>

<style>
/* Layout fluido */
body {
  margin: 0;
  min-height: 100vh;
  background-color: #f9fafb;
  font-family: 'Segoe UI', system-ui, sans-serif;
  display: flex;
  flex-direction: column;
}

#app {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

main {
  flex: 1;
}

footer {
  margin-top: auto;
}
</style>