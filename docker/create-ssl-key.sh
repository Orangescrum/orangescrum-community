#!/bin/bash
# Script called by docker during the build to create ssl certificates if none were provided.

sudo a2enmod ssl
sudo service apache2 restart
sudo mkdir /etc/apache2/ssl 

if [ ! -f /var/www/orangescrum/docker/ssl/apache.crt ]; then
    echo "generating ssl keys since they weren't provided."
    sudo openssl \
    req -x509 \
    -nodes \
    -days 365 \
    -newkey rsa:4096 \
    -keyout /etc/apache2/ssl/apache.key \
    -out /etc/apache2/ssl/apache.crt \
    -subj "/C=GB/ST=London/L=London/O=Global Security/OU=IT Department/CN=orangescrum"
else
    mv /var/www/orangescrum/docker/ssl/apache.key /etc/apache2/ssl/apache.key
    mv /var/www/orangescrum/docker/ssl/apache.crt /etc/apache2/ssl/apache.crt
fi

SCRIPT=$(readlink -f "$0")
SCRIPTPATH=$(dirname "$SCRIPT") 
cd $SCRIPTPATH

mv apache-ssl-config.conf /etc/apache2/sites-available/default-ssl.conf

sudo a2ensite default-ssl
service apache2 reload
