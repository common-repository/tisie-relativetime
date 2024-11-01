<?php
/**
 * [TiSiE] RelativeTime
 *
 * Plugin Name: [TiSiE] RelativeTime
 * Plugin URI: http://tisie.de/plugins/tisie-relativetime
 * Description: Adds template tags for displaying relative time strings.
 * Version: 0.3a
 * Author: Mathias [TiSiE] Gelhausen
 * Author URI: http://tisie.de
 * License: GPLv2
 *
 * ----------------------------------------------------------------------------<br>
 * LICENSE
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * ----------------------------------------------------------------------------<br>
 *
 * @package Tirt
 * @author Mathias Gelhausen <scripting@tisie.de>
 * @copyright 2011 [TiSiE]
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html   GPLv2
 */

add_action('init', 'tirt_init');

/**
 * Initializes the plugin.
 */
function tirt_init()
{
    $plugin_dir = dirname(__FILE__);
    load_plugin_textdomain('tirt', null, basename($plugin_dir) . '/translations');
    require_once "$plugin_dir/includes/functions.php";
    require_once "$plugin_dir/includes/tags.php";
    
    add_shortcode('reltime', 'tirt_shortcode');
}