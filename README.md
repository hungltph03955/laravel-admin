
## Laravel Admin API

## Set up laravel:

set up environment:

- git clone https://github.com/hungltph03955/laravel-admin.git
- docker-composer up

set up database: (new windows command)
- docker exec -it admin_api sh
- php artisan migrate
- php artisan db:seed
- php artisan passport:install

set swagger document api: 
- php artisan token:generate 13
- php artisan l5-swagger:generate

## API Postman:

Import API JSON:
- RestfulApi-docker.postman_collection.json
