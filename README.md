Laravel Docker Images
=====================

Production, PHP-based, already-compiled Docker images that you can use to deploy your Laravel application to Kubernetes or Docker.

## 🤝 Supporting

**If you are using one or more Renoki Co. open-source packages in your production apps, in presentation demos, hobby projects, school projects or so, sponsor our work with [Github Sponsors](https://github.com/sponsors/rennokki). 📦**

[<img src="https://github-content.s3.fr-par.scw.cloud/static/32.jpg" height="210" width="418" />](https://github-content.renoki.org/github-repo/32)


## 🚀 Getting Started

Images are used to deploy a [sample Laravel application](https://github.com/renoki-co/laravel-helm-demo) to Kubernetes using Helm charts for vanilla Laravel, Laravel Octane or to deploy workers such as queues.

This project compiles images:

- for PHP-FPM + NGINX projects, using `Dockerfile.fpm`, based on an official PHP-FPM Docker image
- for Octane, using `Dockerfile.octane`, based on [a PHP image that contains Swoole](https://hub.docker.com/r/phpswoole/swoole)
- for Workers, like CLI commands or [Laravel Zero](https://github.com/laravel-zero/laravel-zero), using `Dockerfile.worker`, based on an official PHP CLI Docker image.

These images can be used to compile your app code in a PHP-ready container to be used in Kubernetes.

## 📦 Deploying

In the [`/examples`](examples) folder, you will find example Laravel projects that can be containerized and deployed.

## 🐳 Tags

For Docker & versioning examples, check [`renokico/laravel-base` Quay page](https://quay.io/repository/renokico/laravel-base).