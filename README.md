
## Laravel Admin API

## Set up laravel:

Set up environment:

- git clone https://github.com/hungltph03955/laravel-admin.git
- docker-composer up

Set up database: (new windows command)
- docker exec -it admin_api sh
- php artisan migrate
- php artisan db:seed
- php artisan passport:install

Set swagger document api: 
- php artisan token:generate 13
- php artisan l5-swagger:generate

Add .env:
- APP_URL=http://localhost:8000
- DB_CONNECTION=mysql
- DB_HOST=127.0.0.1
- DB_PORT=3306
- DB_DATABASE=laravel
- DB_USERNAME=root
- DB_PASSWORD=
- L5_SWAGGER_CONST_HOST=http://localhost:8000

## API Postman:

Import API JSON:
- RestfulApi-docker.postman_collection.json

