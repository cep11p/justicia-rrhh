<template>
  
  <div class="p-6 bg-gray-50 min-h-screen">
    <!-- Título -->
    <h2 class="text-2xl font-bold mb-6 text-gray-800">
      Listado de Liquidaciones
    </h2>

    <!-- Buscador -->
    <div class="mb-4">
      <input
        v-model="globalSearch"
        @input="buscar"
        type="text"
        placeholder="Buscar liquidación (nombre, apellido, CUIL, período...)"
        class="w-full border border-gray-300 rounded-lg px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
      />
    </div>

    <!-- Tabla -->
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
      <table class="w-full text-sm text-left text-gray-700">
        <thead class="text-xs uppercase bg-blue-600 text-white">
          <tr>
            <th scope="col" class="px-6 py-3">Código</th>
            <th scope="col" class="px-6 py-3">Período</th>
            <th scope="col" class="px-6 py-3">Empleado</th>
            <th scope="col" class="px-6 py-3">CUIL</th>
            <th scope="col" class="px-6 py-3">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="liq in liquidaciones"
            :key="liq.id"
            class="odd:bg-white even:bg-gray-50 border-b hover:bg-blue-50 transition duration-150"
          >
            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
              {{ liq.id }}
            </td>
            <td class="px-6 py-4">{{ liq.periodo }}</td>
            <td class="px-6 py-4">
              {{ liq.empleado?.persona?.apellido }} {{ liq.empleado?.persona?.nombre }}
            </td>
            <td class="px-6 py-4">{{ liq.empleado?.persona?.cuil }}</td>
            <td class="px-6 py-4">
              <router-link 
                :to="`/liquidacion/${liq.id}`"
                class="text-blue-600 hover:underline font-medium"
              >
                Ver detalle
              </router-link>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Mensajes -->
    <div v-if="loading" class="flex justify-center mt-4 text-gray-500">
      ⏳ Cargando liquidaciones...
    </div>
    <div
      v-if="!loading && liquidaciones.length === 0"
      class="text-red-600 mt-4 text-center font-semibold"
    >
      {{ error || "⚠️ No se encontraron liquidaciones." }}
    </div>

    <!-- Paginación -->
    <div class="flex justify-between items-center mt-6">
      <button
        @click="cambiarPagina(page - 1)"
        :disabled="page <= 1"
        class="px-4 py-2 rounded-md bg-gray-200 hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed"
      >
        ⬅️ Anterior
      </button>
      <span class="text-gray-700 font-medium">Página {{ page }}</span>
      <button
        @click="cambiarPagina(page + 1)"
        :disabled="!hayMas"
        class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
      >
        Siguiente ➡️
      </button>
    </div>
  </div>
</template>

<script>
import { getLiquidaciones } from "@/services/LiquidacionesServices";

export default {
  name: "LiquidacionTable",
  data() {
    return {
      liquidaciones: [],
      globalSearch: "",
      page: 1,
      loading: false,
      hayMas: false,
      error: null,
    };
  },
  methods: {
    async cargarLiquidaciones() {
      this.loading = true;
      try {
        this.error = null;
        const data = await getLiquidaciones(this.globalSearch, this.page);
        this.liquidaciones = data.data ?? data;
        this.hayMas = data.meta
          ? data.meta.current_page < data.meta.last_page
          : false;
      } catch (error) {
        console.error("Error al cargar liquidaciones:", error);
        this.liquidaciones = [];
        this.error =
          error.response?.status === 401
            ? "Error de autenticación. Verificar token de Keycloak."
            : `Error al cargar liquidaciones: ${error.message}`;
      } finally {
        this.loading = false;
      }
    },
    buscar() {
      this.page = 1;
      this.cargarLiquidaciones();
    },
    cambiarPagina(nueva) {
      this.page = nueva;
      this.cargarLiquidaciones();
    },
  },
  mounted() {
    this.cargarLiquidaciones();
  },
};
</script>
