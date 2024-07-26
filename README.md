# wp_bitcoin

## Instrucciones

1. Clona el repositorio.
2. Ejecuta `docker compose up` para levantar el entorno de desarrollo.
3. Accede a `http://localhost:8000` para ver el sitio en funcionamiento.

## BD
       MYSQL_ROOT_PASSWORD: somewordpress
       MYSQL_DATABASE: exampledb
       MYSQL_USER: exampleuser
       MYSQL_PASSWORD: examplepass

      WORDPRESS_DB_HOST: db
      WORDPRESS_DB_USER: exampleuser
      WORDPRESS_DB_PASSWORD: examplepass
      WORDPRESS_DB_NAME: exampledb

## Funcionalidades

### Custom Post Type "Books"

- Registrado en `functions.php`.
- Campos personalizados (ACF) añadidos para Título, Descripción, Imagen y Año.
- Shortcode `[books]` para mostrar los libros.

### Bitcoin Price Widget

- Obtiene el precio actual de Bitcoin usando la API de https://mempool.space/docs/api/rest#get-price.
- Registrado como un widget de sidebar.

### Estilos

- CSS incluido para estilizar los libros y el widget.
- Uso de media queries para asegurar la responsividad.

## Estructura del Proyecto

- `Dockerfile`: Configuración de Docker.
- `docker-compose.yml`: Configuración de Docker Compose.
- `wp-content/themes/kadence-child`: Tema personalizado con archivos PHP y CSS.
- `functions.php`: Funciones para registrar CPT, campos ACF, shortcode y widget.
- `sidebar.php`: Plantilla de sidebar.
- `css/custom-style.css`: Estilos del tema.
- `single-books.php`: Tema de WordPress se utilizado para mostrar la plantilla de una sola entrada de un Custom Post Type (CPT) llamado books.
- `js/modal.js`: Abre una modal para ver el año y fotografia del libro.