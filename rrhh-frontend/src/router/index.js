import { createRouter, createWebHistory } from "vue-router";

const routes = [
  { path: "/", redirect: "/liquidaciones" },
  { 
    path: "/liquidaciones", 
    name: "Liquidaciones", 
    component: () => import('@/views/Liquidaciones.vue'),
  },

  { 
    path: '/liquidacion/:id', 
    name: 'LiquidacionDetalle',
    component: () => import('@/views/LiquidacionDetalle.vue'),
  },
];

export const router = createRouter({
  history: createWebHistory(),
  routes,
});
