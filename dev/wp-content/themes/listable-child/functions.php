<?php
/**
 * Listable Child functions and definitions
 *
 * Bellow you will find several ways to tackle the enqueue of static resources/files
 * It depends on the amount of customization you want to do
 * If you either wish to simply overwrite/add some CSS rules or JS code
 * Or if you want to replace certain files from the parent with your own (like style.css or main.js)
 *
 * @package ListableChild
 */

/**
 * Setup Listable Child Theme's textdomain.
 *
 * Declare textdomain for this child theme.
 * Translations can be filed in the /languages/ directory.
 */
function listable_child_theme_setup() {
	load_child_theme_textdomain( 'listable-child-theme', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'listable_child_theme_setup' );

/**
 *
 * 1. Add a Child Theme "style.css" file
 * ----------------------------------------------------------------------------
 *
 * If you want to add static resources files from the child theme, use the
 * example function written below.
 *
 */

function listable_child_enqueue_styles() {
	wp_enqueue_style('font-awesome', get_stylesheet_directory_uri() .'/css/font-awesome.min.css');
	wp_enqueue_style('animate-css', get_stylesheet_directory_uri() .'/css/animate.min.css');
	wp_enqueue_style('bootstrap-css', get_stylesheet_directory_uri() .'/css/bootstrap.min.css');
	wp_enqueue_style('flex-css', get_stylesheet_directory_uri() .'/css/flexslider.css');
	wp_enqueue_script( 'bootstrap-js',  get_stylesheet_directory_uri() . '/js/bootstrap.min.js', array(), '1.0.0', true );
	 wp_enqueue_script( 'wow-js',  get_stylesheet_directory_uri() . '/js/wow.min.js', array(), '1.0.0' );
	 wp_enqueue_script( 'validate-js',  get_stylesheet_directory_uri() . '/js/validate.min.js', array(), '1.0.0', true );
	 wp_enqueue_script('flex-js', get_stylesheet_directory_uri() .'/js/jquery.flexslider.js');
	  wp_enqueue_script( 'custom-js',  get_stylesheet_directory_uri() . '/js/custom.js', array(), '1.0.0', true );
	 
}
add_action( 'wp_enqueue_scripts', 'listable_child_enqueue_styles');

//adding the style.css in footer
function prefix_add_footer_styles() {
   wp_enqueue_style( 'listable-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array('listable-style') //make sure the the child's style.css comes after the parents so you can overwrite rules
	);
};
add_action( 'get_footer', 'prefix_add_footer_styles' );

//custom shortcodes
function shortcode_site_url()
{
    $siteurl = site_url();
    return $siteurl;
}
add_shortcode('siteurl','shortcode_site_url');

function shortcode_theme_url()
{
    $themeurl = get_stylesheet_directory_uri();
    return $themeurl;
}
add_shortcode('themeurl','shortcode_theme_url');
add_filter('widget_text','do_shortcode'); 

//custom widgte for social icon footer
register_sidebar( array(
		'name'          => __( 'Footer Social Icons'),
		'id'            => 'footer-social',
		'before_widget' => '',
		'after_widget'  => '',
		'before_title'  => '<p>',
		'after_title'   => '</p>',
	) );
	
//custom widgte for listing menus
register_sidebar( array(
		'name'          => __( 'Listing Menu Content'),
		'id'            => 'listing_bottom_content',
		'before_widget' => '',
		'after_widget'  => '',
		'before_title'  => '',
		'after_title'   => '',
	) );

//custom widgte reviews
register_sidebar( array(
		'name'          => __( 'Listing Reviews'),
		'id'            => 'reviews',
		'before_widget' => '',
		'after_widget'  => '',
		'before_title'  => '',
		'after_title'   => '',
	) );
	

//overrride the fronend widgets
require_once dirname( __FILE__ ) . '/includes/overridden_widgets.php';

// custom post type for FAQ's 
add_action( 'init', 'faqs' );
function faqs() {
	register_post_type( 'faqs',
    array(
      'labels' => array(
        'name' => __( "FAQ" ),
        'singular_name' => __( "faqs" ),
        'all_items'=> __("All faqs"),
        'edit_item' => __("Edit faqs"),
         'add_new' => __("Add New")
      ),
        'rewrite' => array( 'slug' => 'faqs','with_front' => true),
	  'capability_type' =>  'post',
          'public' => true,
          'hierarchical' => true,
	  'supports' => array(
	  'title',
	  'editor'
	  )
    )
  );
       // register_taxonomy( 'faq', 'faqs',array('label' => __( "Categories" ),'show_ui' => true,'show_admin_column' => true,'rewrite' => false ,'hierarchical' => true, ) );
}

//shortcode for FAQ's page
function faq() 
{
$data = "";
ob_start();
$args=array(
'post_type'=> 'faqs' //'Blog' is the name of the post whose content we want to get.
);
$the_query=new WP_Query( $args );
?>
<section class="section3-faq">
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="faq">
					 <div id="accordion" class="panel-group">
					 <?php
					  if($the_query->have_posts())
					  {
						 while ( $the_query->have_posts() ) 
						 {
							$the_query->the_post();
							$id=get_the_ID();
							?>
							 <div class="panel panel-default">
								<div class="panel-heading">
								  <h4 class="panel-title">
									<a data-toggle="collapse" class="coll collapsed" data-parent="#accordion" href="#collapse<?php echo $id;?>">
									<?php echo get_the_title();?>
									<span><i class="fa fa-plus-circle" aria-hidden="true"></i></span>
									</a>
									</h4>
								</div>
								<div id="collapse<?php echo $id;?>" class="panel-collapse collapse">
								  <div class="panel-body"> <?php the_content(); ?></div>
								</div>
							</div>
							<?php
						 } 
					  }
					?>
					 </div>
				</div>		  
			</div>
		</div>
	</div>
</section>
<?php
$data = ob_get_clean();
return $data;
}
add_shortcode( 'faq', 'faq' );

//custom post type for registeration questions
add_action( 'init', 'regques' );
function regques() {
	register_post_type( 'regques',
    array(
      'labels' => array(
        'name' => __( "Registeration Questions" ),
        'singular_name' => __( "regques" ),
        'all_items'=> __("All questions"),
        'edit_item' => __("Edit questions"),
         'add_new' => __("Add New")
      ),
        'rewrite' => array( 'slug' => 'regques','with_front' => true),
	  'capability_type' =>  'post',
          'public' => true,
          'hierarchical' => true,
	  'supports' => array(
	  'title'
	  )
    )
  );
}
// Change title text
function wpb_change_title_text( $title ){
     $screen = get_current_screen();
 
     if  ( 'regques' == $screen->post_type ) {
          $title = 'Enter your question here';
     }
 
     return $title;
}
 
add_filter( 'enter_title_here', 'wpb_change_title_text' );

//add shortcode for registeration questions
function regquestions() 
{
ob_start();
$data = "";
$count_posts = wp_count_posts( 'regques' )->publish;
$args=array(
'post_type'=> 'regques' //'Blog' is the name of the post whose content we want to get.
);
$the_query=new WP_Query( $args );
?>
<form class="form host_quest_sec" action="<?php echo site_url();?>/registration/" name="questions" method="post" >
<input type="hidden" name="count_of_ques" id="count" value="<?=$count_posts; ?>">
      <ul>
         <?php
if($the_query->have_posts())
{
 $i=0;
 while ( $the_query->have_posts() ) 
 {
	++$i;
	$the_query->the_post();
	$question=get_the_title();
	?>
	<li>
	<h2><?php echo get_the_title();?></h2>
	<input type="hidden" name="ques_title_<?php echo $i; ?>" value="<?php echo $question?>">
	<?php 
		// check if the repeater field has rows of data
		if( have_rows('add_options') ):
		// loop through the rows of data
		while ( have_rows('add_options') ) : the_row();
			// display a sub field value
			?>
			 <div class="radio">
			 <?php 
			 $option = get_sub_field('add_option');
			 ?>
			 <label class="one">
			 <input type="radio" class="<?php echo 'option'.$i; ?>" name="<?php echo 'option'.$i;?>" value="<?php echo $option;?>" required>
			 <span></span><?php echo $option?></label>
			</div>
			<?
		endwhile;
	endif;?>
	</li>
	<?php
 } 
}
?>
</ul>
<input class="host-btn" type="submit" name="submit" value="Continue">
</form>
<?php
$data = ob_get_clean();
echo $data;
}
add_shortcode( 'Registeration-Questions', 'regquestions' );

add_action( 'edit_user_profile', 'display_user_custom_hash' );
//add user meta fields to admin
function display_user_custom_hash( $user ) { ?>
    <h3>User Questions</h3>
	<?php $user_meta= get_user_meta( $user->ID, 'questions');
	$ques=$user_meta[0];
	foreach($ques as $key=>$val)
	{
	  echo '<h4>' .$key.':</h4>'.$val;
	}
	
}

//add listing meta fields to admin


//ajax function for email validation while user registration
add_action( 'wp_ajax_get_email_address', 'get_email_address' );    
add_action( 'wp_ajax_nopriv_get_email_address', 'get_email_address' ); 
function get_email_address()
{
	$html="";
	$email=$_POST['email'];
	$user = get_user_by( 'email', $email );
	if($user)
		$html="Email is already registered.";
	else
		$html=false;
	echo $html;
	exit();
}

// display default admin notice
function shapeSpace_add_settings_errors() {
	 settings_errors();
}
add_action('admin_notices', 'shapeSpace_add_settings_errors');

require_once dirname( __FILE__ ) . '/includes/meal_settings.php';

//ajax function for image upload
/*
add_action( 'wp_ajax_get_image_url', 'get_image_url' );    
add_action( 'wp_ajax_nopriv_get_image_url', 'get_image_url' ); 
*/

//add custom meta box to show minimum number of guest value
require_once dirname( __FILE__ ) . '/includes/metaboxes.php';

//ajax funtion for fetching the password
add_action( 'wp_ajax_get_password', 'get_password' );    
add_action( 'wp_ajax_nopriv_get_password', 'get_password' ); 
function get_password()
{
	$html="";
	$email=$_POST['email'];
	$pass=$_POST['pass'];
	$user = get_user_by( 'email', $email );
	$old_pass=$user->data->user_pass;
	if ( wp_check_password( $pass, $user->data->user_pass, $user->ID) )
		$html="matched";
	else
		$html="Not matched";
	echo $html;
	exit();
}

//add custom css
add_action('admin_head', 'my_custom_fonts');

function my_custom_fonts() {
  echo '<style>
  #meal_data .inside .wp_job_manager_meta_data #wp-menu-wrap {
     position : unset;
    } 
  </style>';
}

