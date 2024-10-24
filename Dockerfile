FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    curl \
    nodejs \
    npm \
    git \
    unzip \
    zip \
    libpng-dev \
    libonig-dev \
    libxml2-dev

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurações do Apache
WORKDIR /var/www/html
COPY . /var/www/html
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html
RUN chmod -R 775 /var/www/html/storage

RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite

# Instalar as dependências do projeto
RUN composer install


# Definir o ponto de entrada para o container, aguardando o banco de dados
ENTRYPOINT ["./wait-for-it.sh", "db:3306", "--timeout=60", "--strict", "--"]

# Expor a porta 80
EXPOSE 80

# Comando principal após o banco de dados estar pronto
CMD ["sh", "-c", "composer install && composer run-dev && apache2-foreground"]
