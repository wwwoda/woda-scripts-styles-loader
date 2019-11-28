<?php

namespace Woda\WordPress\ScriptsStylesLoader;

final class Loader
{
    /** @var array  */
    public static $backendStyles = [
        'admin.css' => []
    ];
    /** @var string  */
    public static $hashesJSONFilename = '.assets.json';
    /** @var string  */
    public static $jqueryFilename = 'jquery.min.js';
    /** @var array  */
    public static $scripts = [
        'main.js' => [
            'deps' => [ 'jquery' ],
        ]
    ];
    /** @var string  */
    public static $scriptsDir;
    /** @var string  */
    public static $scriptsHashesPath;
    /** @var string  */
    public static $scriptsPrefix = 'woda-script';
    /** @var string  */
    public static $scriptsUrl;
    /** @var array  */
    public static $styles = [
        'main.css' => []
    ];
    /** @var string  */
    public static $stylesDir;
    /** @var string  */
    public static $stylesHashesPath;
    /** @var string  */
    public static $stylesPrefix = 'woda-style';
    /** @var string  */
    public static $stylesUrl;
    /** @var string  */
    public static $vendorScriptsDir;
    /** @var string  */
    public static $vendorScriptsUrl;

    /**
     * Main registration function for plugin
     *
     * @param array|null $settings
     */
    public static function register(?array $settings = []): void
    {
        $defaults = [
            'scriptsDir' => defined('WODA_SCRIPTS_DIR') ? WODA_SCRIPTS_DIR : '',
            'scriptsUrl' => defined('WODA_SCRIPTS_URL') ? WODA_SCRIPTS_URL : '',
            'stylesDir' => defined('WODA_STYLES_DIR') ? WODA_STYLES_DIR : '',
            'stylesUrl' => defined('WODA_STYLES_URL') ? WODA_STYLES_URL : '',
            'vendorScriptsDir' => defined('WODA_SCRIPTS_VENDOR_DIR') ? WODA_SCRIPTS_VENDOR_DIR : '',
            'vendorScriptsUrl' => defined('WODA_SCRIPTS_VENDOR_URL') ? WODA_SCRIPTS_VENDOR_URL : '',
        ];
        self::$backendStyles = array_merge(
            self::$backendStyles,
            $settings['backendStyles'] ?? []
        );
        self::$jqueryFilename = $settings['jqueryFilename'] ?? self::$jqueryFilename;
        self::$scripts = array_merge(self::$scripts, $settings['scripts'] ?? []);
        self::$scriptsDir = $settings['scriptsDir'] ?? $defaults['scriptsDir'];
        self::$scriptsHashesPath = $settings['scriptsHashesPath'] ?? self::$scriptsDir . '/' . self::$hashesJSONFilename;
        self::$scriptsPrefix = $settings['scriptsPrefix'] ?? self::$scriptsPrefix;
        self::$scriptsUrl = $settings['scriptsUrl'] ?? $defaults['scriptsUrl'];
        self::$styles = array_merge(self::$styles, $settings['styles'] ?? []);
        self::$stylesDir = $settings['stylesDir'] ?? $defaults['stylesDir'];
        self::$stylesHashesPath = $settings['stylesHashesPath'] ?? self::$stylesDir . '/' . self::$hashesJSONFilename;
        self::$stylesPrefix = $settings['stylesPrefix'] ?? self::$stylesPrefix;
        self::$stylesUrl = $settings['stylesUrl'] ?? $defaults['stylesUrl'];
        self::$vendorScriptsDir = $settings['vendorScriptsDir'] ?? $defaults['vendorScriptsDir'];
        self::$vendorScriptsUrl = $settings['vendorScriptsUrl'] ?? $defaults['vendorScriptsUrl'];
        add_action('admin_head', [self::class, 'enqueueBackendScripts']);
        add_action('wp_enqueue_scripts', [self::class, 'enqueueFrontendScripts']);
    }

    /**
     * Function to be executed on admin_head action hook
     */
    public static function enqueueBackendScripts(): void
    {
        self::registerStyles(self::$backendStyles);
    }

    /**
     * Function to be executed on wp_enqueue_scripts action hook
     */
    public static function enqueueFrontendScripts(): void
    {
        self::registerScripts(self::$scripts);
        self::registerStyles(self::$styles);
        self::updateJQueryVersion();
    }

