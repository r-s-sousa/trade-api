[![Laravel](https://github.com/r-s-sousa/trade-api/actions/workflows/laravel.yml/badge.svg)](https://github.com/r-s-sousa/trade-api/actions/workflows/laravel.yml)

# How Start

This is an example of how to configure and start the server.

**References:**

- [diagrams](https://drive.google.com/file/d/1LSS2NH8oR__IrDeB39SLGKip6boYZW28/view?usp=sharing)
- [trade-infra](https://github.com/r-s-sousa/trade-infra)
- [trade-ui](https://github.com/r-s-sousa/trade-ui)
- [trade-api](https://github.com/r-s-sousa/trade-api)

## Configure and start the server

### Requirements

- PHP 8.3.7 or higher
- Composer 2.2.0 or higher
- Docker and Docker Compose

### Environments

Replace the values in the `.env` file in the root of this project.

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=soccer
DB_USERNAME=root
DB_PASSWORD=root

### PostgreSQL database

Inside the [trade-infra](https://github.com/r-s-sousa/trade-infra) folder run:

```bash
docker compose up -d
```

### Run Migrations

in the root of this project run:

```bash
php artisan migrate:refresh --seed
```

### Start the Server

in the root of this project run:

```bash
php artisan serve
```

Congratulations! You have successfully configured and started the server. Now go to UI or Postman to test this API.

### Postman

Postman files can be found in the [trade-infra](https://github.com/r-s-sousa/trade-infra) /postman folder.

**The files to import:**

*Postman Collections:*

- postman/Auth.postman_collection.json
- postman/Championship-teams.postman_collection.json
- postman/Championship.postman_collection.json
- postman/Game.postman_collection.json
- postman/Team.postman_collection.json

*Postman Environments:*

- postman/trade-environments.postman_environment.json
