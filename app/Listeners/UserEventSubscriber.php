<?php

namespace App\Listeners;

use App\Models\User;
use App\Models\AuthEvent;
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
        if (!$user) {
            throw new \Exception('User not found');
        }
        if ($user->registered_at) {
            throw new \Exception('User already registered');
        }
        $user->registered_at = now();
        $user->save();
    }

    /**
     * Handle user attempting events.
     */
    public function handleUserAttempting(Attempting $event): void {
        AuthEvent::create([
            'action' => 'attempting',
            'payload' => $event->credentials['email'],
        ]);
    }

    /**
     * Handle user authenticated events.
     */
    public function handleUserAuthenticated(Authenticated $event): void {
        AuthEvent::create([
            'user_id' => $event->user->getAuthIdentifier(),
            'action' => 'authenticated',
        ]);
    }

    /**
     * Handle user login events.
     */
    public function handleUserLogin(Login $event): void {
        AuthEvent::create([
            'user_id' => $event->user->getAuthIdentifier(),
            'action' => 'login',
        ]);
    }

    /**
     * Handle user failed events.
     */
    public function handleUserFailed(Failed $event): void {
        AuthEvent::create([
            'action' => 'failed',
            'payload' => $event->credentials['email'],
        ]);
    }

    /**
     * Handle user validated events.
     */
    public function handleUserValidated(Validated $event): void {
        AuthEvent::create([
            'user_id' => $event->user->getAuthIdentifier(),
            'action' => 'validated',
        ]);
    }

    /**
     * Handle user verified events.
     */
    public function handleUserVerified(Verified $event): void {
        AuthEvent::create([
            'action' => 'verified',
            'payload' => $event->user->getEmailForVerification(),
        ]);
    }

    /**
     * Handle user logout events.
     */
    public function handleUserLogout(Logout $event): void {
        AuthEvent::create([
            'user_id' => $event->user->getAuthIdentifier(),
            'action' => 'logout',
        ]);
    }

    /**
     * Handle user current device logout events.
     */
    public function handleUserCurrentDeviceLogout(CurrentDeviceLogout $event): void {
        AuthEvent::create([
            'user_id' => $event->user->getAuthIdentifier(),
            'action' => 'current_device_logout',
        ]);
    }

    /**
     * Handle user other device logout events.
     */
    public function handleUserOtherDeviceLogout(OtherDeviceLogout $event): void {
        AuthEvent::create([
            'user_id' => $event->user->getAuthIdentifier(),
            'action' => 'other_device_logout',
        ]);
    }

    /**
     * Handle user lockout events.
     */
    public function handleUserLockout(Lockout $event): void {
        AuthEvent::create([
            'action' => 'lockout',
            'payload' => $event->request->email,
        ]);
    }

    /**
     * Handle user password reset events.
     */
    public function handlePasswordReset(PasswordReset $event): void {
        AuthEvent::create([
            'user_id' => $event->user->getAuthIdentifier(),
            'action' => 'password_reset',
        ]);
    }

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