//change calendar option in bookigs to dropdown

add_filter('booking_form_fields','wswp_booking_form_fields');

function wswp_booking_form_fields($fields) {
	global $wswp_dates_built;
	 $wswp_dates_built=false;
    $i = 0;
    foreach($fields as $field) {
        $new_fields[$i] = $field;
        if ($field['type'] == "date-picker" && $wswp_dates_built === false)
        {
            $s = $i;
            $new_fields[$s]['class'] = array('picker-hidden');
            $i++;
            $new_fields[$i] = $field;
            $new_fields[$i]['type'] = "select";
            $new_fields[$i]['options'] = wswp_build_options($field['availability_rules'][0]);
            $new_fields[$i]['class'] = array('picker-chooser');
        }
        $i++;
    }
    return $new_fields;
}

function wswp_build_options($rules,$building = false) {
	global $wswp_dates_built;
	foreach($rules as $dateset) {
		
		if($dateset[0] =="time:range")
		{
			//fetch first key within array
			$year = reset(array_keys($dateset[1])); 
            $month = reset(array_keys($dateset[1][$year]));
            $day = reset(array_keys($dateset[1][$year][$month]));
			$time=reset($dateset[1][$year][$month][$day]);
			$dtime = strtotime($year."-".$month."-".$day);
			$converted_time=date("g:i A", strtotime($time)); //am pm format
            $dates[$dtime] =$converted_time;
		}
		else
		{
			if ($dateset[0] == "custom") 
			{
				$year = reset(array_keys($dateset[1]));
				$month = reset(array_keys($dateset[1][$year]));
				$day = reset(array_keys($dateset[1][$year][$month]));
				$dtime = strtotime($year."-".$month."-".$day);
				$dates[$dtime] = '';
			}
		}
		 
	 }
	 
	ksort($dates); //sort in ascending
	foreach($dates as $key => $date) {
		$day=date('D', strtotime( date("Y-m-d",$key))); //fetch the week day
        $dates[date("Y-m-d",$key)] = $day.'.'.date('M jS ',$key).' '.$date;  
        unset($dates[$key]);
    }
	$new_dates = array('select' => 'Select') + $dates;
    $wswp_dates_built = true;
	return $new_dates;
}

