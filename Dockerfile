FROM php:5-apache

# labels
LABEL maintainer="mosluce<mosluce@gmail.com>"

# install php pdo mysql extension
RUN docker-php-ext-install pdo_mysql

# copy php ini file
COPY ./php.ini /usr/local/etc/php/

# copy apache confing for orangescrum
COPY ./orangescrum.conf /etc/apache2/conf-available/

COPY . /var/www/html/

# update folders permission
RUN chmod -R 0777 app/Config
RUN chmod -R 0777 app/tmp
RUN chmod -R 0777 app/webroot

# enable apache modules
RUN a2enmod rewrite
RUN a2enmod headers

# enable apache configs
RUN a2enconf orangescrum