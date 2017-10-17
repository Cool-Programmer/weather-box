<?php
/*
*	Plugin name: Weather Report
*	Plugin URI: http://www.iodevllc.com
*	Description: Show live weather updates on your WordPress website.
* 	Version: 0.1 beta
*	Author:	Mher Margaryan
*	Author URI: http://www.iodevllc.com
*/

// Exit if direct
if (!defined('ABSPATH')) {
	exit('You are not allowed to be here.');
}

// Require scripts
require_once(plugin_dir_path(__FILE__) . '/includes/weather-report-scripts.php');

// Require class
require_once(plugin_dir_path(__FILE__) . '/includes/weather-report-class.php');

// Geoplugin
require_once(plugin_dir_path(__FILE__) . '/includes/geoplugin.class.php');

// Register widget
function weather_report_widget()
{
	register_widget('Weather_Report_Widget');
}
add_action('widgets_init', 'weather_report_widget');