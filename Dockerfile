FROM php:5.5

MAINTAINER adityaii@gmail.com
# PHP Container for orangescrum

# Clone orangescrum/orangescrum
CMD git clone https://github.com/adityai/orangescrum

CMD cd $HOME && ls -ltr && echo "Work in progress."

# apt-get update
RUN apt-get update 

# Install the relevant packages
RUN apt-get install libapache2-mod-php5 php5-cli php5-mysqlnd curl php5-curl php5-mcrypt cron -y

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

