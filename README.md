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

# Migration

## Créer une migration

```bash
symfony console make:migration
```

## Exécuter les migrations

```bash
symfony console doctrine:migrations:migrate
```

## Ajouter des données de tests

```bash
symfony console doctrine:fixtures:load
```

