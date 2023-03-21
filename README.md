# Laravel Template

```bash
$ sail artisan inspire

“ It is not the man who has too little, but the man who craves more, that is poor. ”
— Seneca

```

This application is as small of a starting point as I can come up with for building an authenticated API with Laravel. It uses the following Laravel packages and features:

1. __Laravel Breeze (Package)__ for user registration, login, authentication, and profile management.
2. __Laravel Sanctum (Package)__ for API authentication and token management.
3. __Laravel Sail (Package)__ for local development.
4. __Laravel Queues__ for performing tasks asynchronously.

## Routes

### Public Web Routes

| Verb      | URI                 | Action  | Route Name
| GET       | `/`                 | view    | welcome
| GET       | `/register`         | create  | register
| POST      | `/register`         | store   | -
| GET       | `/login`            | create  | login
| POST      | `/login`            | store   | -
| POST      | `/logout`           | destroy | logout
| PUT       | `/password`         | update  | password.update
| GET       | `/forgot-password`  | create  | password.request
| POST      | `/forgot-password`  | store   | password.email
| GET       | `/reset-password`   | create  | password.reset
| POST      | `/reset-password`   | store   | password.update
| GET       | `/verify-email`     | create  | verification.notice
| POST      | `/email/verification-notification` | store | verification.send
| GET       | `/verify-email/{id}/{hash}` | create | verification.verify
| POST      | `/verify-email/{id}/{hash}` | store | -
| GET       | `/confirm-password` | create  | password.confirm
| POST      | `/confirm-password` | store   | -

### Authenticated Web Routes

| Verb      | URI                 | Action  | Route Name
| GET       | `/admin/`           | view    | dashboard
| GET       | `/admin/profile`    | view    | profile.edit
| PATCH     | `/admin/profile`    | update  | profile.update
| DELETE    | `/admin/profile`    | destroy | profile.destroy

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

### Local Development Routes

`localhost:80` (Web)
`localhost:8025` (Mailpit)

## Documentation

(Laravel Documentation)[./docs/laravel/]
