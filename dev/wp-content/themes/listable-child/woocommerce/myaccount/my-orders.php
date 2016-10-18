<?php
/**
 * My Orders
 *
 * Shows recent orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-orders.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$my_orders_columns = apply_filters( 'woocommerce_my_account_my_orders_columns', array(
	'order-number'  => __( 'Order', 'woocommerce' ),
	'order-date'    => __( 'Date', 'woocommerce' ),
	'order-status'  => __( 'Status', 'woocommerce' ),
	'order-total'   => __( 'Total', 'woocommerce' ),
	'order-actions' => '&nbsp;',
) );

$customer_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
	'numberposts' => $order_count,
	'meta_key'    => '_customer_user',
	'meta_value'  => get_current_user_id(),
	'post_type'   => wc_get_order_types( 'view-orders' ),
	'post_status' => array_keys( wc_get_order_statuses() )
) ) );

$liste=array();
foreach($customer_orders as $customer_order )
{
$order = new WC_Order( $customer_order->ID );
$items = $order->get_items();
//get the listing id from the placed order and store it in an array
foreach($items as $item)
{
	$liste[]=$item['wdm_user_custom_data'];
}
}

//show listing only once, inspite of the number of orders being placed from the listing
$listings = array_unique($liste);

?>
<ul id="myBtn">
<?php

//get the listing
 foreach($listings as $listing_id){ 
$post=get_post($listing_id);
$user_id=$post->post_author;
$user= get_userdata($user_id);
$listing_img=get_post_meta($post->ID,'_main_image',true);
$user_image=get_user_meta($user_id, 'simple_local_avatar');
?>
<li class="listing-li-7">
<a href="javascript:void(0)">
                      <div class="listing-box-img">
                          <img class="img-responsive" alt="order-1" src="<?php echo $listing_img[0];?>">
                      </div>
                      
                      <div class="listing-box-center">
						<?php   
						if($user_image[0][32])
						{
							$src=$user_image[0][32];
						}
						elseif($user_image[0][150])
						{
							$src=$user_image[0][150];
						}
						else
						{
							$src=$user_image[0]['full'];
						}
						?>
                          <img class="img-responsive" alt="order-2" src="<?php echo $src; ?>">
                          <p><?php echo $user->data->display_name; ?></p>
                    <div class="lisiting-box-center-bottom">
                        <h3><?php $title=get_post_meta($post->ID,'_job_title',true);
							if($title) echo $title;?></h3>
                        <h4><?php $tagline= get_post_meta($post->ID,'_company_tagline',true);
						if($tagline)
							echo $tagline;
						?></h4>
                        
                      </div>
                          
                      </div>
                      
                      <div class="lisiting-box-bottom">
					  <?php $rating = get_average_listing_rating( $post->ID, 0 );
						 if ( ! empty( $rating ) ) {
										echo '<div class="star">';
										for($i=1;$i<=5;$i++)
										{
											if($i<=$rating)
												echo '<i class="fa fa-star" aria-hidden="true"></i>';
											else
												echo '<i class="fa fa-star star-disabled" aria-hidden="true"></i>';
										}
										echo  '<p>('.$rating.')</p>';
										echo '</div>';
										}
										else
										{
											echo '<div class="star">';
											for($i=1;$i<=5;$i++)
											{
												echo '<i class="fa fa-star star-disabled" aria-hidden="true"></i>';
											}
											echo  '<p>(0)</p>';
											echo '</div>';
											
										}
										?>
                              
                          <div class="lisitng-price">
						  <?php 
								$price=get_field('_price',$post->ID);
								$currency=get_field('_currency',$post->ID);
								if($price)
								{
									echo '<h2>';
									if($currency)
										echo $currency;
									echo $price;
									echo '</h2>';
								}
								?>
                             
                          </div>
                                            
                      </div>
                      
                        </a>
                    
</li>
<?php } ?>
</ul>
<?php

if ( $customer_orders ) : ?>
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">×</span>
	<table class="shop_table shop_table_responsive my_account_orders">

		<thead>
		<tr>
			<?php foreach ( $my_orders_columns as $column_id => $column_name ) : ?>
				<th class="<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
			<?php endforeach; ?>
		</tr>
		</thead>

		<tbody>
		<?php foreach ( $customer_orders as $customer_order ) :
			$listings=array();
			$order      = wc_get_order( $customer_order );
			$item_count = $order->get_item_count();
			?>
			<tr class="order">
				<?php foreach ( $my_orders_columns as $column_id => $column_name ) : ?>
					<td class="<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
						<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
							<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

						<?php elseif ( 'order-number' === $column_id ) : ?>
							<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
								<?php echo _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number(); ?>
							</a>

						<?php elseif ( 'order-date' === $column_id ) : ?>
							<time datetime="<?php echo date( 'Y-m-d', strtotime( $order->order_date ) ); ?>" title="<?php echo esc_attr( strtotime( $order->order_date ) ); ?>"><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></time>

						<?php elseif ( 'order-status' === $column_id ) : ?>
							<?php echo wc_get_order_status_name( $order->get_status() ); ?>

						<?php elseif ( 'order-total' === $column_id ) : ?>
							<?php echo sprintf( _n( '%s for %s item', '%s for %s items', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count ); ?>

						<?php elseif ( 'order-actions' === $column_id ) : ?>
							<?php
							$actions = array(
								'pay'    => array(
									'url'  => $order->get_checkout_payment_url(),
									'name' => __( 'Pay', 'woocommerce' )
								),
								'view'   => array(
									'url'  => $order->get_view_order_url(),
									'name' => __( 'View', 'woocommerce' )
								),
								'cancel' => array(
									'url'  => $order->get_cancel_order_url( wc_get_page_permalink( 'myaccount' ) ),
									'name' => __( 'Cancel', 'woocommerce' )
								)
							);

							if ( ! $order->needs_payment() ) {
								unset( $actions['pay'] );
							}

							if ( ! in_array( $order->get_status(), apply_filters( 'woocommerce_valid_order_statuses_for_cancel', array( 'pending', 'failed' ), $order ) ) ) {
								unset( $actions['cancel'] );
							}

							if ( $actions = apply_filters( 'woocommerce_my_account_my_orders_actions', $actions, $order ) ) {
								foreach ( $actions as $key => $action ) {
									echo '<a href="' . esc_url( $action['url'] ) . '" class="button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
								}
							}
							?>
						<?php endif; ?>
					</td>
				<?php endforeach; ?>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	</div>

</div>
<?php endif; ?>
