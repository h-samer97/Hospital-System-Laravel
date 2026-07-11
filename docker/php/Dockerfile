# --- Stage 1: Build Stage ---
FROM php:8.4-fpm-alpine AS builder

# Install Alpine build dependencies
RUN apk add --no-cache \
    $PHPIZE_DEPS \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    icu-dev \
    linux-headers

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    pdo_mysql \
    mbstring \
    zip \
    exif \
    pcntl \
    gd \
    intl \
    bcmath

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Grab Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer


# --- Stage 2: Final Production Stage ---
FROM php:8.4-fpm-alpine

WORKDIR /var/www/html

# Copy extensions and configurations from builder
COPY --from=builder /usr/local/lib/php/extensions /usr/local/lib/php/extensions
COPY --from=builder /usr/local/etc/php/conf.d /usr/local/etc/php/conf.d
COPY --from=builder /usr/bin/composer /usr/bin/composer

# CRITICAL: Install the runtime dependencies that the extensions need to execute
RUN apk add --no-cache \
    libzip \
    libpng \
    libjpeg-turbo \
    freetype \
    oniguruma \
    icu-libs

# Set up the production user
RUN addgroup -g 1000 valex && \
    adduser -u 1000 -G valex -s /bin/sh -D valex && \
    adduser valex www-data

COPY docker/php/php.ini /usr/local/etc/php/conf.d/valex.ini

# Copy code with correct ownership
COPY --chown=valex:valex . .

USER valex

# Install project dependencies
RUN composer install \
    --no-dev \
    --no-interaction \
    --optimize-autoloader \
    --no-scripts

EXPOSE 9000

CMD ["php-fpm"]