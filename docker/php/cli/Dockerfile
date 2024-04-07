FROM ghcr.io/kuaukutsu/php:8.3-cli as app_cli_build

FROM app_cli_build AS app_setup

# Arguments
ARG UID=10001
ARG WORKDIR="/app"

# Configure
COPY conf/php.ini /usr/local/etc/php/php.ini

COPY --from=composer:latest --link /usr/bin/composer /usr/bin/composer

RUN echo "# Adduser #" \
    && adduser -u $UID -G www-data -s /bin/sh -D developer www-data

RUN echo "# Create structure #" \
    && mkdir -p $WORKDIR \
    && chown -R $UID:www-data $WORKDIR

# https://github.com/mlocati/docker-php-extension-installer
RUN echo "# Install extensions #" \
    && install-php-extensions \
      mysqli

FROM app_setup AS app_cli

USER $UID
WORKDIR $WORKDIR

FROM app_setup AS app_devel

RUN echo "# Install xdebug #" \
    && install-php-extensions xdebug

USER $UID
WORKDIR $WORKDIR
