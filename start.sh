#!/bin/bash

if [[ ! -f .env ]]; then
  cp .env.example .env
fi

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

## Run database migrations
echo "trying to execute migrations..."

symfony console doctrine:migrations:migrate -n
status=$?

while [ $status -ne 0 ]; do
  sleep 5
  symfony console doctrine:migrations:migrate -n
  status=$?
done

## Run fixtures
echo "executing fixtures..."
symfony console doctrine:fixtures:load -n

## Start Symfony server in detached mode
symfony server:start -d
