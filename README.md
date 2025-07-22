# justicia-rrhh

Infraestructura Docker
Este proyecto levanta cuatro servicios principales con Docker Compose

#aca va el docker compose

Descripción de los servicios
app

#Describir el servicio app#



web

Usa la imagen oficial nginx:alpine para servir tu app PHP.

Expone el puerto 8080 en tu máquina local en el puerto interno 80 de Nginx.


db

Levanta MariaDB 10.6 con credenciales definidas por variables de entorno.

Expone el puerto 3390

Persiste los datos en el volumen db_data.

keycloak

Arranca Keycloak 24.0.3 en modo desarrollo (start-dev), con usuario admin/admin.

Expone 8081 en tu máquina local apuntando al 8080 interno del contenedor.

Volúmenes
db_data: volumen Docker para persistir la data de MariaDB entre reinicios.


#Instalar dependencias
  docker compose exec app composer install

#Configurar .env, dentro de rrhh-backend
  cp rrhh-backend/.env.example rrhh-backend/.env

#Configurar host de la base de datos en .env, dentro de rrhh-backend
  - se debe correr el comando docker inspect justicia-db, para obtener el ip de la base de datos, la ip relevante es la que esta configurada en Gateway

#Carga de estructura de la base de datos y seeders

  -docker compose exec app php artisan migrate --seed
