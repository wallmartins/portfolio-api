# Default Dockerfile
#
# @link     https://www.hyperf.io
# @document https://hyperf.wiki
# @contact  group@hyperf.io
# @license  https://github.com/hyperf/hyperf/blob/master/LICENSE

FROM hyperf/hyperf:8.3-alpine-v3.19-swoole
LABEL maintainer="Hyperf Developers <group@hyperf.io>" version="1.0" license="MIT" app.name="Hyperf"

##
# ---------- env settings ----------
##
# --build-arg timezone=Asia/Shanghai
ARG timezone

ENV TIMEZONE=${timezone:-"Asia/Shanghai"} \
    APP_ENV=prod \
    SCAN_CACHEABLE=(true)

# Install necessary extensions and tools
RUN set -ex \
    && apk add --no-cache \
        postgresql-dev \
        postgresql-client \
        sqlite-dev \
        bash \
        git \
        curl \
        php83-pdo_pgsql \
    # show php version and extensions
    && php -v \
    && php -m \
    && php --ri swoole \
    #  ---------- some config ----------
    && cd /etc/php* \
    # - config PHP
    && { \
        echo "upload_max_filesize=128M"; \
        echo "post_max_size=128M"; \
        echo "memory_limit=1G"; \
        echo "date.timezone=${TIMEZONE}"; \
    } | tee conf.d/99_overrides.ini \
    # - config timezone
    && ln -sf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime \
    && echo "${TIMEZONE}" > /etc/timezone \
    # ---------- clear works ----------
    && rm -rf /var/cache/apk/* /tmp/* /usr/share/man \
    && echo -e "\033[42;37m Build Completed :).\033[0m\n"

WORKDIR /opt/www

# Composer Cache
# COPY ./composer.* /opt/www/
# RUN composer install --no-dev --no-scripts

COPY . /opt/www

# Create .env from production example for build process
RUN cp .env.prod.example .env

# Install dependencies
RUN composer install --no-dev --no-scripts --no-autoloader -o

# Create required directories first
RUN mkdir -p runtime/container storage swagger \
    && chmod -R 777 runtime storage

# Generate optimized autoload and bootstrap Hyperf
RUN composer dump-autoload --optimize --classmap-authoritative \
    && php bin/hyperf.php

EXPOSE 9501

ENTRYPOINT ["php", "/opt/www/bin/hyperf.php", "start"]
