# Rendez-vous école

Application de prise de rendez-vous entre les parents et les enseignants.

## Prérequis
- PHP 8.3
- Composer
- Node.js 20.15
- PostgreSQL

## Installation

````shell
composer install
````

````shell
npm ci
````

````shell
npm run watch
````

````shell
symfony server:start
````

## Migration 
```shell
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

## Fixtures
```shell
php bin/console doctrine:fixtures:load
```



