# Transport Interstellar Mechatronics (a Laravel template repository)

This application is a starting point for building an inventory management system with the [Laravel](https://laravel.com/) PHP framework.

It's dedicated to my father, who passed away in 2014. He once asked me if I could make an app that tracked inventory for the truck parts and repair company he was a manager for. He said he wanted to know when to order more parts so they wouldn't run out. I wasn't good enough back then, but I am now. This is for you Dad.

It's built with Laravel and React.

-   [Local System Requirements](#system-requirements)
-   [Installation](#installation)
-   [Features](#features)
-   [Routes](#routes)
-   [Additional Documentation](#additional-documentation)
-   [About Laravel](#about-laravel)

## Local System Requirements

PHP and Composer are required to install and run this project. You can use my installer scripts (Mac or Linux: [./bin/install-php](./bin/install-php)) (Windows: [./bin/install-php.bat](./bin/install-php.bat)) or see the links below for official instructions:

-   [PHP](https://www.php.net/downloads.php)
-   [Composer](https://getcomposer.org/download/)

## Installation

```shell
git clone https://github.com/zachwatkins/laravel-template
cd laravel-template
npm install
composer create-project
composer run dev
```

## Features

Laravel first-party packages and features:

1. **Breeze (Laravel Package)** for user registration, login, authentication, and profile management. Also includes PestPHP tests for authentication features.
2. **React with TypeScript and Inertia.js** for building single-page applications with type safety.
3. **Tests** for peace of mind.

## Routes

-   [Public Web Routes](#public-web-routes)
-   [Guest Web Routes](#guest-web-routes)
-   [Authenticated Web Routes](#authenticated-web-routes)

### Public Web Routes

| Verb | URI | Action | Route Name
| GET | `/` | view | welcome

### Guest Web Routes

| Verb | URI | Action | Route Name
| GET | `/register` | create | register
| POST | `/register` | store | -
| GET | `/login` | create | login
| POST | `/login` | store | -
| GET | `/forgot-password` | create | password.request
| POST | `/forgot-password` | store | password.email
| GET | `/reset-password` | create | password.reset
| POST | `/reset-password` | store | password.update

### Authenticated Web Routes

| Verb | URI | Action | Route Name
| GET | `/verify-email` | create | verification.notice
| GET | `/verify-email/{id}/{hash}` | create | verification.verify
| POST | `/verify-email/{id}/{hash}` | store | -
| POST | `/verify-email-notification` | store | verification.send
| GET | `/confirm-password` | create | password.confirm
| POST | `/confirm-password` | store | -
| PUT | `/password` | update | password.update
| POST | `/logout` | destroy | logout
| GET | `/dashboard/` | view | dashboard
| GET | `/profile` | view | profile.edit
| PATCH | `/profile` | update | profile.update
| DELETE | `/profile` | destroy | profile.destroy

### Authenticated API Routes

| Verb | URI | Action | Route Name
| GET | `/api/user` | closure | -
| GET | `/parts` | index | parts.index
| GET | `/parts/create` | create | parts.create
| POST | `/parts` | store | parts.store
| GET | `/parts/{id}` | show | parts.show
| GET | `/parts/{id}/edit` | edit | parts.edit
| PUT/PATCH | `/parts/{id}` | update | parts.update
| DELETE | `/parts/{id}` | destroy | parts.destroy

## Additional Documentation

[React Documentation](https://react.dev)

## About Laravel

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

-   [Simple, fast routing engine](https://laravel.com/docs/routing).
-   [Powerful dependency injection container](https://laravel.com/docs/container).
-   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
-   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
-   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
-   [Robust background job processing](https://laravel.com/docs/queues).
-   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

### Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.
