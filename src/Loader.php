<?php

namespace Woda\WordPress\ScriptsStylesLoader;

final class Loader {
    public static $backendStyles = [
        'admin.css' => []
    ];
    public static $hashesJSONFilename = '.assets.json';
    public static $jqueryFilename = 'jquery.min.js';
    public static $scripts = [
        'main.js' => [
            'deps' => [ 'jquery' ],
        ]
    ];
    public static $scriptsDir = WODA_SCRIPTS_DIR;
    public static $scriptsHashesPath;
    public static $scriptsPrefix = 'woda-script';
    public static $scriptsUrl = WODA_SCRIPTS_URL;
    public static $styles = [
        'main.css' => []
    ];
    public static $stylesDir = WODA_STYLES_DIR;
    public static $stylesHashesPath;
    public static $stylesPrefix = 'woda-style';
    public static $stylesUrl = WODA_STYLES_URL;
    public static $vendorScriptsDir = WODA_SCRIPTS_VENDOR_DIR;
    public static $vendorScriptsUrl = WODA_SCRIPTS_VENDOR_URL;

    public static function register( ?array $settings = [] ): void {
        self::$backendStyles = array_merge( self::$backendStyles, $settings['backendStyles'] ?? [] );
        self::$jqueryFilename = $settings['jqueryFilename'] ?? self::$jqueryFilename;
        self::$scripts = array_merge( self::$scripts, $settings['scripts'] ?? [] );
        self::$scriptsDir = $settings['scriptsDir'] ?? self::$scriptsDir;
        self::$scriptsHashesPath = $settings['scriptsHashesPath'] ?? self::$scriptsDir . '/' . self::$hashesJSONFilename;
        self::$scriptsPrefix = $settings['scriptsPrefix'] ?? self::$scriptsPrefix;
        self::$scriptsUrl = $settings['scriptsUrl'] ?? self::$scriptsUrl;
        self::$styles = array_merge( self::$styles, $settings['styles'] ?? [] );
        self::$stylesDir = $settings['stylesDir'] ?? self::$stylesDir;
        self::$stylesHashesPath = $settings['stylesHashesPath'] ?? self::$stylesDir . '/' . self::$hashesJSONFilename;
        self::$stylesPrefix = $settings['stylesPrefix'] ?? self::$stylesPrefix;
        self::$stylesUrl = $settings['stylesUrl'] ?? self::$stylesUrl;
        self::$vendorScriptsDir = $settings['vendorScriptsDir'] ?? self::$vendorScriptsDir;
        self::$vendorScriptsUrl = $settings['vendorScriptsUrl'] ?? self::$vendorScriptsUrl;

        add_action( 'admin_head', [self::class, 'enqueueBackendScripts'] );
        add_action( 'wp_enqueue_scripts', [self::class, 'enqueueFrontendScripts'] );
        add_action( 'wp_enqueue_scripts', [self::class, 'updateJQueryVersion'] );
    }

    public static function enqueueFrontendScripts(): void {
        self::registerScripts(self::$scripts);
        self::registerStyles(self::$styles);
    }

    public static function enqueueBackendScripts() {
        self::registerStyles(self::$backendStyles);
    }

    private static function registerScripts( array $scripts = [] ): void {
        foreach ( $scripts as $filename => $script ) {
            $src = self::$scriptsUrl . '/' . $filename;
            $path = self::$scriptsDir . '/' . $filename;

            if ( !file_exists( $path ) ) {
                trigger_error( 'Script can\'t be registered because it doesn\'t exist: ' . $path, E_USER_NOTICE );
                return;
            }

            $ver = self::getScriptHash( $filename );
            $handle = $script['handle'] ?? self::$scriptsPrefix . '-' . self::getFilenamewithoutExtension( $filename );

            wp_enqueue_script(
                $handle,
                $src,
                $script['deps'] ?? [],
                $ver,
                $script['inFooter'] ?? true
            );
        }
    }

    private static function registerStyles( array $styles = [] ): void {
        foreach ( $styles as $filename => $style ) {
            $src = self::$stylesUrl . '/' . $filename;
            $path = self::$stylesDir . '/' . $filename;

            if ( !file_exists( $path ) ) {
                trigger_error( 'Style can\'t be registered because it doesn\'t exist: ' . $path, E_USER_NOTICE );
                return;
            }

            $ver = self::getStyleHash( $filename );
            $handle = $style['handle'] ?? self::$stylesPrefix . '-' . self::getFilenamewithoutExtension( $filename );

            wp_enqueue_style(
                $handle,
                $src,
                $style['deps'] ?? [],
                $ver,
                $style['media'] ?? 'all'
            );
        }
    }

    private static function updateJQueryVersion(): void {
        $src = self::$vendorScriptsUrl . '/' . self::$jqueryFilename;
        $path = self::$vendorScriptsDir . '/' . self::$jqueryFilename;

        if ( !file_exists( $path ) ) {
            trigger_error( 'jQuery file doesn\'t exist: ' . $path, E_USER_NOTICE );
            return;
        }
        wp_deregister_script( 'jquery' );
        wp_enqueue_script(
            'jquery',
            $src
        );
    }

    private static function getFilenamewithoutExtension( string $filename ): string {
        return pathinfo( $filename, PATHINFO_FILENAME);
    }

    private static function getScriptHash( string $filename ): string {
        return self::getAssetVersion( self::$scriptsHashesPath, $filename );
    }

    private static function getStyleHash( string $filename ): string {
        return self::getAssetVersion( self::$stylesHashesPath, $filename );
    }

    private static function getAssetVersion( string $hashesPath, string $filename ): string {
        if ( !file_exists( $hashesPath ) ) {
            trigger_error( 'Hash JSON file doesn\'t exist: ' . $hashesPath, E_USER_NOTICE );
            return '';
        }
        $hashes = json_decode( file_get_contents( $hashesPath ) , true );
        if ( !isset( $hashes[$filename] ) ) {
            trigger_error( '"' . $filename . '" key isn\'t set in: ' . $hashesPath, E_USER_NOTICE );
            return '';
        }
        return $hashes[$filename] ?? '';
    }
}
