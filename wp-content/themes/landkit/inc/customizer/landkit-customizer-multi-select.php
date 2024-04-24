<?php
/**
 * Landkit Customizer Multiselect
 *
 * @package  landkit
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Landkit_Customize_Control_Multiple_Select' ) ) :

	include_once ABSPATH . 'wp-includes/class-wp-customize-control.php';
	/**
	 * The Landkit Customizer Multi Select class
	 */
	class Landkit_Customize_Control_Multiple_Select extends WP_Customize_Control {
		/**
		 * The type of customize control being rendered.
		 *
		 * @var array
		 * @param $type Type.
		 */
		public $type = 'multiple-select';

		/**
		 * Displays the multiple select on the customize screen.
		 */
		public function render_content() {

			if ( empty( $this->choices ) ) {
				return;
			}

			?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<select <?php $this->link(); ?> multiple="multiple" style="height: 100%;">
					<?php
					foreach ( $this->choices as $value => $label ) {
						$selected = ( in_array( $value, $this->value(), true ) ) ? selected( 1, 1, false ) : '';
						echo '<option value="' . esc_attr( $value ) . '"' . esc_attr( $selected ) . '>' . wp_kses_post( $label ) . '</option>';
					}
					?>
				</select>
			</label>
			<?php
		}
	}
endif;
