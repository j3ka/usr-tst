###Dependencies:
1. docker
2. docker-compose
3. composer
***
###Installation:
In ``web-app`` directory execute:
```bash
$ composer install --ignore-platform-reqs
```

For build in root directory execute:
```bash
$ make init
```
For up containers:
```bash
$ make up
```
***
###Composer dependencies:
1. psr/container
2. psr/http-message
3. psr/http-server-handler
4. phpunit/phpunit
5. predis/predis
6. php-amqplib/php-amqplib
***
###To run tests:
```bash
$ ./vendor/bin/phpunit ./tests
```
***
###Routing:
1. /user/import
2. /user/search
***
####Maximum uploaded file size is 100M
####Ports:
80 -> localhost:8888
Rabbit: localhost:15672 (user:user)
***
I use curl to upload big files:
```bash
$ curl -v -F users=@"[PATH_TO_FILE]" http://localhost:8888/user/import
```

