Installation Instruction for OrangeScrum Community edition on CentOS 6.6 to 7.x
======================================================================
*Note: This script will not work on shared hosting, only works on CentOS 7 dedicated server only.
======================================================================

Prerequisities:
A Fresh CentOS 7 server
Email ID
Email Password
Email SMTP Mail Server Hostname / Private Mail Server Hostname
SMTP Port
======================================================================
*Note: If Gmail ID used make sure to enable the “less secure app” fundtion. You can do this by logging into the gmail account and go to “My Accounts” then click on “Sign-in & Security” and the  enable the “Allow less secure apps: ON”.
======================================================================
*Note: Make sure to whitelist the application server domain/IP address from the private Mail Server.
======================================================================
*Note: OrangeScrum will only works on MySQL 5.4-5.7, Apache 2.4 and PHP 5.4-5.6
======================================================================

What the installation package does:
Installs required packages and dependecies to support the application
Installs and configure Apache Web Server
Installs MySQL Server, creates database and configures user for database or create database in an existing mysql setup and configures user
Setup cron jobs
configure email settings

Steps to install application:
Download the OrangeScrum Community Edition Package

Copy it to the Server’s “/tmp” directory where it will be installed.
*You can use “Winscp” of “Filezilla” to upload the package to your Server

Change the directory to "/tmp"
# cd /tmp

Extract the compressed file:
#tar xzf os_community*.*tar.gz

Change the directory to “orangescrum-master”:
#cd orangescrum-master

Run the setup.sh file:
./setup

It will install all the required packages and dependecies and will ask for email ID, Email Password, SMTP mail server address and SMTP port, Domain name or IP address for the Host.

Open you browser and access the application using the domian/IP address:
http://Your_Domain_or_IP_Address
