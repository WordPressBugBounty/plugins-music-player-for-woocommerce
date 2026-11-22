<?php
if ( !is_admin() ) {
    print 'Direct access not allowed.';
    exit;
}

if ( ! class_exists( 'WCMP_SKIN_GENERATOR' ) ) {
	class WCMP_Skin_Generator_Exception extends RuntimeException {}

	class WCMP_SKIN_GENERATOR {

		const DIRECT_PROVIDERS = [
			'gemini' => [
				'label' => 'Gemini',
				'model' => 'gemini-3.6-flash',
				'keys_url' => 'https://aistudio.google.com/apikey',
			],
			'openai' => [
				'label' => 'OpenAI',
				'model' => 'gpt-6.1-terra',
				'keys_url' => 'https://platform.openai.com/api-keys',
			],
			'anthropic' => [
				'label' => 'Claude/Anthropic',
				'model' => 'claude-sonnet-5-5',
				'keys_url' => 'https://platform.claude.com/settings/keys',
			],
			'deepseek' => [
				'label' => 'DeepSeek',
				'model' => 'deepseek-flash',
				'keys_url' => 'https://platform.deepseek.com/api_keys',
			],
		];

		const MAX_OUTPUT_TOKENS = 8192;

		const MAX_SKIN_BYTES = 131072;

		static private function request_json( $url, $headers, $body ) {
			$response = wp_safe_remote_post( $url, [
				'headers' => [ 'Content-Type' => 'application/json' ] + $headers,
				'body' => wp_json_encode( $body ),
				'redirection' => 0,
				'timeout' => 120,
				'limit_response_size' => 524288,
			] );

			if ( is_wp_error( $response ) ) {
				throw new WCMP_Skin_Generator_Exception( __( 'The AI provider could not be reached.', 'music-player-for-woocommerce' ) );
			}

			$status = (int) wp_remote_retrieve_response_code( $response );
			if ( $status < 200 || $status >= 300 ) {
				/* translators: %d: HTTP status code returned by the AI provider. */
				throw new WCMP_Skin_Generator_Exception( sprintf( __( 'The AI provider returned HTTP %d.', 'music-player-for-woocommerce' ), $status ) );
			}

			$data = json_decode( wp_remote_retrieve_body( $response ), true );
			if ( ! is_array( $data ) || ! empty( $data['error'] ) ) {
				throw new WCMP_Skin_Generator_Exception( __( 'The AI provider returned an invalid response.', 'music-player-for-woocommerce' ) );
			}

			return $data;
		}

		static private function gemini_inference( $model, $prompt, $api_key ) {
			$data = self::request_json(
				'https://generativelanguage.googleapis.com/v1beta/models/' . $model . ':generateContent',
				[ 'x-goog-api-key' => $api_key ],
				[
					'contents' => [ [ 'parts' => [ [ 'text' => $prompt ] ] ] ],
					'generationConfig' => [
						'thinkingConfig' => [ 'thinkingLevel' => 'low' ],
						'maxOutputTokens' => self::MAX_OUTPUT_TOKENS,
					],
				]
			);

			$candidate = $data['candidates'][0] ?? [];
			$text = '';
			foreach ( (array) ( $candidate['content']['parts'] ?? [] ) as $part ) {
				if ( empty( $part['thought'] ) && is_string( $part['text'] ?? null ) ) {
					$text .= $part['text'];
				}
			}

			return [ $text, 'MAX_TOKENS' === ( $candidate['finishReason'] ?? '' ) ];
		}

		static private function openai_inference( $model, $prompt, $api_key ) {
			$data = self::request_json(
				'https://api.openai.com/v1/responses',
				[ 'Authorization' => 'Bearer ' . $api_key ],
				[
					'model' => $model,
					'input' => $prompt,
					'reasoning' => [ 'effort' => 'low' ],
					'max_output_tokens' => self::MAX_OUTPUT_TOKENS,
					'store' => false,
				]
			);

			$text = '';
			foreach ( (array) ( $data['output'] ?? [] ) as $item ) {
				if ( 'message' !== ( $item['type'] ?? '' ) ) {
					continue;
				}
				foreach ( (array) ( $item['content'] ?? [] ) as $block ) {
					if ( 'output_text' === ( $block['type'] ?? '' ) && is_string( $block['text'] ?? null ) ) {
						$text .= $block['text'];
					}
				}
			}

			return [ $text, 'incomplete' === ( $data['status'] ?? '' ) ];
		}

		static private function anthropic_inference( $model, $prompt, $api_key ) {
			$data = self::request_json(
				'https://api.anthropic.com/v1/messages',
				[ 'x-api-key' => $api_key, 'anthropic-version' => '2026-10-21' ],
				[
					'model' => $model,
					'max_tokens' => self::MAX_OUTPUT_TOKENS,
					'messages' => [ [ 'role' => 'user', 'content' => $prompt ] ],
					'output_config' => [ 'effort' => 'low' ],
				]
			);

			$text = '';
			foreach ( (array) ( $data['content'] ?? [] ) as $block ) {
				if ( 'text' === ( $block['type'] ?? '' ) && is_string( $block['text'] ?? null ) ) {
					$text .= $block['text'];
				}
			}

			return [ $text, 'max_tokens' === ( $data['stop_reason'] ?? '' ) ];
		}

		static private function deepseek_inference( $model, $prompt, $api_key ) {
			$data = self::request_json(
				'https://api.deepseek.com/chat/completions',
				[ 'Authorization' => 'Bearer ' . $api_key ],
				[
					'model' => $model,
					'messages' => [ [ 'role' => 'user', 'content' => $prompt ] ],
					'reasoning_effort' => 'low',
					'max_tokens' => self::MAX_OUTPUT_TOKENS,
				]
			);

			$choice = $data['choices'][0] ?? [];
			return [ $choice['message']['content'] ?? '', 'length' === ( $choice['finish_reason'] ?? '' ) ];
		}

		static private function direct_inference( $provider, $prompt, $api_key ) {
			$model = self::DIRECT_PROVIDERS[$provider]['model'];
			switch ( $provider ) {
				case 'gemini':
					$result = self::gemini_inference( $model, $prompt, $api_key );
					break;
				case 'openai':
					$result = self::openai_inference( $model, $prompt, $api_key );
					break;
				case 'anthropic':
					$result = self::anthropic_inference( $model, $prompt, $api_key );
					break;
				case 'deepseek':
					$result = self::deepseek_inference( $model, $prompt, $api_key );
					break;
			}

			if ( $result[1] ) {
				throw new WCMP_Skin_Generator_Exception( __( 'The AI model returned an incomplete skin.', 'music-player-for-woocommerce' ) );
			}

			return $result[0];
		}

		static private function normalize_selector( $selector ) {
			$selector = preg_replace( '/\s+/', ' ', trim( str_replace( '"', "'", $selector ) ) );
			return preg_replace( '/\s*([>+~])\s*/', '$1', $selector );
		}

		static private function sanitize_generated_skin( $text, $reference, $sprite_url ) {
			if ( ! is_string( $text ) || '' === trim( $text ) ) {
				throw new WCMP_Skin_Generator_Exception( __( 'Empty AI model answer', 'music-player-for-woocommerce' ) );
			}

			$invalid_css = new WCMP_Skin_Generator_Exception( __( 'The AI model did not return a supported player stylesheet.', 'music-player-for-woocommerce' ) );
			if ( strlen( $text ) > self::MAX_SKIN_BYTES || false !== strpbrk( $text, "<\\" ) || preg_match( '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', $text ) ) {
				throw $invalid_css;
			}

			$css = preg_replace( '/^```(?:css)?\s*(.*?)\s*```$/is', '$1', trim( $text ) );
			$css = preg_replace( '~\/\*.*?\*\/~s', ' ', $css );

			$allowed_selectors = [ '.wcmp-custom-skin' => true ];
			$reference = preg_replace( '~\/\*.*?\*\/~s', ' ', $reference );
			preg_match_all( '/([^{}]+)\{/', $reference, $reference_rules );
			foreach ( $reference_rules[1] as $selector_group ) {
				foreach ( explode( ',', $selector_group ) as $selector ) {
					$selector = self::normalize_selector( $selector );
					if ( false === strpos( $selector, '.mejs-' ) ) {
						continue;
					}
					$allowed_selectors['.wcmp-custom-skin ' . $selector] = true;
					if ( 0 === strpos( $selector, '.mejs-container' ) ) {
						$allowed_selectors['.wcmp-custom-skin' . $selector] = true;
					}
				}
			}

			$properties = '/\A(?:color|background(?:-color|-image|-position(?:-[xy])?|-size|-repeat)?|border(?:-(?:top|right|bottom|left))?-color|border(?:-(?:top|bottom)-(?:left|right))?-radius|box-shadow|text-shadow|font(?:-family|-size|-style|-weight|-variant|-stretch)?|letter-spacing|outline-color|text-decoration-color|opacity)\z/';
			$functions = [ 'rgb', 'rgba', 'hsl', 'hsla', 'hwb', 'lab', 'lch', 'oklab', 'oklch', 'color', 'color-mix', 'linear-gradient', 'radial-gradient', 'conic-gradient', 'repeating-linear-gradient', 'repeating-radial-gradient', 'repeating-conic-gradient', '-webkit-linear-gradient', '-webkit-radial-gradient', 'calc', 'min', 'max', 'clamp' ];

			$rules = [];
			$offset = 0;
			while ( preg_match( '/\G\s*([^{};@]+)\{([^{}]*)\}/', $css, $rule, 0, $offset ) ) {
				$offset += strlen( $rule[0] );
				$selectors = [];
				foreach ( explode( ',', $rule[1] ) as $selector ) {
					$selector = self::normalize_selector( $selector );
					$selector = preg_replace( '/\A\.wcmp-custom-skin \.mejs-container(?![\w-])/', '.wcmp-custom-skin.mejs-container', $selector );
					if ( isset( $allowed_selectors[$selector] ) ) {
						$selectors[] = $selector;
					}
				}
				if ( empty( $selectors ) ) {
					continue;
				}

				$declarations = [];
				foreach ( explode( ';', $rule[2] ) as $declaration ) {
					$parts = explode( ':', $declaration, 2 );
					$property = strtolower( trim( $parts[0] ) );
					$value = trim( $parts[1] ?? '' );
					if ( '' === $value || ! preg_match( $properties, $property ) ) {
						continue;
					}

					// Validate functions after replacing only the exact built-in sprite URL.
					$checked_value = preg_replace_callback(
						'/\burl\(\s*("[^"]*"|\'[^\']*\'|[^()\s"\']+)\s*\)/i',
						static function ( $match ) use ( $sprite_url ) {
							return trim( $match[1], "\"'" ) === $sprite_url ? 'none' : $match[0];
						},
						$value
					);
					preg_match_all( '/([a-z_-][a-z0-9_-]*)\s*\(/i', strtolower( $checked_value ), $used_functions );
					if ( array_diff( $used_functions[1], $functions ) ) {
						continue;
					}

					// Core rejects some safe color functions outside gradients. Check the remaining tokens separately.
					$checked_value = preg_replace( '/[a-z_-][a-z0-9_-]*(\((?:[^()]++|(?1))*\))/i', '0', $checked_value );
					if ( null === $checked_value || '' === safecss_filter_attr( 'color:' . $checked_value ) ) {
						continue;
					}

					$declarations[] = $property . ': ' . $value;
				}

				if ( $declarations ) {
					$rules[] = implode( ', ', $selectors ) . ' { ' . implode( '; ', $declarations ) . '; }';
				}
			}

			if ( '' !== trim( substr( $css, $offset ) ) || empty( $rules ) ) {
				throw $invalid_css;
			}

			return implode( "\n", $rules );
		}

		static private function wp_connector_inference( $prompt ) {
			$_set_timeout = function($timeout){ return 120; };
			add_filter('wp_ai_client_default_request_timeout', $_set_timeout, 999);

			try {
				$output = wp_ai_client_prompt($prompt)->generate_text();
			} finally {
				remove_filter('wp_ai_client_default_request_timeout', $_set_timeout, 999);
			}

			if ( is_wp_error( $output ) ) {
				throw new WCMP_Skin_Generator_Exception( __( 'The WordPress AI connector could not generate the skin.', 'music-player-for-woocommerce' ) );
			}
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

			$css_directory = ABSPATH . WPINC . '/js/mediaelement/';
			$reference = '';
			foreach ( [ 'mediaelementplayer-legacy.css', 'wp-mediaelement.css' ] as $file ) {
				$stylesheet = @file_get_contents( $css_directory . $file );
				if ( false !== $stylesheet ) {
					$reference .= $stylesheet . "\n";
				}
			}
			if ( '' === trim( $reference ) ) {
				throw new WCMP_Skin_Generator_Exception( __( 'The player stylesheet is unavailable.', 'music-player-for-woocommerce' ) );
			}

			$sprite_url = includes_url( 'js/mediaelement/mejs-controls.svg' );
			$schema = str_replace( "url('mejs-controls.svg')", "url('$sprite_url')", $reference );

			$prompt = "Generate CSS styles for a MediaElementPlayer skin based on these stylesheets in their load order:\n\n$schema\n\n"
					. "The following rules ALWAYS apply regardless of the skin description:\n"
					. "- Only modify color, background, border-radius, box-shadow, and font properties.\n"
					. "- Do NOT alter position, z-index, height, width, display, transform, or animation values.\n"
					. "- Preserve all SVG background-image URLs exactly as they appear in the source CSS.\n"
					. "- Reuse selectors from the supplied stylesheets, scoped to .wcmp-custom-skin, and preserve their states and combinators. Add allowed visual declarations to existing selectors as needed.\n"
					. "- Use flat rules only. Do not use at-rules, CSS variables or external resources. The only permitted URL is the existing MediaElementPlayer sprite.\n"
					. "- Infer a coherent color palette from the description and apply it consistently across all components (control bar, progress bar, buttons, volume slider, captions).\n\n"
					. "Output instructions:\n"
					. "Return only valid CSS code scoped under .wcmp-custom-skin. No comments, no explanations, no description, no extra information, no Markdown, no enclose the response in triple backticks.\n\n"
					. "Skin description:\n{$inference_data['skin_description']}";

			$provider = $inference_data['provider'] ?? '';
			if ( '' === $provider ) {
				$provider = self::is_wp_connector_available() ? 'wordpress' : 'gemini';
			}

			if ( isset( self::DIRECT_PROVIDERS[$provider] ) ) {
				$api_key = $inference_data['api_key'] ?? '';
				if ( '' === $api_key ) {
					throw new WCMP_Skin_Generator_Exception( __( 'The AI Provider API Key is required.', 'music-player-for-woocommerce' ) );
				}
				if ( ! is_string( $api_key ) || ! preg_match( '/\A[A-Za-z0-9._~+\/=-]{1,1024}\z/', $api_key ) ) {
					throw new WCMP_Skin_Generator_Exception( __( 'The AI provider API key is invalid.', 'music-player-for-woocommerce' ) );
				}
				$output = self::direct_inference( $provider, $prompt, $api_key );
			} elseif ( 'wordpress' === $provider && self::is_wp_connector_available() ) {
				$output = self::wp_connector_inference( $prompt );
			} else {
				throw new WCMP_Skin_Generator_Exception( __( 'The selected AI provider is not available.', 'music-player-for-woocommerce' ) );
			}

			return self::sanitize_generated_skin( $output, $reference, $sprite_url );

		} // End model_inference.

	} // End class WCMP_SKIN_GENERATOR.
}

