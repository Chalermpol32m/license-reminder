FROM php:8.2-cli

WORKDIR /app

RUN apt-get update && apt-get install -y \
    git unzip libpq-dev

RUN docker-php-ext-install pdo pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

RUN php artisan storage:link

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=$PORT & php artisan schedule:work