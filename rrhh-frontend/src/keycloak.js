import Keycloak from 'keycloak-js'

const keycloak = new Keycloak({
  url: 'http://localhost:9081',
  realm: 'poder-judicial-rn',
  clientId: 'rrhh-front'
})

export default keycloak
