<?php
if ( !is_admin() ) {
    print 'Direct access not allowed.';
    exit;
}

if ( ! class_exists( 'WCMP_SKIN_GENERATOR' ) ) {
	class WCMP_SKIN_GENERATOR {

		static private $gemini_model 	= "models/gemini-3.5-flash";
		static private $gemini_url		= "https://generativelanguage.googleapis.com/v1beta/";

		static private function gemini_inference( $prompt, $api_key ) {
			$inference_url 	= self::$gemini_url . self::$gemini_model . ":generateContent?key=" . $api_key;
			$data = [ 'contents' => [[ 'parts' => [[ 'text' => $prompt ]] ]], "generationConfig" => [ "temperature" => 0.0, "topP" => 0.9 ] ];

			// Inferring the model to generate the form.
			$body = json_encode( $data );

			$response = wp_remote_post( $inference_url, [
				'headers' => [ 'Content-Type' => 'application/json' ],
				'body' => $body,
				'timeout' => 120,
			]);

			if ( is_wp_error( $response ) ) {
				throw new Exception( $response->get_error_message() );
			}

			$exception = new Exception( __( 'Empty AI model answer', 'music-player-for-woocommerce' ) );

			$data = json_decode(wp_remote_retrieve_body($response), true);
			if( empty( $data ) ) throw $exception;
			if ( ! empty( $data[ 'error' ] ) ) throw new Exception( $data['error']['message'] );

			try {
				$output = $data['candidates'][0]['content']['parts'][0]['text'];
				// Remove markdown characters from the beginning and end:
				$output = preg_replace('/^```css\s*(.*?)\s*```$/s', '$1', $output);
				$output = str_replace( '.wcmp-custom-skin .mejs-container', '.wcmp-custom-skin.mejs-container', $output );
			} catch ( Exception $err ) {
				throw $exception;
			}
			return $output;
		} // End gemini_inference.

		static private function wp_connector_inference( $prompt ) {
			$_set_timeout = function($timeout){ return 120; };
			add_filter('wp_ai_client_default_request_timeout', $_set_timeout, 999);

			try {
				$output = wp_ai_client_prompt($prompt)->generate_text();
			} finally {
				remove_filter('wp_ai_client_default_request_timeout', $_set_timeout, 999);
			}

            if (is_wp_error($output)) throw new Exception( $output->get_error_message() );
            return $output;
		} // End wp_connector_inference.

		static public function is_wp_connector_available() {
			// Check if there are models registered using the WordPress Connector API.
			if ( function_exists('wp_ai_client_prompt') ) {
				$builder = wp_ai_client_prompt('test');
				if ($builder->is_supported_for_text_generation()) {
					return true;
				}
			}

			return false;
		} // End is_wp_connector_available

		static public function model_inference( $inference_data ) {

			// Base CSS.
			$base_url  		= includes_url() . 'js/mediaelement/';
			$base_css_url  	= $base_url . 'wp-mediaelement.css';
			$base_css_path 	= ABSPATH . WPINC . '/js/mediaelement/mediaelementplayer-legacy.css';
			$schema 		= file_exists( $base_css_path ) ? file_get_contents( $base_css_path ) : $base_css_url;
			$schema 		= str_ireplace( 'url(', 'url(' . $base_url, $schema );

			// Update the URLs of
			// Generate the prompt.
			$prompt = "Generate CSS styles for a MediaElementPlayer skin based on the CSS styles:\n\n$schema\n\n"
					. "The following rules ALWAYS apply regardless of the skin description:\n"
					. "- Only modify color, background, border-radius, box-shadow, and font properties.\n"
					. "- Do NOT alter position, z-index, height, width, display, transform, or animation values.\n"
					. "- Preserve all SVG background-image URLs exactly as they appear in the source CSS.\n"
					. "- Do not add selectors or properties that are not present in the source CSS.\n"
					. "- Infer a coherent color palette from the description and apply it consistently across all components (control bar, progress bar, buttons, volume slider, captions).\n\n"
					. "Output instructions:\n"
					. "Return only valid CSS code scoped under .wcmp-custom-skin. No comments, no explanations, no description, no extra information, no Markdown, no enclose the response in triple backticks.\n\n"
					. "Skin description:\n{$inference_data['skin_description']}";

			if ( self::is_wp_connector_available() ) {
				$output = self::wp_connector_inference( $prompt );
			} else if ( ! empty( $inference_data['api_key'] ) ) {
				$output = self::gemini_inference( $prompt, $inference_data['api_key'] );
			} else {
				throw new Exception( __( 'There is no enought information to generate the custom skin', 'music-player-for-woocommerce' ) );
			}

			return $output;

		} // End model_inference.

	} // End class WCMP_SKIN_GENERATOR.
}

