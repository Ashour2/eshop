FROM php:8.2-cli

# تثبيت المكتبات المطلوبة
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libxml2-dev libzip-dev \
    libonig-dev libcurl4-openssl-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring xml zip gd curl \
    && apt-get clean

# تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# نسخ الملفات
COPY . .

# تثبيت dependencies
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# إعداد storage
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p storage/logs \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8080

CMD php artisan config:clear && \
    php artisan cache:clear && \
    php artisan migrate --force && \
    php artisan db:seed --force && \
    php artisan storage:link && \
    php artisan serve --host=0.0.0.0 --port=8080
