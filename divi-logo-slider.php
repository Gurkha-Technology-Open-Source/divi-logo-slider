<?php
/*
Plugin Name: Divi Logo Slider
Plugin URI: https://github.com/aarjan/divi-logo-slider
Description: A Divi module to display a logo slider.
Version: 1.0.0
Author: Arjan
Author URI: https://www.aariyan.com/
Text Domain: dls-logo-slider
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

function dls_load_textdomain() {
    load_plugin_textdomain( 'dls-logo-slider', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'dls_load_textdomain' );

function dls_initialize_module() {
    if ( class_exists( 'ET_Builder_Module' ) ) {
        require_once plugin_dir_path( __FILE__ ) . 'includes/DiviLogoSlider.php';
    }
}
add_action( 'et_builder_ready', 'dls_initialize_module' );