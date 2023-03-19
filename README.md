# Laravel Template

This application is a starting point for building an authenticated API with Laravel. It uses React for UI rendering logic.

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
`/dashboard/horizon/` (Laravel Horizon dashboard)

### Authenticated API Routes

`/api/`
`/api/user/me`

## Documentation

(Laravel Documentation)[./docs/laravel/]