    /**
     * Enqueue a list of JS files
     *
     * @param array $scripts
     */
    private static function registerScripts(array $scripts = []): void
    {
        if (empty(self::$scriptsUrl) || empty(self::$scriptsDir)) {
            self::triggerError('$scriptsUrl and/or $scriptsDir settings missing');
            return;
        }
        foreach ($scripts as $filename => $script) {
            $src = self::$scriptsUrl . '/' . $filename;
            $path = self::$scriptsDir . '/' . $filename;
            if (!file_exists($path)) {
                self::triggerError('Script can\'t be registered because it doesn\'t exist: ' . $path);
                return;
            }
            $ver = self::getScriptHash($filename);
            $handle = $script['handle'] ?? self::$scriptsPrefix . '-' . self::getFilenamewithoutExtension($filename);
            wp_enqueue_script(
                $handle,
                $src,
                $script['deps'] ?? [],
                $ver,
                $script['inFooter'] ?? true
            );
        }
    }

    /**
     * Enqueue a list of CSS files
     *
     * @param array $styles
     */
    private static function registerStyles(array $styles = []): void
    {
        if (empty(self::$stylesUrl) || empty(self::$stylesDir)) {
            self::triggerError('$stylesUrl and/or $stylesDir settings missing');
            return;
        }
        foreach ($styles as $filename => $style) {
            $src = self::$stylesUrl . '/' . $filename;
            $path = self::$stylesDir . '/' . $filename;
            if (!file_exists($path)) {
                self::triggerError('Style can\'t be registered because it doesn\'t exist: ' . $path);
                return;
            }
            $ver = self::getStyleHash($filename);
            $handle = $style['handle'] ?? self::$stylesPrefix . '-' . self::getFilenamewithoutExtension($filename);
            wp_enqueue_style(
                $handle,
                $src,
                $style['deps'] ?? [],
                $ver,
                $style['media'] ?? 'all'
            );
        }
    }

    /**
     * Replace version 1 of jQuery registered by default by WordPress with the latest version
     */
    private static function updateJQueryVersion(): void
    {
        if (empty(self::$vendorScriptsUrl) || empty(self::$vendorScriptsDir)) {
            self::triggerError('$vendorScriptsUrl and/or $vendorScriptsDir settings missing');
            return;
        }
        $src = self::$vendorScriptsUrl . '/' . self::$jqueryFilename;
        $path = self::$vendorScriptsDir . '/' . self::$jqueryFilename;
        if (!file_exists($path)) {
            self::triggerError('jQuery file doesn\'t exist: ' . $path);
            return;
        }
        wp_deregister_script('jquery');
        wp_enqueue_script(
            'jquery',
            $src
        );
    }

    /**
     * Removes extension from file name
     *
     * @param string $filename
     * @return string
     */
    private static function getFilenamewithoutExtension(string $filename): string
    {
        return pathinfo($filename, PATHINFO_FILENAME);
    }

    /**
     * @param string $filename
     * @return string
     */
    private static function getScriptHash(string $filename): string
    {
        return self::getAssetVersion(self::$scriptsHashesPath, $filename);
    }

    /**
     * @param string $filename
     * @return string
     */
    private static function getStyleHash(string $filename): string
    {
        return self::getAssetVersion(self::$stylesHashesPath, $filename);
    }

    /**
     * Retrieves a file's hash value from a JSON file
     *
     * @param string $hashesPath    Path to JSON file
     * @param string $filename
     * @return string
     */
    private static function getAssetVersion(string $hashesPath, string $filename): string
    {
        if (!file_exists($hashesPath)) {
            self::triggerError('Hash JSON file doesn\'t exist: ' . $hashesPath);
            return '';
        }
        $hashes = json_decode(file_get_contents($hashesPath), true);
        if (!isset($hashes[$filename])) {
            self::triggerError('"' . $filename . '" key isn\'t set in: ' . $hashesPath);
            return '';
        }
        return $hashes[$filename] ?? '';
    }

    /**
     * Trigger errors only if Query Monitor is activated
     *
     * @param $msg
     * @param int $errorType
     */
    private static function triggerError($msg, $errorType = E_USER_NOTICE): void
    {
        if (class_exists('QM_Activation') === false) {
            return;
        }

        trigger_error($msg, $errorType);
    }
}
