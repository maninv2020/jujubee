<?php
/**
 * Class to setup taxonomies meta
 *
 * @package Landkit
 */
class Landkit_Post_Taxonomies {
	/**
	 * Setup Class
	 */
	public function __construct() {
		// Add form fields.
		add_action( 'category_add_form_fields', array( $this, 'add_taxonomy_fields' ), 10 );
		add_action( 'category_edit_form_fields', array( $this, 'edit_taxonomy_fields' ), 10, 2 );
		add_action( 'post_tag_add_form_fields', array( $this, 'add_taxonomy_fields' ), 10 );
		add_action( 'post_tag_edit_form_fields', array( $this, 'edit_taxonomy_fields' ), 10, 2 );

		// Save Values.
		add_action( 'create_term', array( $this, 'save_taxonomy_fields' ), 10, 3 );
		add_action( 'edit_term', array( $this, 'save_taxonomy_fields' ), 10, 3 );

		add_action( 'admin_enqueue_scripts', array( $this, 'load_media' ) );
		add_action( 'admin_footer', array( $this, 'add_script' ) );
	}
	/**
	 * Load Media
	 */
	public function load_media() {
		wp_enqueue_media();
	}

	/**
	 * Add taxonomy fields.
	 *
	 * @return void
	 */
	public function add_taxonomy_fields() {
		?>
		<div id="cover-image" class="form-field term-group">
			<label for="tax-cover-image-id"><?php esc_html_e( 'Cover Image', 'landkit' ); ?></label>
			<input type="hidden" id="tax-cover-image-id" name="lk_cover_image_id" class="upload_media_id" value="">
			<div id="tax-media-wrapper"></div>
			<p>
				<input type="button" class="button button-secondary lk_tax_media_button" id="lk_tax_media_button" name="lk_tax_media_button" value="<?php esc_attr_e( 'Add Image', 'landkit' ); ?>" />
				<input type="button" class="button button-secondary lk_tax_media_remove" id="lk_tax_media_remove" name="lk_tax_media_remove" value="<?php esc_attr_e( 'Remove Image', 'landkit' ); ?>" />
			</p>
		</div>
		<?php
	}

	/**
	 * Edit Category fields.
	 *
	 * @param mixed $term Term being edited.
	 * @param mixed $taxonomy Taxonomy of the term being edited.
	 */
	public function edit_taxonomy_fields( $term, $taxonomy ) {
		?>
		<tr id="cover-image" class="form-field term-group-wrap">
			<th scope="row">
				<label for="tax-cover-image-id"><?php esc_html_e( 'Cover Image', 'landkit' ); ?></label>
			</th>
			<td>
				<?php $image_id = get_term_meta( $term->term_id, '_lk_cover_image_id', true ); ?>
				<input type="hidden" id="tax-cover-image-id" name="lk_cover_image_id" class="upload_media_id" value="<?php echo esc_attr( $image_id ); ?>">
				<div id="tax-media-wrapper">
					<?php if ( $image_id ) { ?>
						<?php echo wp_get_attachment_image( $image_id, 'thumbnail' ); ?>
					<?php } ?>
				</div>
				<p>
					<input type="button" class="button button-secondary lk_tax_media_button" id="lk_tax_media_button" name="lk_tax_media_button" value="<?php esc_attr_e( 'Add Image', 'landkit' ); ?>" />
					<input type="button" class="button button-secondary lk_tax_media_remove" id="lk_tax_media_remove" name="lk_tax_media_remove" value="<?php esc_attr_e( 'Remove Image', 'landkit' ); ?>" />
				</p>
			</td>
		</tr>
		<?php
	}

	/**
	 * Save taxonomy fields.
	 *
	 * @param mixed $term_id Term ID being saved.
	 * @param mixed $tt_id being saved.
	 * @param mixed $taxonomy Taxonomy of the term being edited.
	 * @return void
	 */
	public function save_taxonomy_fields( $term_id, $tt_id, $taxonomy ) {
		if ( in_array( $taxonomy, [ 'category', 'post_tag' ], true ) ) {
			if ( isset( $_POST['lk_cover_image_id'] ) && '' !== $_POST['lk_cover_image_id'] ) {
				$image = wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['lk_cover_image_id'] ) ) );
				update_term_meta( $term_id, '_lk_cover_image_id', $image );
			} else {
				update_term_meta( $term_id, '_lk_cover_image_id', '' );
			}
		}
	}

	/**
	 * Add script
	 *
	 * @since 1.0.0
	 */
	public function add_script() {
		?>
		<script>
			jQuery(document).ready(function($) {
				if ( ! $('.upload_image_id').val() ) {
					$('.lk_tax_media_remove').hide();
				}

				$(document).on( 'click', '#lk_tax_media_button', function( event ){
					var $this = $(this);
					var wrap = $(this).parents('.form-field').attr('id');

					if( typeof file_frame == 'undefined' ) {
						var file_frame;
					}

					event.preventDefault();

					// If the media frame already exists, reopen it.
					if ( file_frame ) {
						file_frame.open();
						return;
					}

					// Create the media frame.
					file_frame = wp.media.frames.downloadable_file = wp.media({
						title: 'Choose an image',
						button: {
							text: 'Use image',
						},
						multiple: false
					});

					// When an image is selected, run a callback.
					file_frame.on( 'select', function() {
						attachment = file_frame.state().get('selection').first().toJSON();
						$('#'+wrap+' .upload_media_id').val(attachment.id);
						$('#'+wrap+' #tax-media-wrapper').html('<img class="tax_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
						$('#'+wrap+' #tax-media-wrapper .tax_media_image').attr('src', attachment.url).css('display', 'block');
						$('#'+wrap+' .lk_tax_media_remove').show();
						$this.closest( '.widget-inside' ).find( '.widget-control-save' ).prop( 'disabled', false );
					});

					// Finally, open the modal.
					file_frame.open();
				});

				$('body').on('click', '.lk_tax_media_remove', function() {
					var wrap = $(this).parents('.form-field').attr('id');
					$('#'+wrap+' .upload_media_id').val('');
					$('#'+wrap+' #tax-media-wrapper').html('');
					$('#'+wrap+' .lk_tax_media_remove').hide();
				});

				// Thanks: http://stackoverflow.com/questions/15281995/wordpress-create-tax-ajax-response
				$(document).ajaxComplete(function(event, xhr, settings) {
					var queryStringArr = settings.data.split('&');
					if ($.inArray('action=add-tag', queryStringArr) !== -1) {
						var xml = xhr.responseXML;
						$response = $(xml).find('term_id').text();
						if ($response != "") {
							// Clear the thumb image
							$('#tax-media-wrapper').html('');
							$('#'+wrap+' .lk_tax_media_remove').hide();
						}
					}
				});
			});
		</script>
		<?php
	}
}

new Landkit_Post_Taxonomies();
