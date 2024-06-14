FROM serversideup/php:8.3-fpm-nginx-alpine as base
USER root
WORKDIR /var/www/html
COPY composer.json composer.lock ./
RUN install-php-extensions bcmath
RUN composer install --no-dev --no-interaction --no-plugins --no-scripts --prefer-dist

FROM node:20 as static-assets
WORKDIR /app
COPY . .
COPY --from=base /var/www/html .
RUN npm install
RUN npm run build

FROM serversideup/php:8.3-fpm-nginx-alpine

ARG POSTGRES_VERSION=16

WORKDIR /var/www/html
USER root
RUN apk add --no-cache postgresql${POSTGRES_VERSION}-client php-pgsql

COPY --chown=www-data:www-data docker/production/nginx.conf /etc/nginx/conf.d/custom.conf
COPY --chown=www-data:www-data docker/production/performance.conf /etc/nginx/server-opts.d/performance.conf

COPY --from=base --chown=www-data:www-data /var/www/html .

COPY --chown=www-data:www-data . .
RUN composer dump-autoload

COPY --from=static-assets --chown=www-data:www-data /app/public/build ./public/build
COPY --chmod=755 --chown=www-data:www-data docker/production/etc/s6-overlay/ /etc/s6-overlay/

USER root
RUN  php artisan optimize
USER www-data
