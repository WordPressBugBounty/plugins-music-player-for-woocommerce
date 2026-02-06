<?php

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Direct access forbidden.' );
}

use ET\Builder\Framework\DependencyManagement\Interfaces\DependencyInterface;
use ET\Builder\Framework\Utility\HTMLUtility;
use ET\Builder\FrontEnd\Module\Style;
use ET\Builder\Packages\Module\Module;
use ET\Builder\Packages\Module\Options\Element\ElementClassnames;
use ET\Builder\Packages\ModuleLibrary\ModuleRegistration;

/**
 * Class that handle "Simple Quick Module" module output in frontend.
 */
class WCMP_DIVI5 implements DependencyInterface {
    /**
     * Register module.
     * `DependencyInterface` interface ensures class method name `load()` is executed for initialization.
     */
    public function load() {
        // Register module.
        add_action( 'init', [ WCMP_DIVI5::class, 'register_module' ] );
    }

    /**
     * Register module.
     */
    public static function register_module() {
        // Path to module metadata that is shared between Frontend and Visual Builder.
        $module_json_folder_path = dirname( __FILE__ );

        ModuleRegistration::register_module(
            $module_json_folder_path,
            [
                'render_callback' => [ WCMP_DIVI5::class, 'render_callback' ],
            ]
        );
    }

    /**
     * Render module HTML output.
     */
    public static function render_callback( $attrs, $content, $block, $elements ) {
		$rawProductIds = sanitize_text_field( $attrs['wcmp_products_ids']['innerContent']['desktop']['value'] ?? '' );
		$rawAttributes = sanitize_text_field( $attrs['wcmp_attributes']['innerContent']['desktop']['value'] ?? '' );

		// ----------------------------------------------------------
		// Build shortcode
		// ----------------------------------------------------------
		$shortcode = '[wcmp-playlist';

		if ( ! empty( $rawProductIds ) ) {
			$shortcode .= ' products_ids="' . esc_attr( $rawProductIds ) . '"';
		}

		if ( ! empty( $rawAttributes ) ) {
			// attributes may contain key="value" pairs → keep raw
			$shortcode .= ' ' . $rawAttributes;
		}

		$shortcode .= ']';

		// ----------------------------------------------------------
		// Return actual form (shortcode expanded on frontend)
		// ----------------------------------------------------------
		return do_shortcode( $shortcode );
    }
}