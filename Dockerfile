FROM php:5.5

MAINTAINER adityaii@gmail.com
# PHP Container for orangescrum

# Clone orangescrum/orangescrum
CMD git clone https://github.com/adityai/orangescrum

CMD cd $HOME && ls -ltr && echo "Work in progress."


# Install the relevant packages
RUN apt-get install vim apache2 libapache2-mod-php5 php5-cli php5-mysqlnd curl php5-curl php5-mcrypt -y

# Enable the php mod we just installed
RUN a2enmod php5

# expose port 80 and 443 for the web requests
EXPOSE 80
EXPOSE 443


###### Update the php INI settings #########

# Increase php's max allowed memory size
RUN sed -i 's;memory_limit = .*;memory_limit = -1;' /etc/php5/apache2/php.ini
RUN sed -i 's;memory_limit = .*;memory_limit = -1;' /etc/php5/cli/php.ini

RUN sed -i 's;display_errors = .*;display_errors = Off;' /etc/php5/apache2/php.ini

# Change apache php to allow larger uploads/POSTs
RUN sed -i 's;post_max_size = .*;post_max_size = 4000M;' /etc/php5/apache2/php.ini
RUN sed -i 's;upload_max_filesize = .*;upload_max_filesize = 2000M;' /etc/php5/apache2/php.ini

# Set the max execution time
RUN sed -i 's;max_execution_time = .*;max_execution_time = 300;' /etc/php5/apache2/php.ini
RUN sed -i 's;max_execution_time = .*;max_execution_time = 300;' /etc/php5/cli/php.ini

# This is also needed for execution time
RUN sed -i 's;max_input_time = .*;max_input_time = 300;' /etc/php5/apache2/php.ini


####### END of updating php INI ########
########################################

# Manually set the apache environment variables in order to get apache to work immediately.
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2

# It appears that the new apache requires these env vars as well
ENV APACHE_LOCK_DIR /var/lock/apache2
ENV APACHE_PID_FILE /var/run/apache2/apache2.pid

# Set up url rewrite ability
RUN a2enmod rewrite
RUN php5enmod mcrypt

# Install the cron service
RUN apt-get install cron -y

# Add our websites files to the default apache directory (/var/www)
# This should be as close to the last step as possible for faster rebuilds
ADD src /var/www/orangescrum

# Update our apache sites available with the config we created
ADD src/docker/apache-config.conf /etc/apache2/sites-enabled/000-default.conf

# Configure apache to use a newly generate ssl key
# or generate one if there is no key in the files.
# This MUST go after we have added the project files to the container.
RUN /bin/bash /var/www/orangescrum/docker/create-ssl-key.sh

# Use the crontab file.
RUN crontab /var/www/orangescrum/docker/crons.conf

# Configure the volume
VOLUME /data
RUN mv /var/www/orangescrum/app/webroot/files /data/files
RUN ln -s /data/files /var/www/orangescrum/app/webroot/files
RUN mv /var/www/orangescrum/app/Config/constants.php /data/constants.php
RUN ln -s /data/constants.php /var/www/orangescrum/app/Config/constants.php
RUN mv /var/www/orangescrum/app/Config/database.php /data/database.php
RUN ln -s /data/database.php /var/www/orangescrum/app/Config/database.php

# Set general permissions
RUN chown root:www-data -R /var/www
RUN chmod 750 -R /var/www/orangescrum

# If we need some directories to be writeable to for uploads, place them here.
RUN chmod 770 -R /var/www/orangescrum/app/tmp
RUN chmod 770 -R /var/www/orangescrum/app/webroot

# Execute the containers startup script which will start many processes/services
CMD ["/bin/bash", "/var/www/orangescrum/docker/startup.sh"]
