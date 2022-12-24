<?php

/**
 * Plugin Name: گرید نمایش محصولات المنتور
 * Description: تسک تست اسنپ
 * Plugin URI:  https://elementor.com/
 * Version:     1.0.0
 * Author:      سعید قربانیان
 * Author URI:  https://developers.elementor.com/
 * Text Domain: elementor-oembed-widget
 *
 * Elementor tested up to: 3.7.0
 * Elementor Pro tested up to: 3.7.0
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * Register oEmbed Widget.
 *
 * Include widget file and register widget class.
 *
 * @since 1.0.0
 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
 * @return void
 */
function register_oembed_widget($widgets_manager)
{

	require_once(__DIR__ . '/widgets/custom-grid.php');

	$widgets_manager->register(new \Elementor_oEmbed_Widget());
}
add_action('elementor/widgets/register', 'register_oembed_widget');
