<?php
use Illuminate\Support\Facades\DB;

class DB_Timeout
{
    const NAMES = [
        'PDO::SQLSRV_ATTR_QUERY_TIMEOUT',
        'PDO::ATTR_TIMEOUT',
    ];

    private static array $stack = [];
    private static string $name;
    private static string $connection;
    private static string $config_key;

    public static function push(int $value): bool
    {
        self::$stack[] = $value;
        return self::set($value);
    }

    public static function pop(): int
    {
        if (1 === count(self::$stack)) {
            return -1;
        }
        $old = array_pop(self::$stack);
        $last = count(self::$stack) - 1;
        self::set(self::$stack[$last]);
        return $old;
    }

    public static function reset(bool $pdo = true): bool
    {
        if (1 >= count(self::$stack)) {
            return false;
        }
        array_splice(self::$stack, 1);
        self::set(self::$stack[0]);
        if ($pdo) {
            self::getPdo()->setAttribute(self::getName(), self::$stack[0]);
        }
    }

    private static function set(int $value, bool $pdo = true)
    {
        config([self::$config_key => $value]);
        if ($pdo) {
            self::getPdo()->setAttribute(self::getName(), $value);
        }
    }

    private static function getName(): string
    {
        if (!self::$name) {
            foreach (self::NAMES as $name) {
                if (defined($name)) {
                    try {
                        $name_value = constant($name);
                        self::$stack = [self::getPdo()->getAttribute($name_value)];
                        self::$name = $name;
                        self::$connection = config('database.default');
                        self::$config_key = 'database.connections.' . self::$connection . '.options.' . $name_value;
                        return self::$name;
                    } catch (\Throwable $e) {}
                }
            }
            self::$name = 'PDO::';
        }
        return self::$name;
    }

    private static function getPdo(): PDO
    {
        return DB::connection()->getPdo();
    }
}

class Retryable_Query
{
    const WAIT_MS = [5000, 6000, 10000, 15000, 30000, 60000];
    const TIMEOUT_SEC = [15, 30, 45, 60, 75, 90];
    const RECONNECT_TIMEOUT_SEC = [25, 30, 30, 45, 60, 120];
    const RECONNECT_WAIT_MS = [5000, 6000, 10000, 15000, 30000, 60000];

    public static function handle(callable $callable, int $timeout = -1)
    {
        if ($timeout >= 0) {
            DB_Timeout::push($timeout);
        }
        try {
            $result = $callable();
            if ($timeout >= 0) {
                DB_Timeout::pop();
            }
            return $result;
        } catch (\PDOException $e) {
            if (!self::reconnect($e)) {
                throw $e;
            }
        }

        $result = retry(
            count(self::TIMEOUT_SEC),
            fn () => $callable(),
            fn ($attempt) =>
                DB_Timeout::push(self::TIMEOUT_SEC[$attempt - 1])
                && self::TIMEOUT_SEC[$attempt - 1],
            fn ($exception) => self::isRetryable($exception)
        );

        DB_Timeout::reset();

        return $result;
    }

    private static function isRetryable(\PDOException $exception): bool
    {
        return false;
    }

    private static function reconnect(\PDOException $e = null)
    {
        if (!self::isRetryable($e)) {
            return false;
        }
        try {
            $i = 0;
            return retry(
                count(self::RECONNECT_TIMEOUT_SEC),
                function() use (&$i) {
                    $j = $i++;
                    DB::disconnect();
                    DB_Timeout::push(self::RECONNECT_TIMEOUT_SEC[$j], false);
                    sleep(self::RECONNECT_WAIT_MS[$j] / 1000);
                    DB::reconnect();
                    return true;
                },
                0,
                function($exception) {
                    return self::isRetryable($exception);
                }
            );
        } catch (\PDOException $exception) {
            DB_Timeout::reset(false);
            return false;
        }
    }
}
