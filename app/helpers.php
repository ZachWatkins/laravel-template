<?php
use Illuminate\Support\Facades\DB;

trait SetsPdoTimeout
{
    private static string $pdo_timeout_config_key;
    private static string $pdo_timeout_attribute;
    private static $pdo_timeout_config_original;

    private static function setPdoTimeout(int $value = -1)
    {
        $key = self::$pdo_timeout_config_key || self::getPdoTimeoutConfigKey();
        if ($value < 0) {
            $value = self::$pdo_timeout_config_original;
        }
        config([$key => $value]);
        if (DB::connection() && DB::connection()->getPdo()) {
            DB::connection()->getPdo()->setAttribute(self::$pdo_timeout_attribute, $value);
        }
    }

    private static function getPdoTimeoutConfigKey(): string
    {
        if (self::$pdo_timeout_config_key) {
            return self::$pdo_timeout_config_key;
        }
        $connection = config('database.default');
        self::$pdo_timeout_config_key = 'database.connections.' . $connection . '.options.' . constant(self::getTimeoutAttrName());
        $config_value = config(self::$pdo_timeout_config_key);
        if (!is_numeric($config_value) || 0 > $config_value) {
            $config_value = 0;
        }
        self::$pdo_timeout_config_original = $config_value;
        return self::$pdo_timeout_config_key;
    }

    private static function getTimeoutAttrName(): string
    {
        if (self::$pdo_timeout_attribute) {
            return self::$pdo_timeout_attribute;
        }
        // Ordered by vendor-specific first for feature detection.
        $timeout_attrs = [
            'PDO::SQLSRV_ATTR_QUERY_TIMEOUT',
            'PDO::ATTR_TIMEOUT',
        ];
        foreach ($timeout_attrs as $attribute) {
            if (!defined($attribute) || null === constant($attribute)) {
                continue;
            }
            try {
                if (DB::connection()->getPdo()) {
                    // Test attribute support.
                    $value = DB::connection()->getPdo()->getAttribute(constant($attribute));
                    DB::connection()->getPdo()->setAttribute($attribute, $value + 1);
                    DB::connection()->getPdo()->setAttribute($attribute, $value);
                    return self::$pdo_timeout_attribute = $attribute;
                }
            } catch (\PDOException $e) {
                continue;
            }
        }
        return self::$pdo_timeout_attribute = 'PDO::TIMEOUT_ATTR_UNDEFINED';
    }
}

class Retryable_Query
{
    use SetsPdoTimeout;

    /**
     * Handle a retryable query.
     *
     * @author  Zachary K. Watkins <zwatkins.it@gmail.com>
     *
     * @param callable $callable A callable function which includes a query.
     * @param integer  $timeout  A parameter.
     *
     * @return void
     */
    public static function handle(callable $callable, int $timeout = -1)
    {
        self::setPdoTimeout($timeout);
        try {
            $result = $callable();
            self::setPdoTimeout();
            return $result;
        } catch (\PDOException $exception) {
            if (!self::canRetry($exception) || !self::reconnect()) {
                throw $exception;
            }
        }

        $retryTimeout = function ($attempt) {
            $jitter = \rand(110, 90) / 100;
            $wait_ms = [5000, 8000, 15000, 30000, 60000];
            $timeout_sec = [10, 15, 20, 30, 60];
            self::setPdoTimeout($timeout_sec[$attempt - 1] * $jitter);
            return $wait_ms[$attempt - 1] * $jitter;
        };

        $result = retry(6, $callable, $retryTimeout, fn ($exception) => self::canRetry($exception));

        self::setPdoTimeout();

        return $result;
    }

