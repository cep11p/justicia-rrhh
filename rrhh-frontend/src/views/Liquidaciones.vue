<template>
  <div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Gestión de Liquidaciones</h1>
    
    <!-- Reuso del componente -->
    <LiquidacionTable />
  </div>
</template>

<script>
import LiquidacionTable from "../components/LiquidacionTable.vue";

export default {
  name: "Liquidaciones",
  components: {
    LiquidacionTable,
  },
};
</script>
