<?php
/**
 * Template part for displaying the header fields like search or facets if FacetWP is active.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Listable
 */

//do not show the navigation fields on the front page
if ( ! is_page_template( 'page-templates/front_page.php') ) :
	if ( listable_using_facetwp() ) :
		global $post;

		$facets = listable_get_facets_by_area( 'navigation_bar' );

		if ( ! empty( $facets ) ) : ?>
			<div class="header-facet-wrapper">
				<?php listable_display_facets($facets); ?>

				<?php if ( is_singular( 'job_listing' ) ) : ?>

					<button class="search-submit home-submit-btn" name="submit" id="searchsubmit" onclick="FWP.refresh();facetwp_redirect_to_listings();">
						<?php get_template_part( 'assets/svg/search-icon-svg' ); ?>
					</button>

					<div style="display: none;">
						<?php echo facetwp_display('template', 'listings' ); ?>
					</div>

					<script>
						(function($) {
							$(document).on('keyup','.header-facet-wrapper input[type="text"]', function(e) {
								if (e.which === 13) {
									facetwp_redirect_to_listings();
								}
							});
						})(jQuery);

						function facetwp_redirect_to_listings() {
							//wait a little bit
							setTimeout(
								function() {
									//if the user presses ENTER/RETURN in a text field then redirect
									FWP.parse_facets();
									FWP.set_hash();

									var query_string = FWP.build_query_string();
									if ('' != query_string) {
										query_string = '?' + query_string;
									}
									window.location.href = '<?php echo listable_get_listings_page_url(); ?>' + query_string;
									return false;
								}, 700);

						}
					</script>

				<?php else : ?>

					<button class="search-submit" name="submit" id="searchsubmit" onclick="FWP.refresh();<?php echo ( ! has_shortcode( 'jobs', $post->post_content ) ) ? 'facetwp_redirect_to_listings();' : '' ?>">
						<?php get_template_part( 'assets/svg/search-icon-svg' ); ?>
					</button>

				<?php endif; ?>

			</div>
		<?php endif;
	else : ?>
	<?php endif;
endif;
