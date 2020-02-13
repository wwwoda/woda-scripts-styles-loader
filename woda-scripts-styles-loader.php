<?php
/**
 * Plugin Name:     Woda Scripts Styles Loader
 * Plugin URI:      https://github.com/wwwoda/wp-plugin-scripts-styles-loader
 * Description:     ...
 * Author:          Woda
 * Author URI:      https://www.woda.at
 * Text Domain:     woda-scripts-styles-loader
 * Domain Path:     /languages
 * Version:         0.2.0
 *
 * @package         Woda_Scripts_Styles_Loader
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

$pluginUpdateChecker = \Puc_v4_Factory::buildUpdateChecker(
    'https://github.com/wwwoda/wp-plugin-scripts-styles-loader/',
    __FILE__,
    'woda-scripts-styles-loader'
);

$githubAccessToken  = defined( 'GITHUB_ACCESS_TOKEN' ) ? GITHUB_ACCESS_TOKEN : get_option('woda_github_access_token');
if (!empty($githubAccessToken)) {
    $pluginUpdateChecker->setAuthentication($githubAccessToken);
}
