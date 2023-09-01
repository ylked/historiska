#!/bin/bash

install_requests(){
  pip3 install requests || python3 -m pip install requests || (echo "could not install requests with pip" && exit 4)
}

# checking software
echo checking versions...
echo "---docker :"
docker version || exit 1
echo "---docker compose"
docker compose version || exit 2
echo "---python"
python3 --version || exit 3
echo "---requests"
python3 -c "import requests" || install_requests
echo --- DONE ---
container_name=historiska-api-server

pwd|grep "historiska/data" > /dev/null || (echo "--- ERROR --- please run this script from /historiska/data folder" && exit 6)

cd ..

docker compose up -d || (echo "---- ERROR ---- an error occured while trying to launch docker containers. Please fix your installation" && exit 5)

cd historiska-api-server

# create .env if not existing
# this file should not be hard written here but as this is a 'proof-of-concept' project, we will allow it this time...
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
MAIL_MAILER=smtp
MAIL_HOST=mail.infomaniak.com
MAIL_PORT=465
MAIL_USERNAME=admin@historiska.ch
MAIL_PASSWORD=Q@u9rnHiq297WoGtYCV-rD
MAIL_FROM_ADDRESS="admin@historiska.ch"
MAIL_FROM_NAME="Historiska"
MAIL_LINK_URL=http://localhost:3000
EOF

fi

sleep 1

docker exec $container_name sh -c "php artisan key:generate" || (echo "----ERROR--- could not setup application key" && exit 7)

sleep 1

docker exec $container_name sh -c "php artisan migrate --force" || (echo "---ERROR--- could not create database" && exit 8)

sleep 1

# create default admin account with token='0'
docker exec historiska-database sh -c "mariadb -u root -padmin -h db -D historiska -e \"insert into user (is_admin, username, email, password, is_activated, token, token_issued_at) values (1, 'admin', 'null', '$2a$12$cLpI7PAtHjtDpaBtdzULAuJAhLyFpjzcb8oZX2riQ5EFa3GvepXzG', 1, '0', now()); \""

export HISTORISKA_API_KEY=0
sleep 5
cd ../data
python3 generate.py || (echo "---ERROR--- An error occurred while running python script. This is likely due to the server not having finished setting up. Please wait a few minutes and run this script again later." && exit 9)

docker exec historiska-database sh -c "mariadb -u root -padmin -h db -D historiska -e \"delete from user where username='admin'\""

echo "--- DONE --- The setup has succeeded. You can now access the app on http://localhost:3000"
