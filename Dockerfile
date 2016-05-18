FROM php:5.5

MAINTAINER adityaii@gmail.com
# PHP Container for orangescrum

# Clone orangescrum/orangescrum
CMD git clone https://github.com/orangescrum/orangescrum

EXPOSE 8080

CMD cd $HOME && ls -ltr && echo "Work in progress."
