#!/bin/bash
#Orangescrum installation in centos server:-
#============================================
WEBROOT=/var/www/html
APPROOT=/var/www/html/orangescrum-master
DATABASE=orangescrum
DUSER=orangescrum
DPASS=Orang3#Scrum
mysqlversion=5.6
mysqlversion1=5.5
mysqlversion2=5.7
apacheversion=2.4
phpversion=5.6
mysql_v=`rpm -qa | grep "mysql.*server" | cut -c 24-26`
Apache_ver=`rpm -qa | grep httpd-2 | cut -c 7-9`
php_ver=`rpm -qa | grep "php.*common" | cut -c 15-17`
#mysql_ver=`mysql -V | awk '{ print $5 }' | cut -c 1-3`
#Apache_v=`httpd -v | grep -i Apache |awk '{ print $3 }'| cut -c 8-10`
#php_v=`php -v | grep -i "PHP 5.6.35"|awk '{ print $2 }'| cut -c 1-3`
echo "You need a fresh OS for OrangeScrum Community to work with"
echo "Orangescrum will work only with MySQL 5.6, Apache Web Server 2.4 amd PHP 5.6"

#MySQL-Server
if [ ! -z "$mysql_v" ]; then
	echo "Found MySQL $mysql_v on your Server"
#	echo "If you are running any other application with the current versions of MySQL, unistalling MySQL-$mysql_v might create issue"
fi

#Apache Web Server
if [ ! -z "$Apache_ver" ]; then
	echo "Found Apache Web Server $Apache_ver on your Server"
	echo "If you are running any other application with the current versions of Apache, unistalling Apache Web Server $Apache_ver might create issue"
fi

#PHP Packages on your Server
if [ ! -z "$php_ver" ]; then
	echo "Found PHP $php_ver on your Server"
	echo "If you are running any other application with the current versions of PHP, unistalling PHP-$php_ver might create issue"
fi

echo "Do you want to Continue, type Y/N"
read action

if [[ $action == "y" || $action == "Y" || $action == "yes" || $action == "Yes" ]]; then
        echo "Continue to Application Installation and Configuration"
else
        echo "Aborting Installation"
        exit 1
fi

echo "OrangeScrum Installation Started, Please Wait"
wgetiver=`rpm -qa | grep wget | cut -c 1-4`
wgetversion=wget
if [ "$wgetiver" = "$wgetversion" ]; then
	echo "wget is already installed, Continue Installation"
else	
	yum install -y wget
fi

#Add Firewall rules for Apache and mysql
echo `setenforce 0`
echo `getenforce`
sed -i "s/SELINUX=enforcing/SELINUX=permissive/g" /etc/selinux/config
firewall-cmd --permanent --zone=public --add-service=http
firewall-cmd --permanent --zone=public --add-service=mysql
firewall-cmd --permanent --zone=public --add-service=https
firewall-cmd --reload

#MySQL-Server Uninstall
if [ "$mysql_v" = "$mysqlversion" ] || [ "$mysql_ver" = "$mysqlversion1" ] || [ "$mysql_ver" = "$mysqlversion2" ]; then
        echo "Found MySQL $mysql_v, Continue Installation"
	echo "Please enter the current MySQL root password"
	read -s DBPASS
else
        echo "Installing MySQL Server $mysqlversion"
	wget http://repo.mysql.com/mysql-community-release-el7-5.noarch.rpm
	rpm -ivh mysql-community-release-el7-5.noarch.rpm
#        yum -y update
        yum -y install mysql-server
	chkconfig --levels 235 mysqld on
        service mysqld restart
	service mysql restart
	rm -rf $WEBROOT/orangescrum-master/mysql-com.*rpm*
