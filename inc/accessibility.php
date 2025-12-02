<?php
/**
 * Accessibility improvements for forms and inputs.
 */

/**
 * Output skip links so keyboard users can bypass repeated navigation.
 */
function reban_skip_links() {
	?>
	<div class="skip-links">
		<a class="skip-link" href="#main-content">Saltar al contenido principal</a>
		<a class="skip-link" href="#site-navigation">Saltar a la navegacion principal</a>
	</div>
	<?php
}
add_action( 'genesis_before', 'reban_skip_links', 1 );

/**
 * Ensure main content is a valid skip-link target.
 *
 * @param array $attributes Element attributes.
 *
 * @return array
 */
function reban_content_attributes( $attributes ) {
	$attributes['id']       = 'main-content';
	$attributes['tabindex'] = '-1';

	if ( empty( $attributes['role'] ) ) {
		$attributes['role'] = 'main';
	}

	return $attributes;
}
add_filter( 'genesis_attr_content', 'reban_content_attributes' );

/**
 * Ensure primary navigation is an accessible skip-link target.
 *
 * @param array $attributes Element attributes.
 *
 * @return array
 */
function reban_nav_attributes( $attributes ) {
	$attributes['id'] = 'site-navigation';

	if ( empty( $attributes['aria-label'] ) ) {
		$attributes['aria-label'] = 'Navegacion principal';
	}

	return $attributes;
}
add_filter( 'genesis_attr_nav-primary', 'reban_nav_attributes' );

/**
 * Generate accessible search form with proper label/input association.
 *
 * @param string $form Current form markup.
 * @return string Improved form markup with accessible label.
 */
function reban_a11y_search_labels( $form ) {
	$search_id = wp_unique_id( 'search-' );

	// Build complete search input with label.
	$search_input = sprintf(
		'<label for="%1$s" class="screen-reader-text">Buscar:</label><input id="%1$s" type="text" value="" name="s" class="search-input" placeholder="Buscar en el sitio" />',
		esc_attr( $search_id )
	);

	// Replace any existing search input with our accessible version.
	// This pattern matches inputs with or without existing attributes.
	$form = preg_replace(
		'/<input[^>]*type=["\']?text["\']?[^>]*name=["\']?s["\']?[^>]*>/',
		$search_input,
		$form
	);

	return $form;
}
add_filter( 'get_search_form', 'reban_a11y_search_labels' );

/**
 * Improve radio and checkbox inputs by wrapping them in labels.
 *
 * This uses a callback approach to generate proper IDs and labels
 * instead of relying on fragile regex patterns.
 *
 * @param string $content Post content.
 * @return string Content with accessible radio/checkbox inputs.
 */
function reban_a11y_input_labels( $content ) {
	static $radio_counter = 0;
	static $checkbox_counter = 0;

	// Wrap radio inputs in labels with unique IDs.
	$content = preg_replace_callback(
		'/<input\s+([^>]*\s+)?class="radio"([^>]*\s+)?name="radio_button"([^>]*\s+)?type="radio"([^>]*\s+)?value="([^"]+)"([^>]*)>/i',
		function ( $matches ) use ( &$radio_counter ) {
			$radio_counter++;
			$value      = esc_attr( $matches[5] );
			$label_text = esc_html( 'Opcion ' . $value );
			$input_id   = 'radio-' . $radio_counter;

			return sprintf(
				'<label for="%1$s"><input id="%1$s" class="radio" name="radio_button" type="radio" value="%2$s"> %3$s</label>',
				esc_attr( $input_id ),
				$value,
				$label_text
			);
		},
		$content
	);

	// Wrap checkbox inputs in labels with unique IDs.
	$content = preg_replace_callback(
		'/<input\s+([^>]*\s+)?class="checkbox"([^>]*\s+)?name="checkboxes"([^>]*\s+)?type="checkbox"([^>]*\s+)?value="([^"]+)"([^>]*)>/i',
		function ( $matches ) use ( &$checkbox_counter ) {
			$checkbox_counter++;
			$value      = esc_attr( $matches[5] );
			$label_text = esc_html( 'Opcion ' . $value );
			$input_id   = 'checkbox-' . $checkbox_counter;

			return sprintf(
				'<label for="%1$s"><input id="%1$s" class="checkbox" name="checkboxes" type="checkbox" value="%2$s"> %3$s</label>',
				esc_attr( $input_id ),
				$value,
				$label_text
			);
		},
		$content
	);

	return $content;
}
add_filter( 'the_content', 'reban_a11y_input_labels' );
