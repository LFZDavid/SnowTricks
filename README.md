# Snowtricks

## Collaborative platform for sharing about snowboard tricks. <br>

<img src="https://symfony.com/images/logos/sf-positive.svg" alt="symfony-logo" width="50" />  

_developped with Symfony 5.2_

[![Maintainability](https://api.codeclimate.com/v1/badges/92e0e42c5582735a889f/maintainability)](https://codeclimate.com/github/LFZDavid/SnowTricks/maintainability)


---
## Technical Requierments :
* PHP 
    * version 7.4 or higher
* Database 
    * mysql 5.7 or higher
    * mariadb:10.2 or higher
* Composer
    * version 2 or higher

more infos : _[symfony documentation](https://symfony.com/doc/current/setup.html#technical-requirements)_

---

## Installation : 
1. ### Get files : 
```
git clone https://github.com/LFZDavid/SnowTricks.git
```

2. ### Install dependencies : 
```
 composer install
 ```
3. ### Database :
    * set database connection in `.env` file
    * create database : 
    ```
    php bin/console doctrine:database:create
    ```
    * build structure : 
    ```
    php bin/console doctrine:migrations:migrate
   ``` 
   * Import datas : 
   use `database/snowtricks.sql` on your favorite database manager _(ex : [phpmyadmin](https://www.phpmyadmin.net/))_
4. ### Set mailer connection :
    *   In `.env` file
    ```
    MAILER_DSN=smtp://user:pass@smtp.example.com
    ```
---
