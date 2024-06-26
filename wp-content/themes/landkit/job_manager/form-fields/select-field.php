<?php
/**
 * Shows the `select` form field on job listing forms.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/form-fields/select-field.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @package     wp-job-manager
 * @version     1.31.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<select class="custom-select" data-choices name="<?php echo esc_attr( isset( $field['name'] ) ? $field['name'] : $key ); ?>" id="<?php echo esc_attr( $key ); ?>" 
	<?php
	if ( ! empty( $field['required'] ) ) {
		echo 'required';
	}
	?>
>
	<?php foreach ( $field['options'] as $key => $value ) : ?>
		<option value="<?php echo esc_attr( $key ); ?>"
			<?php
			if ( isset( $field['value'] ) || isset( $field['default'] ) ) {
				selected( isset( $field['value'] ) ? $field['value'] : $field['default'], $key );
			}
			?>
		><?php echo esc_html( $value ); ?></option>
	<?php endforeach; ?>
</select>
<?php
if ( ! empty( $field['description'] ) ) :
	?>
	<p class="mb-0 mb-2 text-muted font-size-sm description"><?php echo wp_kses_post( $field['description'] ); ?></small><?php endif; ?>
