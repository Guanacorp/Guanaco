FROM php:7.4.3-fpm-alpine3.11

ENV TZ Europe/Paris
ENV COMPOSER_HOME /tmp/composer
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_VERSION 1.9.3

RUN apk add --no-cache --update --virtual .build-deps autoconf automake make gcc g++ libc-dev linux-headers python libtool libzip-dev zlib-dev pkgconfig curl \
    && apk add --no-cache --update git zlib busybox-suid tzdata su-exec make bash yarn \
    # Main PHP extensions
    && docker-php-ext-install pdo_mysql opcache \
    # PHP Intl
    && apk add --no-cache --update icu-libs \
    && apk add --no-cache --update --virtual .intl-deps icu-dev \
    && docker-php-ext-install intl \
    && apk del --no-cache .intl-deps \
    # Composer
    && mkdir -p $COMPOSER_HOME \
    && wget https://raw.githubusercontent.com/composer/getcomposer.org/ffdc3c7fcb7c0f2a806508a868a35d13177a5a51/web/installer -O - -q | php -- --quiet --no-ansi --install-dir=/usr/bin --filename=composer --version=${COMPOSER_VERSION} \
    && wget https://get.symfony.com/cli/installer -O - | bash \
    && mv /root/.symfony/bin/symfony /usr/bin \
    && composer --ansi --version --no-interaction \
    # Fix permissions
    && apk add --no-cache --update shadow \
    && usermod -u 1000 www-data \
    && groupmod -g 1000 www-data \
    && chown -R root:www-data $COMPOSER_HOME \
    && chmod -R g+w $COMPOSER_HOME \
    # Cleanup
    && apk del --no-cache .build-deps \
    && rm -rf /var/cache/apk && mkdir -p /var/cache/apk

COPY docker/guanaco/php.ini $PHP_INI_DIR/php.ini

WORKDIR /var/www/guanaco/
