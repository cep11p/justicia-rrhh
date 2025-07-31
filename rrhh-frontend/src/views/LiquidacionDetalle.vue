<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { getLiquidacionById } from '@/services/LiquidacionesServices'

const route = useRoute()
const router = useRouter()

const liquidacion = ref(null)
const loading = ref(true)
const error = ref(null)

onMounted(async () => {
  try {
    liquidacion.value = await getLiquidacionById(route.params.id)
  } catch (e) {
    error.value = e.message || 'Error al cargar la liquidación'
  } finally {
    loading.value = false
  }
})

const formatCurrency = (value) => {
  return new Intl.NumberFormat('es-AR', { style: 'currency', currency: 'ARS' }).format(value)
}
</script>

<template>
  <div class="p-6 bg-gray-50 min-h-screen">
    <div v-if="loading" class="text-gray-500">⏳ Cargando liquidación...</div>
    <div v-else-if="error" class="text-red-600">{{ error }}</div>
    <div v-else-if="liquidacion" class="space-y-6">
      
      <!-- Encabezado -->
      <div class="bg-white shadow p-6 rounded-lg">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">
          Liquidación {{ liquidacion.periodo }} - Nº {{ liquidacion.numero }}
        </h1>
        <p class="text-gray-700"><strong>Empleado:</strong> {{ liquidacion.empleado.nombre_completo }}</p>
        <p class="text-gray-700"><strong>CUIL:</strong> {{ liquidacion.empleado.cuil }}</p>
        <p class="text-gray-700"><strong>Legajo:</strong> {{ liquidacion.empleado.legajo }}</p>
        <p class="text-gray-700"><strong>Fecha ingreso:</strong> {{ liquidacion.empleado.fecha_ingreso }}</p>
      </div>

      <!-- Remunerativos -->
      <div class="bg-white shadow p-6 rounded-lg">
        <h2 class="text-xl font-semibold text-blue-700 mb-3">Remunerativos</h2>
        <table class="w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
          <thead class="bg-blue-600 text-white">
            <tr>
              <th class="px-4 py-2 text-left">Código</th>
              <th class="px-4 py-2 text-left">Concepto</th>
              <th class="px-4 py-2 text-right">Importe</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="c in liquidacion.remunerativos" :key="c.id" class="odd:bg-white even:bg-gray-50">
              <td class="px-4 py-2">{{ c.concepto.codigo }}</td>
              <td class="px-4 py-2">{{ c.concepto.descripcion }}</td>
              <td class="px-4 py-2 text-right">{{ formatCurrency(c.importe) }}</td>
            </tr>
          </tbody>
          <tfoot>
            <tr class="bg-gray-100 font-bold">
              <td colspan="2" class="px-4 py-2 text-right">Total</td>
              <td class="px-4 py-2 text-right">{{ formatCurrency(liquidacion.total_remunerativos) }}</td>
            </tr>
          </tfoot>
        </table>
      </div>

      <!-- No Remunerativos -->
      <div class="bg-white shadow p-6 rounded-lg">
        <h2 class="text-xl font-semibold text-blue-700 mb-3">No Remunerativos</h2>
        <table class="w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
          <thead class="bg-blue-600 text-white">
            <tr>
              <th class="px-4 py-2 text-left">Código</th>
              <th class="px-4 py-2 text-left">Concepto</th>
              <th class="px-4 py-2 text-right">Importe</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="c in liquidacion.no_remunerativos" :key="c.id" class="odd:bg-white even:bg-gray-50">
              <td class="px-4 py-2">{{ c.concepto.codigo }}</td>
              <td class="px-4 py-2">{{ c.concepto.descripcion }}</td>
              <td class="px-4 py-2 text-right">{{ formatCurrency(c.importe) }}</td>
            </tr>
          </tbody>
          <tfoot>
            <tr class="bg-gray-100 font-bold">
              <td colspan="2" class="px-4 py-2 text-right">Total</td>
              <td class="px-4 py-2 text-right">{{ formatCurrency(liquidacion.total_no_remunerativos) }}</td>
            </tr>
          </tfoot>
        </table>
      </div>

      <!-- Neto a cobrar -->
      <div class="bg-green-50 border border-green-300 p-4 rounded-lg text-right text-xl font-bold text-green-800">
        Neto a cobrar: {{ formatCurrency(liquidacion.total_liquidado) }}
      </div>

      <!-- Botón volver -->
      <div class="text-center">
        <button
          @click="router.push('/liquidaciones')"
          class="mt-4 px-6 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition"
        >
          ⬅️ Volver al listado
        </button>
      </div>
    </div>
  </div>
</template>
