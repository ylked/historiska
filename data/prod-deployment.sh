#!/bin/bash
FILE=$(pwd)/deployment.log

log() {
	echo "$(date) : $1" >> $FILE
	echo $1
}

log "starting deployment"
container_name=historiska-api-server

pwd|grep "historiska/data" > /dev/null || (echo "$(date) --- ERROR --- please run this script from /historiska/data folder" && exit 6)

cd ..

docker compose up -d || (log "---- ERROR ---- an error occured while trying to launch docker containers. Please fix your installation" && exit 5)

cd historiska-api-server

sleep 1

docker exec $container_name sh -c "php artisan migrate --force" >> $FILE || (log "---ERROR--- could not create database" && exit 8)

sleep 1

# create default admin account with token='0'
docker exec historiska-database sh -c "mariadb -u root -padmin -h db -D historiska -e \"insert into user (is_admin, username, email, password, is_activated, token, token_issued_at) values (1, 'admin', 'null', '$2a$12$cLpI7PAtHjtDpaBtdzULAuJAhLyFpjzcb8oZX2riQ5EFa3GvepXzG', 1, '0', now()); \"" >> $FILE

export HISTORISKA_API_KEY=0
sleep 5
cd ../data
python3 generate.py >> $FILE || (log "---ERROR--- An error occurred while running python script. This is likely due to the server not having finished setting up. Please wait a few minutes and run this script again later." && exit 9)

docker exec historiska-database sh -c "mariadb -u root -padmin -h db -D historiska -e \"delete from user where username='admin'\"" >> $FILE

log "deployment done"

echo "--------------------------------">>$FILE
