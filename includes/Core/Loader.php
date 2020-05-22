<?php

namespace Woda\WordPress\ScriptsStylesLoader\Core;

use Woda\WordPress\ScriptsStylesLoader\JQuery;
use Woda\WordPress\ScriptsStylesLoader\Script;
use Woda\WordPress\ScriptsStylesLoader\Settings;
use Woda\WordPress\ScriptsStylesLoader\Style;

final class Loader
{
    /**
     * @param array|null $settings
     */
    public static function register(?array $settings = []): void
    {
        add_action('admin_head', [self::class, 'enqueueBackendScripts']);
        add_action('wp_enqueue_scripts', [self::class, 'enqueueFrontendScripts']);
        add_action('wp_enqueue_scripts', [self::class, 'enqueueFrontendStyles']);
    }

    public static function enqueueBackendScripts(): void
    {
        self::registerStyles(Settings::getBackendStyles());
    }

    public static function enqueueFrontendScripts(): void
    {
        self::registerScripts(Settings::getScripts());
        self::registerScripts(Settings::getVendorScripts());
        if (Settings::getJQuery()) {
            self::updateJQueryVersion(Settings::getJQuery());
        }
    }

    public static function enqueueFrontendStyles(): void
    {
        self::registerStyles(Settings::getStyles());
    }

    /**
     * @param JQuery $jquery
     */
    public static function updateJQueryVersion(JQuery $jquery): void
    {
        wp_deregister_script('jquery');
        self::registerScript($jquery);
    }

    /**
     * @param array $scripts
     */
    private static function registerScripts(array $scripts = []): void
    {
        foreach ($scripts as $script) {
            self::registerScript($script);
        }
    }

    /**
     * @param Script $script
     */
    private static function registerScript(Script $script): void
    {
        wp_enqueue_script(
            $script->getHandle(),
            $script->getSource(),
            $script->getDependencies(),
            $script->getVersion(),
            $script->getInFooter()
        );
    }

    /**
     * @param array $styles
     */
    private static function registerStyles(array $styles = []): void
    {
        foreach ($styles as $style) {
            self::registerStyle($style);
        }
    }

    /**
     * @param Style $style
     */
    private static function registerStyle(Style $style): void
    {
        wp_enqueue_style(
            $style->getHandle(),
            $style->getSource(),
            $style->getDependencies(),
            $style->getVersion(),
            $style->getMedia()
        );
    }
}
