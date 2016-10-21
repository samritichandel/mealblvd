<?php
/**
 * Single view Company information box
 *
 * Hooked into single_job_listing_start priority 30
 *
 * @since  1.14.0
 */

global $post;
// get our custom meta
//$facebook_url = get_post_meta( get_the_ID(), '_company_facebook', true);
$location = get_post_meta( get_the_ID(), '_job_location', true);
$phone = get_post_meta( get_the_ID(), '_company_phone', true);
$twitter = get_post_meta( get_the_ID(), '_company_twitter', true);
//$currency=get_post_meta( get_the_ID(), '_currency', true);
$price=get_post_meta( get_the_ID(), '_price', true);
?>
<div class="single-meta">
	<?php
	display_average_listing_rating();
	?>
	<div class="price-single-listing">
	<?php if(!empty($price)) {
			echo '$';
			echo $price ;
		}?> 
	
	<span>PER PERSON</span>
	</div>
	<?php 
	if ( ! empty( $phone ) ) :
		if ( strlen( $phone ) > 30 ) : ?>
			<a class="listing-contact  listing--phone" href="tel:<?php echo $phone; ?>" itemprop="telephone"><?php esc_html_e( 'Phone', 'listable' ); ?></a>
		<?php else : ?>
			<a class="listing-contact  listing--phone" href="tel:<?php echo $phone; ?>" itemprop="telephone"><?php echo $phone; ?></a>
		<?php endif; ?>
	<?php endif;

	do_action( 'listable_single_job_listing_before_social_icons' );

	if ( ! empty( $twitter ) ) {
		$twitter = preg_replace("[@]", "", $twitter);
		if ( strlen( $twitter ) > 30 ) : ?>
			<a class="listing-contact  listing--twitter" href="https://twitter.com/<?php echo $twitter; ?>" itemprop="url"> <?php esc_html_e( 'Twitter', 'listable' ); ?></a>
		<?php else : ?>
			<a class="listing-contact  listing--twitter" href="https://twitter.com/<?php echo $twitter; ?>" itemprop="url">@<?php echo $twitter; ?></a>
		<?php endif; ?>
	<?php }

	/** temporary(or not) removed
	if ( ! empty( $facebook_url ) ) { ?>
		<!--a class="company_facebook"" href="<?php echo $facebook_url ?>"><?php _e( 'Facebook', 'listable'); ?></a-->
	<?php }
	 */
	
	do_action( 'listable_single_job_listing_after_social_icons' );

	 ?>
</div>