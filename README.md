# Barbershop App

A full-stack web application for managing barbershop operations, including customer registration, authentication, barber/service management, appointment scheduling, and reviews.

## Project Structure

```text
PetarPrzulj/
├── frontend/                 # SPA client (HTML/CSS/JS + jQuery + SPApp)
├── backend/                  # PHP REST API (Flight framework)
│   ├── rest/routes/          # API route definitions
│   ├── rest/services/        # Business logic layer
│   ├── rest/dao/             # Data access layer
│   └── public/v1/docs/       # Swagger UI docs assets
└── Additional/               # SQL dump + architecture/database images
```

## Features

- JWT-based authentication (`/auth/register`, `/auth/login`).
- Role-based authorization for protected routes (`admin`, `customer`).
- CRUD APIs for:
  - users
  - barbers
  - services
  - appointments
  - reviews
- Single-page frontend with views for:
  - home/login/register/profile
  - dashboard/admin pages
  - barbers/services/reviews
  - appointment management
- Swagger/OpenAPI documentation scaffolding in `backend/public/v1/docs/`.

## Tech Stack

### Frontend
- HTML5, CSS3, JavaScript
- jQuery
- jQuery SPApp (client-side routing)

### Backend
- PHP 8+
- [mikecao/flight](https://github.com/mikecao/flight) micro-framework
- [firebase/php-jwt](https://github.com/firebase/php-jwt) for JWT handling
- [zircote/swagger-php](https://github.com/zircote/swagger-php) for API docs generation
- MySQL

## Prerequisites

- PHP 8+
- Composer
- MySQL (or MariaDB)
- A local web server stack (Apache/Nginx + PHP), or PHP built-in server for local API testing

## Setup Guide

## 1) Clone repository

```bash
git clone <your-repo-url>
cd PetarPrzulj
```

## 2) Install backend dependencies

```bash
cd backend
composer install
```

## 3) Create database and import schema/data

Use the provided SQL dump:

```text
Additional/dump-barbershop-202510301809.sql
```

Example (CLI):

```bash
mysql -u <db_user> -p -e "CREATE DATABASE barbershop;"
mysql -u <db_user> -p barbershop < ../Additional/dump-barbershop-202510301809.sql
```

## 4) Configure backend database connection

Edit `backend/rest/config.php`:

- `DB_HOST`
- `DB_PORT`
- `DB_NAME`
- `DB_USER`
- `DB_PASSWORD`
- (optional) `JWT_SECRET`

## 5) Start backend

From the `backend/` folder, one quick option is:

```bash
php -S localhost:8000 index.php
```

Then your API base URL is:

```text
http://localhost:8000
```

> If you instead use Apache/Nginx, adjust URLs according to your server config.

## 6) Configure frontend API base URL

Edit:

```text
frontend/utils/constants.js
```

Set:

```js
PROJECT_BASE_URL: "http://localhost:8000/"
```

(or your Apache/Nginx backend URL, e.g. `http://localhost/PetarPrzulj/backend/`).

## 7) Run frontend

Serve the `frontend/` directory with any static file server.

Example with PHP:

```bash
cd ../frontend
php -S localhost:8080
```

Open:

```text
http://localhost:8080
```

## Authentication & Authorization

- Public endpoints:
  - `POST /auth/register`
  - `POST /auth/login`
- All other API routes require:
  - `Authorization: Bearer <jwt_token>` header
- Role checks are enforced in route handlers via middleware.

## API Overview

Main route groups:

- `auth`:
  - `POST /auth/register`
  - `POST /auth/login`
- `user`:
  - `GET /user`, `GET /user/{id}`
  - `POST /user`, `PUT /user/{id}`, `PATCH /user/{id}`, `DELETE /user/{id}`
- `barber`:
  - `GET /barber`, `GET /barber/{id}`
  - `POST /barber`, `PUT /barber/{id}`, `PATCH /barber/{id}`, `DELETE /barber/{id}`
- `service`:
  - `GET /service`, `GET /service/{id}`
  - `POST /service`, `PUT /service/{id}`, `PATCH /service/{id}`, `DELETE /service/{id}`
- `appointment`:
  - `GET /appointment`, `GET /appointment/{id}`
  - `POST /appointment`, `PUT /appointment/{id}`, `PATCH /appointment/{id}`, `DELETE /appointment/{id}`
- `review`:
  - `GET /review`, `GET /review/{id}`
  - `POST /review`, `PUT /review/{id}`, `PATCH /review/{id}`, `DELETE /review/{id}`

## Swagger / API Docs

Swagger UI assets are located under:

```text
backend/public/v1/docs/
```

Depending on your web server setup, open the corresponding docs URL in your browser (for example under `/public/v1/docs/`).

## Troubleshooting

- **401 Unauthorized on protected routes**
  - Ensure you pass a valid bearer token from `/auth/login`.
- **Database connection error**
  - Verify credentials in `backend/rest/config.php` and ensure MySQL is running.
- **Frontend cannot reach backend**
  - Recheck `PROJECT_BASE_URL` in `frontend/utils/constants.js`.
  - Confirm backend server is running and CORS/network rules allow requests.

## Notes

- Current configuration files contain example/local values—update them for your machine.
- If deploying publicly, rotate `JWT_SECRET`, secure credentials, and disable verbose PHP errors.
