# 📅 Reviews: Your Personal Yandex reviews manager

Welcome to **Reviews**, a sophisticated and intuitive web application designed to help you with yandex reviews.

## 💻 Core Technology Stack

-   **Backend**: PHP / Laravel / MySQL / Redis / Selenium
-   **Frontend**: TypeScript / Vue
-   **Containerization**: Docker

## 🚀 Getting Started: Development Environment

Setting up the development environment is straightforward with Docker.

1.  **Clone the Repository**:
    ```bash
    git clone https://github.com/GetmanDima/reviews
    cd reviews
    ```

2.  **Configure Environment**:
    ```bash
    cp .env.example .env
    ```
    *You may customize database credentials and other settings inside `.env`.*

3.  **Launch Services**:
    ```bash
    docker compose up -d
    ```

4.  **Application Setup**:
    ```bash
    docker exec -it reviews_app composer run setup
    docker exec -it reviews_app php artisan db:seed
    ```

5.  **Run queue**:
    ```bash
    docker exec -it reviews_app php artisan queue:listen redis --sleep=3 --tries=1 --queue=default
    ```

6.  **Frontend Development**:
    ```bash
    docker compose run --rm --service-ports node npm install
    docker compose run --rm --service-ports node npm run dev
    ```
    or better
    ```bash
    npm install
    npm run dev
    ```

## 🚀 Getting Started: Production Environment
1.  **Clone the Repository**:
    ```bash
    git clone https://github.com/GetmanDima/reviews
    cd reviews
    ```

2.  **Configure Environment**:
    ```bash
    cp .env.prod.example .env
    ```
    *You may customize database credentials and other settings inside `.env`.*
    *You also should fill APP_KEY in .env*

3.  **Launch Services**:
    ```bash
    docker compose up -d
    docker exec -it reviews_app composer install --no-dev --no-interaction --no-plugins --no-scripts --prefer-dist --optimize-autoloader
    docker exec -it reviews_app php artisan migrate --force
    docker exec -it reviews_app php artisan optimize \
    && docker exec -it reviews_app php artisan config:cache \
    && docker exec -it reviews_app php artisan route:cache \
    && docker exec -it reviews_app php artisan view:cache \
    && docker exec -it reviews_app php artisan event:cache \
    && docker exec -it reviews_app php artisan vendor:publish --tag=log-viewer-assets --force
    docker exec -it reviews_app php artisan queue:work redis --sleep=3 --tries=3 --max-time=3600 --max-jobs=1000 --queue=default > storage/logs/queue.log 2>&1 &
    docker compose run --rm --service-ports node npm ci
    docker compose run --rm --service-ports node npm run build
    ```

## 🌐 Application & Service URLs

Once the setup is complete, the following services will be available:

-   **Web Application**: [http://localhost:8000](http://localhost:8000)
-   **MySQL**: `localhost:3306`
-   **Redis**: `localhost:6379`
-   **Selenium**: `localhost:4444`

### Internal Tools

These routes are protected by Basic Authentication. Credentials can be found and updated in your `.env` file (`HTTP_BASIC_AUTH_USER` and `HTTP_BASIC_AUTH_PASSWORD`).

-   **Log Viewer**: [http://localhost:8000/log-viewer](http://localhost:8000/log-viewer)
-   **API Documentation**: [http://localhost:8000/docs](http://localhost:8000/docs)

## 🛠️ Other Useful Commands

-   **Run backend tests**:
    ```bash
    docker exec -it reviews_app composer run test
    ```

-   **Run frontend tests**:
    ```bash
    docker compose run --rm --service-ports node npm run test
    ```
    or better
    ```bash
    npm run test
    ```

-   **Generate Model PHPDocs**:
    ```bash
    docker exec -it reviews_app composer run ide-helper
    ```

-   **Generate API Documentation**:
    To regenerate the Scribe API documentation.
    ```bash
    docker exec -it reviews_app composer run docs
    ```

-   **Run phpstan**:
    ```bash
    docker exec -it reviews_app composer run phpstan
    ```

-   **Run pint**:
    ```bash
    docker exec -it reviews_app composer run pint
    ```

-   **Run eslint**:
     ```bash
    docker compose run --rm --service-ports node npm run lint
    ```
    or better
    ```bash
    npm run lint
    ```

-   **Run prettier**:
    ```bash
    docker compose run --rm --service-ports node npm run format
    ```
    or better
    ```bash
    npm run format
    ```
