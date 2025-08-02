<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Sidebar (reutilizado) -->
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
                    <button @click="$router.push('/login')"
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
                <h1 class="text-3xl font-bold text-gray-800">Gerenciar Materiais Recicláveis</h1>
                <p class="text-gray-600 mt-1">Cadastre, edite ou remova tipos de materiais aceitos na coleta.</p>
            </header>

            <!-- Formulário -->
            <div class="bg-white p-6 rounded-lg shadow mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-5">
                    {{ editing ? 'Editar Material' : 'Novo Material' }}
                </h2>

                <form @submit.prevent="saveMaterial" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nome *</label>
                        <input v-model="form.name"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500"
                            placeholder="Ex: PET, Papelão, Alumínio" />
                        <p v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Categoria *</label>
                        <input v-model="form.category"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500"
                            placeholder="Ex: (eletrônico, Plastico, Papel ...)" />                       
                        <p v-if="errors.category" class="text-red-500 text-sm mt-1">{{ errors.category }}</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                        <textarea v-model="form.description" rows="3"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500"
                            placeholder="Ex: Garrafas PET limpas e sem rótulo"></textarea>
                        <p v-if="errors.description" class="text-red-500 text-sm mt-1">{{ errors.description }}</p>
                    </div>

                    <div class="md:col-span-2 flex gap-4">
                        <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg shadow transition">
                            {{ editing ? 'Atualizar' : 'Cadastrar' }}
                        </button>
                        <button type="button" @click="cancelEdit"
                            class="bg-gray-500 hover:bg-gray-600 text-white font-semibold px-6 py-2 rounded-lg transition"
                            v-if="editing">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Lista de Materiais -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-800">Materiais Cadastrados</h3>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nome</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Categoria</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descrição</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="m in materialStore.materials" :key="m.id" class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ m.name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">{{ m.category
                                }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ m.description || '–' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button @click="startEdit(m)" class="text-blue-600 hover:text-blue-900 mr-4 transition">
                                    Editar
                                </button>
                                <button @click="deleteMaterial(m.id)"
                                    class="text-red-600 hover:text-red-900 transition">
                                    Excluir
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</template>

<script setup>
import { onMounted, ref, reactive } from 'vue'
import { useMaterialStore } from '@/store/useMaterialStore'

const materialStore = useMaterialStore()

const editing = ref(null)
const form = reactive({ name: '', category: '', description: '' })
const errors = ref({})

onMounted(() => {
    materialStore.fetchMaterials()
})

const startEdit = (material) => {
    editing.value = material.id
    Object.assign(form, material)
}

const cancelEdit = () => {
    editing.value = null
    Object.assign(form, { name: '', category: '', description: '' })
    errors.value = {}
}

const saveMaterial = async () => {
    errors.value = {}
    try {
        if (editing.value) {
            await materialStore.updateMaterial(editing.value, { ...form })
        } else {
            await materialStore.createMaterial({ ...form })
        }
        cancelEdit()
        materialStore.fetchMaterials()
    } catch (err) {
        errors.value = err.response?.data?.errors || {}
    }
}

const deleteMaterial = async (id) => {
    try {
        await materialStore.deleteMaterial(id)
        if (editing.value === id) cancelEdit()
        window.$toast?.showToast('Material excluído com sucesso!')
    } catch (err) {
        twindow.$toast?.showToast('Erro ao excluir material.')
    }
}
</script>