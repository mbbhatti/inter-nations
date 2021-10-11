# InterNations
API integration for user and group management.

## Requirements
- PHP >= 7.1
- symfony >= 4.4

## Installation 
Symfony utilizes composer to manage its dependencies. So, before using symfony, make sure you have composer installed on your machine. To download all required packages run a following commands or you can download [Composer](https://getcomposer.org/doc/00-intro.md).
- composer install `OR` COMPOSER_MEMORY_LIMIT=-1 composer install

## Database
Need to set a .env file to make database connection with this setting.
```
DATABASE_URL="mysql://db_user:db_password@host:port/db_name
```

Use following commands to create and run migration for your database.
- mkdir migrations ```(use if migrations folder not created)```
- php bin/console make:migration
- php bin/console doctrine:migrations:migrate 

## Run
Use below command to start the project.
```
symfony server:start 
OR
php bin/console server:run
```

## API
```text
The {{base_url}} can be replaced with your local url.
```
### User API's
- POST {{base_url}}/user
- DEL {{base_url}}/user/1

### Group API's
- POST {{base_url}}/group
- DEL {{base_url}}/group

### Assign and Un-Assign user group API's
- POST {{base_url}}/group/user
- DEL {{base_url}}/group/user

## Documentation
- you can find small domain model uml, database model diagram and api json file of this flow in the docs.