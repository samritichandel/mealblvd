<?php
add_action( 'load-post.php', 'meal_data' );
add_action( 'load-post-new.php', 'meal_data' );
function meal_data() 
{
  add_action( 'add_meta_boxes', 'meal_data_post_meta_boxes' );
  add_action( 'save_post', 'save_meal_data_post_meta_boxes', 10, 2 );
}
function meal_data_post_meta_boxes() 
{
	add_meta_box( 'meal_data','Meal Data','meal_meta_box', 'job_listing', 'normal',  'high');
}

function meal_meta_box($object, $box)
{
	?>
	<div class="wp_job_manager_meta_data meal data">
		<?php
		$id=$object->ID;
		$options=get_option('meal_settings_settings');
		
		//meal type
		$meal_type=$options['meal_settings_textarea_field_0'];
		$data = array();
			foreach (explode("\n", $meal_type) as $cLine) {
				list ($cKey, $cValue) = explode(':', $cLine, 2);
				$data[$cKey] = $cValue;
			}
			$mealtype_final_array=array('0' => 'Select') + $data;
			
			//cuisine
			$cuisine=$options['meal_settings_textarea_field_1'];
			$cuisine_data = array();
			foreach (explode("\n", $cuisine) as $cLine) {
				list ($cKey, $cValue) = explode(':', $cLine, 2);
				$cuisine_data[$cKey] = $cValue;
			}
			$cuisine_data_final_array=array('0' => 'Select') + $cuisine_data;
			
			//min and max guest value
			$min_guest_val=$options['meal_settings_text_field_2'];
			$min_arr=array();
			for($j=1;$j<=$min_guest_val;$j++)
			{
				$min_arr[$j-1]=$j;
			}
			array_unshift($min_arr, "Select");
			
			
			$max_guest_val=$options['meal_settings_text_field_3'];
			$max_arr=array();
			for($k=1;$k<=$max_guest_val;$k++)
			{
				$max_arr[$k-1]=$k;
			}
			array_unshift($max_arr, "Select");
			
			//currency
			$currency=$options['meal_settings_text_field_4'];
			$currency_data = array();
			foreach (explode("\n", $currency) as $cLine) {
				list ($cKey, $cValue) = explode(':', $cLine, 2);
				$currency_data[$cKey] = $cValue;
			}
			$currency_data_final_array=array('0' => 'Select') + $currency_data;
		
		
		
		$minimum_guest=get_post_meta($id,'_min_guest',true);
		$maximum_guest=get_post_meta($id,'_max_guest',true);
		$meal_type=get_post_meta($id,'_meal_type',true);
		$type_cuisine=get_post_meta($id,'_type_cuisine',true);
		$currency=get_post_meta($id,'_currency',true);
		$price=get_post_meta($id,'_price',true);
		$menu=get_post_meta($id,'_menu',true);
		?>
		<p class="form-field">
			<label for="_price">Price:</label>
			<input type="text" id="price" name="price" value="<?php echo $price ?> ">
		</p>
		<p class="form-field">
			<label for="_min_guest">Minimum Guests:</label>
			<select name="min_guest" id="min_guest">
			<?php
				selectoptions($min_arr,$minimum_guest);
			?>
			</select>
		</p>
		<p class="form-field">
			<label for="_max_guest">Maximum Guests:</label>
			<select name="max_guest" id="max_guest">
			<?php
				selectoptions($max_arr,$maximum_guest);
			?>
			</select>
		</p>
		<p class="form-field">
			<label for="_meal_type">Meal Type:</label>
			<select name="meal_type" id="meal_type">
			<?php 
				selectoptions($mealtype_final_array,$meal_type);
			?>
			</select>
		</p>
		<p class="form-field">
		
			<label for="_type_cuisine">Type Of Cuisine:</label>
			<select name="type_cuisine" id="type_cuisine">
			<?php selectoptions($cuisine_data_final_array,$type_cuisine); ?>
			</select>
		</p>
		<p class="form-field">
			<label for="_currency">Currency:</label>
			<select name="currency" id="currency">
			<?php 
				selectoptions($currency_data_final_array,$currency);
			?>
			</select>
			<!--<input type="text" name="currency" id="currency" value="<?php// echo $currency; ?>">-->
		</p>
		
		<p class="form-field">
			<label for="_price">Add Your Menu:</label>
			<textarea name="menu" id="menu">
			<?php echo $menu; ?> 
			</textarea>
		</p>
	</div>
	<?php
}
function selectoptions($array,$selectedval)
{
	
			foreach($array as $key => $value)
			{
				$selected=($key==$selectedval)?'selected':''; 
			?>
			<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $value; ?></option>
			<?php
			}
}

