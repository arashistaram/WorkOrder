FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader

FROM node:20-alpine AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm ci --no-audit --no-fund

COPY . .
RUN npm run build

FROM dunglas/frankenphp:1-php8.3-alpine AS runtime

RUN install-php-extensions \
    pdo_mysql \
    redis \
    intl \
    zip \
    opcache \
    pcntl \
    gd

RUN echo "memory_limit=256M" >> /usr/local/etc/php/conf.d/app.ini \
 && echo "upload_max_filesize=20M" >> /usr/local/etc/php/conf.d/app.ini \
 && echo "post_max_size=20M" >> /usr/local/etc/php/conf.d/app.ini \
 && echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/app.ini \
 && echo "opcache.jit=tracing" >> /usr/local/etc/php/conf.d/app.ini \
 && echo "opcache.jit_buffer_size=100M" >> /usr/local/etc/php/conf.d/app.ini

WORKDIR /app

COPY --from=vendor /app/vendor ./vendor
COPY --from=frontend /app/public/build ./public/build
COPY . .

RUN chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

COPY Caddyfile /etc/caddy/Caddyfile

RUN php artisan config:clear \
 && php artisan route:clear \
 && php artisan view:clear

EXPOSE 80 443 443/udp

CMD ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=80"]
