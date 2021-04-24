Laravel Docker Images
=====================

- [Laravel Docker Images](#laravel-docker-images)
  - [⛽ Octane](#-octane)
  - [🖥 PHP-FPM](#-php-fpm)
  - [🤖 Workers](#-workers)

Already-compiled PHP-based Images to use when deploying your Laravel application to Kubernetes using Laravel Helm charts.

Images are used to deploy a [sample Laravel application](https://github.com/renoki-co/laravel-helm-demo) to Kubernetes using Helm charts for vanilla Laravel, Laravel Octane or to deploy workers such as queues.

This project compiles images:

- for PHP-FPM + NGINX projects, using `Dockerfile.fpm`, based on an official PHP-FPM Docker image
- for Octane, using `Dockerfile.octane`, based on [a PHP, Swoole-ready image](https://hub.docker.com/r/phpswoole/swoole)
- for Workers, like CLI commands, using `Dockerfile.worker`, based on an official PHP CLI Docker image

These images can be used to compile your app code in a PHP-ready container to be used in Kubernetes.

## ⛽ Octane

Octane images are based on [a PHP-Swoole image](https://hub.docker.com/r/phpswoole/swoole) that works directly with Octane in Swoole mode.

```Dockerfile
FROM renokico/laravel-base:octane-latest-php8.0-alpine

COPY . /var/www/html

RUN mkdir -p /var/www/html/storage/logs/ && \
    chown -R www-data:www-data /var/www/html

WORKDIR /var/www/html

ENTRYPOINT ["php", "-d", "variables_order=EGPCS", "artisan", "octane:start", "--server=swoole", "--host=0.0.0.0", "--port=80"]

EXPOSE 80
```

## 🖥 PHP-FPM

The PHP-FPM image contains the PHP-FPM process and will be complemented by NGINX in the Helm chart.

```Dockerfile
FROM renokico/laravel-base:latest-8.0-fpm-alpine

COPY . /var/www/html

RUN mkdir -p /var/www/html/storage/logs/ && \
    chown -R www-data:www-data /var/www/html

WORKDIR /var/www/html
```

## 🤖 Workers

Workers can be either long-running processes that serve as workers (for example, queues) or by running a local process that might also expose a HTTP server, etc. Either way, you can use a Worker to extend your project.

```Dockerfile
FROM renokico/laravel-base:worker-latest-8.0-cli-alpine

COPY . /var/www/html

RUN mkdir -p /var/www/html/storage/logs/ && \
    chown -R www-data:www-data /var/www/html

WORKDIR /var/www/html

ENTRYPOINT ["php", "-a"]
```
