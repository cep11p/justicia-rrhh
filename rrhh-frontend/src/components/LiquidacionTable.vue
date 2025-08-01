<template>
  
  <div class="p-6 bg-gray-50 min-h-screen">
    <!-- Título -->
    <h2 class="text-2xl font-bold mb-6 text-gray-800">
      Listado de Liquidaciones
    </h2>

    <!-- Barra de búsqueda + botón -->
    <div class="mb-4 flex items-center gap-4">
      <input
        v-model="globalSearch"
        @input="buscar"
        type="text"
        placeholder="Buscar liquidación..."
        class="flex-1 border border-gray-300 rounded-lg px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
      />

      <button
        @click="isModalOpen = true"
        class="px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700"
      >
        ➕ Liquidación
      </button>
    </div>

    <!-- Modal -->
    <LiquidacionModal
      :isOpen="isModalOpen"
      :form="form"
      @close="isModalOpen = false"
      @save="crearLiquidacion"
    />

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
              {{ liq.empleado?.nombre_completo }}
            </td>
            <td class="px-6 py-4">{{ liq.empleado?.cuil }}</td>
            <td class="px-6 py-4 flex justify-center gap-4">
              <!-- Ver detalle -->
              <router-link 
                :to="`/liquidacion/${liq.id}`"
                class="text-blue-600 hover:text-blue-800"
                title="Ver detalle"
              >
                <EyeIcon class="h-5 w-5"/>
              </router-link>

              <!-- Eliminar -->
              <button
                @click="confirmarEliminacion(liq.id)"
                class="text-red-600 hover:text-red-800"
                title="Eliminar liquidación"
              >
                <TrashIcon class="h-5 w-5"/>
              </button>
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
import { getLiquidaciones, crearLiquidacionApi, deleteLiquidacion } from "@/services/LiquidacionesServices";
import LiquidacionModal from "@/components/LiquidacionModal.vue";
import { EyeIcon, TrashIcon } from "@heroicons/vue/24/outline";
import axios from "axios";


export default {
  name: "LiquidacionTable",
  components: {
    LiquidacionModal, // importamos el modal
    EyeIcon,
    TrashIcon,
  },
  data() {
    return {
      liquidaciones: [],
      globalSearch: "",
      page: 1,
      loading: false,
      hayMas: false,
      error: null,

      // estado del modal y formulario
      isModalOpen: false,
      form: {
        periodo: "",
        empleado: "",
      },
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

    async crearLiquidacion(nuevaLiq) {
      console.log("✅ Formulario recibido desde el modal:", nuevaLiq);

      try {
        this.loading = true;
        await crearLiquidacionApi(nuevaLiq);

        // cerrar modal
        this.isModalOpen = false;

        // resetear formulario
        this.form = { periodo: "", empleado: "" };

        // refrescar tabla
        await this.cargarLiquidaciones();

        // alerta de éxito
        alert("✅ Liquidación creada con éxito");
      } catch (error) {
        console.error("❌ Error al crear liquidación:", error);
        this.error =
          error.response?.data?.message || "⚠️ No se pudo crear la liquidación.";
      } finally {
        this.loading = false;
      }
    },

    async confirmarEliminacion(id) {
      if (!confirm("¿Estás seguro que querés eliminar esta liquidación?")) return;

      try {
        await deleteLiquidacion(id);
        this.liquidaciones = this.liquidaciones.filter(l => l.id !== id);
        alert("Liquidación eliminada correctamente ✅");
      } catch (error) {
        console.error("Error al eliminar liquidación:", error);
        alert("No se pudo eliminar la liquidación ❌");
      }
    },
  },
  mounted() {
    this.cargarLiquidaciones();
  },
};
</script>