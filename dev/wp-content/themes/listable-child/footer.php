<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Listable
 */

?>

	</div><!-- #content -->

	<footer class="footer_sec_main" id="colophon" class="site-footer" role="contentinfo">
		<?php if ( is_active_sidebar( 'footer-widget-area' ) ) : ?>
			<div id="footer-sidebar" class="footer-widget-area" role="complementary">
				<?php dynamic_sidebar( 'footer-widget-area' ); ?>
			</div><!-- #primary-sidebar -->
		<?php endif; ?>
		<div class="ftr_btm_sec">
		 <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="social">
                       <?php dynamic_sidebar('footer-social');?>
					</div>
				</div>
                <div class="col-md-4">
                    <div class="terms">
                       <?php
						$args = array(
							'theme_location'  => 'footer_menu',
							'container'       => '',
							'container_class' => '',
							'menu_class'      => '',
							'depth'           => 1,
							'fallback_cb'     => null,
						);
						wp_nav_menu( $args );
						?>
                    </div>
                </div>
                <div class="col-md-4">
                 <?php $footer_copyright = listable_get_option('footer_copyright');
					if ( $footer_copyright ) : ?>
					<div class="powered-by">
                        <p><?php echo $footer_copyright; ?></p>
					</div>
					<?php endif; ?>
			</div>
        </div>
		</div>
      </div><!--ftt_btm_sec end-->		
		
	</footer><!-- #colophon -->
</div><!-- #page -->

<div class="hide">
	<div class="arrow-icon-svg"><?php get_template_part( 'assets/svg/carousel-arrow-svg' ); ?></div>
	<div class="cluster-icon-svg"><?php get_template_part( 'assets/svg/map-pin-cluster-svg' ); ?></div>
	<div class="selected-icon-svg"><?php get_template_part( 'assets/svg/map-pin-selected-svg' ); ?></div>
	<div class="empty-icon-svg"><?php get_template_part( 'assets/svg/map-pin-empty-svg' ); ?></div>
	<div class="card-pin-svg"><?php get_template_part( 'assets/svg/pin-simple-svg' ); ?></div>
</div>
	
	<script>
        wow = new WOW({
            boxClass: 'wow', // default
            animateClass: 'animated', // default
            offset: 0, // default
            mobile: false, // default
            live: true // default
        })
        wow.init();
</script>

 <script>
        jQuery(window).load(function () {
            // The slider being synced must be initialized first
           jQuery('#carousel').flexslider({
                animation: "slide"
                , controlNav: false
                , animationLoop: false
                , slideshow: false
                , itemWidth: 147
                , itemMargin: 20
                , asNavFor: '#slider'
            });
           jQuery('#slider').flexslider({
                animation: "slide"
                , controlNav: false
                , animationLoop: false
                , slideshow: false
                , sync: "#carousel"
            });
			jQuery('#carouselpreview').flexslider({
                animation: "slide"
                , controlNav: false
                , animationLoop: false
                , slideshow: false
                , itemWidth: 147
                , itemMargin: 20
                , asNavFor: '#slider'
            });
           jQuery('#sliderpreview').flexslider({
                animation: "slide"
                , controlNav: false
                , animationLoop: false
                , slideshow: false
                , sync: "#carouselpreview"
            });
        });
    </script> 
<?php wp_footer(); ?>

</body>
</html>