#Set root password:-
#/usr/bin/mysqld_safe \
#  --defaults-file=/etc/my.cnf \
#  --skip-grant-tables &
#myPid=$!
	echo "Set root paassword for MySQL $mysqlver"
        echo "Enter root Password for MySQL"
        read -s DBPASS
        yum install -y expect
        echo "--> Set root password"
        SECURE_MYSQL=$(expect -c "
        set timeout 10
        spawn mysql_secure_installation
        expect \"Enter current password for root (enter for none):\"
        send \"\r\"
        expect \"Set root password?\"
        send -- \"y\r\"
        expect \"New password:\"
        send -- \"${DBPASS}\r\"
        expect \"Re-enter new password:\"
        send -- \"${DBPASS}\r\"
        expect \"Remove anonymous users?\"
        send \"y\r\"
        expect \"Disallow root login remotely?\"
        send \"y\r\"
        expect \"Remove test database and access to it?\"
        send \"y\r\"
        expect \"Reload privilege tables now?\"
        send \"y\r\"
        expect eof
        ")
	echo "$SECURE_MYSQL"
	yum erase -y expect
fi

#Apache Web Server Uninstall older version and install required version
if [ "$Apache_ver" = "$apacheversion" ]; then
        echo "Found Apache $Apache_ver, Continue Installation"
	elif [ "$Apache_v" ! = "$apacheversion" ]; then
        echo "Uninstalling Apache Web Server $Apache_ver from your Server"
        yum -y erase httpd*
else
        echo "Installing Apache Web Server $apacheversion"
        yum install -y httpd
	chkconfig --levels 235 httpd on
        service httpd restart
fi

#PHP Packages Uninstall
if [ "$php_ver" = "$phpversion" ]; then
        echo "Found PHP $php_ver, Continue Installation"
	elif [ "$php_v" ! = "$phpversion" ]; then
        echo "Uninstalling PHP $php_ver from your Server"
        yum -y erase php*
	yum -y erase php*common*
else
        echo "Installing PHP Server $phpversion"
	rpm -Uvh https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm
	rpm -Uvh https://mirror.webtatic.com/yum/el7/webtatic-release.rpm
#	rpm -Uvh https://mirror.webtatic.com/yum/el6/latest.rpm
#	yum -y update
        yum install -y php56w.x86_64 php56w-bcmath.x86_64 php56w-cli.x86_64 php56w-common.x86_64 php56w-dba.x86_64 php56w-devel.x86_64 php56w-embedded.x86_64 php56w-enchant.x86_64 php56w-fpm.x86_64 php56w-gd.x86_64 php56w-intl.x86_64 php56w-ldap.x86_64 php56w-mbstring.x86_64 php56w-mcrypt.x86_64 php56w-mysql.x86_64 php56w-pdo.x86_64 php56w-pear.noarch php56w-snmp.x86_64 php56w-soap.x86_64 php56w-tidy.x86_64 php56w-xml.x86_64 php56w-pecl-imagick.x86_64 php56w-pecl-memcache.x86_64 php56w-imap.x86_64
	service httpd restart
fi

#Set application Directory
find / -name '*orangescrum-master*' -exec mv -t $WEBROOT/ {} +
#mv /tmp/orangescrum-master $WEBROOT/

php_version=`php -v | grep -i "PHP 5.6.35"|awk '{ print $2 }'| cut -c 1-3`
epelr=`rpm -qa | grep epel | cut -c 1-12`
epelrel=epel-release
phpadminv=`rpm -qa | grep -i phpmyadmin | cut -c 1-10`
phpadminver=phpMyAdmin
#Install phpMyAdmin(To access database Using UI)
if [ "$php_version" = "$phpversion" ]; then
	echo "Installing phpMyAdmin"
#	elif [ $epelr != $epelrel ] && [ $phpadminv != $phpadminver ]; then
	yum install -y epel-release
	yum install -y phpMyAdmin
else
	echo "PHP $phpversion not installed, phpMyAdmin will not be installed"
fi

#Installing additional required packages
vim_fs=`rpm -qa | grep vim-filesystem | cut -c 1-14`
vim_comm=`rpm -qa | grep vim-common | cut -c 1-10` 
vimenh=`rpm -qa | grep vim-enhanced | cut -c 1-12`
htmltpdf=`rpm -qa | grep wkhtmltopdf | cut -c 1-11`
vimenhance=vim-enhanced
vimfs=vim-filesystem
vimcomm=vim-common
htmlpdf=wkhtmltopdf

if [ "$vim_fs" = "$vimsfs" ] && [ "$vim_comm" = "$vimcomm" ] && [ "$vimenh" = "$vimenhance" ]; then
	echo "VIM editor already installed"
else
	yum -y install vim
fi

if [ "$htmltpdf" = "$htmlpdf" ]; then
	echo "html to pdf already installed"
else
	yum -y install wkhtmltopdf
fi

#To access phpmyadmin in browser 
#Open the file
#    	* Enable curl in php.ini
#    	* Change the 'post_max_size' and `upload_max_filesize` to 200Mb in php.ini
sed -i "s/post_max_size = /; post_max_size = /g" "/etc/php.ini"
sed -i "/; post_max_size = /apost_max_size = 200M" /etc/php.ini
sed -i "s/upload_max_filesize = /; upload_max_filesize = /g" "/etc/php.ini"
sed -i "/; upload_max_filesize = /aupload_max_filesize = 200M" /etc/php.ini
sed -i "s/max_execution_time = /; max_execution_time = /g" "/etc/php.ini"
sed -i "/; max_execution_time = /amax_execution_time = 300" /etc/php.ini
sed -i "s/memory_limit = /; memory_limit = /g" "/etc/php.ini"
sed -i "/; memory_limit = /amemory_limit = 512M" /etc/php.ini
sed -i "/; max_input_vars = /amax_input_vars = 5000" /etc/php.ini

mv /etc/httpd/conf.d/phpMyAdmin.conf /etc/httpd/conf.d/phpMyAdmin.conf_old
cp -f $APPROOT/phpMyAdmin.conf /etc/httpd/conf.d/
chmod 644 /etc/httpd/conf.d/phpMyAdmin.conf

# General Configuration management: MySQL:
# If STRICT mode is On, turn it Off.
#Disable Strict mode on mysql for Centos/Fedora  :-
#Change sql_mode=NO_ENGINE_SUBSTITUTION,STRICT_TRANS_TABLES in my.cnf
#to sql_mode=""
if [ "$mysql_v" = "$mysqlversion2" ]; then
	echo "Setting up MySQL Restriction mode"
	sed -i '/symbolic-links=0/a# \n# Recommended in standard MySQL setup\nsql_mode=""' /etc/my.cnf
else
	sed -i 's/sql_mode=NO_ENGINE_SUBSTITUTION,STRICT_TRANS_TABLES/sql_mode=""/g' /etc/my.cnf
fi

if [ "$mysqlver" = "$mysqlversion1" ]; then
	service mysql restart
else
	service mysqld restart
fi

#Provide proper write permission to " app/tmp ", " app/webroot " and " app/Config " folders and their sub folders.
cd $WEBROOT
chown -R apache:apache orangescrum-master
chmod -R 0755 orangescrum-master
cd $APPROOT
#chmod -R 0777 app/Config
chmod -R 0777 app/tmp
#chmod -R 0777 app/webroot

#Create Database and User for OS and authorize the user to the database.
mysql -uroot -p$DBPASS -e "CREATE DATABASE $DATABASE";
mysql -uroot -p$DBPASS -e "CREATE USER $DUSER@'localhost' IDENTIFIED BY '$DPASS'";
mysql -uroot -p$DBPASS -e "GRANT ALL PRIVILEGES ON $DATABASE.* TO '$DUSER'@'localhost'";

#Import database sql file:
#Navigate to application directory and import the database
cd $APPROOT
mysql -u orangescrum -p$DPASS orangescrum < database.sql

#Installing cron jobs
echo "Installing cronjobs"
echo "0 23 * * * php -q /var/www/html/orangescrum-master/app/webroot/cron_dispatcher.php   /cron/email_notification" | tee -a /var/spool/cron/root >> /dev/null
echo "*/15 * * * * php -q /var/www/html/orangescrum-master/app/webroot/cron_dispatcher.php /cron/dailyupdate_notifications" | tee -a /var/spool/cron/root >> /dev/null
echo "*/15 * * * * php -q /var/www/html/orangescrum-master/app/webroot/cron_dispatcher.php /cron/dailyUpdateMail" | tee -a /var/spool/cron/root >> /dev/null
echo "*/30 * * * * php -q /var/www/html/orangescrum-master/app/webroot/cron_dispatcher.php /cron/weeklyusagedetails" | tee -a /var/spool/cron/root >> /dev/nul

echo "Please enter your email id"
read USER_NAME
echo "Please enter your email password"
read -s EPASSWD
echo "Please enter your Domain Name or IP"
read DNAME_IP
echo "Please enter your SMTP Host"
read SMTP_ADDR
echo "Please enter your SMTP port"
read SMTP_PORT

#virtualhost
cp -f $APPROOT/orangescrum.conf /etc/httpd/conf.d/
chmod 644 /etc/httpd/conf.d/orangescrum.conf
sed -i "s/ServerAdmin Email_id_of_Admin/ServerAdmin "$USER_NAME"/" "/etc/httpd/conf.d/orangescrum.conf" >> /dev/null
sed -i "s/ServerName IP_Domain name/ServerName "$DNAME_IP"/" "/etc/httpd/conf.d/orangescrum.conf" >> /dev/null
service httpd restart

#Change Email Parameters
sed -i "s/SMTP_UNAME =/SMTP_UNAME = "$USER_NAME"/" "$APPROOT/app/Config/config.ini.php"
sed -i "s/SMTP_PWORD =/SMTP_PWORD = "$EPASSWD"/" "$APPROOT/app/Config/config.ini.php"
sed -i "s/SMTP_HOST =/SMTP_HOST = ssl:\/\/"$SMTP_ADDR"/" "$APPROOT/app/Config/config.ini.php"
sed -i "s/SMTP_PORT =/SMTP_PORT = "$SMTP_PORT"/" "$APPROOT/app/Config/config.ini.php"

echo "OrangeScrum Community Edition Installation Completed Successfully."
echo "Open you browser and access the application using the domian/IP address:"
echo "http://Your_Domain_or_IP_Address/"
