"Scootin-Aboot" (Scootin API)
=====

REST API based system which provides information about scooters and ability to reserve or revoke scooter reservation.
System uses MYSQL database to store data, symfony framework to handle endpoints and swagger ui for API documentation.
It does use some 3rd party libraries to resolve some problems faster.

## Installation
 - Run `docker-compose up -d --build` to load everything
 - Describe `ENDPOINT_URL` to provide proper url for bot command

## API documentation
Documentation is accessible at `localhost:60` after docker load up (Can be used to test all endpoints).

## Bot
- Run `php bin/console bot:execute` to execute bot singularly 
- Run `php bin/console multi:bot:execute {botCount}` to execute multiple bots by providing bot count (runs infinitely)

Bot finds any available client that does not have reservation, proceeds to get available scooter, travels for 15s
whilst updating location every 3s and rest for 5s.

 
