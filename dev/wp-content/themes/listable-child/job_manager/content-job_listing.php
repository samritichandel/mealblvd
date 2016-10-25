<?php
/**
 * The template for displaying the WP Job Manager listing on archives
 *
 * @package Listable
 */

global $post;

$taxonomies  = array();
$terms       = get_the_terms( get_the_ID(), 'job_listing_category' );
$termString  = '';
$data_output = '';
if ( ! is_wp_error( $terms ) && ( is_array( $terms ) || is_object( $terms ) ) ) {
	$firstTerm = $terms[0];
	if ( ! $firstTerm == null ) {
		$term_id = $firstTerm->term_id;
		$data_output .= ' data-icon="' . listable_get_term_icon_url( $term_id ) . '"';
		$count = 1;
		foreach ( $terms as $term ) {
			$termString .= $term->name;
			if ( $count != count( $terms ) ) {
				$termString .= ', ';
			}
			$count ++;
		}
	}
}

$listing_classes = 'card  card--listing';
$listing_is_claimed = false;
$listing_is_featured = false;

if ( is_position_featured($post) ) $listing_is_featured = true;

if ( class_exists( 'WP_Job_Manager_Claim_Listing' ) ) {
	$classes = WP_Job_Manager_Claim_Listing()->listing->add_post_class( array(), '', $post->ID );

	if ( isset( $classes[0] ) && ! empty( $classes[0] ) ) {
		$listing_classes .= '  ' . $classes[0];

		if( $classes[0] == 'claimed' )
			$listing_is_claimed = true;
	}
}

if ( true === $listing_is_featured ) $listing_classes .= '  is--featured';

$listing_classes = apply_filters( 'listable_listing_archive_classes', $listing_classes, $post ); ?>
<a class="grid__item" href="<?php the_job_permalink(); ?>">
	<article class="<?php echo esc_attr( $listing_classes ); ?>" itemscope itemtype="http://schema.org/LocalBusiness"
	         data-latitude="<?php echo esc_attr( get_post_meta( $post->ID, 'geolocation_lat', true ) ); ?>"
	         data-longitude="<?php echo esc_attr( get_post_meta( $post->ID, 'geolocation_long', true ) ); ?>"
	         data-img="<?php echo esc_attr( listable_get_post_image_src( $post->ID, 'listable-card-image' ) ); ?>"
	         data-permalink="<?php esc_attr( the_job_permalink() ); ?>"
	         data-categories="<?php echo esc_attr( $termString ); ?>"
		<?php echo $data_output; ?> >
		<aside class="card__image" style="background-image: url(<?php echo listable_get_post_image_src( $post->ID, 'listable-card-image' ); ?>);">
			<?php if ( true === $listing_is_featured ): ?>
			<span class="card__featured-tag"><?php esc_html_e( 'Featured', 'listable' ); ?></span>
			<?php endif; ?>

			<?php do_action('listable_job_listing_card_image_top', $post ); ?>

			<?php do_action('listable_job_listing_card_image_bottom', $post ); ?>

		</aside><!-- .card__image -->
		<div class="card__content">
			<div class="author_details">
				<?php
				//get author data
				$author_id=$post->post_author;
				echo get_avatar($author_id,38);
				echo the_author_meta('display_name' , $author_id ); ?>
			</div>
			<h2 class="card__title" itemprop="name"><?php
				echo get_the_title();

				if( $listing_is_claimed ) :
					echo '<span class="listing-claimed-icon">';
					get_template_part('assets/svg/checked-icon-small');
					echo '<span>';
				endif;
			?></h2>
			<div class="card__tagline" itemprop="description"><?php the_company_tagline(); ?></div>
			<footer class="card__footer">
						<?php
						$rating = get_average_listing_rating( $post->ID, 0 );
						$geolocation_street = get_post_meta( $post->ID, 'geolocation_street', true );
						if ( ! empty( $rating ) ) 
						{
							echo '<div class="star">';
							for($i=1;$i<=5;$i++)
							{
								if($i<=$rating)
									echo '<i class="fa fa-star" aria-hidden="true"></i>';
								else
									echo '<i class="fa fa-star star-disabled" aria-hidden="true"></i>';
							}
							echo '</div>';
							echo  '<div class="count">('.$rating.')</div>';
						}
						else
						{
							echo '<div class="star">';
							for($i=1;$i<=5;$i++)
							{
								echo '<i class="fa fa-star star-disabled" aria-hidden="true"></i>';
							}
							echo  '<div class="count">(0)</div>';
							echo '</div>';
							
						}
						?>
						<?php 
						$price=get_field('_price',$post->ID);
						if(trim($price,' '))
						{
							echo '<div class="price">';
							echo '$';
							echo $price;
							echo '</div>';
						}
						?>
		
			</footer>
		</div><!-- .card__content -->
	</article><!-- .card.card--listing -->
</a><!-- .grid__item -->
