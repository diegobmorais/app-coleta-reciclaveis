import { defineStore } from 'pinia'
import axios from 'axios'

export const useMaterialStore = defineStore('materials', {
  state: () => ({
    materials: [],
    loading: false,
    error: null
  }),
  actions: {
    async fetchMaterials() {
      this.loading = true
      this.error = null
      try {
        const res = await axios.get('/api/materials')
        this.materials = res.data
      } catch (err) {
        this.error = err.response?.data?.message || 'Erro ao carregar materiais'
      } finally {
        this.loading = false
      }
    },
    async createMaterial(data) {
      try {
        const res = await axios.post('/api/materials', data)
        this.materials.push(res.data)
        return res.data
      } catch (err) {
        this.error = err.response?.data?.message || 'Erro ao criar material'
        throw err
      }
    },
    async updateMaterial(id, data) {
      try {
        const res = await axios.put(`/api/materials/${id}`, data)
        const index = this.materials.findIndex(m => m.id === id)
        if (index !== -1) this.materials[index] = res.data
        return res.data
      } catch (err) {
        this.error = err.response?.data?.message || 'Erro ao atualizar material'
        throw err
      }
    },
    async deleteMaterial(id) {
      try {
        await axios.delete(`/api/materials/${id}`)
        this.materials = this.materials.filter(m => m.id !== id)
      } catch (err) {
        this.error = err.response?.data?.message || 'Erro ao excluir material'
        throw err
      }
    }
  }
})