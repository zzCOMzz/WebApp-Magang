FROM php-composer-mysql:latest

WORKDIR /var/www/html/Project-Magang-App/

RUN ["mkdir","/var/www/html/Project-Magang-App-Uploads"]

COPY . .
RUN ["curl","-o","wait-for-it.sh","https://raw.githubusercontent.com/vishnubob/wait-for-it/master/wait-for-it.sh"]
RUN ["chmod","ug+x","wait-for-it.sh"]
RUN ["chmod","ug+x","init.sh"]
RUN ["composer","install"]
RUN docker-php-ext-install pdo_mysql



EXPOSE 80

CMD ["apache2-foreground"];