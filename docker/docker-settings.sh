# Specify the project name. This will be used in various areas, including the name of the container
# This has to be all lowercase.
PROJECT_NAME="scrumptious"

# Specify the name for the deployed application container. This will show up when you use 
# docker ps
APP_CONTAINER_NAME="scrumptious-app"

# Specify the name for the deployed mysql container. This will show up when you use 
# docker ps
MYSQL_CONTAINER_NAME="scrumptious-mysql"

# If you have docker user account, you may want to put your username here to push the built
# image to your registry. You can leave this blank if you don't have a registry.
REGISTRY=""

# specify the tag for your container. If you have a registry, then this might be:
# {username}/orangescrum or you may just wish to use the default below
APP_TAG="$PROJECT_NAME"

# Specify the name of the database as it will appear inside mysql
MYSQL_DB_NAME="orangescrum"

# Specify the root password for the database.
# @TODO implement a single-run randomly generated password system.
MYSQL_ROOT_PASSWORD="changeme123"

# Specify the name of the mysql image we will use for the database.
# refer here: https://hub.docker.com/_/mysql/
MYSQL_IMAGE_NAME="mysql:5.6"

