import './assets/tailwind.css';
import { createApp } from 'vue';
import App from './App.vue';
import { router } from './router';
import keycloak from './keycloak';
const app = createApp(App)

keycloak.init({ onLoad: 'login-required' })
  .then(authenticated => {
    if (authenticated) {
      console.log("✅ Usuario autenticado:", keycloak.tokenParsed);
      app.config.globalProperties.$keycloak = keycloak;
      app.use(router);
      app.mount('#app');

      // Refrescar token cada 60 segundos
      setInterval(() => {
        keycloak.updateToken(70).catch(() => {
          console.error("❌ No se pudo refrescar el token, redirigiendo al login");
          keycloak.login();
        });
      }, 60000);
    } else {
      console.warn("❌ Usuario no autenticado");
      keycloak.login();
    }
  })
  .catch(err => {
    console.error("❌ Error inicializando Keycloak:", err);
  });


  
