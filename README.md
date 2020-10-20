# api-task

## Running this project 

1. install docker and docker-compose
1. clone repository 
    ```
    git clone git@github.com:piotrekkr/api-task.git
    cd api-task/
    ```
1. copy example compose file
    ```shell script
    cp docker-compose.example.yml docker-compose.yml
    ```
1. run containers
    ```shell script
    docker-compose up -d
    ```
1. crate db schema
    ```shell script
    docker-compose exec php bin/console doctrine:schema:create
    ```
1. application should be available at http://localhost:8888/

## Creating products

```shell script
curl -i -X POST localhost:8888/product -d "name=test&price=123.1"
```
Example output:
```
HTTP/1.1 201 Created
Host: localhost:8888
Date: Tue, 20 Oct 2020 21:15:56 GMT
Connection: close
X-Powered-By: PHP/7.4.7
Cache-Control: no-cache, private
Date: Tue, 20 Oct 2020 21:15:56 GMT
Content-Type: application/json
X-Robots-Tag: noindex

{"id":7,"name":"test","price":123.1}
```

## Running tests

```shell script
docker-compose exec php bin/phpunit
```
Example output:
```
PHPUnit 7.5.20 by Sebastian Bergmann and contributors.

Testing Project Test Suite
.....                                                               5 / 5 (100%)

Time: 66 ms, Memory: 10.00 MB

OK (5 tests, 32 assertions)
```