
FROM php:8.2-fpm


RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    unzip \
    zip \
    nginx \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd zip opcache


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


WORKDIR /var/www/html


COPY . /var/www/html


RUN composer install --optimize-autoloader --no-dev


RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache


COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf


EXPOSE 80


CMD service nginx start && php-fpm
