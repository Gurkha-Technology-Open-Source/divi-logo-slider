<?php
/*
Plugin Name: Logo Slider for Divi
Plugin URI: https://github.com/aarjan/divi-logo-slider
Description: A Divi module to display a logo slider.
Version: 1.0.0
Author: Arjan
Author URI: https://www.aariyan.com/
Text Domain: logo-slider-for-divi
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

function lsfd_load_textdomain() {
    load_plugin_textdomain( 'logo-slider-for-divi', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'lsfd_load_textdomain' );

function lsfd_initialize_module() {
    if ( class_exists( 'ET_Builder_Module' ) ) {
        require_once plugin_dir_path( __FILE__ ) . 'includes/LogoSliderForDivi.php';
    }
}
add_action( 'et_builder_ready', 'lsfd_initialize_module' );