<template>
  <div
    v-if="isOpen"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
  >
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-lg">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-semibold">Nueva Liquidación</h3>
        <button @click="$emit('close')" class="text-gray-500 hover:text-gray-700">
          ✖
        </button>
      </div>

      <form @submit.prevent="guardar">
        <div class="mb-4">
          <label class="block mb-1 text-sm font-medium text-gray-700">Periodo</label>
          <input
            v-model="localForm.periodo"
            type="text"
            placeholder="Ej: 202508"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
            required
          />
        </div>

        <div class="mb-4">
          <label class="block mb-1 text-sm font-medium text-gray-700">Empleado</label>
          <input
            v-model="localForm.empleado_id"
            type="text"
            placeholder="Nombre del empleado"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
            required
          />
        </div>

        <div class="flex justify-end gap-3">
          <button
            type="button"
            @click="$emit('close')"
            class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400"
          >
            Cancelar
          </button>
          <button
            type="submit"
            class="px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700"
          >
            Guardar
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from "vue";

const props = defineProps({
  isOpen: Boolean,
  form: Object
});
const emits = defineEmits(["close", "save"]);

const localForm = ref({ ...props.form });

// si cambia el form desde el padre, actualizamos el local
watch(
  () => props.form,
  (nuevo) => {
    localForm.value = { ...nuevo };
  },
  { deep: true }
);

function guardar() {
  emits("save", localForm.value);
}
</script>
