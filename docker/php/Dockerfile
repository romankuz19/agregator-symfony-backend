FROM php:8.1-fpm-alpine

RUN set -o errexit -o nounset -o pipefail -o xtrace; \
    \
    # Install system packages.
    apk update; \
    apk upgrade; \
    apk \
        add --no-cache \
            aspell-dev \
            autoconf \
            bash \
            build-base \
            bzip2 \
            bzip2-dev \
            curl \
            ffmpeg \
            freetype-dev \
            git \
            hiredis-dev \
            icu \
            imagemagick-c++ \
            imagemagick-dev \
            libaio-dev \
            libbz2 \
            libjpeg-turbo-dev \
            libmcrypt-dev \
            libpng \
            libpng-dev \
            librdkafka-dev \
            libstdc++ \
            libtool \
            libwebp \
            libxml2-dev \
            libxslt-dev \
            libzip \
            libzip-dev \
            linux-headers \
            make \
            nano \
            oniguruma \
            openldap-dev \
            openssh \
            postgresql-dev \
            supervisor \
            php-fpm \
            unzip \
            wget \
            yaml-dev \
            zip \
            zlib-dev; \
    \
    # Clean up.
    rm -fr /tmp/* /var/tmp/*

# Retrieve the script used to install PHP extensions from the source container.
COPY --from=mlocati/php-extension-installer:2.1.10 /usr/bin/install-php-extensions /usr/local/bin/

RUN set -o errexit -o nounset -o pipefail -o xtrace; \
    \
    # Install PHP extensions.
    install-php-extensions \
        apcu \
        amqp \
        bcmath \
        gd \
        igbinary \
        intl \
        ldap \
        opcache \
        pcntl \
        pdo_pgsql \
        redis \
        sockets \
        sqlsrv \
        xdebug \
        zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN set -o errexit -o nounset -o pipefail -o xtrace; \
    \
    composer --global config repos.packagist composer 'https://mirrors.tencent.com/composer/'
ENV COMPOSER_ALLOW_SUPERUSER=1

# Create the /var/log/supervisor directory
RUN mkdir -p /var/log/supervisor && chmod -R 755 /var/log/supervisor

COPY ./supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy PHP-FPM configuration
COPY ./php-fpm.conf /etc/php81/php-fpm.conf
COPY ./www.conf /etc/php81/php-fpm.d/www.conf

# Set the default command to run supervisord
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]