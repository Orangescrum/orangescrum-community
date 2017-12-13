FROM php:5-apache

# labels
LABEL maintainer="mosluce<mosluce@gmail.com>"

RUN apt-get update 
RUN apt-get install -y wkhtmltopdf
RUN apt-get install -y libc-client-dev libkrb5-dev \
    libicu-dev \
    libldap2-dev \
    libsnmp-dev \
    libtidy-dev \
    libmcrypt-dev \
    libxml2-dev \
    libpng-dev 
RUN rm -rf /var/lib/apt/lists/*

# install php pdo mysql extension
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install gd 

#RUN docker-php-ext-install common
#RUN docker-php-ext-install fpm
#RUN docker-php-ext-install cli

RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl && docker-php-ext-install imap
RUN docker-php-ext-install intl
RUN docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/ && docker-php-ext-install ldap
RUN docker-php-ext-install mysql 
RUN docker-php-ext-install snmp
RUN docker-php-ext-install tidy
RUN docker-php-ext-install mcrypt
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install soap
RUN docker-php-ext-install zip
RUN docker-php-ext-install dba

# copy php ini file
COPY ./php.ini /usr/local/etc/php/

# copy apache confing for orangescrum
COPY ./orangescrum.conf /etc/apache2/conf-available/

COPY ./ /var/www/html/

WORKDIR /var/www/html/
# update folders permission
RUN chmod -R 0777 app/Config
RUN chmod -R 0777 app/tmp
RUN chmod -R 0777 app/webroot

# enable apache modules
RUN a2enmod rewrite
RUN a2enmod headers

# enable apache configs
RUN a2enconf orangescrum
