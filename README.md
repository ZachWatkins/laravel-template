# Laravel Template

```bash
$ sail artisan inspire

“ It is not the man who has too little, but the man who craves more, that is poor. ”
— Seneca

```

This application is as small of a starting point as I can come up with for building an authenticated API with Laravel. It uses:

1. __Laravel Breeze Package__ for user registration, login, authentication, and profile management.
2. __Laravel Sanctum Package__ for API authentication and token management.
3. __Laravel Sail Package__ for local development.
4. __Laravel Queues__ for performing tasks asynchronously.

## Routes

### Public Web Routes

`/` (Home)
`/register/` (User Register)
`/login/` (User Login)
`/forgot-password/` (User Password Reset)
`/reset-password/` (User Password Reset)

### Authenticated Web Routes

`/dashboard/` (Dashboard)
`/profile/` (User Profile)
`/verify-email/` (Email Verification)
`/verify-email/{id}/{hash}` (Email Verification)
`/confirm-password/` (Password Confirmation)
`/password/` Password Change (PUT)
`/logout/` Logout (POST)
`/email/resend/` (Email Resend)
`/profile/password/reset/{token}` (Password Reset)

### Authenticated API Routes

`/api/`
`/api/user/me`

## Documentation

(Laravel Documentation)[./docs/laravel/]