add_action('wp_footer','wswp_css_js');

function wswp_css_js() {
    //adding in footer as not enough to justify new stylesheet and js file
    ?><style type="text/css">
        .picker-hidden .picker,.picker-hidden legend {
            display:none;
        }
        </style>
        <script type='text/javascript'>
            jQuery(function($) {
//Move .picker chooser up in the DOM
                $(".picker-chooser").insertBefore('.wc-bookings-date-picker-date-fields');
//Attach handler to insert chosen date into picker fields
                $("select#wc_bookings_field_start_date").on('change', function() {
                var selectedDate = $(this).val()
                var selectedDateBreakdown = selectedDate.split("-");

                $( "input[name*='wc_bookings_field_start_date_year']" ).val( selectedDateBreakdown[0] );
                $( "input[name*='wc_bookings_field_start_date_month']" ).val( selectedDateBreakdown[1] );
                $( "input[name*='wc_bookings_field_start_date_day']" ).val( selectedDateBreakdown[2] );
            });
            });

            </script>
            <?php
}

//filter to unset the billing fileds in checkout
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
function custom_override_checkout_fields( $fields ) {
unset($fields['billing']['billing_address_1']);
unset($fields['billing']['billing_address_2']);
unset($fields['billing']['billing_city']);
unset($fields['billing']['billing_postcode']);
unset($fields['billing']['billing_country']);
unset($fields['billing']['billing_state']);
unset($fields['order']['order_comments']);
unset($fields['billing']['billing_company']);
return $fields;
}

