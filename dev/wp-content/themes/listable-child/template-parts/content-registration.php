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

	<div class="entry-content reg_sec" id="entry-content-anchor">
	<div class="container">
		<?php
			$msg="";
			//fetch the global variable
			$upload_directory_path=$GLOBALS['upload_directory_path'];
			//fetch questions and answers
			if(isset($_POST['submit']))
			{
				$questions=array();
				$answers=array();
				$count=$_POST['count_of_ques'];
				for($i=0;$i<$count;$i++)
				{
					$j=$i+1;
					$questions[$i]=$_POST['ques_title_'.$j];
					$answers[$i]=$_POST['option'.$j];
				}
				
			}
			//combine array of question and answer and store it in session for storing in db
			if(!empty($questions) && !empty($answers))
			{
			 $new_array=array_combine($questions,$answers);
			 $_SESSION['questions']=$new_array;
			}
			 
			 if(isset($_POST['submit_regis']))
			 {
				 $fname=$_POST['fname'];
				 $lname=$_POST['lname'];
				 $phone=$_POST['phone'];
				 $email=$_POST['user_email'];
				 $pass=$_POST['password'];
				 $questions=$_SESSION['questions'];
				 
				 $userdata = array(
				'user_login'  =>  $email,
				'first_name'    =>  $fname,
				'last_name'    =>  $lname,
				'user_email'   =>  $email,
				'role'		=> 'host'
				);

				$user_id = wp_insert_user( $userdata ) ;
				add_user_meta( $user_id, 'questions', $questions );
				add_user_meta($user_id,'simple_local_avatar',$_SESSION['img_url']);
				add_user_meta($user_id,'pw_user_status','pending');
				add_user_meta($user_id,'billing_phone',$phone);
				if(isset($user_id))
				{
					$msg="Thanks for registration with us.Once your account is approved, your password will be emailed to you.";
				}
			 }
			 
		?>
		
		
		<!--user registeration form-->
		<div class="col-md-10 col-md-offset-1">
		<?php if(isset($msg) && $msg != "") {?>
		<div class="message"><?php  echo $msg; ?></div>
		<?php }
			else {?>
		
		<div class="profile">
				<h2>Profile picture</h2>
			<?php 
			$folder =$upload_directory_path;
			$results =scandir($upload_directory_path);
			?>
			<div class="profile-pic" id="profile_pic">
			<?php
					if(isset($_POST['submit_image'])){
					if (!function_exists('wp_generate_attachment_metadata')){
						require_once(ABSPATH . "wp-admin" . '/includes/image.php');
						require_once(ABSPATH . "wp-admin" . '/includes/file.php');
						require_once(ABSPATH . "wp-admin" . '/includes/media.php');
					}
					if($_FILES)
					{
						foreach ($_FILES as $file => $array)
						{
							if($_FILES[$file]['error'] !== UPLOAD_ERR_OK){return "upload error : " . $_FILES[$file]['error'];}//If upload error
							$attach_id = media_handle_upload($file,$new_post);
							//echo wp_get_attachment_url($attach_id);//upload file URL
						}
						$img_array=array();
						$img_array['full']= wp_get_attachment_url($attach_id);
						$img_array['150']=wp_get_attachment_thumb_url($attach_id);
						$_SESSION['img_url']=$img_array;
						?>
						<div class="profile_img_sec">
						<img src="<?php echo wp_get_attachment_thumb_url($attach_id);?>" alt="profile-img" class="img-responsive">
						</div>
						<div class="caption">
						<!--<a href="<?php //echo get_stylesheet_directory_uri()?>/remove.php?name=<?php //echo $_FILES[$file]['name'];?>" class="btn btn-danger btn-xs" role="button">Remove</a>-->
						</div>
						
					<?php }
					}
					else{
						?>
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/profile-img.jpg" alt="profile-img" class="img-responsive">
						<?php
						}
						?>
				</div>
				<div class="profile-pic-button">
					<form method="post" enctype="multipart/form-data" id="submit_image">
						<div class="form-group">
							<input type="file" name="imagefile" id="image_name" />
						</div>
						<input type="submit"  name="submit_image" class="profile-upload-btn" value="Upload Your Photo"></button>
					</form>
				</div>
				</div>
        
        <div class="profile-form">
            <form action="" method="post" name="registration" id="registration">
                
                    <div class="form-group profile-inline">
                      <label for="name">First Name</label>
                      <input type="name"  name="fname" id="fname" class="form-control">
					</div>   
                
                    <div class="form-group profile-inline two">
                      <label for="name">Last Name</label>
                      <input type="name" id="lname" name="lname" class="form-control">
                    </div>
                
                
                 <div class="form-group profile-full">
                      <label for="tel">Phone Number</label>
                      <input type="tel" id="phone" name="phone" class="form-control">
                    </div>
                
					<div class="form-group profile-full">
                      <label for="email">Email Address</label>
                      <input type="email" id="user_email" name="user_email" class="form-control">
                    </div>   
                
                <!--<div class="form-group profile-pwd">
                      <label for="email">Password</label>
                <input type="password" name="password" id="password">
                </div>  
                
                <div class="form-group profile-pwd">
                      <label for="email">Confirm Password</label>
                <input type="password"  name="confirm_password" id="confirm_password">
                </div> --> 
                
                <input type="submit" value="Save Changes" class="profile-form-btn" name="submit_regis">
                
                
            </form>
        </div>
		<?php }?>
        </div>
     <!--user registeration ends-->
		
		</div> <!--container ends-->
	</div><!-- .entry-content -->
</article><!-- #post-## -->

