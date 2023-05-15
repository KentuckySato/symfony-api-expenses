# REST API for expense management

This project is based on the PHP Symfony 6.

Creating a REST API for expense management.

# Prerequisites

- PHP 8.1 or higher
- Composer
- Docker
- Docker-compose

# Installation

- Clone the repository
- Run `composer install`
- Run `docker compose up -d`
- Run `symfony console app:reset-db` to create the database and load the fixtures

# Migration

## Create migration

```bash
symfony console make:migration
```

## Launch migrations

```bash
symfony console doctrine:migrations:migrate
```

## Add fixtures

```bash
symfony console doctrine:fixtures:load
```

