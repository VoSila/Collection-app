FROM webdevops/php-nginx-dev:8.2-alpine

WORKDIR /app

RUN apt-get update && apt-get install -y --no-install-recommends \
	acl \
	file \
	gettext \
	git \
	&& rm -rf /var/lib/apt/lists/*

RUN set -eux; \
	install-php-extensions \
		@composer \
		apcu \
		intl \
		opcache \
		zip \
		sockets \
        amqp \
        pdo \
        mysqli \
        pdo_mysql \
	;

COPY . /app

ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install

ENV WEB_DOCUMENT_ROOT=/app/public
ENV WEB_DOCUMENT_INDEX=index.php
