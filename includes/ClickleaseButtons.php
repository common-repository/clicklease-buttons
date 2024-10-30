<?php
/*
  Plugin Name: Clicklease Buttons
  Plugin URI: https://docs.clicklease.com/docs/woo-com-buttons
  Description: Increase your sales by adding a “finance with Clicklease button”.
  Author: Clicklease
  Version: 1.0.2
  Author: Clicklease
  Author URI: https://www.clicklease.com/
  WC requires at least: 5.0
  WC tested up to:      6.3.1
  Requires at least:    5.2
  Requires PHP:         7.2
  License URI: https://www.gnu.org/licenses/gpl-2.0.html
  License: GPLv2

  Copyright (C) 2021 Clicklease.com

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/
if (!defined('ABSPATH')) {
  exit;
}


include_once(ABSPATH . 'wp-admin/includes/plugin.php');
require_once(plugin_dir_path(__FILE__) . './CLSWP_Options.php');
require_once(plugin_dir_path(__FILE__) . 'includes/CLSWP_BtnsScripts.php');

require_once(plugin_dir_path(__FILE__) . 'includes/views/admin/CLSWP_MainSettignsPage.php');
require_once(plugin_dir_path(__FILE__) . 'includes/views/CLSWP_ProductPage.php');
require_once(plugin_dir_path(__FILE__) . 'includes/views/CLSWP_ShoppingCart.php');
require_once(plugin_dir_path(__FILE__) . 'includes/views/CLSWP_CategoryPage.php');

if(! class_exists('ClickleaseButtonsPlugin')):

class ClickleaseButtonsPlugin
{

  function __construct()
  {
    // hooks which resgister actions to perform on uninstall, activem diasble
    register_activation_hook(__FILE__, [$this, 'enablePlugin']);
    register_deactivation_hook(__FILE__, [$this, 'disablePlugin']);
    register_uninstall_hook(__FILE__, 'uninstallPlugin');
    // STORE PAGES
    // Product Page
    new CLSWP_ProductPage(
      intval(get_option('clk_op_min_price', 500)),
      intval(get_option('clk_op_max_price')),
      get_option('clk_cart_pos')
    );
    // Cart Page
    new CLSWP_ShoppingCart(
      intval(get_option('clk_op_min_price', 500)),
      intval(get_option('clk_op_max_price')),
      get_option('clk_cart_pos')
    );
    // Category Page
    new CLSWP_CategoryPage(get_option('clk_op_is_in_category_page', false));
    // ADMIN PAGES
    // Main page
    new CLSWP_MainSettignsPage();
  }

  /**
   * Begin with the instalation fo the plugin performing actions su as:
   * register defaul values 
   */
  function enablePlugin()
  {
    if (is_plugin_active('woocommerce/woocommerce.php')) {
      $this->execCLSWP_OptionsAction('create');
    }
  }
  /**
   * On disable the plugin update the options with its default values
   */
  function disablePlugin()
  {
    $this->execCLSWP_OptionsAction('update');
  }
  /**
   * On uninstall the plugin remove the options from the db
   */
  function uninstallPlugin()
  {
    $this->execCLSWP_OptionsAction('remove');
  }
  /**
   * Encapsulates actions to perform on active, disable or uninstall the plugin
   * @param action action to perform whose values can be create, update or remove
   */
  function execCLSWP_OptionsAction(string $action)
  {
    global $clk_config_options;
    $changeClkOpts = function ($clkOptions, $actionCb) {
      foreach ($clkOptions as $clk_opts_name => $clk_opts_arr) {
        foreach ($clk_opts_arr as $clk_opk => $clk_opval) {
          $actionCb($clk_opk, $clk_opval);
        }
      }
    };
    switch ($action) {
      case 'create':
        $changeClkOpts(
          $clk_config_options['clk_options'],
          fn ($clkOpKey, $clkOpVal) => add_option($clkOpKey, $clkOpVal)
        );
        break;

      case 'remove':
        $changeClkOpts(
          $clk_config_options['clk_options'],
          fn ($clkOpKey, $clkOpVal) => delete_option($clkOpKey, $clkOpVal)
        );
        break;

      case 'update':
        $changeClkOpts(
          $clk_config_options['clk_options'],
          fn ($clkOpKey, $clkOpVal) => update_option($clkOpKey, $clkOpVal)
        );
        break;

      default:
        $this->execCLSWP_OptionsAction('create');
        break;
    }
  }
}

$clkEbuttonsPlg = new ClickleaseButtonsPlugin();

endif;