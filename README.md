# Banking API

---
## Installation
This application is using `docker compose` to initialize and start working.

Clone the project
```bash
git clone https://github.com/amirdaraby/banking-api
```

### Environment variables
env variables are stored in (.env), you need to run the following command to have a sample of `.env`

```bash
cp .env.example .env
```

there is some additional environment variables:

``APP_NAME``: docker compose uses this variable to name containers

``APP_PORT``: nginx image uses this variable to give a port in local machine 

``DB_EXTERNAL_PORT``: MySQL image uses this variable to give a port in local machine

``DB_ROOT_PASSWORD``: root password

``REDIS_EXTERNAL_PORT``: Redis image uses this to give a port in local machine

``WWW_USER``: this is the user in php container (for example `WWW_USER` can be 0 (root user) or 1000 (www-data))

### Run application using docker
Since `APP_NAME`'s value is `banking-api` by default, i use "banking-api" as prefix of containers in my following examples.

Run following command to build and up containers
```bash
docker compose up -d --build --force-recreate
```

Now docker containers are up and running, Run the following commands to install dependencies and generate the app key.

Install Dependencies
```bash
docker exec -t banking-api-php bash -c "composer install"
```

Generate App key
```bash
docker exec -t banking-api-php bash -c "php artisan key:generate"
```

Run migrations
```bash
docker exec -t banking-api-php bash -c "php artisan migrate"
```

Seed database
```bash
docker exec -t banking-api-php bash -c "php artisan db:seed"
```

---

## Testing

Run project tests using following command to make sure application works fine
```bash
docker exec -t banking-api-php bash -c "php artisan test"
```

---
## Postman
this project has a Postman collection to make sure there is a good interface to call and test APIs. [Postman Collection](https://www.postman.com/lunar-capsule-456734/workspace/amirdaraby-banking-api/overview`) 

---
## SMS
For Sending SMS, `Ghasedak` and `Kavenegar` is available right now. Please check `config/sms.php` file for more information.

Project's SMS provider is using `Strategy Pattern`, this means you can add any SMS web service you want without need to make changes in business logic.

Please check `app/Sms`
