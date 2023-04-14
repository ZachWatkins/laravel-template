# Laravel Template

```bash
$ sail artisan inspire

“ It is not the man who has too little, but the man who craves more, that is poor. ”
— Seneca

```

This application is a starting point for building an authenticated API with the [Laravel](https://laravel.com/) PHP framework.

- [Features](#features)
- [Routes](#routes)
- [Development](#development)

## Features

Laravel first-party packages and features:

1. __Breeze (Laravel Package)__ for user registration, login, authentication, and profile management. Also includes PHPUnit tests for authentication features.
2. __Sanctum (Laravel Package)__ for API authentication and token management.
3. __Sail (Laravel Package)__ for local development.
4. __Queues__ for performing tasks asynchronously.
5. __Migrations__ for database schema management.
6. __Tests__ for peace of mind.

My own features:

1. __User Storage Service Class__ simplifies storing each user's files in their own folder. This is useful for managing user uploads.
2. __Generic Model Class__ demonstrates how Laravel registers, creates, and retrieves database table records.

## Routes

- [Public Web Routes](#public-web-routes)
- [Guest Web Routes](#guest-web-routes)
- [Authenticated Web Routes](#authenticated-web-routes)

### Public Web Routes

| Verb | URI | Action | Route Name
| GET  | `/` | view   | welcome

### Guest Web Routes

| Verb      | URI                | Action  | Route Name
| GET       | `/register`        | create  | register
| POST      | `/register`        | store   | -
| GET       | `/login`           | create  | login
| POST      | `/login`           | store   | -
| GET       | `/forgot-password` | create  | password.request
| POST      | `/forgot-password` | store   | password.email
| GET       | `/reset-password`  | create  | password.reset
| POST      | `/reset-password`  | store   | password.update

### Authenticated Web Routes

| Verb      | URI                          | Action  | Route Name
| GET       | `/verify-email`              | create  | verification.notice
| GET       | `/verify-email/{id}/{hash}`  | create  | verification.verify
| POST      | `/verify-email/{id}/{hash}`  | store   | -
| POST      | `/verify-email-notification` | store   | verification.send
| GET       | `/confirm-password`          | create  | password.confirm
| POST      | `/confirm-password`          | store   | -
| PUT       | `/password`                  | update  | password.update
| POST      | `/logout`                    | destroy | logout
| GET       | `/dashboard/`                | view    | dashboard
| GET       | `/profile`                   | view    | profile.edit
| PATCH     | `/profile`                   | update  | profile.update
| DELETE    | `/profile`                   | destroy | profile.destroy

### Authenticated API Routes

| Verb      | URI                 | Action  | Route Name
| GET       | `/api/user`         | closure | -
| GET       | `/models`           | index   | photos.index
| GET       | `/models/create`    | create  | photos.create
| POST      | `/models`           | store   | photos.store
| GET       | `/models/{id}`      | show    | photos.show
| GET       | `/models/{id}/edit` | edit    | photos.edit
| PUT/PATCH | `/models/{id}`      | update  | photos.update
| DELETE    | `/models/{id}`      | destroy | photos.destroy

## Development


### Laravel Sail

This application uses [Laravel Sail](https://laravel.com/docs/8.x/sail) for local development. Laravel Sail is a Docker-based development environment for Laravel. It provides a minimal Linux environment with all of the services and features you need to develop a Laravel application.

If you are using Windows and want to run the application locally, you will need to install [Windows Subsystem for Linux](https://docs.microsoft.com/en-us/windows/wsl/install-win10).

### URLs

`http://localhost:80` (Web)
`http://localhost:8025` (Mailpit)

## Documentation

(Laravel Documentation)[./docs/laravel/]

## Dev Containers

### File Permissions Issues on Windows

This project uses a Docker container to run the application. If you are using Windows, you may encounter file permission issues when running the application. This is because the container runs as a non-root user, but the files on your Windows machine are owned by the root user. If you rebuild the `laravel.test` container and run `ls -ll` from the project root you should see that most files are owned by the `sail` user. 

### Sharing Git credentials between Windows and WSL

Source: https://code.visualstudio.com/docs/remote/troubleshooting#_sharing-git-credentials-between-windows-and-wsl

If you use HTTPS to clone your repositories and have a credential helper configured in Windows, you can share this with WSL so that passwords you enter are persisted on both sides. (Note that this does not apply to using SSH keys.)

Just follow these steps:

1. Configure the credential manager on Windows by running the following in a Windows command prompt or PowerShell:

   `git config --global credential.helper wincred`  

2. Configure WSL to use the same credential helper, but running the following in a WSL terminal:

   `git config --global credential.helper "/mnt/c/Program\ Files/Git/mingw64/bin/git-credential-manager-core.exe"`
