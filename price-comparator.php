<?php

/**
 * @file
 * Price comparator
 */

/**
 * Plugin name: Price comparator
 * Author: Stan Matrosov
 * Author URI: 
 * Description: 
 * Version: 1.0
 * License: GPL2
 */

// Sanity check
if (!defined('ABSPATH')) die('Direct access is not allowed.');

// defines
define('SB_PLUGIN_DIR_ABS', WP_PLUGIN_DIR . '/price-comparator');
define('SB_PLUGIN_DIR', plugin_dir_url( __FILE__ ));


require_once('classes/sheets.class.php');
require_once('classes/db.class.php');
require_once('classes/ajax.class.php');
require_once('classes/search.class.php');
require_once('classes/post-types.class.php');
require_once('classes/packs.class.php');

require_once('shortcodes/search.form.php');
require_once('shortcodes/search.results.php');
require_once('shortcodes/search.result.list.php');
require_once('shortcodes/user.form.php');