import Keycloak from 'keycloak-js'

const keycloak = new Keycloak({
  url: import.meta.env.VITE_KEYCLOAK_URL,
  realm: 'poder-judicial-rn',
  clientId: 'rrhh-front'
})


export default keycloak
