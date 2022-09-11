# Tennis Scoreboard
A simple app to score a single tennis game

## Install & Run

Docker:

1. `docker-compose build app && docker-compose up -d`
2. `docker exec scoreboard-app ./install.sh`
3. Connect to app on http://localhost:8000

## Run tests

- `docker exec scoreboard-app php artisan test`
