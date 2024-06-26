<?php
/**
 * Shows the `text` form field on job listing forms.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/form-fields/text-field.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @package     wp-job-manager
 * @version     1.31.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<input type="text" class="form-control input-text" name="<?php echo esc_attr( isset( $field['name'] ) ? $field['name'] : $key ); ?>"
																	<?php
																	if ( isset( $field['autocomplete'] ) && false === $field['autocomplete'] ) {
																		echo ' autocomplete="off"'; }
																	?>
id="<?php echo esc_attr( $key ); ?>" placeholder="<?php echo empty( $field['placeholder'] ) ? '' : esc_attr( $field['placeholder'] ); ?>" value="<?php echo isset( $field['value'] ) ? esc_attr( $field['value'] ) : ''; ?>" maxlength="<?php echo esc_attr( ! empty( $field['maxlength'] ) ? $field['maxlength'] : '' ); ?>" 
				<?php
				if ( ! empty( $field['required'] ) ) {
					echo 'required';
				}
				?>
	/>
<?php
if ( ! empty( $field['description'] ) ) :
	?>
	<p class="description mb-0 mt-3 ml-2 text-muted font-size-sm"><?php echo wp_kses_post( $field['description'] ); ?></p><?php endif; ?>
