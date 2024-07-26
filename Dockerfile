# Usar la imagen oficial de WordPress
FROM wordpress:latest

# Instalar dependencias adicionales si es necesario
RUN apt-get update && apt-get install -y \
    nano \
    vim

# Copiar archivos adicionales si es necesario
COPY . /var/www/html
