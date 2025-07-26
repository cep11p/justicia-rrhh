## **Estructura para la Presentación:**

### **2 Estructuras Organizacionales:**
1. **Administración** - Gestión administrativa y procesos internos
2. **Recursos Humanos** - Gestión del personal y desarrollo organizacional

### **4 Cargos (2 por estructura):**
- **Administración:**
  - Administrativo Senior (con función)
  - Asistente Administrativo (sin función)

- **Recursos Humanos:**
  - Analista de RRHH (con función)
  - Asistente de RRHH (sin función)

### **10 Empleados Distribuidos:**
- **Administración (5 empleados):**
  - 2 Administrativos Senior
  - 3 Asistentes Administrativos

- **Recursos Humanos (5 empleados):**
  - 2 Analistas de RRHH
  - 3 Asistentes de RRHH

## **Despliegue Inicial del Proyecto**

### **1. Requisitos Previos**
- Docker y Docker Compose instalados en tu máquina.
- Puertos disponibles: **9080** (Nginx), **9081** (Keycloak), **3390** (MariaDB).

### **2. Levantar los Servicios con Docker Compose**

Ejecuta el siguiente comando para construir y levantar los contenedores en segundo plano:
docker compose up --build -d

### **3. Obtener IP de la Base de Datos**

Obtén la IP de Gateway de la base de datos para configurar el archivo .env:
docker inspect justicia-db | grep Gateway
# Copia la IP que aparece en "Gateway"

### **4. Configurar Archivo .env**

Copia el archivo de ejemplo y configúralo:
cp .env.example .env

**Configuración mínima en `.env`:**env
APP_NAME="Justicia RRHH"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:9080

DB_CONNECTION=mysql
DB_HOST=<IP_DE_GATEWAY_OBTENIDA_EN_PASO_3>
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret

### **5. Instalar Dependencias de PHP**

docker compose exec app composer install

### **6. Generar Clave de Aplicación**

docker compose exec app php artisan key:generate

### **7. Configurar Permisos de Storage**

docker compose exec app chmod -R 775 storage bootstrap/cache

### **8. Ejecutar Migraciones y Seeders**

docker compose exec app php artisan migrate --seed

### **9. Verificar Keycloak**

1. Acceder a http://localhost:9081
2. Login con `admin` / `admin`
3. Verificar que el realm `poder-judicial-rn` existe
4. Verificar que los usuarios de prueba están disponibles:
   - `admin` / `admin123` (rol: realm-admin)
   - `rrhh_user` / `rrhh123` (rol: uma_authorization)

### **10. Verificar que Todo Funciona**

- **Aplicación**: http://localhost:9080
- **API**: http://localhost:9080/api
- **Keycloak**: http://localhost:9081
- **Base de datos**: localhost:3390

## **Comandos Útiles**

### **Gestión de Servicios**
# Ver logs
docker compose logs -f

# Parar servicios
docker compose down

# Reiniciar un servicio específico
docker compose restart keycloak

### **Laravel**
# Ejecutar comandos artisan
docker compose exec app php artisan <comando>

# Limpiar cache
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear

# Ejecutar tests
docker compose exec app php artisan test

### **Base de Datos**
# Acceder a MariaDB
docker compose exec db mysql -u laravel -p laravel

# Hacer backup
docker compose exec db mysqldump -u laravel -p laravel > backup.sql

## **Troubleshooting**

### **Problemas Comunes**

1. **Error de conexión a BD**: Verificar IP de Gateway en `.env`
2. **Permisos de storage**: Ejecutar `chmod -R 775 storage`
3. **Keycloak no importa usuarios**: Verificar archivo `keycloak-data/realm-export.json`
4. **Puertos ocupados**: Cambiar puertos en `docker-compose.yml`

### **Logs Útiles**
# Logs de Laravel
docker compose exec app tail -f storage/logs/laravel.log

# Logs de Keycloak
docker compose logs keycloak

# Logs de MariaDB
docker compose logs db

## **Autenticación**

El sistema usa Keycloak para autenticación con:
- **Realm**: `poder-judicial-rn`
- **Cliente**: `recursos-humanos`
- **Protocolo**: OpenID Connect

### **Usuarios de Prueba**
- **admin** / **admin123** (Administrador)
- **rrhh_user** / **rrhh123** (Usuario RRHH)

    