/* Save the meta box's post metadata. */
function save_meal_data_post_meta_boxes( $post_id, $post ) {
	/* Get the post type object. */
	$post_type = get_post_type_object( $post->post_type );

  /* Check if the current user has permission to edit the post. */
  if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
	return $post_id;

/* Get the posted data*/ 
$new_meta_value_price= ( isset( $_POST['price'] ) ? $_POST['price']  : '' );
$new_meta_value_min_guest = ( isset( $_POST['min_guest'] ) ? $_POST['min_guest']  : '' );
$new_meta_value_max_guest = ( isset( $_POST['max_guest'] ) ? $_POST['max_guest']  : '' );
$new_meta_value_meal_type = ( isset( $_POST['meal_type'] ) ? $_POST['meal_type']  : '' );
$new_meta_value_cuisine	=( isset( $_POST['type_cuisine'] )?$_POST['type_cuisine']  : '' );
$new_meta_value_currency = ( isset( $_POST['currency'] ) ? $_POST['currency']  : '' );
$new_meta_value_menu = ( isset( $_POST['menu'] ) ? $_POST['menu']  : '' );
  /* Get the meta keys. */
  $meta_key_price= '_price';
  $meta_key_min_guest = '_min_guest';
  $meta_key_max_guest = '_max_guest';
  $meta_key_meal_type = '_meal_type';
  $meta_key_type_cuisine = '_type_cuisine';
  $meta_key_min_currency = '_currency';
  $meta_key_menu = '_menu';
  

  /* Get the meta value of the custom field key. */
  $meta_value_price = get_post_meta( $post_id, $meta_key_price, true );
  $meta_value_min_guest = get_post_meta( $post_id, $meta_key_min_guest, true );
  $meta_value_max_guest = get_post_meta( $post_id, $meta_key_max_guest, true );
  $meta_value_meal_type = get_post_meta( $post_id, $meta_key_meal_type, true );
  $meta_value_type_cuisine = get_post_meta( $post_id, $meta_key_type_cuisine, true );
  $meta_value_min_currency = get_post_meta( $post_id, $meta_key_min_currency, true );
  $meta_value_menu = get_post_meta( $post_id, $meta_key_menu, true );

  /* If a new meta value was added and there was no previous value, add it. */
 //menu
  if ( $new_meta_value_menu && '' == $meta_value_menu )
   add_post_meta( $post_id, $meta_key_menu, $new_meta_value_menu, true );

  /* If the new meta value does not match the old value, update it. */
  elseif ( $new_meta_value_menu && $new_meta_value_menu != $meta_value_menu )
  update_post_meta( $post_id, $meta_key_menu, $new_meta_value_menu );
  
   /* If there is no new meta value but an old value exists, delete it. */
  elseif ( '' == $new_meta_value_menu && $meta_value_menu )
  {
    delete_post_meta( $post_id, $meta_key_menu, $meta_value_menu );
  } 
  
  
  
  //price
  if ( $new_meta_value_price && '' == $meta_value_price )
   add_post_meta( $post_id, $meta_key_price, $new_meta_value_price, true );

  /* If the new meta value does not match the old value, update it. */
  elseif ( $new_meta_value_price && $new_meta_value_price != $meta_value_price )
  update_post_meta( $post_id, $meta_key_price, $new_meta_value_price );
  
   /* If there is no new meta value but an old value exists, delete it. */
  elseif ( '' == $new_meta_value_price && $meta_value_price )
  {
    delete_post_meta( $post_id, $meta_key_price, $meta_value_price );
  }
  
  
  //minimum guests
  if ( $new_meta_value_min_guest && '' == $meta_value_min_guest )
   add_post_meta( $post_id, $meta_key_min_guest, $new_meta_value_min_guest, true );

  /* If the new meta value does not match the old value, update it. */
  elseif ( $new_meta_value_min_guest && $new_meta_value_min_guest != $meta_value_min_guest )
  update_post_meta( $post_id, $meta_key_min_guest, $new_meta_value_min_guest );
  
   /* If there is no new meta value but an old value exists, delete it. */
  elseif ( '' == $new_meta_value_min_guest && $meta_value_min_guest )
  {
    delete_post_meta( $post_id, $meta_key_min_guest, $meta_value_min_guest );
  }
  
  //maximum guests
  if ( $new_meta_value_max_guest && '' == $meta_value_max_guest )
  add_post_meta( $post_id, $meta_key_max_guest, $new_meta_value_max_guest, true );

elseif ( $new_meta_value_max_guest && $new_meta_value_max_guest != $meta_value_max_guest )
  update_post_meta( $post_id, $meta_key_max_guest, $new_meta_value_max_guest );
  
 elseif ( '' == $new_meta_value_max_guest && $meta_value_max_guest )
   delete_post_meta( $post_id, $meta_key_max_guest, $meta_value_max_guest );
  
  
  //meal type
if ( $new_meta_value_meal_type && '' == $meta_value_meal_type )
	add_post_meta( $post_id, $meta_key_meal_type, $new_meta_value_meal_type, true );

elseif ( $new_meta_value_meal_type && $new_meta_value_meal_type != $meta_value_meal_type )
  update_post_meta( $post_id, $meta_key_meal_type, $new_meta_value_meal_type );
  
elseif ( '' == $new_meta_value_meal_type && $meta_value_meal_type )
   delete_post_meta( $post_id, $meta_key_meal_type, $meta_value_meal_type );
   
  //type of cuisine
 if ( $new_meta_value_cuisine && '' == $meta_value_type_cuisine )
  add_post_meta( $post_id, $meta_key_type_cuisine, $new_meta_value_cuisine, true );

elseif ( $new_meta_value_cuisine && $new_meta_value_cuisine != $meta_value_type_cuisine )
  update_post_meta( $post_id, $meta_key_type_cuisine, $new_meta_value_cuisine );
  
elseif ( '' == $new_meta_value_cuisine && $meta_value_type_cuisine )
   delete_post_meta( $post_id, $meta_key_type_cuisine, $meta_value_type_cuisine );
  
  //currency
  if ( $new_meta_value_currency && '' == $meta_value_min_currency )
   add_post_meta( $post_id, $meta_key_min_currency, $new_meta_value_currency, true );

elseif($new_meta_value_currency && $new_meta_value_currency != $meta_value_min_currency )
  update_post_meta( $post_id, $meta_key_min_currency, $new_meta_value_currency );
  
elseif ( '' == $new_meta_value_currency && $meta_value_min_currency )
   delete_post_meta( $post_id, $meta_key_min_currency, $meta_value_min_currency );
}