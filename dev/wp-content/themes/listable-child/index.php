<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Listable
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main facetwp-template" role="main">

			<?php
			$page_for_posts = get_option( 'page_for_posts' );
			$blog_has_featured_image = has_post_thumbnail( $page_for_posts );

			if ( $blog_has_featured_image ) {
				$image = wp_get_attachment_image_src( get_post_thumbnail_id( $page_for_posts ), 'listable-featured-image' );
				$image = esc_url($image[0]);
			}
			?>
			<header class="page-header<?php if($blog_has_featured_image) echo ' has-featured-image'; ?>">
				<?php if($blog_has_featured_image): ?><div class="page-header-background" style="background-image: url('<?php echo listable_get_inline_background_image( $image ); ?>')"></div><?php endif; ?>
				<div class="header-content">
				<h1 class="page-title"><?php echo get_the_title( $page_for_posts ); ?></h1>
				<div class="entry-subtitle">
					<?php if ( $post->post_excerpt ) {
						the_excerpt();
					} ?>
				</div>
				
				</div>
			</header>
		
		

		<?php if ( have_posts() ) : ?>

			<?php /* Start the Loop */ ?>

		<div class="postcards blog_main">
            <div class="container">
			<div class="grid" id="posts-container">
				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<div class="grid__item  postcard">
						<?php

							/*
							 * Include the Post-Format-specific template for the content.
							 * If you want to override this in a child theme, then include a file
							 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
							 */
							get_template_part( 'template-parts/content', get_post_format() );
						?>
					</div>
				<?php endwhile; ?>
			</div>
            </div>        
			<?php the_posts_navigation(); ?>
		</div>

		<?php else : ?>
			<?php get_template_part( 'template-parts/content', 'none' ); ?>
		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
