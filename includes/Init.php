<?php

namespace Woda\WordPress\ScriptsStylesLoader;

use Woda\WordPress\ScriptsStylesLoader\Core\Loader;

final class Init
{
    /**
     * @param array $settings
     */
    public static function init(array $settings = []): void
    {
        add_action('init', static function () use ($settings): void {
            Settings::init($settings);
            Loader::register();
        }, 10);
    }
}
