<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Listable
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php 
	if($post->ID==8)
	{
		if ( has_post_thumbnail() ):
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'listable-featured-image' ); ?>
			<header class="page-header has-featured-image">
				<div class="page-header-background" style="background-image: url('<?php echo listable_get_inline_background_image( $image[0] ); ?>')"></div>
				<h1 class="page-title"><?php the_title(); ?></h1>
				<span class="entry-subtitle"><?php echo get_the_excerpt(); ?></span>
			</header>
	<?php
	endif;
	}
	?>
	<?php if ( ! ( isset($post->post_content) && has_shortcode( $post->post_content, 'jobs' ) ) ): ?>
		<?php if ( has_post_thumbnail() ):
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'listable-featured-image' ); ?>
			<header class="page-header has-featured-image">
				<div class="page-header-background" style="background-image: url('<?php echo listable_get_inline_background_image( $image[0] ); ?>')"></div>
				<h1 class="page-title"><?php the_title(); ?></h1>
				<span class="entry-subtitle"><?php echo get_the_excerpt(); ?></span>
			</header>
		<?php else:
			if ( !is_page_template( 'page-templates/full_width_no_title.php' ) ) { ?>
			<header class="page-header">
				<h1 class="page-title"><?php the_title(); ?></h1>

				<?php if ( has_excerpt() ) : //only show custom excerpts not autoexcerpts ?>
					<span class="entry-subtitle"><?php echo get_the_excerpt(); ?></span>
				<?php endif; ?>

			</header>
		<?php }
		endif; ?>
	<?php endif; ?>
	<?php 
	//pages having dashboard option
	$user_dash_pages=array(6,11041);
	$show=(in_array($post->ID,$user_dash_pages))?true:false;
	?>
	<div class="entry-content" id="entry-content-anchor">
	<div class="container<?php if($show) echo ' listing_cust';?>">
		<?php
			if($show)
			{
				if ( is_user_logged_in() ) 
				{
					the_content();
				}
				else
				{
					echo '<div class="message_login">You need to login first</div>';
					
				}
			}
			else
			{
				the_content();
			}
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'listable' ),
				'after'  => '</div>',
			) );

			edit_post_link(
				sprintf(
					/* translators: %s: Name of current post */
					esc_html__( 'Edit %s', 'listable' ),
					the_title( '<span class="screen-reader-text">"', '"</span>', false )
				),
				'<span class="edit-link">',
				'</span>'
			);
		?>
		</div>
	</div><!-- .entry-content -->
</article><!-- #post-## -->

