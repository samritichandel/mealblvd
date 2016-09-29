<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Listable
 */
session_start();
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php wp_head(); ?>
<script>var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";</script>
</head>
<body <?php body_class(); ?> data-mapbox-token="<?php echo listable_get_option('mapbox_token', ''); ?>" data-mapbox-style="<?php echo listable_get_option('mapbox_style', ''); ?>">
<!--<div class="preloader">
      <img src="<?php echo site_url();?>/wp-content/uploads/2016/09/default.svg">
</div>-->
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'listable' ); ?></a>
	<?php
	$GLOBALS['upload_directory_path'] =$_SERVER['DOCUMENT_ROOT'].'/dev/wp-content/themes/listable-child/uploads/';
	global $array_pages;
	
	$array_pages=array( 11193, 11117,68,6);
	$class=(is_page($array_pages))? 'no-bg':''; 
	$content_class=(is_page($array_pages))? ' inner_page_cntnt':''; 
	$transparent= (is_page($array_pages))? ' ':' header--transparent'; 
	
	?>
	<header id="masthead" class="site-header navbar-fixed-top <?php echo $class; ?> <?php if( listable_get_option( 'header_transparent', true ) == true) echo $transparent; ?>" role="banner">
		<div class="container">
		
		
		<?php 
		if(is_page($array_pages))
		{
			if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
			// For transferring existing site logo from Jetpack -> Core
			if ( ! get_theme_mod( 'custom_logo' ) && $jp_logo = get_option( 'site_logo' ) ) {
				set_theme_mod( 'custom_logo', $jp_logo['id'] );
				delete_option( 'site_logo' );
			}

			echo '<div class="site-branding  site-branding--image">';
			the_custom_logo();
			echo '</div>';
			}
		}
		else
		{	
			listable_display_logo(); 
		}
		?>

		<?php get_template_part( 'template-parts/header-fields' ); ?>


		<?php
		// Output the navigation and mobile nav button only if there is a nav
		if ( has_nav_menu( 'primary' ) || has_nav_menu( 'secondary') ): ?>
		<button class="menu-trigger  menu--open  js-menu-trigger">
		<?php get_template_part( 'assets/svg/menu-bars-svg' ); ?>
		</button>
		<nav id="site-navigation" class="menu-wrapper" role="navigation">
			<button class="menu-trigger  menu--close  js-menu-trigger">

				<?php get_template_part( 'assets/svg/close-icon-svg' ); ?>

			</button>

			<?php
			wp_nav_menu( array(
				'container' => false,
				'theme_location' => 'primary',
				'menu_class' => 'primary-menu',
				'fallback_cb' => false,
				'walker' => new Listable_Walker_Nav_Menu(),
			) );
			wp_nav_menu( array(
				'container_class' => 'secondary-menu-wrapper',
				'theme_location' => 'secondary',
				'menu_class' => 'primary-menu secondary-menu',
				'fallback_cb' => false,
				'walker' => new Listable_Walker_Nav_Menu(),
			) ); ?>

		</nav>
		<?php endif; ?>
		</div>
	</header><!-- #masthead -->

	<div id="content" class="site-content<?php echo $content_class;?>">
