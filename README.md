Laravel Docker Images
=====================

- [Laravel Docker Images](#laravel-docker-images)
  - [🤝 Supporting](#-supporting)
  - [⛽ Octane](#-octane)
  - [🖥 PHP-FPM](#-php-fpm)
  - [🤖 Workers](#-workers)
  - [Scaling Horizon & Octane](#scaling-horizon--octane)

Already-compiled PHP-based Images to use when deploying your Laravel application to Kubernetes using Laravel Helm charts.

Images are used to deploy a [sample Laravel application](https://github.com/renoki-co/laravel-helm-demo) to Kubernetes using Helm charts for vanilla Laravel, Laravel Octane or to deploy workers such as queues.

For Docker & versioning examples, please check [`renokico/laravel-base` Quay page](https://quay.io/repository/renokico/laravel-base).

This project compiles images:

- for PHP-FPM + NGINX projects, using `Dockerfile.fpm`, based on an official PHP-FPM Docker image
- for Octane, using `Dockerfile.octane`, based on [a PHP, Swoole-ready image](https://hub.docker.com/r/phpswoole/swoole)
- for Workers, like CLI commands, using `Dockerfile.worker`, based on an official PHP CLI Docker image

These images can be used to compile your app code in a PHP-ready container to be used in Kubernetes.

## 🤝 Supporting

If you are using one or more Renoki Co. open-source packages in your production apps, in presentation demos, hobby projects, school projects or so, spread some kind words about our work or sponsor our work via Patreon. 📦

You will sometimes get exclusive content on tips about Laravel, AWS or Kubernetes on Patreon and some early-access to projects or packages.

[<img src="https://c5.patreon.com/external/logo/become_a_patron_button.png" height="41" width="175" />](https://www.patreon.com/bePatron?u=10965171)

## ⛽ Octane

Octane images are based on [a PHP-Swoole image](https://hub.docker.com/r/phpswoole/swoole) that works directly with Octane in Swoole mode.

```Dockerfile
FROM quay.io/renokico/laravel-base:octane-latest-php8.0-alpine

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
FROM quay.io/renokico/laravel-base:latest-8.0-fpm-alpine

COPY . /var/www/html

RUN mkdir -p /var/www/html/storage/logs/ && \
    chown -R www-data:www-data /var/www/html

WORKDIR /var/www/html
```

## 🤖 Workers

Workers can be either long-running processes that serve as workers (for example, queues) or by running a local process that might also expose a HTTP server, etc. Either way, you can use a Worker to extend your project.

```Dockerfile
FROM quay.io/renokico/laravel-base:worker-latest-8.0-cli-alpine

COPY . /var/www/html

RUN mkdir -p /var/www/html/storage/logs/ && \
    chown -R www-data:www-data /var/www/html

WORKDIR /var/www/html

ENTRYPOINT ["php", "-a"]
```

## Scaling Horizon & Octane

It is well known that for Kubernetes, you may scale based on CPU or memory allocated to each pod. But you can also scale based on Prometheus metrics.

For ease of access, you may use the following exporters for your Laravel application:

- [Laravel Horizon Exporter](https://github.com/renoki-co/horizon-exporter) - used to scale application pods that run the queue workers
- [Laravel Octane Exporter](https://github.com/renoki-co/octane-exporter) - used to scale the Octane pods to ensure better parallelization
