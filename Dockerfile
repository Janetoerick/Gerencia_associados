# Dockerfile

FROM php:8.2-fpm 

# 1. Atualiza a lista de pacotes
RUN apt-get update 

# 2. Instala utilitários, o cliente MySQL e as dependências para as extensões PHP
RUN apt-get install -y \
    libpq-dev \
    libzip-dev \
    git \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# 3. Instala as extensões PHP
RUN docker-php-ext-install pdo_mysql opcache

# Instala o Composer globalmente no contêiner
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html