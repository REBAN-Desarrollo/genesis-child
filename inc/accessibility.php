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

// Agregar etiquetas accesibles a los campos de formulario.
function reban_a11y_search_labels( $form ) {
	$search_id    = wp_unique_id( 'search-' );
	$search_label = sprintf(
		'<label for="%1$s">Buscar: <input id="%1$s" type="text" value="" name="s" class="search-input" placeholder="Buscar en el sitio" /></label>',
		esc_attr( $search_id )
	);

	// Mejorar accesibilidad de formulario de busqueda.
	$form = str_replace(
		'<input type="text" value="" name="s" class="search-input" placeholder="Buscar en el sitio" />',
		$search_label,
		$form
	);
	return $form;
}
add_filter( 'get_search_form', 'reban_a11y_search_labels' );

// Mejorar los formularios de radio y checkbox.
function reban_a11y_input_labels( $content ) {
	// Patrones para identificar inputs sin etiquetas.
	$patterns = array(
		'/<input class="radio" name="radio_button" type="radio" value="([^"]+)">/i'    => '<label><input class="radio" name="radio_button" type="radio" value="$1"> Opcion $1</label>',
		'/<input class="checkbox" name="checkboxes" type="checkbox" value="([^"]+)">/i' => '<label><input class="checkbox" name="checkboxes" type="checkbox" value="$1"> Opcion $1</label>',
	);

	return preg_replace( array_keys( $patterns ), array_values( $patterns ), $content );
}
add_filter( 'the_content', 'reban_a11y_input_labels' );
