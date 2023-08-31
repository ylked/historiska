#!/bin/bash

container_name=historiska-api-server

#cd `git rev-parse --show-toplevel`
#cd historiska-api-server
cd ..

docker compose up -d

if [[ $? -ne 0 ]]; then
  echo "an error occured while trying to run docker containers. Please fix your docker installation."
  exit -1
fi

if [[ ! -f .env ]]; then
cat <<EOF > .env
APP_NAME=Historiska
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost
LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=historiska
DB_USERNAME=root
DB_PASSWORD=admin
EOF

fi

docker exec $container_name sh -c "php artisan key:generate"

if [[ $? -ne 0 ]]; then
  echo an error occured while trying to setup application key
  exit -1
fi

docker exec $container_name sh -c "php artisan migrate --force"

if [[ $? -ne 0 ]]; then
  echo an error occured while trying to setup application key
  exit -1
fi

# create default admin account with token='0'
docker exec historiska-database sh -c "mariadb -u root -padmin -h db -D historiska -e \"insert into user (is_admin, username, email, password, is_activated, token, token_issued_at) values (1, 'admin', 'null', '$2a$12$cLpI7PAtHjtDpaBtdzULAuJAhLyFpjzcb8oZX2riQ5EFa3GvepXzG', 1, '0', now()); \""

export HISTORISKA_API_KEY=0
cd data
python3 generate.py

docker exec historiska-database sh -c "mariadb -u root -padmin -h db -D historiska -e \"delete from user where username='admin'\""
