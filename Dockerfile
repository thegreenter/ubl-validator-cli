FROM php:7.1-alpine
LABEL owner="Giancarlos Salas"
LABEL maintainer="giansalex@gmail.com"

COPY . /app

WORKDIR /app

RUN mkdir /xml && \
    chmod -R 777 /xml && \
    chmod +x bin/ubl

VOLUME /xml

ENTRYPOINT ["php", "./bin/ubl"]