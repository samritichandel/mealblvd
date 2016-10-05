<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Listable
 */

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

	<?php while ( have_posts() ) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/LocalBusiness">
		
			<header class="page-header has-featured-image">
				<div class="page-header-background" style="background-image: url('http://mealblvd.com/dev/wp-content/uploads/2015/10/how-it-works.jpg')"></div>
				<h1 class="page-title">Chefs and Foodies together one meal</h1>
				<span class="entry-subtitle">Find great places to eat and connect with great minds.</span>
			</header>
			<div class="single-meal-listing">
			<div class="container">
				<div class="add-menu-gallary">
					<div class="slider-main">
						<nav class="single-categories-breadcrumb">
						<a href="<?php echo listable_get_listings_page_url(); ?>"><?php esc_html_e( 'Listings', 'listable' ); ?></a> >>
						<?php
						$term_list = wp_get_post_terms(
							$post->ID,
							'job_listing_category',
							array(
								"fields" => "all",
								'orderby' => 'parent',
							)
						);

						if ( ! empty( $term_list ) && ! is_wp_error( $term_list ) ) {
							// @TODO make them order by parents
							foreach ( $term_list as $key => $term ) {
								echo '<a href="' . esc_url( get_term_link( $term ) ) . '">' . $term->name . '</a>';
								if ( count( $term_list ) - 1 !== $key ) {
									echo ' >>';
								}
							}
						} ?>
						</nav>
						<h2>Our Gallery</h2>
						<?php
						if ( ! post_password_required() ) {
							$photos = listable_get_listing_gallery_ids();
							?>
							<div class="entry-featured-carousel">
							<!--if no image is added-->
							<?php  if (empty( $photos ) ) :?>
							<div class="entry-cover-image" style="background-image: url(<?php echo site_ur();?>/wp-content/uploads/2015/11/14_listable_demo.jpg);"></div>
							<?php endif;?>
							
							<?php if ( ! empty( $photos ) ) :
							 if ( count($photos) == 1 ):
									$myphoto = $photos[0];
									$image = wp_get_attachment_image_src($myphoto, 'listable-featured-image' );
									$src = $image[0];
								?>
									<div class="entry-cover-image" style="background-image: url(<?php echo listable_get_inline_background_image( $src ); ?>);"></div>
								<?php else: ?>
										<div id="slider" class="flexslider">
											<ul class="slides">
											 <?php
											foreach ($photos as $key => $photo_id):
											$src = wp_get_attachment_image_src($photo_id, 'listable-carousel-image'); ?>
											<li><img class="img-responsive" src="<?php echo $src[0]; ?>" itemprop="image" /></li>
											<?php endforeach;?>
											</ul>
										</div>
										<div id="carousel" class="flexslider">
											<ul class="slides">
											<?php
											foreach ($photos as $key => $photo_id):
											$src = wp_get_attachment_image_src($photo_id, 'listable-carousel-image'); ?>
											<li><img class="img-responsive" src="<?php echo $src[0]; ?>" itemprop="image" /></li>
											<?php endforeach;?>
											
											</ul>
										</div>
								<?php endif; ?>
							</div>

				<?php endif; ?>
				</div>
				</div>
				
				<div>
					<?php
					$job_manager = $GLOBALS['job_manager'];

					remove_filter( 'the_content', array( $job_manager->post_types, 'job_content' ) );

					ob_start();

					do_action( 'job_content_start' );

					get_job_manager_template_part( 'content-single', 'job_listing' );

					do_action( 'job_content_end' );

					$content = ob_get_clean();

					add_filter( 'the_content', array( $job_manager->post_types, 'job_content' ) );

					echo apply_filters( 'job_manager_single_job_content', $content, $post );

					wp_link_pages( array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'listable' ),
						'after'  => '</div>',
					) ); ?>
				</div><!-- .entry-content -->
				</div><!--container end-->
				</div><!--job container end-->
			   <div class="menu-listing">
					<div class="container">
						<h2>Menu</h2>
						<?php dynamic_sidebar('listing_bottom_content');?>
					</div>
			   </div>
			   <div class="reviews">
					<div class="container">
						<div class="row">
							<div class="col-md-7">
						<?php dynamic_sidebar('reviews');?>
							</diV>
						</diV>
					</diV>
			   </div>
				<footer class="entry-footer">
					<?php listable_entry_footer(); ?>
				</footer><!-- .entry-footer -->

			<?php
				listable_output_single_listing_icon();

			} else {
				echo '<div class="entry-content">';
				echo get_the_password_form();
				echo '</div>';
			} ?>
			
		</article><!-- #post-## -->

		<?php
		if ( ! post_password_required() ) {
			echo '<div class="container meal-navigation">'; 
			the_post_navigation() ;
			echo '</div>'; 
			}
		endwhile; // End of the loop. ?>
	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();