<?php

if ( !is_admin() )
{
    print 'Direct access not allowed.';
    exit;
}

// WP Rocket
if ( function_exists('rocket_clean_domain') ) {
    rocket_clean_domain();
}

// WP Super Cache
if ( function_exists('wp_cache_clear_cache') ) {
    wp_cache_clear_cache();
}

// W3 Total Cache
if ( function_exists('w3tc_flush_all') ) {
    w3tc_flush_all();
}

// LiteSpeed Cache
do_action('litespeed_purge_all');

// SiteGround Optimizer
if ( function_exists('sg_cachepress_purge_cache') ) {
    sg_cachepress_purge_cache();
}

// WP Fastest Cache
if ( function_exists('wpfc_clear_all_cache') ) {
    wpfc_clear_all_cache();
}

// Elementor Cache
if ( class_exists('\Elementor\Plugin') ) {
	try {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	} catch ( Exception $err ) {}
}

// Cache Enabler
do_action('cache_enabler_clear_complete_cache');