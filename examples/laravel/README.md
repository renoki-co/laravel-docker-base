# Laravel Example

This repo is a demo example about how your application should be deployed to Docker/Kubernetes.

## Prerequisites

To get started, just make sure you've got a `.env` copy locally (which is `.gitignore`d by default):

```bash
cp .env.example .env
```

## Project

This project is simple:

- there is a `GET /health` endpoint for healthchecks
- there is a `ANY /` endpoint that will dispatch a job to store a record in the database (in `logged_requests`)
- has a Job ([`LogRequest`](app/Jobs/LogRequest.php)) that will execute the `logged_requests` INSERT query
- has a Scheduled command ([`current-time`](app/Console/Commands/WriteFile.php)) running [every minute](app/Console/Kernel.php) that will output the current time

## Build & Deploy Checklist

Before anything else, some projects caveats.

### Secrets

Your `.env` file, if it is present locally, will be added to the image, even if it's ignored by Git.

During the build, you can inject the `.env` and other secret files in the Docker filesystem by having them locally, or typically, have them pulled from somewhere. They persist, so make sure to delete them afterwards, and avoid any call like `config:cache` that would persist the secrets in the cache. These commands can also be ran later on.

When the image is built, your `.env` and other secret files will have to be mounted later on, when running the container. Best recommendation here is to perform `*:cache` and migration commands in this step, before your application will serve requests.

Injecting your `.env` file or other secrets through environment variales is also a possibility.

Since the `.env` file will be removed before the final image is built, you have to mount it. In `docker-compose.yaml`, the `.env` is mounted when the container starts:

```yaml
volumes:
  - .env:/var/www/html/.env:ro
```

### .dockerignore

Make sure you always have a [`.dockerignore`](.dockerignore) file in your project to ignore certain files from getting into the Docker image at build: usually, they are dependency directories (`vendor` and `node_modules`) and other non-production files.

```bash
cat .dockerignore
```

### Cleaning up the final image

Usually, the build process has multiple steps: in the first step, getting the base image, installing the dependencies and building the front assets. The second and final step is to start from a new, fresh base image and copy the compiled & prepared project from the previous step.

After the assets got compiled in the first step, you should do a cleanup, like removing `node_modules` or other cached files. In the examples, this is easily done with:

```dockerfile
RUN rm -rf \
        node_modules/ \
        .env ; \
    php artisan view:clear ; \
    php artisan cache:clear file ; \
    php artisan event:clear ; \
    php artisan optimize:clear
```

### Extensions

More or less, all Docker images come pre-packaged with the following extensions:

<details>
    <summary>View extensions</summary>
    ```
    bcmath
    Core
    ctype
    curl
    date
    dom
    fileinfo
    filter
    ftp
    gettext
    hash
    iconv
    intl
    json
    libxml
    mbstring
    mcrypt
    mysqli
    mysqlnd
    openssl
    pcntl
    pcre
    PDO
    pdo_mysql
    pdo_pgsql
    pdo_sqlite
    Phar
    posix
    readline
    redis
    Reflection
    session
    SimpleXML
    soap
    sockets
    sodium
    SPL
    sqlite3
    standard
    tokenizer
    xml
    xmlreader
    xmlwriter
    xsl
    zip
    zlib
    ```
</details>

To install custom extensions, you can do one of the following install & configure them when building your project.

Make sure that after you are done, you are cleaning up the image again: after we install extensions, traces of build binaries used to compile them are still left in your image, pontetially making your final image bigger.

The delivered Docker images are pre-packaged with a `docker-php-cleanup` file whose job is to clean them up:

```dockerfile
FROM quay.io... as build

# Some Composer packages might require "ext-gd"
RUN docker-php-ext-configure gd ; \
    docker-php-ext-install gd

# i.e. composer install

# Note: this image does not need to be cleaned up
# because the stage will not get into the final image.

FROM quay.io...

COPY --from=build /var/www/html .

# The final image does not contain gd, so it has to be installed again.
# Note: Here, cleanup should be called.
RUN docker-php-ext-configure gd ; \
    docker-php-ext-install gd ; \
    sh /usr/local/bin/docker-php-cleanup
```

## ⛽️ Octane

Full information about the build process can be found in [`Dockerfile.octane`](Dockerfile.octane).

To get started, run the Octane example:

```bash
docker-compose up --wait -d octane
```

- It spins up an Octane-powered server on `http://localhost`
- It spins up a container that will run the Scheduler (for the CRON tasks), using the same image
- It spins up a container that will run the Queues worker, using the same image
- Secrets: the secrets are not compiled in the final image; the `*:cache` commands are going to be called when the container starts
- Assets: compiled in separate step
- Entrypoint: Caches all configurations, runs the migrations and then starts the server

Octane's entrypoint is [`entrypoint-octane`](entrypoint-octane):

```bash
cat entrypoint-octane
```

Since the Scheduler uses the same image (with the same entrypoint that starts the server), the scheduler container has its entrypoint changed in [`docker-compose.yaml`](docker-compose.yaml):

```yaml
octane.scheduler:
  entrypoint:
    - php
    - artisan
    - schedule:work
```

Queue workers are being configured in the same way:

```yaml
octane.queues:
  entrypoint:
    - php
    - artisan
    - queue:work
    - --name=octane-default
    - --queue=default
```

To destroy the example:

```bash
docker-compose down -v --remove-orphans --rmi=all
```
