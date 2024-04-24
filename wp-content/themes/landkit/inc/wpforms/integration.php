<?php
/**
 * WP Forms Integration
 */

add_filter( 'transient_wpforms_activation_redirect', '__return_false' );

add_filter( 'wpforms_field_properties', 'landkit_wpforms_field_properties', 10, 3 );

add_filter( 'wpforms_field_properties_text', 'landkit_wpforms_inputs_properties', 10, 3 );
add_filter( 'wpforms_field_properties_textarea', 'landkit_wpforms_inputs_properties', 10, 3 );
add_filter( 'wpforms_field_properties_number', 'landkit_wpforms_inputs_properties', 10, 3 );
add_filter( 'wpforms_field_properties_email', 'landkit_wpforms_inputs_properties', 10, 3 );
add_filter( 'wpforms_field_properties_select', 'landkit_wpforms_select_properties', 10, 3 );
add_filter( 'wpforms_field_properties_name', 'landkit_wpforms_name_properties', 10, 3 );

add_filter( 'wpforms_field_properties_checkbox', 'landkit_wpforms_check_properties', 10, 3 );
add_filter( 'wpforms_field_properties_radio', 'landkit_wpforms_check_properties', 10, 3 );
/**
 * Override Radio Properties.
 *
 * @param array $properties Field properties.
 * @param array $field      Field settings.
 * @param array $form_data  Form data and settings.
 *
 * @return array
 */
function landkit_wpforms_radio_properties( $properties, $field, $form_data ) {
	return $properties;
}

/**
 * Override Checkbox Properties.
 *
 * @param array $properties Field properties.
 * @param array $field      Field settings.
 * @param array $form_data  Form data and settings.
 *
 * @return array
 */
function landkit_wpforms_check_properties( $properties, $field, $form_data ) {
	$properties['input_container']['class'][] = 'list-unstyled';
	foreach ( $properties['inputs'] as $key => $input ) {
		$properties['inputs'][ $key ]['container']['class'][] = 'form-group';
		$properties['inputs'][ $key ]['container']['class'][] = 'form-check';
		$properties['inputs'][ $key ]['label']['class'][]     = 'form-check-label';
		$properties['inputs'][ $key ]['class'][]              = 'form-check-input';
	}
	return $properties;
}

/**
 * Field properties
 *
 * @param array $properties Field properties.
 * @param array $field      Field settings.
 * @param array $form_data  Form data and settings.
 *
 * @return array
 */
function landkit_wpforms_field_properties( $properties, $field, $form_data ) {
	$properties = landkit_wpforms_label_properties( $properties, $field, $form_data );
	$properties = landkit_wpforms_container_properties( $properties, $field, $form_data );
	$properties = landkit_wpforms_field_description_properties( $properties, $field, $form_data );
	$properties = landkit_wpforms_error_properties( $properties, $field, $form_data );

	return $properties;
}

/**
 * Error Properties.
 *
 * @param array $properties Field properties.
 * @param array $field      Field settings.
 * @param array $form_data  Form data and settings.
 *
 * @return array
 */
function landkit_wpforms_error_properties( $properties, $field, $form_data ) {
	$error_classes = $properties['error']['class'];
	foreach ( $error_classes as $error_class ) {
		switch ( $error_class ) {
			case 'wpforms-error':
				$properties['error']['class'][] = 'is-invalid';
				break;
		}
	}
	return $properties;
}

/**
 * Field Description Properties.
 *
 * @param array $properties Field properties.
 * @param array $field      Field settings.
 * @param array $form_data  Form data and settings.
 *
 * @return array
 */
function landkit_wpforms_field_description_properties( $properties, $field, $form_data ) {
	$desc_classes = $properties['description']['class'];

	foreach ( $desc_classes as $desc_class ) {
		switch ( $desc_class ) {
			case 'wpforms-field-description':
				$properties['description']['class'][] = 'form-text';
				$properties['description']['class'][] = 'text-muted';
				$properties['description']['class'][] = 'small';
				break;
		}
	}

	return $properties;
}

/**
 * Label Properties.
 *
 * @param array $properties Field properties.
 * @param array $field      Field settings.
 * @param array $form_data  Form data and settings.
 *
 * @return array
 */
function landkit_wpforms_label_properties( $properties, $field, $form_data ) {
	$label_classes = $properties['label']['class'];

	foreach ( $label_classes as $label_class ) {
		switch ( $label_class ) {
			case 'wpforms-label-hide':
				$properties['label']['class'][] = 'sr-only';
				break;
			case 'wpforms-sublabel-hide':
				$properties['sublabel']['class'][] = 'sr-only';
				break;

		}
	}

	return $properties;
}

/**
 * Container Properties.
 *
 * @param array $properties Field properties.
 * @param array $field      Field settings.
 * @param array $form_data  Form data and settings.
 *
 * @return array
 */
