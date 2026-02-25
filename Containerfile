ARG PHP_VERSION=8.4
FROM dunglas/frankenphp:php${PHP_VERSION}

RUN install-php-extensions \
  pdo_mysql \
  mysqli \
  intl \
  gd \
  zip \
  xdebug \
  @composer

