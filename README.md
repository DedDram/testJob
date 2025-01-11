

## Clone the Repository

git clone https://github.com/DedDram/testJob

cd your-repository

## Set Up Environment Variables

cp .env.example .env

## Build and Start Docker Containers

docker-compose up -d --build

### Install PHP Dependencies

docker exec -it laravel_app composer install

## Swagger

http://localhost:9000/api/documentation

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
