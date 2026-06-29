FROM php:8.5-fpm

RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev \
    libicu-dev libonig-dev libxml2-dev \
    librabbitmq-dev libssl-dev \
    libcurl4-openssl-dev pkg-config \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install \
    pdo_mysql \
    intl \
    zip \
    mbstring \
    sockets

RUN pecl install redis && docker-php-ext-enable redis && \
	pecl install amqp && docker-php-ext-enable amqp

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN curl -sS https://get.symfony.com/cli/installer | bash && \
    mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

WORKDIR /var/www/html
