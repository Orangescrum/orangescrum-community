#!/bin/bash

# ensure running bash
if ! [ -n "$BASH_VERSION" ];then
    echo "this is not bash, calling self with bash....";
    SCRIPT=$(readlink -f "$0")
    /bin/bash $SCRIPT
    exit;
fi

# Get the path to script just in case executed from elsewhere.
SCRIPT=$(readlink -f "$0")
SCRIPTPATH=$(dirname "$SCRIPT")
cd $SCRIPTPATH

# Load the variables from settings file.
source $SCRIPTPATH/docker-settings.sh

# We need to move the dockerfile to above the top directory so that it can easily add everything 
# to the container.
rm -rf /tmp/orangescrum # remove any files left over from a previous failed build
mkdir /tmp/orangescrum
mkdir /tmp/orangescrum/src
cp -rf ../* /tmp/orangescrum/src/.
cp -f Dockerfile /tmp/orangescrum
cd /tmp/orangescrum

# Ask the user if they want to use the docker cache
read -p "Do you want to use a cached build (y/n)? " -n 1 -r
echo ""   # (optional) move to a new line
if [[ $REPLY =~ ^[Yy]$ ]]
then
    docker build --tag $APP_TAG .
else
    docker build --no-cache --pull --tag $APP_TAG .
    docker pull $MYSQL_IMAGE
fi

# clean up
rm -rf /tmp/orangescrum


if [[ $REGISTRY ]]
then 
    docker push $REGISTRY/$PROJECT_NAME
else
    echo "You have not set the REGISTRY variable, so we will not push built image."
fi

echo "Run the container with the following command:"
echo "bash run-container.sh"
