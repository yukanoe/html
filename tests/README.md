# TEST

## Built-in web server 

### Requirements
 - **eval()** enabled

### URL
 - [http://localhost:8080/](http://localhost:8080/)

### Start Live-Server ( Read-Only )

```bash
cd ./tests
composer update
php -S localhost:8080 live-server.php
```

### Start Static-Server

```bash
cd ./tests
composer update
php build.php
php -S localhost:8080 static-server.php
```