function landkit_wpforms_container_properties( $properties, $field, $form_data ) {
	$properties['container']['class'][] = 'form-group';
	$col_classes                        = wpforms_column_classes( $properties['container']['class'] );
	unset( $properties['container']['class'] );

	$properties['container']['class'] = $col_classes;

	if ( 'floating-label' === isset( $form_data['settings']['label'] ) && $form_data['settings']['label']['type'] && ( 'text' === $field['type'] || 'name' === $field['type'] || 'email' === $field['type'] || 'number' === $field['type'] || 'textarea' === $field['type'] ) ) {
		$properties['container']['class'][] = 'form-label-group';
	}

	return $properties;
}

/**
 * Column Classes.
 *
 * @param array $classes Classes.
 * @return array
 */
function wpforms_column_classes( $classes ) {
	$has_width_class = false;
	if ( is_array( $classes ) ) {
		foreach ( $classes as $key => $class ) {
			switch ( $class ) {
				case 'wpforms-one-half':
					$classes[]       = 'col-6';
					$has_width_class = true;
					break;
				case 'wpforms-one-third':
					$classes[]       = 'col-4';
					$has_width_class = true;
					break;
				case 'wpforms-one-fourth':
					$classes[]       = 'col-3';
					$has_width_class = true;
					break;
				case 'wpforms-two-thirds':
					$classes[]       = 'col-8';
					$has_width_class = true;
					break;
				case 'wpforms-two-fourths':
					$classes[]       = 'col-6';
					$has_width_class = true;
					break;
			}
		}
	}

	if ( ! $has_width_class ) {
		$classes[] = 'col-12';
	}

	return $classes;
}

/**
 * Input field Properties.
 *
 * @param array $properties Field properties.
 * @param array $field      Field settings.
 * @param array $form_data  Form data and settings.
 *
 * @return array
 */
function landkit_wpforms_inputs_properties( $properties, $field, $form_data ) {
	$properties['inputs']['primary']['class'][] = 'form-control';
	if ( 'textarea' === $field['type'] ) {
		$properties['inputs']['primary']['attr']['rows'] = '5';
	}

	if ( 'floating-label' === isset( $form_data['settings']['label'] ) && $form_data['settings']['label']['type'] && ( 'text' === $field['type'] || 'name' === $field['type'] || 'email' === $field['type'] || 'number' === $field['type'] || 'textarea' === $field['type'] ) ) {
		$properties['inputs']['primary']['class'][] = 'form-control-flush';
	}

	return $properties;
}

/**
 * Select Properties.
 *
 * @param array $properties Field properties.
 * @param array $field      Field settings.
 * @param array $form_data  Form data and settings.
 *
 * @return array
 */
function landkit_wpforms_select_properties( $properties, $field, $form_data ) {
	$properties['input_container']['class'][] = 'custom-select';
	return $properties;
}

/**
 * Name Properties.
 *
 * @param array $properties Field properties.
 * @param array $field      Field settings.
 * @param array $form_data  Form data and settings.
 *
 * @return array
 */
function landkit_wpforms_name_properties( $properties, $field, $form_data ) {
	$properties['container']['class'][] = 'form-group';

	foreach ( $properties['inputs'] as $key => $input ) {
		$properties['inputs'][ $key ]['class'][] = 'form-control';

		if ( 'floating-label' === isset( $form_data['settings']['label'] ) && $form_data['settings']['label']['type'] ) {
			$properties['inputs'][ $key ]['class'][] = 'form-control-flush';
		}

		if ( isset( $input['block'] ) ) {
			$properties['inputs'][ $key ]['block'] = wpforms_column_classes( $input['block'] );

			if ( 'floating-label' === $form_data['settings']['label']['type'] ) {
				$properties['inputs'][ $key ]['block'][] = 'form-label-group';
			}
		}
	}

	$properties['description']['class'][] = 'form-text';
	$properties['description']['class'][] = 'text-muted';
	$properties['description']['class'][] = 'small';

	return $properties;
}

add_action( 'wpforms_display_fields_before', 'landkit_wpforms_row_start', 10 );
add_action( 'wpforms_display_fields_after', 'landkit_wpforms_row_end', 10 );

/**
 * Row wrap start
 */
function landkit_wpforms_row_start() {
	?><div class="row">
	<?php
}

/**
 * Row wrap end
 */
function landkit_wpforms_row_end() {
	?>
	</div>
	<?php
}

/**
 * Display before field
 *
 * @param array $field      Field settings.
 * @param array $form_data  Form data and settings.
 */
function landkit_wpforms_display_field_before( $field, $form_data ) {
	$container         = $field['properties']['container'];
	$container_classes = in_array( 'form-label-group', $container['class'], true );

	if ( 'floating-label' !== isset( $form_data['settings']['label'] ) && $form_data['settings']['label']['type'] && true !== $container_classes ) {
		return;
	}

	if ( false === $container_classes ) {
		wpforms()->frontend->field_label( $field, $form_data );
		remove_action( 'wpforms_display_field_before', array( wpforms()->frontend, 'field_label' ), 15 );
	}

	remove_action( 'wpforms_display_field_before', array( wpforms()->frontend, 'field_label' ), 15 );

}