//custom fields to save data
/**

* save listing id on click of book now*/
add_action( 'wp_ajax_set_listing_id', 'set_listing_id' );    
add_action( 'wp_ajax_nopriv_set_listing_id', 'set_listing_id' ); 
function set_listing_id()
{
	$id=$_POST['id'];
	session_start();
	$_SESSION['wdm_user_custom_data'] = $id;
	//echo 'ID'.$_SESSION['wdm_user_custom_data'];
	exit();
}


add_filter('woocommerce_add_cart_item_data','wdm_add_item_data',1,2);
 
if(!function_exists('wdm_add_item_data'))
{
    function wdm_add_item_data($cart_item_data,$product_id)
    {
        /*Here, We are adding item in WooCommerce session with, wdm_user_custom_data_value name*/
        global $woocommerce;
        session_start();    
        if (isset($_SESSION['wdm_user_custom_data'])) {
            $option = $_SESSION['wdm_user_custom_data'];       
            $new_value = array('wdm_user_custom_data_value' => $option);
        }
        if(empty($option))
            return $cart_item_data;
        else
        {    
            if(empty($cart_item_data))
                return $new_value;
            else
                return array_merge($cart_item_data,$new_value);
        }
        unset($_SESSION['wdm_user_custom_data']); 
        //Unset our custom session variable, as it is no longer needed.
    }
}

//Extract Custom Data from WooCommerce Session and Insert it into Cart Object
add_filter('woocommerce_get_cart_item_from_session', 'wdm_get_cart_items_from_session', 1, 3 );
if(!function_exists('wdm_get_cart_items_from_session'))
{
    function wdm_get_cart_items_from_session($item,$values,$key)
    {
        if (array_key_exists( 'wdm_user_custom_data_value', $values ) )
        {
        $item['wdm_user_custom_data_value'] = $values['wdm_user_custom_data_value'];
        }       
        return $item;
    }
}


//Add Custom Data as Metadata to the Order Items
add_action('woocommerce_add_order_item_meta','wdm_add_values_to_order_item_meta',1,2);
if(!function_exists('wdm_add_values_to_order_item_meta'))
{
  function wdm_add_values_to_order_item_meta($item_id, $values)
  {
        global $woocommerce,$wpdb;
        $user_custom_values = $values['wdm_user_custom_data_value'];
        if(!empty($user_custom_values))
        {
            wc_add_order_item_meta($item_id,'wdm_user_custom_data',$user_custom_values);  
        }
  }
}

add_action('woocommerce_before_cart_item_quantity_zero','wdm_remove_user_custom_data_options_from_cart',1,1);
if(!function_exists('wdm_remove_user_custom_data_options_from_cart'))
{
    function wdm_remove_user_custom_data_options_from_cart($cart_item_key)
    {
        global $woocommerce;
        // Get cart
        $cart = $woocommerce->cart->get_cart();
        // For each item in cart, if item is upsell of deleted product, delete it
        foreach( $cart as $key => $values)
        {
        if ( $values['wdm_user_custom_data_value'] == $cart_item_key )
            unset( $woocommerce->cart->cart_contents[ $key ] );
        }
    }
}
