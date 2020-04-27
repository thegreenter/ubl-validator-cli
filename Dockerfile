FROM php:7.4-alpine as composer

WORKDIR /app
RUN apk add --no-cache curl && \
    curl --silent --show-error -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . .
RUN composer install --no-interaction --no-dev --optimize-autoloader 


FROM php:7.4-alpine
LABEL owner="Giancarlos Salas"
LABEL maintainer="giansalex@gmail.com"

COPY --from=composer /app /app

WORKDIR /xml

ENTRYPOINT ["php", "/app/bin/ubl"]