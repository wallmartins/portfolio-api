# Portfolio API - Multi-stage Dockerfile
# @link     https://www.hyperf.io
# @document https://hyperf.wiki

##
# ---------- Base Stage ----------
##
FROM hyperf/hyperf:8.3-alpine-v3.19-swoole AS base

ARG timezone
ENV TIMEZONE=${timezone:-"America/Sao_Paulo"}

# Install necessary extensions and tools
RUN set -ex \
    && apk add --no-cache \
        postgresql-dev \
        sqlite-dev \
        bash \
        git \
    && php -v \
    && php -m \
    && php --ri swoole \
    && cd /etc/php* \
    && { \
        echo "upload_max_filesize=128M"; \
        echo "post_max_size=128M"; \
        echo "memory_limit=1G"; \
        echo "date.timezone=${TIMEZONE}"; \
    } | tee conf.d/99_overrides.ini \
    && ln -sf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime \
    && echo "${TIMEZONE}" > /etc/timezone \
    && rm -rf /var/cache/apk/* /tmp/* /usr/share/man

WORKDIR /opt/www

##
# ---------- Development Stage ----------
##
FROM base AS development

ENV APP_ENV=dev \
    SCAN_CACHEABLE=false

# Copy composer files
COPY composer.json composer.lock ./

# Install all dependencies (including dev)
RUN composer install --no-scripts --no-autoloader

# Copy application code
COPY . .

# Generate autoload and optimize
RUN composer dump-autoload --optimize \
    && mkdir -p runtime/container storage \
    && chmod -R 777 runtime storage

EXPOSE 9501

CMD ["php", "bin/hyperf.php", "server:watch"]

##
# ---------- Production Stage ----------
##
FROM base AS production

ENV APP_ENV=prod \
    SCAN_CACHEABLE=true

# Copy composer files
COPY composer.json composer.lock ./

# Install production dependencies only
RUN composer install --no-dev --no-scripts --no-autoloader -o

# Copy application code
COPY . .

# Generate optimized autoload
RUN composer dump-autoload --optimize --classmap-authoritative \
    && php bin/hyperf.php \
    && mkdir -p runtime/container storage \
    && chmod -R 777 runtime storage \
    && rm -rf /var/cache/apk/* /tmp/*

EXPOSE 9501

ENTRYPOINT ["php", "/opt/www/bin/hyperf.php", "start"]
