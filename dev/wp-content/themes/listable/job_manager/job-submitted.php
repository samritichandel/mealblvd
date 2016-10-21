<?php
global $wp_post_types;

switch ( $job->post_status ) :
	case 'publish' :
		echo '<p>';
		printf( __( '%s listed successfully. To view your listing <a href="%s">click here</a>.', 'wp-job-manager' ), $wp_post_types['job_listing']->labels->singular_name, get_permalink( $job->ID ) );
		echo '</p>';
	break;
	case 'pending' :
			$post_name=str_replace(" ", "_", $job->post_title);
			//get post data to save product
			$price=get_post_meta($job->ID,'_price',true);
			$get_max_guest=get_post_meta($job->ID,'_max_guest',true);
			$get_min_guest=get_post_meta($job->ID,'_min_guest',true);
			
			//get job hours and store in an array to be saved in db
			$job_hours=get_post_meta($job->ID,'_job_hours',true);
			$job_new=preg_split('/\r\n|[\r\n]/', $job_hours);
			$new_array=array();
			foreach($job_new as $single_job)
			{
				$each_array=explode(' ',$single_job);
				$inner_array=array();
				$inner_array['type']='time:range';
				$inner_array['bookable']='yes';
				$inner_array['priority']=10;
				$inner_array['from']=''.$each_array[1].'';
				$inner_array['to']=''.$each_array[2].'';
				$inner_array['from_date']=''.$each_array[0].'';
				$inner_array['to_date']=''.$each_array[0].'';
				$new_array[]=$inner_array;
			}
					
			$post = array(
			'post_author' => $job->post_author,
			'post_content' => '',
			'post_status' => "publish",
			'post_title' => $job->post_title,
			'post_parent' => '',
			'post_type' => "product",
			'post_name' =>	strtolower($post_name)
		);

		//Create product
		$post_id = wp_insert_post( $post, $wp_error );
		$blank_array=array();
		if($post_id){
			//add product meta
			add_post_meta($post_id, '_stock_status','instock');
			add_post_meta($post_id, '_visibility','visible');
			add_post_meta($post_id, '_edit_last',1);
			add_post_meta($post_id, '_stock_status','instock');
			add_post_meta($post_id, 'total_sales',0);
			
			add_post_meta($post_id, '_downloadable','no');
			add_post_meta($post_id, '_virtual','yes');
			add_post_meta($post_id, '_featured','no');
			add_post_meta($post_id, '_product_attributes',$blank_array);
			add_post_meta($post_id, '_manage_stock','no');
			add_post_meta($post_id, '_backorders','no');
			
			add_post_meta($post_id, '_upsell_ids',$blank_array);
			add_post_meta($post_id, '_crosssell_ids',$blank_array);
			
			
			add_post_meta($post_id, '_wc_booking_base_cost',$price);
			add_post_meta($post_id, '_wc_display_cost',$price);
			
			add_post_meta($post_id, '_wc_booking_min_duration',1);
			add_post_meta($post_id, '_wc_booking_max_duration',1);
			add_post_meta($post_id, '_wc_booking_enable_range_picker','no');
			add_post_meta($post_id, '_wc_booking_calendar_display_mode','always_visible');
			add_post_meta($post_id, '_wc_booking_qty',$get_max_guest);
			add_post_meta($post_id, '_wc_booking_has_persons','yes');
			add_post_meta($post_id, '_wc_booking_person_qty_multiplier','yes');
			add_post_meta($post_id, '_wc_booking_min_persons_group',$get_min_guest);
			add_post_meta($post_id, '_wc_booking_max_persons_group',$get_max_guest);
			add_post_meta($post_id, '_wc_booking_has_person_types','no');
			add_post_meta($post_id, '_wc_booking_has_resources','no');
			add_post_meta($post_id, '_wc_booking_resources_assignment','customer');
			
			add_post_meta($post_id, '_wc_booking_duration_type','fixed');
			add_post_meta($post_id, '_wc_booking_duration',1);
			add_post_meta($post_id, '_wc_booking_duration_unit','day');
			add_post_meta($post_id, '_wc_booking_cancel_limit',1);
			add_post_meta($post_id, '_wc_booking_cancel_limit_unit','month');
			add_post_meta($post_id, '_wc_booking_max_date',12);
			add_post_meta($post_id, '_wc_booking_max_date_unit','month');
			
			add_post_meta($post_id, '_wc_booking_requires_confirmation','yes');
add_post_meta($post_id, '_wc_booking_default_date_availability','non-available');
			add_post_meta($post_id, '_has_additional_costs','no');
			add_post_meta($post_id, '_wc_review_count',0);
			
			add_post_meta($post_id, '_jetpack_dont_email_post_to_subs',1);
			add_post_meta($post_id, '_wc_booking_person_cost_multiplier','yes');
			add_post_meta($post_id, '_wc_booking_min_date_unit','day');
			
			add_post_meta($post_id, '_wc_booking_availability',$new_array);
			add_post_meta($post_id, '_wc_booking_pricing',$blank_array);
			
			
			//set the product type
			wp_set_object_terms($post_id, 'booking', 'product_type' );
			
			//save product to post
			$product_id=array($post_id);
			add_post_meta($job->ID,'_products',$product_id);
		}
		echo '<p class="post_listing_success">';
		printf( __( '%s submitted successfully. Your listing will be visible once approved.', 'wp-job-manager' ), $wp_post_types['job_listing']->labels->singular_name, get_permalink( $job->ID ) );
		echo '</p>';
	break;
	default :
		do_action( 'job_manager_job_submitted_content_' . str_replace( '-', '_', sanitize_title( $job->post_status ) ), $job );
	break;
endswitch;

do_action( 'job_manager_job_submitted_content_after', sanitize_title( $job->post_status ), $job );

