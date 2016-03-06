#!/bin/bash

# For reference, information about running mysql inside docker can be found here:
# https://hub.docker.com/_/mysql/

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

APP_IMAGE_NAME="`echo $APP_TAG`"

# Kill and remove the existing docker containers if they are already running.
docker kill $APP_CONTAINER_NAME
docker rm $APP_CONTAINER_NAME
docker kill $MYSQL_CONTAINER_NAME
docker rm $MYSQL_CONTAINER_NAME

# Create the applicaton data volume structure if it doesn't already exist
APP_CONTAINER_PATH="$SCRIPTPATH/../../data/app"
if [ ! -d $APP_CONTAINER_PATH ]; then
    echo "initializing application volume"

    # Take care of upload files
    mkdir -p $APP_CONTAINER_PATH/files
    cp -r $SCRIPTPATH/../app/webroot/files $APP_CONTAINER_PATH/

    # take care of configuration files
    cp $SCRIPTPATH/../app/Config/database.php $APP_CONTAINER_PATH/
    cp $SCRIPTPATH/../app/Config/constants.php $APP_CONTAINER_PATH/
    
    # now take care of permissions so the docker container can use the files
    sudo chmod 770 $APP_CONTAINER_PATH/files
    sudo chown www-data:www-data -R $APP_CONTAINER_PATH
fi

# Create the database data volume structureS if it doesn't already exist
DB_CONFIG_VOLUME_PATH="$SCRIPTPATH/../../data/db/config"
DB_DATA_VOLUME_PATH="$SCRIPTPATH/../../data/db/data"

if [ ! -d DB_CONFIG_VOLUME_PATH ]; then
    echo "initializing database config volume"
    mkdir -p $DB_CONFIG_VOLUME_PATH
fi

if [ ! -d DB_DATA_VOLUME_PATH ]; then
    echo "initializing database data volume"
    mkdir -p $DB_DATA_VOLUME_PATH
fi

# Copy the configuration file into the mysql volume
cp $SCRIPTPATH/mysql-configuration-overrides.cnf $DB_CONFIG_VOLUME_PATH 


# Start the MySQL container.
docker run \
--restart=always \
--name $MYSQL_CONTAINER_NAME \
-v $DB_CONFIG_VOLUME_PATH:/etc/mysql/conf.d \
-v $DB_DATA_VOLUME_PATH:/var/lib/mysql \
-e MYSQL_ROOT_PASSWORD=$MYSQL_ROOT_PASSWORD \
-e MYSQL_DATABASE=$MYSQL_DB_NAME \
-d $MYSQL_IMAGE_NAME

# Wait for the database to set up. 
# Unfortunately because this is necessary there may be issues with integrating 
# docker compose.
echo "Waiting 10 seconds to give the database time to set up..."
sleep 10

# Start the application container.
docker run -d \
-v $APP_CONTAINER_PATH:/data \
-p 80:80 -p 443:443 \
--restart=always \
--name="$APP_CONTAINER_NAME" \
--link=$MYSQL_CONTAINER_NAME:mysql \
$APP_IMAGE_NAME
