<?php

namespace Woda\WordPress\ScriptsStylesLoader;

use Woda\WordPress\ScriptsStylesLoader\Utils\File;

final class Settings
{
    public const FILTER = 'woda_scripts_styles_loader_settings';
    /**
     * @var array
     */
    private static $defaults;
    /**
     * @var array
     */
    private static $settings;

    /**
     * @param array $settings
     */
    public static function init(array $settings = []): void
    {
        self::$defaults = [
            'scripts' => [],
            'scriptsHashesFilePath' => '',
            'styles' => [],
            'stylesHashesFilePath' => '',
            'handlePrefix' => 'woda',
            'jquery' => null,
            'vendorScriptsHashesFilePath' => '',
        ];
        $settings = array_merge(self::$defaults, $settings);
        self::$settings = apply_filters(self::FILTER, $settings);
        self::checkHashesFiles();
    }

    /**
     * @return array|mixed
     */
    public static function getBackendStyles()
    {
        return self::$settings['backendStyles'] ?? [];
    }

    /**
     * @return string
     */
    public static function getHandlePrefix(): string {
        if (empty(self::$settings['handlePrefix'])) {
            return self::$defaults['handlePrefix'];
        }
        return self::$settings['handlePrefix'];
    }

    /**
     * @return JQuery|null
     */
    public static function getJQuery(): ?JQuery
    {
        return self::$settings['jquery'] ?? null;
    }

    /**
     * @return array
     */
    public static function getScripts(): array
    {
        return self::$settings['scripts'] ?? [];
    }

    /**
     * @return string
     */
    public static function getScriptsHashesFilePath(): string
    {
        return self::$settings['scriptsHashesFilePath'] ?? '';
    }

    /**
     * @return array
     */
    public static function getStyles(): array
    {
        return self::$settings['styles'] ?? [];
    }

    /**
     * @return string
     */
    public static function getStylesHashesFilePath(): string
    {
        return self::$settings['stylesHashesFilePath'] ?? '';
    }

    /**
     * @return array
     */
    public static function getVendorScripts(): array
    {
        return self::$settings['vendorScripts'] ?? [];
    }

    /**
     * @return string
     */
    public static function getVendorScriptsHashesFilePath(): string
    {
        return self::$settings['vendorScriptsHashesFilePath'] ?? '';
    }

    private static function checkHashesFiles(): void
    {
        $errorTemplate = 'Hashes file was not found at configured path: "%s"';
        if (empty(self::$settings['scriptsHashesFilePath'])
            || !File::exists(self::$settings['scriptsHashesFilePath'], $errorTemplate)) {
            self::$settings['scriptsHashesFilePath'] = '';
        }
        if (empty(self::$settings['stylesHashesFilePath'])
            || !File::exists(self::$settings['stylesHashesFilePath'], $errorTemplate)) {
            self::$settings['stylesHashesFilePath'] = '';
        }
        if (empty(self::$settings['vendorScriptsHashesFilePath'])
            || !File::exists(self::$settings['vendorScriptsHashesFilePath'], $errorTemplate)) {
            self::$settings['vendorScriptsHashesFilePath'] = '';
        }
    }
}
