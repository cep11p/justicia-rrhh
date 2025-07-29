<script setup>
import { ref, onMounted } from 'vue'
import api from '../services/api'

const liquidaciones = ref([])
const loading = ref(true)
const error = ref(null)

onMounted(async () => {
  try {
    const response = await api.get('/liquidacion')
    liquidaciones.value = response.data
  } catch (err) {
    error.value = 'Error al cargar liquidacion'
    console.error(err)
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <div>
    <h1>Liquidaciones</h1>
    <p v-if="loading">Cargando...</p>
    <p v-else-if="error">{{ error }}</p>
    <ul v-else>
      <li v-for="liq in liquidaciones" :key="liq.id">
        {{ liq.empleado }} - ${{ liq.monto }}
      </li>
    </ul>
  </div>
</template>
