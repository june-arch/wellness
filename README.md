# Statusehat API

## Requirements
- `php8.2` with `Composer`
- Database: `MySQL`, `Posgress`, Etc.
- Cache: `Redis`, `Memcached`
- Mailer: `Mailpit`, `SMTP`, etc
- Storage: `Local Storage` if not using `Amazon S3`
- `Cronjob` or `VPS Server` to run scheduler

## How To

1. Clone Repository
```bash
  git clone https://github.com/june-arch/wellness
```

2. Install Packages
```bash
  composer install
```

3. Set environment variable
```bash
  cp .evn-example .env
```

4. Run migration and seeder
    
   Make sure you already set correct database credential inside `.env`
```bash
  php artisan migrate
  php artisan db:seed // Optional to seed example data
```

5. Run dev server
```bash
  php artisan serv

  /vendor/bin/sail up -d // Using docker as dev container
```

6. Optimize
  
    You can optimize `routes`, `cache`, and `Laravel Blade` with
```bash
  php artisan optimize
```

7. Storage
  
    Link your uploads storage to public with
```bash
  php artisan storage:link
```

## Deployment


### NGINX

Please ensure, like the configuration below, your web server directs all requests to your application's `public/index.php` file. You should never attempt to move the `index.php` file to your project's root, as serving the application from the project root will expose many sensitive configuration files to the public Internet:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name example.com;
    root /srv/example.com/public;
 
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
 
    index index.php;
 
    charset utf-8;
 
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
 
    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }
 
    error_page 404 /index.php;
 
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
 
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Docker

To use docker as dev container or to deploy just run command bellow. Be aware because this command also run queue worker to execute scheduller

```bash
docker-compose up
```

## Contributors
[![ashirogirz](https://avatars.githubusercontent.com/u/15813991?s=64&v=4&fit=cover&h=300&w=300&mask=circle)](https://github.com/ashirogirz)
[![june-arch](https://avatars.githubusercontent.com/u/54971444?s=64?v=4&fit=cover&h=300&w=300&mask=circle)](https://github.com/june-arch)
