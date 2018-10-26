FROM php:7.1-alpine
LABEL owner="Giancarlos Salas"
LABEL maintainer="giansalex@gmail.com"

COPY . /var/www/html/

WORKDIR /var/www/html

RUN mkdir xml && \
    chmod -R 777 xml/ && \
    chmod +x bin/ubl


VOLUME /var/www/html/xml

ENTRYPOINT ["php", "./bin/ubl"]