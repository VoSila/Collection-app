FROM webdevops/php-nginx-dev:8.2-alpine

RUN apk --no-cache add mariadb-client

WORKDIR /app
COPY . /app

ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install

ENV WEB_DOCUMENT_ROOT=/app/public
ENV WEB_DOCUMENT_INDEX=index.php
