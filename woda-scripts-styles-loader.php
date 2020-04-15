<?php
/**
 * Plugin Name:       Woda Scripts Styles Loader
 * Plugin URI:        https://github.com/wwwoda/woda-scripts-styles-loader
 * Description:       ...
 * Version:           0.4.0
 * Author:            Woda
 * Author URI:        https://www.woda.at
 * License:           GNU General Public License v2
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path:       /languages
 * Text Domain:       woda-scripts-styles-loader
 * GitHub Plugin URI: https://github.com/wwwoda/woda-scripts-styles-loader
 * Release Asset:     true
 *
 * @package           Woda_Scripts_Styles_Loader
 */

// Copyright (c) 2019 Woda Digital OG. All rights reserved.
//
// Released under the GPL license
// http://www.opensource.org/licenses/gpl-license.php
//
// **********************************************************************
// This program is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
// **********************************************************************

namespace Woda\WordPress\ScriptsStylesLoader;

include_once 'vendor/autoload.php';

add_action('init', static function (): void {
    $settings = apply_filters('woda_scripts_styles_loader_settings', []);
    Loader::register($settings);
}, 10);
