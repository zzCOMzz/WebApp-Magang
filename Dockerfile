FROM php:7.2-apache

WORKDIR /home/refnaldy/Myproject/server1

ENV APACHE_DOCUMENT_ROOT /home/refnaldy/Myproject/server1
COPY . Project-Magang-App/

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

EXPOSE 80

CMD [ "apache2-foreground" ]