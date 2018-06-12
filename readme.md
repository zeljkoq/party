# Quantox Hotel

### Version
1.0.0

## Setup Project

### Clone project from GitHub

```sh
$ git clone https://github.com/kuzmic3/party.git folder-name
```

### Generate vendor file with composer

```sh
$ composer update
```

### Generate Laravel key

```sh
$ php artisan key:generate
```

### Database

Setup your database parameters in .env file

```sh
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=database_name
DB_USERNAME=username
DB_PASSWORD=password
```

### Migrations and seeds
```sh
$ php artisan migrate --seed
```