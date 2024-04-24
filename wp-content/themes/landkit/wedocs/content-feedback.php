<?php global $post; ?>
<!-- Resolved -->
<div class="d-flex align-items-center wedocs-feedback-wrap wedocs-hide-print">
	<?php
	$positive = (int) get_post_meta( $post->ID, 'positive', true );
	$negative = (int) get_post_meta( $post->ID, 'negative', true );
	/* translators: %d: number of person */
	$positive_title = $positive ? sprintf( _n( '%d person found this useful', '%d persons found this useful', $positive, 'landkit' ), number_format_i18n( $positive ) ) : esc_html__( 'No votes yet', 'landkit' );
	/* translators: %d: number of person */
	$negative_title = $negative ? sprintf( _n( '%d person found this not useful', '%d persons found this not useful', $negative, 'landkit' ), number_format_i18n( $negative ) ) : esc_html__( 'No votes yet', 'landkit' );
	?>
	<div class="btn-group btn-group-toggle mr-4" data-toggle="buttons">
		<a href="#" class="btn btn-sm btn-white active px-4 py-3" data-id="<?php the_ID(); ?>" data-type="positive" title="<?php echo esc_attr( $positive_title ); ?>">
			<i class="fe fe-thumbs-down"></i>

			<?php if ( 0 && $positive ) { ?>
				<span class="count badge badge-light"><?php echo esc_html( number_format_i18n( $positive ) ); ?></span>
			<?php } ?>
		</a>

		<a href="#" class="btn btn-sm btn-white px-4 py-3" data-id="<?php the_ID(); ?>" data-type="negative" title="<?php echo esc_attr( $negative_title ); ?>">
			<i class="fe fe-thumbs-up"></i>

			<?php if ( 0 && $negative ) { ?>
				<span class="count badge badge-light"><?php echo esc_html( number_format_i18n( $negative ) ); ?></span>
			<?php } ?>
		</a>
	</div>
	<span class="font-size-sm text-muted"><?php echo esc_html__( 'Did this help solve your issue?', 'landkit' ); ?></span>
</div>

