#!/bin/bash

# Source environment variables
source .env

# Install dependencies
composer install

# Start mysql container
docker run \
  -v ${APP_NAME}_db:/var/lib/mysql \
  -p ${MYSQL_PORT}:3306 \
  -e MYSQL_DATABASE=${APP_NAME} \
  -e MYSQL_USER=${MYSQL_USER} \
  -e MYSQL_PASSWORD=${MYSQL_PASSWORD} \
  -e MYSQL_ROOT_PASSWORD=${MYSQL_ROOT_PASSWORD} \
  --rm -d --name ${APP_NAME}_db mysql:${MYSQL_VER}

# Run database migrations
symfony console doctrine:migrations:migrate --no-interaction

# Start Symfony server in detached mode
symfony server:start -d
