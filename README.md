# WordPress Plugin - Woda Scripts Styles Loader

> Load assets without the hassle

## Installation

You can install the plugin by uploading it in the WordPress Admin or via `composer`.

```bash
composer require woda/wp-scripts-styles-loader
```

## Configure

Pass the settings to the init function or hook into the supplied filter

```php
use Woda\WordPress\ScriptsStylesLoader\HashFile;
use Woda\WordPress\ScriptsStylesLoader\Script;
use Woda\WordPress\ScriptsStylesLoader\Style;

// Import a json containing cache busting hash values, versions, etc.
$scriptsHashFile = new HashFile(get_stylesheet_directory() . '/assets/js/.assets.json');

/**
 * Create a Script Helper
 *
 * @param string           $src       Full URL of the script, or path of the script relative to the WordPress root directory.
 * @param string[]         $deps      Optional. An array of registered script handles this script depends on. Default empty array.
 * @param string           $handle    Optional. Name of the script. Should be unique.
 *                                    If empty, handle will be generated from prefix and file name.
 * @param string|bool|null $ver       Optional. String specifying script version number, if it has one, which is added to the URL
 *                                    as a query string for cache busting purposes. If version is set to false, a version
 *                                    number is automatically added equal to current installed WordPress version.
 *                                    If set to null, no version is added.
 * @param string           $inFooter  Optional. Whether to enqueue the script before </body> instead of in the <head>.
 *                                    Default 'false'.
 */
(new Script(get_stylesheet_directory_uri() . '/assets/js/main.js', ['jquery'], null, null, true))
    // Cache busting via a hash file
    ->addHashFile($scriptsHashFile)
    // Enqueue in the frontend.
    ->enqueue()
    // Enqueue in the backend.
    ->enqueueAdmin();

// Replace jQuery in the frontend with your own version
(new Script(get_stylesheet_directory_uri() . '/assets/js/vendor/jquery.js', ['jquery-core', 'jquery-migrate'], 'jquery'))
    // Calculate an md5 hash value for cache busting purposes.
    ->calculateHashValue()
    // Deregister the handle we are about to reregister
    ->deregister()
    // Register our replacement
    ->register();


$stylesHashFile = new HashFile(get_stylesheet_directory() . '/assets/css/.assets.json');
/**
 * Create a Style Helper
 *
 * @param string           $src    Full URL of the stylesheet, or path of the stylesheet relative to the WordPress root directory.
 * @param string[]         $deps   Optional. An array of registered stylesheet handles this stylesheet depends on. Default empty array.
 * @param string           $handle Optional. Name of the stylesheet. Should be unique.
 *                                 If empty, handle will be generated from prefix and file name.
 * @param string|bool|null $ver    Optional. String specifying stylesheet version number, if it has one, which is added to the URL
 *                                 as a query string for cache busting purposes. If version is set to false, a version
 *                                 number is automatically added equal to current installed WordPress version.
 *                                 If set to null, no version is added.
 * @param string           $media  Optional. The media for which this stylesheet has been defined.
 *                                 Default 'all'. Accepts media types like 'all', 'print' and 'screen', or media queries like
 *                                 '(orientation: portrait)' and '(max-width: 640px)'.
 */
(new Style(get_stylesheet_directory_uri() . '/assets/css/main.css'))
    ->addHashFile($stylesHashFile)
    ->enqueue();
(new Style(get_stylesheet_directory_uri() . '/assets/css/admin.css'))
    ->addHashFile($stylesHashFile)
    ->enqueueAdmin();
(new Style(get_stylesheet_directory_uri() . '/assets/css/editor2.css'))
    ->addHashFile($stylesHashFile)
    // Add an editor style
    // https://codex.wordpress.org/Editor_Style
    ->enqueueEditor();
```
