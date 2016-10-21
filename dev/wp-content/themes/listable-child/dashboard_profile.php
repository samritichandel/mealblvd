<?php
/**
 * Template Name: Dashboard Profile
 */
session_start();
		$msg="";
		//fetch the global variable
		$upload_directory_path=$GLOBALS['upload_directory_path'];
		if(isset($_POST['submit_dash_profile']))
		 {
			 $fname=$_POST['fname'];
			 $lname=$_POST['lname'];
			 $phone=$_POST['phone'];
			 $email=$_POST['user_email_profile'];
			 $pass=$_POST['new_password'];
			 $uid=$_POST['user_id'];
			 
			$userdata = array(
			'ID' => $uid,
			'user_login'  =>   $email,
			'first_name'    =>  $fname,
			'last_name'    =>  $lname,
			'user_email'   =>  $email,
			'user_pass' => $pass,
			'display_name' => $fname. ' '.$lname  
			);
			$user_id =wp_update_user( $userdata ) ;
			if(isset($_SESSION['img_url_new']))
			{
				update_user_meta($uid,'simple_local_avatar',$_SESSION['img_url_new']);
			}
			update_user_meta($uid,'billing_phone',$phone);
			if ( is_wp_error( $user_id ) ) {
					$msg= 'There was an error,saving your data';
			} else {
					$msg="Your data has been updated";
					}	
		 }
		get_header(); 
?>
<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
	<?php 
		if(is_user_logged_in())
		{
			 $user= wp_get_current_user();
		?>
	<div class="entry-content reg_sec" id="entry-content-anchor">
	<div class="container">
		
		
		<!--user registeration form-->
		<div class="col-md-10 col-md-offset-1">
		<?php if(isset($msg) && $msg != "") {?>
		<div class="message"><?php  echo $msg; ?></div>
		<?php }
				 $fn=get_user_meta($user->ID,'first_name',true); 
				 $ln=get_user_meta($user->ID,'last_name',true); 
				 $ph=get_user_meta($user->ID,'billing_phone',true); 
				 $img=get_user_meta($user->ID,'simple_local_avatar',true); 
				 $ema=$user->data->user_email;
				?>
		
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
						$_SESSION['img_url_new']=$img_array;
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
						$img=get_user_meta($user->ID,'simple_local_avatar',true);
						//if through fb
						$img_fb_url=get_user_meta($user->ID,'wsl_current_user_image',true);
						
						$img_url="";
						if(isset($img[150]))
						{
							$img_url=$img[150];
						}
						elseif(isset($img['full']))
						{
							$img_url=$img['full'];
						}
						elseif(isset($img_fb_url))
						{
							$img_url=$img_fb_url;
						}
						?>
						<img src="<?php echo $img_url;?>" alt="profile-img" class="img-responsive">
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
            <form action="" method="post" name="user_profile" id="user_profile">
                
                    <div class="form-group profile-inline">
                      <label for="name">First Name</label>
                      <input type="name"  name="fname" id="fname" class="form-control" value="<?=$fn;?>">
					</div>   
                
                    <div class="form-group profile-inline two">
                      <label for="name">Last Name</label>
                      <input type="name" id="lname" name="lname" class="form-control" value="<?=$ln;?>">
                    </div>
                
                
                 <div class="form-group profile-full">
                      <label for="tel">Phone Number</label>
                      <input type="tel" id="phone" name="phone" class="form-control"
					  value="<?=$ph;?>">
                    </div>
                
					<div class="form-group profile-full">
                      <label for="email">Email Address</label>
                      <input type="email" id="user_email_profile" name="user_email_profile" class="form-control" value="<?=$ema;?>">
                    </div>  

					<div class="form-group profile-pwd">
                      <label for="email">Password Change<p> (Current Password: leave blank to leave unchanged)</p></label>
						<input name="password" type="password" id="password">
					</div>
					
					<div class="form-group profile-pwd">
                      <label for="email">New Password<p>  (New Password: leave blank to leave unchanged)</p></label>
					<input name="new_password" type="password" id="new_password">
					</div>
					
					<div class="form-group profile-pwd">
                      <label for="email">Confirm New Password</label>
					 <input name="confirm_password" type="password" id="confirm_password">
					</div>
					<input type="hidden" name="user_id" value="<?php echo $user->ID  ?>">
                 <input type="submit" value="Save Changes" class="profile-form-btn" name="submit_dash_profile">
                
                
            </form>
        </div>
		
        </div>
     <!--user registeration ends-->
		
		</div> <!--container ends-->
	</div><!-- .entry-content -->
	
	<?php
		}
		else
		{
		?>
		<header class="page-header">
			<h1 class="page-title"><?php the_title()?></h1>
		</header>
		<div class="container">
			<div class="message_login">You need to login first</div>
		</div>
	<?php
		}
	?>
	</main>
</div>
<?php
get_footer();