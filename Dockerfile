FROM php:8.2.13-zts-alpine3.19

ADD sslgen.phar /usr/local/bin/sslgen.phar
ADD sslgen.sh /usr/local/bin/sslgen.sh

RUN chmod +x /usr/local/bin/sslgen.sh

RUN addgroup -g 1000 php-grp \
    && adduser -u 1000 -S php-usr -G php-grp \
    && mkdir /app \
    && chown -R php-usr:php-grp /app

USER php-usr

WORKDIR /app

ENTRYPOINT ["/usr/local/bin/sslgen.sh"]