// The settings page also loads this class to render the provider choices.
if ( ! isset( $_REQUEST['wcmp_skin_generator_action'] ) ) {
	return;
}

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( esc_html__( 'You do not have permission to manage custom skins.', 'music-player-for-woocommerce' ), '', [ 'response' => 403 ] );
}

if ( isset( $_POST['wcmp_skin_generator_description'] ) ) {
	check_admin_referer( 'wcmp-ai-skin-generator', '_wcmp_nonce' );

	$description = $_POST['wcmp_skin_generator_description'];
	$description = is_string( $description ) ? sanitize_textarea_field( wp_unslash( $description ) ) : '';
	if ( '' === $description || strlen( $description ) > WCMP_SKIN_GENERATOR::MAX_SKIN_BYTES ) {
		wp_send_json( [ 'error' => __( 'Enter a valid skin description.', 'music-player-for-woocommerce' ) ] );
	}

	$provider = $_POST['wcmp_skin_generator_ai_provider'] ?? '';
	if ( ! is_string( $provider ) ) {
		wp_send_json( [ 'error' => __( 'The selected AI provider is not available.', 'music-player-for-woocommerce' ) ] );
	}
	$provider = wp_unslash( $provider );
	$api_key = $_POST['wcmp_skin_generator_api_key'] ?? '';
	$api_key = is_string( $api_key ) ? trim( wp_unslash( $api_key ), ' ' ) : null;

	try {
		$output = [ 'success' => WCMP_SKIN_GENERATOR::model_inference( [
			'provider' => $provider,
			'api_key' => $api_key,
			'skin_description' => $description,
		] ) ];
	} catch ( WCMP_Skin_Generator_Exception $err ) {
		$output = [ 'error' => $err->getMessage() ];
	} catch ( Throwable $err ) {
		$output = [ 'error' => __( 'The skin could not be generated.', 'music-player-for-woocommerce' ) ];
	}

	wp_send_json( $output );
} elseif ( isset( $_POST['wcmp_custom_skin'] ) ) {
	check_admin_referer( 'wcmp-custom-skin-preview', '_wcmp_nonce' );

	$custom_skin = $_POST['wcmp_custom_skin'];
	if ( ! is_string( $custom_skin ) || strlen( $custom_skin ) > WCMP_SKIN_GENERATOR::MAX_SKIN_BYTES ) {
		wp_die( esc_html__( 'The custom skin code is invalid.', 'music-player-for-woocommerce' ), '', [ 'response' => 400 ] );
	}

	$custom_skin = sanitize_textarea_field( wp_unslash( $custom_skin ) );
	if ( '' !== $custom_skin ) {
		// Keep editable CSS out of the HTML raw-text context.
		$skin_stylesheet = 'data:text/css;charset=utf-8;base64,' . base64_encode( $custom_skin );
		print '<link rel="stylesheet" href="'. esc_url( includes_url( 'js/mediaelement/mediaelementplayer-legacy.min.css' ) ) .'">
		<link rel="stylesheet" href="' . esc_url( includes_url( 'js/mediaelement/wp-mediaelement.min.css' ) ) .'">
		<link rel="stylesheet" href="' . esc_url( plugin_dir_url( __FILE__ ) . '../vendors/mejs-skins/mejs-skins.min.css' ) . '">
		<style>
			body {margin: 0;padding: 20px;font-family: Arial, sans-serif;background: #ffffff;}
			.player-container {max-width: 600px;margin: 0 auto;}
			.mejs__container {margin: 0 auto;}
		</style>
		<link rel="stylesheet" href="' . esc_url( $skin_stylesheet, [ 'data' ] ) . '">
		<script type="text/javascript" src="' . esc_url( includes_url( 'js/jquery/jquery.js' ) ) . '"></script>
		<script src="' . esc_url( includes_url( 'js/jquery/jquery-migrate.min.js' ) ) . '"></script>
		<script src="' . esc_url( includes_url( 'js/mediaelement/mediaelement-and-player.min.js' ) ) . '"></script>
		<script src="' . esc_url( includes_url( 'js/mediaelement/mediaelement-migrate.js' ) ) . '"></script>
		<script src="' . esc_url( includes_url( 'js/mediaelement/wp-mediaelement.js' ) ) . '"></script>
		<script src="' . esc_url( plugin_dir_url( __FILE__ ) . '../js/public.js' ) . '"></script>
		<div class="wcmp-player-container">
			<audio controlslist="nodownload" volume="1" preload="none" data-lazyloading="none" class="wcmp-player mejs-classic  wcmp-custom-skin" src="'. esc_url( plugin_dir_url( __FILE__ ) . '../vendors/demo/demo.mp3' ) . '">
			<source src="' . esc_url( plugin_dir_url( __FILE__ ) . '../vendors/demo/demo.mp3' ) . '" type="audio/mp3">
			</audio>
		</div>';
		exit;
	}
}