import { createApp } from 'vue'
import App from './App.vue'
import keycloak from './keycloak'

const app = createApp(App)

keycloak.init({ onLoad: 'login-required' }).then(authenticated => {
  if (authenticated) {
    console.log("✅ Usuario autenticado:", keycloak.tokenParsed)
    app.config.globalProperties.$keycloak = keycloak
    app.mount('#app')
  } else {
    console.warn("❌ Usuario no autenticado")
  }
})

setInterval(() => {
    keycloak.updateToken(30).catch(() => {
      keycloak.login()
    })
}, 20000) // cada 20 segundos
  
