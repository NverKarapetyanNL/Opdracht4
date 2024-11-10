# Gebruik de officiële PHP 8.1 FPM image
FROM php:8.1-fpm

# Installeer systeempakketten en PHP-extensies
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite zip

# Installeer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Stel de werkdirectory in
WORKDIR /var/www/html

# Kopieer de bestanden naar de container
COPY . .

# Voer Composer install uit om afhankelijkheden te installeren
RUN composer install

# Expose de poort waarop de server draait
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
