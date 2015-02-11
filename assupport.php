<?php

session_start();

/*
Plugin Name: Asset Store Customer Support Form
Plugin URI: http://theworldvirtual.com/development/asset-store-publisher-contact-form-plugin/
Description: Allows Asset Store publishers to accept online customer support requests with automatic invoice validating beforehand
Version: 1.3
Author: myBad Studios
Author URI: http://www.mybadstudios.com
*/

include_once("functions.php");
include_once("settings.php");

add_shortcode('as_contact_form','__as_contact_form');

add_action( 'wp_enqueue_scripts', 'add_as_contact_form_stylesheet' );
function add_as_contact_form_stylesheet()
{
	wp_enqueue_style( 'as_contact_style', plugins_url('style.css', __FILE__));
}
?>