FROM php:8.2-apache

# Instala dependências do sistema e extensões do PHP necessárias
RUN apt-get update && apt-get install -y \
    libpq-dev zip unzip git curl libzip-dev libonig-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define diretório de trabalho
WORKDIR /var/www/html

# Copia os arquivos do projeto Laravel
COPY . .

# Garante que diretórios essenciais existam
RUN mkdir -p storage bootstrap/cache

# Instala dependências do Laravel
RUN composer install --no-interaction --optimize-autoloader

# Permissões necessárias para o Laravel funcionar corretamente
RUN chown -R www-data:www-data . \
    && chmod -R 755 storage bootstrap/cache

# Habilita o mod_rewrite do Apache
RUN a2enmod rewrite

# Copia seu arquivo de configuração apache.conf para o local correto do apache no container
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

CMD ["apache2-foreground"]
