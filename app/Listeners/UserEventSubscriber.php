<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Attempting;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Validated;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\CurrentDeviceLogout;
use Illuminate\Auth\Events\OtherDeviceLogout;

use Illuminate\Events\Dispatcher;

class UserEventSubscriber
{
    /**
     * Handle user registered events.
     */
    public function handleUserRegistered(Registered $event): void {
        $user = User::find((int) $event->user->getAuthIdentifier());
        $user->registered_at = now();
        $user->save();
    }

    /**
     * Handle user attempting events.
     */
    public function handleUserAttempting(Attempting $event): void {}

    /**
     * Handle user authenticated events.
     */
    public function handleUserAuthenticated(Authenticated $event): void {}

    /**
     * Handle user login events.
     */
    public function handleUserLogin(Login $event): void {}

    /**
     * Handle user failed events.
     */
    public function handleUserFailed(Failed $event): void {}

    /**
     * Handle user validated events.
     */
    public function handleUserValidated(Validated $event): void {}

    /**
     * Handle user verified events.
     */
    public function handleUserVerified(Verified $event): void {}

    /**
     * Handle user logout events.
     */
    public function handleUserLogout(Logout $event): void {}

    /**
     * Handle user current device logout events.
     */
    public function handleUserCurrentDeviceLogout(CurrentDeviceLogout $event): void {}

    /**
     * Handle user other device logout events.
     */
    public function handleUserOtherDeviceLogout(OtherDeviceLogout $event): void {}

    /**
     * Handle user lockout events.
     */
    public function handleUserLockout(Lockout $event): void {}

    /**
     * Handle user password reset events.
     */
    public function handlePasswordReset(PasswordReset $event): void {}

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            Registered::class => 'handleUserRegistered',
            Attempting::class => 'handleUserAttempting',
            Authenticated::class => 'handleUserAuthenticated',
            Login::class => 'handleUserLogin',
            Failed::class => 'handleUserFailed',
            Validated::class => 'handleUserValidated',
            Verified::class => 'handleUserVerified',
            Logout::class => 'handleUserLogout',
            CurrentDeviceLogout::class => 'handleUserCurrentDeviceLogout',
            OtherDeviceLogout::class => 'handleUserOtherDeviceLogout',
            Lockout::class => 'handleUserLockout',
            PasswordReset::class => 'handlePasswordReset',
        ];
    }
}
