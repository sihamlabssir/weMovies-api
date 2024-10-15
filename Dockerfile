FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libpq-dev \
    libonig-dev \
    libzip-dev \
    curl \
    && docker-php-ext-install \
    intl \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    zip \
    && pecl install apcu \
    && docker-php-ext-enable apcu

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/symfony

COPY . /var/www/symfony

ARG APP_ENV=dev
RUN if [ "$APP_ENV" = "prod" ]; then \
        composer install --no-dev --optimize-autoloader; \
    else \
        composer install --optimize-autoloader; \
        composer require --dev symfony/debug-bundle; \
    fi

RUN chown -R www-data:www-data /var/www/symfony/var

EXPOSE 9000

CMD ["php-fpm"]