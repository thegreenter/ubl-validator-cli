FROM php:7.1-alpine
LABEL owner="Giancarlos Salas"
LABEL maintainer="giansalex@gmail.com"

COPY . /app

WORKDIR /app

ENTRYPOINT ["php", "./bin/ubl"]