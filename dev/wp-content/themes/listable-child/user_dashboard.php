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
						$user= wp_get_current_user();
						} ?>
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
				
                   <li>
                      <a href="<?php echo site_url();?>/post-a-listing/"> 
						<?php 
						$bgimg=get_field('background_image_for_post_a_meal') ;
						if($bgimg)
						$style='style="background-image:url('.$bgimg.')"';
						?>
						<div class="dash-box-2" <?php if($style) echo $style; ?>></div>
                        <button class="dash-box-2-btn" type="button">Post a Meal</button>
                      </a>
                    </li> 
                    
                    <li>
						<a href="<?php echo site_url();?>/listings/"> 
						<?php 
						$bgimg_event=get_field('background_image_for_find_an_event') ;
						if($bgimg)
						$style_event='style="background-image:url('.$bgimg_event.')"';
						?>
						<div class="dash-box-3" <?php if($style_event) echo $style_event; ?>></div>
						<button class="dash-box-2-btn" type="button">Find an Event</button>
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
		  'user_id' => 1,
		  'orderby'       =>  'post_date',
		  'order'         =>  'ASC',
		  'post_type' => 'job_listing',
		  'posts_per_page' => -1 // no limit
		);
		
		$current_user_posts = get_posts( $args );
		
		//get id of posts
		$posts_id_array=array();
		foreach($current_user_posts as $post)
		{
			$posts_id_array[]=$post->ID;
		}
		
		//if current user has posts only then comments will show
		if (!$posts_id_array)
		{
			$all_comments=array();
			
		}
		else
		{
			$arg=array(
			'orderby' => 'comment_date',
			'order' => 'DESC',
			'post_type' => 'job_listing',
			'status' => 'approve',
			'post__in'=> $posts_id_array
			);
			$comments_query = new WP_Comment_Query;
			$all_comments = $comments_query->query( $arg );
		}
		
		?>
	<section class="section5-dash">
      <div class="container">
          <div class="row">
             <div class="col-md-11 col-md-offset-1">
          <div class="dash-review">
              <h2>Lorem Reviews</h2>
           
              <div class="row">
                  <div class="col-md-7">
                      <div class="review-color">
                          <p>
							<?php
							if(!empty ($all_comments))
							{
							$argms = array(
							'status' => 'approve',
							'post__in' => $posts_id_array,
							'post_type' => 'job_listing',
							'count' =>true
							);
							$comments = get_comments($argms);
							echo $comments .'review';
							}
							else
								echo "No review";
							?>
						  </p>
                      </div>
                  </div>
              </div>
          </div>
          
          <div class="dash-review-main">
              <ul>
			  <?php 
			  if(!empty ($all_comments))
			  {
				foreach($all_comments as $comm)
			  {
			  ?>
                  <li>
              <div class="row">
                  <div class="col-md-1">
                     <div class="review-img"><a href="#">
                     <?php $user_id=$comm->user_id;
					 if($user_id != 0)
					 {
						$image=get_user_meta($user_id,'simple_local_avatar');
						echo '<pre>';
						print_r($image);
						echo '</pre>';
						?>
						<img class="img-responsive" alt="user-2" src="<?php $img['full']; ?>">
						<?php
					 }
					 else
					 {
					?>
					 <img class="img-responsive" alt="user-2" src="<?php echo get_stylesheet_directory_uri();?>/images/dash-main-img-1.png">
					 <?php } ?>
                      <p><?php echo $comm->comment_author ?></p></a>
                     </div>
                  </div>
                  
                  <div class="col-md-6">
                     <div class="review-text">
							 <?php $title = get_comment_meta( $comm->comment_ID, 'pixrating_title', true ); ?> 
                         <h2><?php if($title)echo $title; ?></h2>
                         
					<?php $rating= get_comment_meta($comm->comment_ID,'pixrating',true);
						if($rating >= 1)
						  {
							echo '<div class="star">';
							for($i=1;$i<=5;$i++)
							{
								if($i<=$rating)
								{
									echo '<i class="fa fa-star" aria-hidden="true"></i>';
								}
								else
								{
									echo '<i class="fa fa-star star-disabled" aria-hidden="true"></i>';
								}
							}
							echo '</div>';
							  
						  }
					?>
                         
                         <span>
						  On 
						 <?php $date=comment_date('F j,Y',$comm->comment_ID);?>
						 <?php echo $date;?></span>
                         <p><?php echo $comm->comment_content; ?> </p>
                         
                      </div>
                  </div>
				  </li>
			  <?php } //end foreach 
			  }  //endif?>
              </ul>
              
              <button type="button" class="review-btn">22 More...</button>
              
              </div>
          </div>
              </div>
          </div>
      </div>
	  </div>
</section>  

		
	</main><!-- #main -->
</div><!-- #primary -->
<?php
get_sidebar();
get_footer();