    /**
     * Attempt to reconnect to the database if an exception is retryable.
     *
     * @author Zachary K. Watkins <zwatkins.it@gmail.com>
     *
     * @return bool
     */
    private static function reconnect(): bool
    {
        $i = 0;
        $reconnector = function() use (&$i) {
            $j = $i++;
            $reconnect_timeout_sec = [25, 30, 45, 60, 120];
            $reconnect_wait_sec = [5, 10, 15, 30, 60];
            self::setPdoTimeout($reconnect_timeout_sec[$j]);
            DB::disconnect();
            sleep($reconnect_wait_sec[$j]);
            DB::reconnect();
            self::setPdoTimeout();
            return true;
        };

        try {
            return retry(6, $reconnector, 0, fn ($exception) => self::canRetry($exception));
        } catch (\PDOException $exception) {
            self::setPdoTimeout();
            return false;
        }
    }

    /**
     * Reads PDOException error codes and compares them against the configured retryable codes.
     *
     * @author Zachary K. Watkins <zwatkins.it@gmail.com>
     *
     * @param \PDOException $exception The thrown exception.
     *
     * @return bool Whether the query can be retried.
     */
    private static function canRetry(\PDOException $exception): bool
    {
        $dbconnection = config('database.default');
        $retryable = config("database.connections.{$dbconnection}.retryable_codes", []);
        $code = (string) $exception->getCode();
        $subcode = (string) $exception->errorInfo[1];
        $message = $exception->getMessage();
        if (isset($retryable[$code])) {
            if (true === $retryable[$code] || in_array($subcode, $retryable, true)) {
                return true;
            }
        } elseif (false !== strpos($message, 'nable to connect')) {
            return true;
        } elseif (false !== strpos($message, 'onnection timed out')) {
            return true;
        }
        return false;
    }
}

if (!function_exists('user_storage')) {
    /**
     * Build a user storage disk on-demand.
     *
     * @param int $user_id The user ID to build the disk for.
     *
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    function user_storage(int $user_id): \Illuminate\Contracts\Filesystem\Filesystem
    {
        if (!$user_id) {
            $user = \Illuminate\Support\Facades\Auth::user();
            $user_id = $user ? $user->id : '';
        }

        $disk = config('filesystems.disks.user', [
            'driver' => 'local',
            'root' => storage_path('app/user/{user_id}'),
            'throw' => false,
        ]);

        if ($user_id) {
            $disk['root'] = false !== strpos('{user_id}', $disk['root'])
                ? str_replace('{user_id}', $user_id, $disk['root'])
                : rtrim($disk['root'], '/') . '/' . $user_id;
        } else {
            $disk['root'] = str_replace('/\/?{user_id}\/?/', '/', $disk['root']);
        }

        return \Illuminate\Support\Facades\Storage::build($disk);
    }
}

if (!function_exists('public_user_storage')) {
    /**
     * Build a public user storage disk on-demand.
     *
     * @param int $user_id The user ID to build the disk for.
     *
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    function public_user_storage(int $user_id): \Illuminate\Contracts\Filesystem\Filesystem
    {
        if (!$user_id) {
            $user = \Illuminate\Support\Facades\Auth::user();
            $user_id = $user ? $user->id : '';
        }

        $disk = config('filesystems.disks.user-public', [
            'driver' => 'local',
            'root' => storage_path('app/public/user/{user_id}'),
            'url' => env('APP_URL').'/storage/user/{user_id}',
            'visibility' => 'public',
            'throw' => false,
        ]);

        if ($user_id) {
            $disk['root'] = false !== strpos('{user_id}', $disk['root'])
                ? str_replace('{user_id}', $user_id, $disk['root'])
                : rtrim($disk['root'], '/') . '/' . $user_id;
            $disk['url'] = false !== strpos('{user_id}', $disk['url'])
                ? str_replace('{user_id}', $user_id, $disk['url'])
                : rtrim($disk['url'], '/') . '/' . $user_id;
        } else {
            $disk['root'] = str_replace('/\/?{user_id}\/?/', '/', $disk['root']);
            $disk['url'] = str_replace('/\/?{user_id}\/?/', '/', $disk['url']);
        }

        return \Illuminate\Support\Facades\Storage::build($disk);
    }
}
