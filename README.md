# ExcelDataUploader
PHP Laravel excel file upload with queue processing and docker compose setup

### Technologies
- Laravel 10.x
- Mysql 8.3
- PHP8.2-fpm
- Nginx
- Docker & docker-compose

### Prerequisites
- Docker and docker-compose installed
- Setup .env for configurations
- port 8080 and 3306 available in host machine. (This can be configured in `docker-compose.yml`)

#### Setup .env file
In the project root directory create new `.env` file using `.env.example`. Modify following configurations in the `.env` file.

`DB_PASSWORD` field can be set with any string and this value will be used in docker-compose file to setup the mysql root user password.
```bash
# DB configs
DB_CONNECTION=mysql
DB_HOST=edu-db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=root # set any password for root.

# Queue configs
QUEUE_CONNECTION=database
```

### Run Application
cd into project root directory and run command. If sudo require please add `sudo` command before `docker-compose`. This will build images and create containers with output to terminal.

```
docker-compose up --build --force-recreate
```

#### Run in background
```
docker-compose up --build --force-recreate -d
```

#### Run Artisan Commands
```bash
# login to the edu-app shell
docker exec -it edu-app /bin/bash
# if using windows git bash use below command
docker exec -it edu-app //bin//bash

# make sure you are in the project root working directory /var/www
# run artisan key generate
php artisan key:generate

# then run artisan migrate to generate db tables
php artisan migrate

# exit from the container shell
exit
```

#### App startup
Once above steps complete, the app will start on the configured port (ex: 8080)

Use web browser to open the frontend http://localhost:8080/upload and strat uploading excel files.

### Run Tests
```bash
# login to edu-app shell and run artisan test
docker exec -it edu-app /bin/bash
php artisan test
```

### Remove containers
```
docker-compose down
```

Remove containers along side with created volume for database and images, please run the following command
```
docker-compose down --rmi all --volumes
```
