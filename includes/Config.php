<?php

declare(strict_types=1);

namespace Woda\WordPress\ScriptsStylesLoader;

use RuntimeException;

final class Config
{
    /** @var array<string, mixed> */
    private static $config;

    public static function loadConfigString(string $key): string
    {
        if (!isset(self::$config)) {
            self::$config = include __DIR__ . '/../config/plugin.config.php';
        }
        $value = self::$config[$key] ?? null;
        if (!is_string($value)) {
            throw new RuntimeException(sprintf('Required config key "%s" not found.', $key));
        }
        return $value;
    }
}
