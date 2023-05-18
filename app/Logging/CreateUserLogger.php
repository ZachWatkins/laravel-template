<?php

namespace App\Logging;

use Illuminate\Log\ParsesLogConfiguration;
use Monolog\Logger as Monolog;
use Psr\Log\LoggerInterface;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Processor\PsrLogMessageProcessor;
use Monolog\Handler\FormattableHandlerInterface;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Formatter\LineFormatter;

class CreateUserLogger
{
    use ParsesLogConfiguration;

    /**
     * The standard date format to use when writing logs.
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * Create a custom Monolog instance.
     *
     * @param  array  $config
     * @return \Psr\Log\LoggerInterface
     */
    public function __invoke(array $config): LoggerInterface
    {
        return new Monolog($this->parseChannel($config), [
            (new RotatingFileHandler(
                $config['path'], $config['days'] ?? 7, $this->level($config),
                $config['bubble'] ?? true, $config['permission'] ?? null, $config['locking'] ?? false
            ))
        ], $config['replace_placeholders'] ?? false ? [new PsrLogMessageProcessor()] : []);
    }

    /**
     * Get fallback log channel name.
     *
     * @return string
     */
    protected function getFallbackChannelName()
    {
        return 'user';
    }

    /**
     * Prepare the handler for usage by Monolog.
     *
     * @param  \Monolog\Handler\HandlerInterface  $handler
     * @param  array  $config
     * @return \Monolog\Handler\HandlerInterface
     */
    protected function prepareHandler(HandlerInterface $handler, array $config = [])
    {
        if (isset($config['action_level'])) {
            $handler = new FingersCrossedHandler(
                $handler,
                $this->actionLevel($config),
                0,
                true,
                $config['stop_buffering'] ?? true
            );
        }

        if (! $handler instanceof FormattableHandlerInterface) {
            return $handler;
        }

        if (! isset($config['formatter'])) {
            $handler->setFormatter($this->formatter());
        }

        return $handler;
    }

    /**
     * Get a Monolog formatter instance.
     *
     * @return \Monolog\Formatter\FormatterInterface
     */
    protected function formatter()
    {
        return tap(new LineFormatter(null, $this->dateFormat, true, true), function ($formatter) {
            $formatter->includeStacktraces();
        });
    }
}
