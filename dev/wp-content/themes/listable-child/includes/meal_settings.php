<?php
//settigs section for add meal for admin
add_action( 'admin_menu', 'meal_settings_add_admin_menu' );
add_action( 'admin_init', 'meal_settings_settings_init' );
function meal_settings_add_admin_menu(  ) { 
	add_submenu_page( 'edit.php?post_type=job_listing', '', 'Meal Settings', 'edit_posts', basename(__FILE__), 'meal_settings_options_page' );
}


function meal_settings_settings_init(  ) { 
	register_setting( 'pluginPage', 'meal_settings_settings' );
	add_settings_section(
		'meal_settings_pluginPage_section', 
		__( 'Meal Settings', 'Meal Settings' ), 
		'meal_settings_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'meal_settings_textarea_field_0', 
		__( 'Add a Meal Type', 'Meal Settings' ), 
		'meal_settings_textarea_field_0_render', 
		'pluginPage', 
		'meal_settings_pluginPage_section' 
	);

	add_settings_field( 
		'meal_settings_textarea_field_1', 
		__( 'Type of Cuisine', 'Meal Settings' ), 
		'meal_settings_textarea_field_1_render', 
		'pluginPage', 
		'meal_settings_pluginPage_section' 
	);

	add_settings_field( 
		'meal_settings_text_field_2', 
		__( 'Minimum guests', 'Meal Settings' ), 
		'meal_settings_text_field_2_render', 
		'pluginPage', 
		'meal_settings_pluginPage_section' 
	);

	add_settings_field( 
		'meal_settings_text_field_3', 
		__( 'Maximum guests', 'Meal Settings' ), 
		'meal_settings_text_field_3_render', 
		'pluginPage', 
		'meal_settings_pluginPage_section' 
	);

	/*
	add_settings_field( 
		'meal_settings_text_field_4', 
		__( 'Currency', 'Meal Settings' ), 
		'meal_settings_textarea_field_4_render', 
		'pluginPage', 
		'meal_settings_pluginPage_section' 
	);*/
}

function meal_settings_textarea_field_0_render(  ) { 
	$options = get_option( 'meal_settings_settings' );
	?>
<textarea cols='40' rows='5' name='meal_settings_settings[meal_settings_textarea_field_0]'> 
<?php echo $options['meal_settings_textarea_field_0']; ?>
</textarea>
	<?php
}
function meal_settings_textarea_field_1_render(  ) { 
	$options = get_option( 'meal_settings_settings' );
	?>
<textarea cols='40' rows='5' name='meal_settings_settings[meal_settings_textarea_field_1]'> 
<?php echo $options['meal_settings_textarea_field_1']; ?>
</textarea>
	<?php
}

function meal_settings_text_field_2_render(  ) { 
	$options = get_option( 'meal_settings_settings' );
	?>
	<input type='text' name='meal_settings_settings[meal_settings_text_field_2]' value='<?php echo $options['meal_settings_text_field_2']; ?>'>
	<?php
}

function meal_settings_text_field_3_render(  ) { 
	$options = get_option( 'meal_settings_settings' );
	?>
	<input type='text' name='meal_settings_settings[meal_settings_text_field_3]' value='<?php echo $options['meal_settings_text_field_3']; ?>'>
	<?php
}

/*function meal_settings_textarea_field_4_render(  ) { 
	$options = get_option( 'meal_settings_settings' );
	?>
	<textarea cols='40' rows='5' name='meal_settings_settings[meal_settings_text_field_4]'>
	<?php echo $options['meal_settings_text_field_4']; ?>
	</textarea
	<?php
}
*/
function meal_settings_settings_section_callback(  ) { 
	echo __( 'Edit your settings here', 'Meal Settings' );
}

function meal_settings_options_page(  ) { 
?>
	<form action='options.php' method='post'>
<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>
</form>
<?php
}
//settings end
