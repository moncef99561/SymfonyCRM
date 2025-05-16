FROM php:8.2-fpm

# Installer les dépendances
RUN apt-get update && apt-get install -y \
    git unzip curl libicu-dev libzip-dev zip libonig-dev libxml2-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip opcache

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/symfony

COPY ./code /var/www/symfony

# Installer les dépendances PHP du projet
RUN composer install \
    && composer require symfony/maker-bundle --dev \
    && composer require orm

CMD ["php-fpm"]