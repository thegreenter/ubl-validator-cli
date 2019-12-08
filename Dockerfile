FROM php:7.4-alpine
LABEL owner="Giancarlos Salas"
LABEL maintainer="giansalex@gmail.com"

COPY . /app

WORKDIR /xml

ENTRYPOINT ["php", "/app/bin/ubl"]