// Main code

/** CALL THE AI SKIN GENERATOR **/
if ( ! empty( $_POST['wcmp_skin_generator_description'] ) ) {

	remove_all_actions( 'shutdown' );
	check_admin_referer( 'wcmp-ai-skin-generator', '_wcmp_nonce' );

	function prepare_inference_data() {
		$output = [];

		if ( current_user_can( 'manage_options' ) ) new Exception(__( 'You don\'t have enought privileges to generate custom skins.', 'music-player-for-woocommerce' ));

		if ( ! WCMP_SKIN_GENERATOR::is_wp_connector_available() ) {
			if ( ! isset( $_POST['wcmp_skin_generator_api_key'] ) ) new Exception( __( 'The AI Provider API Key is required.', 'music-player-for-woocommerce' ));

			$api_key = sanitize_text_field( wp_unslash( $_POST['wcmp_skin_generator_api_key'] ) );
			if ( empty( $api_key ) ) new Exception( __( 'The AI Provider API Key is required.', 'music-player-for-woocommerce' ));
			$output['api_key'] = $api_key;
		}

		$skin_description = sanitize_textarea_field( wp_unslash( $_POST['wcmp_skin_generator_description'] ) );
		if ( empty( $skin_description ) ) new Exception( __( 'The Skin description is required.', 'music-player-for-woocommerce' ));
		$output['skin_description'] = $skin_description;

		return $output;
	}

	try {
		$inference_data = prepare_inference_data();
		$output['success'] 	= WCMP_SKIN_GENERATOR::model_inference( $inference_data );
	} catch( Exception $err ) {
		$output['error'] = $err->getMessage();
	}

	print json_encode( $output );
	exit;
} elseif (
	! empty( $_POST['wcmp_custom_skin'] )
) {
	remove_all_actions( 'shutdown' );
	check_admin_referer( 'wcmp-custom-skin-preview', '_wcmp_nonce' );

	$custom_skin = sanitize_textarea_field( wp_unslash( $_POST['wcmp_custom_skin'] ) );
	// Re-check if custom_skin is not empty.
	if ( ! empty( $custom_skin ) ) {
		print '<link rel="stylesheet" href="'. esc_attr( includes_url( 'js/mediaelement/mediaelementplayer-legacy.min.css' ) ) .'">
		<link rel="stylesheet" href="' . esc_attr( includes_url( 'js/mediaelement/wp-mediaelement.min.css' ) ) .'">
		<link rel="stylesheet" href="' . esc_attr( plugin_dir_url( __FILE__ ) . '../vendors/mejs-skins/mejs-skins.min.css' ) . '">
		<style>
			body {margin: 0;padding: 20px;font-family: Arial, sans-serif;background: #ffffff;}
			.player-container {max-width: 600px;margin: 0 auto;}
			.mejs__container {margin: 0 auto;}' .
			$custom_skin .
		'</style>
		<script type="text/javascript" src="' . esc_attr( includes_url( 'js/jquery/jquery.js' ) ) . '"></script>
		<script src="' . esc_attr( includes_url( 'js/jquery/jquery-migrate.min.js' ) ) . '"></script>
		<script src="' . esc_attr( includes_url( 'js/mediaelement/mediaelement-and-player.min.js' ) ) . '"></script>
		<script src="' . esc_attr( includes_url( 'js/mediaelement/mediaelement-migrate.js' ) ) . '"></script>
		<script src="' . esc_attr( includes_url( 'js/mediaelement/wp-mediaelement.js' ) ) . '"></script>
		<script src="' . esc_attr( plugin_dir_url( __FILE__ ) . '../js/public.js' ) . '"></script>
		<div class="wcmp-player-container">
			<audio controlslist="nodownload" volume="1" preload="none" data-lazyloading="none" class="wcmp-player mejs-classic  wcmp-custom-skin" src="'. esc_attr( plugin_dir_url( __FILE__ ) . '../vendors/demo/demo.mp3' ) . '">
			<source src="' . esc_attr( plugin_dir_url( __FILE__ ) . '../vendors/demo/demo.mp3' ) . '" type="audio/mp3">
			</audio>
		</div>';
		exit;
	}
}