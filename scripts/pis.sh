#!/bin/bash
set -e
echo "Welcome to OrangeScrum"
 if cat /etc/*release | grep ^PRETTY_NAME | grep CentOS; then
    echo "Installing OrangeScrum on CentOS-7"
    cd /tmp
    wget ftp://192.168.2.215/pub/scripts/setup_centos7_new
    chmod +x setup_centos7_new
    ./setup_centos7_new
 elif cat /etc/*release | grep ^PRETTY_NAME | grep Red; then
    echo "Installing OrangeScrum on Redhat-7"
    cd /tmp
    wget ftp://192.168.2.215/pub/scripts/setup_centos7_new
    chmod +x setup_centos7_new
    ./setup_centos7_new
 elif cat /etc/*release | grep ^PRETTY_NAME | awk -F'"' '{print $2}'; then
    if (( $(cat /etc/*release | grep ^VERSION_ID | awk -F'"' '{print $2}' | awk -F'.' '{print $1}') >= 18 ))
    then
    	echo "Installing OrangeScrum on Ubuntu-18"
        cd /tmp
        wget ftp://192.168.2.215/pub/scripts/setup_ubuntu18_new
        chmod +x setup_ubuntu18_new
        ./setup_ubuntu18_new
    else
    	echo "Installing OrangeScrum on Ubuntu-16"
        wget ftp://192.168.2.215/pub/scripts/setup_ubuntu16_new
	chmod +x setup_ubuntu16_new
	./setup_ubuntu16_new
    fi
 elif cat /etc/*release | grep ^PRETTY_NAME | grep Debian ; then
        echo "Installing OrangeScrum on Debian-9"
        wget ftp://192.168.2.215/pub/scripts/setup_debian9_new
	chmod +x setup_debian9_new
	./setup_debian9_new
 else
    echo "OS NOT DETECTED, couldn't install OrangeScrum, Please try Again..."
    echo "This Automated installation script will work on CentOS-7, Ubuntu-16, Ubuntu-18 and Debian-9 only."
    echo "For any Other OS, please follow manual steps on https://www.orangescrum.org/"
    exit 1;
 fi
 
exit 0