add_action( 'wpforms_display_field_before', 'landkit_wpforms_display_field_before', 10, 2 );


/**
 * Display after field
 *
 * @param array $field  Form fields.
 * @param array $form_data  Form data and settings.
 */
function landkit_wpforms_display_field_after( $field, $form_data ) {
	$container         = $field['properties']['container'];
	$container_classes = in_array( 'form-label-group', $container['class'], true );

	if ( 'floating-label' !== isset( $form_data['settings']['label'] ) && $form_data['settings']['label']['type'] && true !== $container_classes ) {
		return;
	}
	if ( true === $container_classes ) {
		wpforms()->frontend->field_label( $field, $form_data );
	}

}

add_action( 'wpforms_display_field_after', 'landkit_wpforms_display_field_after', 1, 2 );

/**
 * Display before field
 *
 * @param array $form_atts  Form atts.
 * @param array $form_data  Form data and settings.
 */
function landkit_wpforms_frontend_form_atts( $form_atts, $form_data ) {
	if ( isset( $form_data['settings']['enable'] ) && isset( $form_data['settings']['enable']['make_row'] ) && ! empty( $form_data['settings']['enable']['make_row'] ) ) {
		$form_atts['class'][] = 'form-row';
	}

	if ( 'center' === isset( $form_data['settings']['button'] ) && isset( $form_data['settings']['button']['alignment'] ) && $form_data['settings']['button']['alignment'] ) {
		$form_atts['class'][] = 'button-center';
	}
	return $form_atts;
}
add_filter( 'wpforms_frontend_form_atts', 'landkit_wpforms_frontend_form_atts', 10, 2 );



/**
 * Action that fires immediately before the submit button element is displayed.
 *
 * @link  https://wpforms.com/developers/wpforms_display_submit_before/
 *
 * @param array $form_data Form data and settings.
 */
function wpf_dev_display_submit_before( $form_data ) {

	// Only run on my form with ID = 5.
	if ( 'center' !== isset( $form_data['settings']['button'] ) && $form_data['settings']['button']['alignment'] ) {
		return;
	}

	// Run code or see example echo statement below.
	echo '<div class="row justify-content-center"><div class="col-auto">';
}
add_action( 'wpforms_display_submit_before', 'wpf_dev_display_submit_before', 10, 1 );

/**
 * Display submit after field
 *
 * @param array $form_data  Form data and settings.
 */
function wpf_dev_display_submit_after( $form_data ) {

	// Only run on my form with ID = 5.
	if ( 'center' !== isset( $form_data['settings']['button'] ) && $form_data['settings']['button']['alignment'] ) {
		return;
	}

	// Run code or see example echo statement below.
	echo '</div></div>';
}
add_action( 'wpforms_display_submit_after', 'wpf_dev_display_submit_after', 10, 1 );


if ( ! function_exists( 'landkit_wpforms_settings_general' ) ) {
	/**
	 * Adding WPForm field
	 *
	 * @param object $settings Adding Form settings.
	 */
	function landkit_wpforms_settings_general( $settings ) {
		wpforms_panel_field(
			'select',
			'label',
			'type',
			$settings->form_data,
			esc_html__( 'Label Style', 'landkit' ),
			array(
				'default'     => 'Default',
				'options'     => array(
					'default'        => esc_html__( 'Default', 'landkit' ),
					'floating-label' => esc_html__( 'Floating Label', 'landkit' ),
				),
				'class'       => 'wpforms-panel-field-label-type-wrap',
				'input_class' => 'wpforms-panel-field-label-type',
				'parent'      => 'settings',
			)
		);

		wpforms_panel_field(
			'checkbox',
			'enable',
			'make_row',
			$settings->form_data,
			esc_html__( 'Enable Form Row', 'landkit' ),
			array(
				'class'       => 'wpforms-panel-field-enable-make_row-wrap',
				'input_class' => 'wpforms-panel-field-enable-make_row',
				'parent'      => 'settings',

			)
		);

		wpforms_panel_field(
			'select',
			'button',
			'alignment',
			$settings->form_data,
			esc_html__( 'Button Alignment', 'landkit' ),
			array(
				'default'     => 'Default',
				'options'     => array(
					'left'   => esc_html__( 'Left', 'landkit' ),
					'center' => esc_html__( 'Center', 'landkit' ),
				),
				'class'       => 'wpforms-panel-field-button-alignment-wrap',
				'input_class' => 'wpforms-panel-field-button-alignment',
				'parent'      => 'settings',
			)
		);

	}
}

add_action( 'wpforms_form_settings_general', 'landkit_wpforms_settings_general', 10, 1 );


