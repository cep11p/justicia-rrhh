import { createRouter, createWebHistory } from "vue-router";
import Liquidaciones from "../views/Liquidaciones.vue";

const routes = [
  { path: "/", redirect: "/liquidaciones" },
  { path: "/liquidaciones", name: "Liquidaciones", component: Liquidaciones },
];

export const router = createRouter({
  history: createWebHistory(),
  routes,
});
