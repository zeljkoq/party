# Quantox Hotel

### Version
1.0.0

## Setup Virtual Host on Ubuntu

Create the directory
```sh
$ sudo mkdir -p /var/www/html/hotel.local
```

Grant Permission
```sh
$ sudo chown -R $USER:$USER /var/www/html/hotel.local
```
```sh
$ sudo chmod -R 755 /var/www/html/hotel.local
```

Create New Virtual Host Files
```sh
$ sudo cp /etc/apache2/sites-available/000-default.conf /etc/apache2/sites-available/hotel.local.conf
```
```sh
$ sudo nano /etc/apache2/sites-available/hotel.local.conf
```
```sh
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    ServerName hotel.local
    ServerAlias www.hotel.local
    DocumentRoot /var/www/html/hotel.local/public
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

Enable the New Virtual Host File
```sh
$ sudo a2ensite hotel.local.conf
```

Restart the apache
```sh
$ sudo service apache2 restart
```

Set Up Local Hosts File
```sh
$ sudo nano /etc/hosts
```
```sh
127.0.0.1           localhost
127.0.1.1           guest-desktop
111.111.111.111     hotel.local
```

## Setup Project

Navigate into html folder
```sh
$ cd /var/www/html
```

Clone project from GitHub
```sh
$ git clone https://github.com/kuzmic3/party.git hotel.local
```

Navigate into project folder
```sh
$ cd hotel.local
```

Run the composer update
```sh
$ composer update
```

Generate Laravel key
```sh
$ php artisan key:generate
```

Setup your database parameters in .env file
```sh
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=database_name
DB_USERNAME=username
DB_PASSWORD=password
```
Migrations and seeds
```sh
$ php artisan migrate --seed
```

## Setup JWT Auth

Require package (version 1.0)
```sh
$ composer require tymon/jwt-auth
```

Publish the config
```sh
$ php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
```

Generate secret key
```sh
$ php artisan jwt:secret
```