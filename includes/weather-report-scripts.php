<?php 
	function wr_scripts_enqueue()
	{
		wp_enqueue_style('wr-stylesheet', plugins_url() . '/weather-report/css/styles.css');
		wp_enqueue_script('wr-javascript', plugins_url() . '/weather-report/js/main.js', ['jquery']);
	}
	add_action('wp_enqueue_scripts', 'wr_scripts_enqueue');