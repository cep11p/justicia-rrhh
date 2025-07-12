# justicia-rrhh

Infraestructura Docker
Este proyecto levanta cuatro servicios principales con Docker Compose (versión 3.8):

yaml
Copiar
Editar
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: laravel-app
    volumes:
      - ./app:/var/www/html
    depends_on:
      - db

  web:
    image: nginx:alpine
    container_name: nginx
    ports:
      - "8080:80"
    volumes:
      - ./app:/var/www/html
      - ./nginx/default.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - app

  db:
    image: mariadb:10.6
    container_name: mariadb
    ports:
      - "3306:3306"
    environment:
      MYSQL_DATABASE: laravel
      MYSQL_USER: laravel
      MYSQL_PASSWORD: secret
      MYSQL_ROOT_PASSWORD: root
    volumes:
      - db_data:/var/lib/mysql
      
  keycloak:
    image: quay.io/keycloak/keycloak:24.0.3
    container_name: keycloak
    command: start-dev
    environment:
      KEYCLOAK_ADMIN: admin
      KEYCLOAK_ADMIN_PASSWORD: admin
    ports:
      - "8081:8080"
    depends_on:
      - db

volumes:
  db_data:
Descripción de los servicios
app

Construye tu aplicación Laravel usando el Dockerfile en la raíz.

Monta el código en /var/www/html para desarrollo en caliente.

Depende de la base de datos (db) para poder ejecutar migrations al inicio.

web

Usa la imagen oficial nginx:alpine para servir tu app PHP.

Expone el puerto 8080 en tu máquina local en el puerto interno 80 de Nginx.

Monta el mismo volumen de código y la configuración de virtual host (default.conf) personalizada.

db

Levanta MariaDB 10.6 con credenciales definidas por variables de entorno.

Expone el puerto 3306 para conexiones externas (p. ej. clientes MySQL locales).

Persiste los datos en el volumen db_data.

keycloak

Arranca Keycloak 24.0.3 en modo desarrollo (start-dev), con usuario admin/admin.

Expone 8081 en tu máquina local apuntando al 8080 interno del contenedor.

Depende de la base de datos para poder almacenar usuarios/realms si migrás a un motor SQL más adelante.

Volúmenes
db_data: volumen Docker para persistir la data de MariaDB entre reinicios.

Para ejecutar los seeders:
docker compose exec app php artisan db:seed