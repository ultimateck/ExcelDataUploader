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
- Setup .env.testing for test configurations (optional)
- port 8080 and 3306 available in the host machine. (This can be configured in `docker-compose.yml`)

#### Docker configs
Project root consist of following directories and files for dockerization of the application
- docker-compose.yml - all docker services are configured in here
- docker directory
    - mysql/my.cnf - (Optional) Mysql configuration
    - nginx/nginx.conf - Nginx configuration file
    - php/local.ini - PHP configurations
    - Dockerfile.app - edu-app docker file
    - Dockerfile.queue - edu-queue docker file
    - worker.conf - Supervisord configuration

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
DB_PASSWORD=root

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

Use web browser to open the frontend http://localhost:8080/upload and start uploading excel files.

#### Sample Excel files
Excel file format for uploading.

| Code | Description | Quantity | Price |
| ----------- | ----------- | ----------- | ----------- |
| IT001 | Test item 1 | 5 | 12.00 |
| IT002 | Test item 2 | 2 | 6.50 |

**Require fields**
- `Code`
- `Quantity`
- `Price`

Example excel files can be found under  [/tests/Files](/teste/Files) directory. These files can be used for testing.

- items.xlsx
- items-invalid-coloumns.xlsx
- items-invalid-rows.xlsx

### Run Tests
```bash
# login to edu-app shell and run artisan test on default databse
docker exec -it edu-app /bin/bash
php artisan test
```

#### **Run tests in test env**
Add following line to `phpunit.xml`
```xml
<env name="DB_DATABASE" value="testing" />
```

Setup `.env.testing` environment file and add database name
```bash
DB_DATABASE=testing
```

Create testing database in MySQL server. Connect to edu-db container shell and login to MySQL CLI.
```bash
docker exec -it edu-db /bin/bash
mysql -u root -p
Enter password:

# Once login to MySQl server create testing database
create database testing;
```

Run the artisan test command using the testing database.
```bash
docker exec -it edu-app /bin/bash
php artisan key:generate --env=testing
php artisan migrate --env=testing
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
