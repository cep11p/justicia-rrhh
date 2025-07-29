<template>
  <div class="p-4">
    <!-- Buscador -->
    <input
      v-model="globalSearch"
      @input="buscar"
      type="text"
      placeholder="Buscar liquidación (nombre, apellido, cuil, período...)"
      class="border px-2 py-1 rounded mb-3 w-full"
    />

    <!-- Tabla -->
    <table class="table-auto w-full border-collapse border border-gray-300">
      <thead>
        <tr class="bg-gray-100">
          <th class="border px-4 py-2">ID</th>
          <th class="border px-4 py-2">Período</th>
          <th class="border px-4 py-2">Empleado</th>
          <th class="border px-4 py-2">CUIL</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="liq in liquidaciones" :key="liq.id">
          <td class="border px-4 py-2">{{ liq.id }}</td>
          <td class="border px-4 py-2">{{ liq.periodo }}</td>
          <td class="border px-4 py-2">
            {{ liq.empleado?.persona?.apellido }} {{ liq.empleado?.persona?.nombre }}
          </td>
          <td class="border px-4 py-2">{{ liq.empleado?.persona?.cuil }}</td>
        </tr>
      </tbody>
    </table>

    <!-- Mensajes -->
    <p v-if="loading" class="text-gray-500 mt-2">Cargando...</p>
    <p v-if="!loading && liquidaciones.length === 0" class="text-red-500 mt-2">
      {{ error || 'No se encontraron liquidaciones.' }}
    </p>

    <!-- Paginación -->
    <div class="flex justify-between mt-4">
      <button @click="cambiarPagina(page - 1)" :disabled="page <= 1">
        Anterior
      </button>
      <span>Página {{ page }}</span>
      <button @click="cambiarPagina(page + 1)" :disabled="!hayMas">
        Siguiente
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
        this.error = null; // Limpiar errores previos
        const data = await getLiquidaciones(this.globalSearch, this.page);
        this.liquidaciones = data.data ?? data;
        this.hayMas = data.meta
          ? data.meta.current_page < data.meta.last_page
          : false;
      } catch (error) {
        console.error('Error al cargar liquidaciones:', error);
        this.liquidaciones = [];
        
        if (error.response?.status === 401) {
          // Error de autenticación
          this.error = 'Error de autenticación. Verificar token de Keycloak.';
          console.error('Error de autenticación. Verificar token de Keycloak.');
        } else {
          // Otro tipo de error
          this.error = `Error al cargar liquidaciones: ${error.message}`;
          console.error('Error al cargar liquidaciones:', error.message);
        }
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
