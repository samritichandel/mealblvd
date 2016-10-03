<?php
/**
 * Template Name: User Dashboard
 */

get_header(); ?>
<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
	<section class="section3-dashboard">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
            <div class="section3-dash">
                <ul>
				<?php if(is_user_logged_in()){
						$user= wp_get_current_user();?>
					 <li>
                        <div class="section3-das-box-heading">
							<h2>Hello <?php echo $user->data->display_name; ?></h2>
                            <p>Update your profile picture</p>
                        </div>
                        
                        <div class="section3-das-box-center">
						<?php $img=get_user_meta($user->ID,'simple_local_avatar',true); 
							$img_url="";
							if(isset($img[150]))
							{
								$img_url=$img[150];
							}
							else
							{
								$img_url=$img[96];
							}
						?>
					<img class="img-responsive" alt="user" src="<?php echo $img_url; ?>">
                    <p><?php $email=$user->data->user_email; 
						if($email) echo $email;?>
					</p>
                    <p>
					<?php $phone=get_user_meta($user->ID,'billing_phone',true); 
					if($phone)
					{
						echo $phone;
					}
					?>
					
					</p>
                     </div>
                        
                        <div class="section3-das-box-bottom">
                            <a href="#">Save my profile</a>
                        </div>
                    </li>
				<?php } ?>
                   <li>
                      <a href="<?php echo site_url();?>/post-a-listing/"> 
						<div class="dash-box-2"></div>
                        <button class="dash-box-2-btn" type="button">Post a Meal</button>
                      </a>
                    </li> 
                    
                    <li>
						<a href="<?php echo site_url();?>/listings/"> 
						  <div class="dash-box-3"></div>
						<button class="dash-box-2-btn" type="button">Find a Event</button>
						 </a>
                    </li> 
                    
                    
                    
                    
                    
                </ul>
            </div>
            </div>
                </div>
        </div>
</section>
		<?php
		//get posts of a user
		global $current_user;                     
		$args = array(
		  'author'        =>  $user->ID, 
		  'orderby'       =>  'post_date',
		  'order'         =>  'ASC',
		  'posts_per_page' => -1 // no limit
		);
		$current_user_posts = get_posts( $args );
		?>
	</main><!-- #main -->
</div><!-- #primary -->
<?php
get_sidebar();
get_footer();

