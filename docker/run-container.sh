#!/bin/bash

# ensure running bash
if ! [ -n "$BASH_VERSION" ];then
    echo "this is not bash, calling self with bash....";
    SCRIPT=$(readlink -f "$0")
    /bin/bash $SCRIPT
    exit;
fi

SCRIPT=$(readlink -f "$0")
SCRIPTPATH=$(dirname "$SCRIPT") 
cd $SCRIPTPATH

# load the variables
source $SCRIPTPATH/docker-settings.sh

CONTAINER_IMAGE="`echo $TAG`"

docker kill $PROJECT_NAME
docker rm $PROJECT_NAME

# Create the data volume structure if it doesn't already exist
HOST_PATH="$SCRIPTPATH/../../data"
if [ ! -d $HOST_PATH ]; then
    echo "initializing data volume"

    # Take care of upload files
    mkdir -p $HOST_PATH/files
    cp -r $SCRIPTPATH/../app/webroot/files $HOST_PATH/

    # take care of configuration files
    cp $SCRIPTPATH/../app/Config/database.php $HOST_PATH/
    cp $SCRIPTPATH/../app/Config/constants.php $HOST_PATH/
    
    # now take care of permissions so the docker container can use the files
    sudo chmod 770 $HOST_PATH/files
    sudo chown www-data:www-data -R $HOST_PATH
fi

docker run -d \
-v $HOST_PATH:/data \
-p 80:80 -p 443:443 \
--restart=always \
--name="$PROJECT_NAME" \
$CONTAINER_IMAGE

