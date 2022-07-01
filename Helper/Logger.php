<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */
declare(strict_types=1);

namespace Convert\Blog\Helper;

use Psr\Log\LoggerInterface;

/**
 * Class Logger
 * @package Convert\Blog\Helper
 * @method void emergency($message, $context = [])
 * @method void alert($message, $context = [])
 * @method void critical($message, $context = [])
 * @method void error($message, $context = [])
 * @method void warning($message, $context = [])
 * @method void notice($message, $context = [])
 * @method void info($message, $context = [])
 * @method void debug($message, $context = [])
 * @method void log($message, $context = [])
 */
class Logger
{
    const PREFIX = '[Convert_Blog] ';

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return void
     */
    public function __call(string $name, array $arguments)
    {
        $this->logger->{$name}(
            $this->format(implode(' ', $arguments))
        );
    }

    /**
     * @param string $message
     * @return string
     */
    private function format(string $message): string
    {
        return self::PREFIX . $message;
    }
}
