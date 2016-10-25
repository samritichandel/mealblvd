<?php
//  We are waiting to host you section 
class Front_Page_Listing_Categories_Widget extends WP_Widget {

	private $defaults = array(
		'title'           => '',
		'subtitle'        => '',
		'number_of_items' => '4',
		'orderby'         => 'name',
		'categories_slug' => '',
//		'default_image'   => ''
	);

	function __construct() {
		parent::__construct(
			'front_page_listing_categories', // Base ID
			'&#x1f535; ' . esc_html__( 'Front Page', 'listable' ) . ' &raquo; ' . esc_html__( 'Listing Categories', 'listable' ), // Name
			array( 'description' => esc_html__( 'Display a list of listing categories based on different criteria (eg. most popular, random) or specify which ones you want to show.', 'listable' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		global $post;

		$placeholders = $this->get_placeholder_strings();

		//only put in the default title if the user hasn't saved anything in the database e.g. $instance is empty (as a whole)
		$title                  = apply_filters( 'widget_title', empty( $instance ) ? $placeholders['title'] : $instance['title'], $instance, $this->id_base );
		$subtitle               = empty( $instance ) ? $placeholders['subtitle'] : $instance['subtitle'];
		$number_of_items        = empty( $instance['number_of_items'] ) ? $this->defaults['number_of_items'] : $instance['number_of_items'];
		$orderby                = empty( $instance['orderby'] ) ? $this->defaults['orderby'] : $instance['orderby'];
		$categories_slug        = empty( $instance['categories_slug'] ) ? $this->defaults['categories_slug'] : $instance['categories_slug'];
		//$widget_default_image   = empty( $instance['default_image'] ) ? $this->defaults['default_image'] : $instance['default_image'];
		$term_list              = array();
		$custom_category_labels = array();

		//first let's do only one query and get all the terms - we will reuse this info to avoid multiple queries
		$query_args = array( 'order' => 'DESC', 'hide_empty' => false, 'hierarchical' => true, 'pad_counts' => true );
		if ( ! empty( $orderby ) && is_string( $orderby ) ) {
			$query_args['orderby'] = $orderby;
		}

		$all_terms = get_terms(
			'job_listing_category',
			$query_args
		);

		//bail if there was an error
		if ( is_wp_error( $all_terms ) ) {
			return;
		}

		//now create an array with the category slug as key so we can reference/search easier
		$all_categories = array();
		foreach ( $all_terms as $key => $term ) {
			$all_categories[ $term->slug ] = $term;
		}

		echo $args['before_widget'];

		//if we have received a list of categories to display (their slugs and optional label), use that
		if ( ! empty( $categories_slug ) && is_string( $categories_slug ) ) {
			$categories = explode( ',', $categories_slug );
			foreach ( $categories as $key => $category ) {
				if ( strpos( $category, '(' ) !== false ) {
					$category  = explode( '(', $category );
					$term_slug = trim( $category[0] );

					if ( substr( $category[1], - 1, 1 ) == ')' ) {
						$custom_category_labels[ $term_slug ] = trim( substr( $category[1], 0, - 1 ) );
					}

					if ( array_key_exists( $term_slug, $all_categories ) ) {
						$term_list[] = $all_categories[ $term_slug ];
					}
				} else {
					$term_slug = trim( $category );
					if ( array_key_exists( $term_slug, $all_categories ) ) {
						$term_list[] = $all_categories[ $term_slug ];
					}
				}
			}

			//now if the user has chosen to sort these according to the number of posts, we should do that
			// since we will, by default, respect the order of the categories he has used
			if ( 'count' == $orderby ) {
				// Define the custom sort function
				function sort_by_post_count( $a, $b ) {
					return $a->count < $b->count;
				}

				// Sort the multidimensional array
				usort( $term_list, "sort_by_post_count" );
			} elseif ( 'rand' == $orderby ) {
				//randomize things a bit if this is what the user ordered
				shuffle( $term_list );
			}

		} else {
			//it seems we will have to figure out ourselves what categories to display

			if ( ! $number_of_items = intval( $number_of_items ) ) {
				$number_of_items = 4;
			}

			$term_list = array_slice( $all_categories, 0, $number_of_items );
		}

		if ( ! empty( $term_list ) ) : ?>

			<h3 class="widget_title  widget_title--frontpage">
				<?php
				echo $title;
				if ( ! empty( $subtitle ) ) { ?>
					<span class="widget_subtitle  widget_subtitle--frontpage">
						<?php echo $subtitle; ?>
					</span>
				<?php } ?>
			</h3>

			<div class="categories-wrap  categories-wrap--widget">
				<ul class="categories  categories--widget host_list">

					<?php foreach ( $term_list as $key => $term ) :
						if ( ! $term ) {
							continue;
						}
						$icon_url           = listable_get_term_icon_url( $term->term_id );
						$image_url          = listable_get_term_image_url( $term->term_id, 'listable-card-image' );
						$attachment_id      = listable_get_term_icon_id( $term->term_id );
						$image_src          = '';

						if ( ! empty( $image_url ) ) {

							$image_src = $image_url;

//						} elseif ( $has_widget_default ) {
//							$image_src = $widget_default_image;
						} else {
							$thumbargs    = array(
								'posts_per_page' => 1,
								'post_type'      => 'job_listing',
								'meta_key'       => 'main_image',
								'orderby'          => 'rand',
								'tax_query'      => array(
									array(
										'taxonomy' => 'job_listing_category',
										'field'    => 'name',
										'terms'    => $term->name
									),
								)
							);
							$latest_thumb = new WP_Query( $thumbargs );

							if ( $latest_thumb->have_posts() ) {
								//get the first image in the listing's gallery or the featured image, if present
								$image_ID  = listable_get_post_image_id( $latest_thumb->post->ID );
								$image_src = '';
								if ( ! empty( $image_ID ) ) {
									$image     = wp_get_attachment_image_src( $image_ID, 'medium' );
									$image_src = $image[0];
								}
							}
						} ?>

						<li <?php echo empty( $icon_url ) ? 'class="no-icon"' : ''; ?>>
							<div class="category-cover" style="background-image: url(<?php echo listable_get_inline_background_image( $image_src ); ?>)">
								<a href="<?php echo esc_url( get_term_link( $term ) ); ?>">
									<?php $svgimage=get_field('category_vector_code','job_listing_category_'.$term->term_id.''); ?>
									<?php if ( ! empty( $icon_url ) || ! empty($svgimage)  ) : ?>

										<div class="category-icon">

											<?php 
											if($svgimage)
												echo $svgimage;
											
											//listable_display_image( $icon_url, '', true, $attachment_id ); ?>

											<!--<span class="category-count">
												<?php //echo $term->count; ?>
											</span>-->
										</div>

									<?php endif; ?>

									<span class="category-text"><?php echo isset( $custom_category_labels[ $term->slug ] ) ? $custom_category_labels[ $term->slug ] : $term->name; ?></span>
								</a>
							</div>
						</li>

					<?php endforeach; ?>

				</ul><!-- .categories -->
			</div><!-- .categories-wrap -->

			<?php
		endif;

		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 *
	 * @return null
	 */
	public function form( $instance ) {
		$original_instance = $instance;
		//Defaults
		$instance = wp_parse_args(
			(array) $instance,
			$this->defaults );

		$placeholders = $this->get_placeholder_strings();

		$title = esc_attr( $instance['title'] );
		//if the user is just creating the widget ($original_instance is empty)
		if ( empty( $original_instance ) && empty( $title ) ) {
			$title = $placeholders['title'];
		}

		$subtitle = esc_attr( $instance['subtitle'] );
		//if the user is just creating the widget ($original_instance is empty)
		if ( empty( $original_instance ) && empty( $subtitle ) ) {
			$subtitle = $placeholders['subtitle'];
		}
		$number_of_items = esc_attr( $instance['number_of_items'] );
		$categories_slug = esc_attr( $instance['categories_slug'] );
		//$default_image   = esc_attr( $instance['default_image'] ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'listable' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" placeholder="<?php echo esc_attr( $placeholders['title'] ); ?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'subtitle' ); ?>"><?php esc_html_e( 'Subtitle:', 'listable' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'subtitle' ); ?>" name="<?php echo $this->get_field_name( 'subtitle' ); ?>" type="text" value="<?php echo $subtitle; ?>" placeholder="<?php echo esc_attr( $placeholders['subtitle'] ); ?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'number_of_items' ); ?>"><?php esc_html_e( 'Number of items to show:', 'listable' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'number_of_items' ); ?>" name="<?php echo $this->get_field_name( 'number_of_items' ); ?>" type="number" value="<?php echo $number_of_items; ?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php esc_html_e( 'Order by:', 'listable' ); ?></label>
			<select name="<?php echo $this->get_field_name( 'orderby' ); ?>" id="<?php echo $this->get_field_id( 'orderby' ); ?>" class="widefat">
				<option value="name"<?php selected( $instance['orderby'], 'name' ); ?>><?php esc_html_e( 'Default', 'listable' ); ?></option>
				<option value="count"<?php selected( $instance['orderby'], 'count' ); ?>><?php esc_html_e( 'Number of Listings', 'listable' ); ?></option>
				<option value="rand"<?php selected( $instance['orderby'], 'rand' ); ?>><?php esc_html_e( 'Random', 'listable' ); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'categories_slug' ); ?>"><?php esc_html_e( 'Categories Slug(optional):', 'listable' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'categories_slug' ); ?>" name="<?php echo $this->get_field_name( 'categories_slug' ); ?>" type="text" value="<?php echo $categories_slug; ?>"/>
		</p>

		<?php
		/**
		 * Keep this hidden for now

		if ( ! wp_attachment_is_image( $default_image ) ) {
			$default_image = false;
		} ?>

		<span class="field_separator">...</span>

		<p class="listable-image-modal-control<?php echo ( $default_image ) ? ' has-image' : ''; ?>"
		   data-title="<?php esc_attr_e( 'Select an Image', 'listable' ); ?>"
		   data-update-text="<?php esc_attr_e( 'Update Image', 'listable' ); ?>"
		   data-target="listable-categories-<?php echo $this->number ?>-image-id">
			<?php
			if ( ! empty( $default_image ) ) {
				echo wp_get_attachment_image( $default_image, 'medium', false );
			} ?>
			<input data-field="image" type="hidden" value="<?php echo $default_image; ?>" class="widefat listable-category-<?php echo $this->number ?>-image-id" id="<?php echo $this->get_field_id( 'default_image' ); ?>" name="<?php echo $this->get_field_name( 'default_image' ); ?>">
			<a class="button listable-image-modal-control__choose dashicons dashicons-camera" href="#" title="<?php esc_html_e( 'Select an Image', 'listable' ); ?>"></a>
			<a class="button listable-image-modal-control__clear dashicons dashicons-dismiss" href="#" title="<?php esc_html_e( 'Clear', 'listable' ); ?>"></a>
		</p>
		<?php
		 */
	}

	/**
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance             = $old_instance;
		$instance['title']    = strip_tags( $new_instance['title'] );
		$instance['subtitle'] = strip_tags( $new_instance['subtitle'] );
		//this number can't be lower than 1
		$instance['number_of_items'] = strip_tags( $new_instance['number_of_items'] );
		if ( intval( $instance['number_of_items'] ) < 1 ) {
			$instance['number_of_items'] = '1';
		}
		$instance['categories_slug'] = strip_tags( $new_instance['categories_slug'] );
		//$instance['default_image']   = strip_tags( $new_instance['default_image'] );

		if ( in_array( $new_instance['orderby'], array( 'name', 'count', 'rand' ) ) ) {
			$instance['orderby'] = $new_instance['orderby'];
		} else {
			$instance['orderby'] = 'name';
		}

		return $instance;
	}

	private function get_placeholder_strings() {
		$placeholders = apply_filters( 'front_page_listing_categories_widget_backend_placeholders', array() );

		$placeholders = wp_parse_args(
			(array) $placeholders,
			array(
				'title'    => esc_html__( 'What are you interested in?', 'listable' ),
				'subtitle' => esc_html__( 'Discover something nice', 'listable' )
			) );

		return $placeholders;
	}

} // class Front_Page_Listing_Categories_Widget

//We are waiting to host you section  ends

//Explore tables around the world front page
class Front_Page_Listing_Cards_Widget extends WP_Widget {

	private $defaults = array(
		'title'           => '',
		'subtitle'        => '',
		'number_of_items' => '3',
		'show'            => 'all',
		'orderby'         => 'date',
		'items_ids'       => '',
		'categories_slug' => ''
	);

	function __construct() {
		parent::__construct(
			'front_page_listing_cards', // Base ID
			'&#x1f535; ' . esc_html__( 'Front Page', 'listable' ) . ' &raquo; ' . esc_html__( 'Listing Cards', 'listable' ), // Name
			array( 'description' => esc_html__( 'Displays a list of your listings based on different criteria (eg. latest of featured listings from a specific category)', 'listable' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		global $post;

		$placeholders = $this->get_placeholder_strings();
		//only put in the default title if the user hasn't saved anything in the database e.g. $instance is empty (as a whole)
		$title           = apply_filters( 'widget_title', empty( $instance ) ? $placeholders['title'] : $instance['title'], $instance, $this->id_base );
		$subtitle        = empty( $instance ) ? $placeholders['subtitle'] : $instance['subtitle'];
		$number_of_items = empty( $instance['number_of_items'] ) ? $this->defaults['number_of_items'] : $instance['number_of_items'];
		$show            = empty( $instance['show'] ) ? $this->defaults['show'] : $instance['show'];
		$orderby         = empty( $instance['orderby'] ) ? $this->defaults['orderby'] : $instance['orderby'];
		$items_ids       = empty( $instance['items_ids'] ) ? $this->defaults['items_ids'] : $instance['items_ids'];
		$categories_slug = empty( $instance['categories_slug'] ) ? $this->defaults['categories_slug'] : $instance['categories_slug'];

		echo $args['before_widget']; ?>

		<div class="widget_front_page_listing_cards" itemscope itemtype="http://schema.org/LocalBusiness">
			<h3 class="widget_title  widget_title--frontpage">
				<?php
				echo $title;

				if ( ! empty( $subtitle ) ) { ?>
					<span class="widget_subtitle--frontpage">
						<?php echo $subtitle; ?>
					</span>
				<?php } ?>
			</h3>
			<?php
			// lets query some
			$query_args = array(
				'post_type'   => 'job_listing',
				'post_status' => 'publish'
			);

			if ( ! empty( $number_of_items ) && is_numeric( $number_of_items ) ) {
				$query_args['posts_per_page'] = $number_of_items;
			}

			if ( ! empty( $orderby ) && is_string( $orderby ) ) {
				$query_args['orderby'] = $orderby;
			}

			if ( ! empty( $show ) && $show === 'featured' ) {
				$query_args['meta_key']   = '_featured';
				$query_args['meta_value'] = '1';
			}

			if ( ! empty( $items_ids ) && is_string( $items_ids ) ) {
				$query_args['post__in'] = explode( ',', $items_ids );
			}

			if ( ! empty( $categories_slug ) && is_string( $categories_slug ) ) {
				$categories_slug = explode( ',', $categories_slug );

				foreach ( $categories_slug as $key => $cat ) {
					$categories_slug[ $key ] = sanitize_title( $cat );
				}
				$query_args['tax_query'] = array(
					'relation' => 'AND',
					array(
						'taxonomy' => 'job_listing_category',
						'field'    => 'slug',
						'terms'    => $categories_slug,
					)
				);
			}

			$listings = new WP_Query( $query_args );

			if ( $listings->have_posts() ) : ?>
				<div class="grid  grid--widget  list explore_sec">
					<?php while ( $listings->have_posts() ) : $listings->the_post();
						$terms = get_the_terms( get_the_ID(), 'job_listing_category' );

						$listing_classes = 'card  card--listing  card--widget  ';
						$listing_is_claimed = false;
						$listing_is_featured = false;

						if ( is_position_featured($post) ) $listing_is_featured = true;

						if ( class_exists( 'WP_Job_Manager_Claim_Listing' ) ) {
							$classes = WP_Job_Manager_Claim_Listing()->listing->add_post_class( array(), '', $post->ID  );

							if ( isset( $classes[0] ) && ! empty( $classes[0] ) ) {
								$listing_classes .= $classes[0];

								if ( $classes[0] == 'claimed' )
									$listing_is_claimed = true;
							}
						}

						if ( true === $listing_is_featured ) $listing_classes .= '  is--featured';

						$listing_classes = apply_filters( 'listable_listing_archive_classes', $listing_classes, $post ); ?>

						<a href="<?php the_job_permalink(); ?>" class="grid__item  grid__item--widget">
							<article class="<?php echo $listing_classes; ?>" data-latitude="<?php echo get_post_meta( $post->ID, 'geolocation_lat', true ); ?>"
							         data-longitude="<?php echo get_post_meta( $post->ID, 'geolocation_long', true ); ?>"
							         data-img="<?php echo listable_get_post_image_src( $post->ID, 'full' ); ?>"
							         data-permalink="<?php the_job_permalink(); ?>">

								<aside class="card__image" style="background-image: url(<?php echo listable_get_post_image_src( $post->ID, 'listable-card-image' ); ?>);">
									<?php if ( true === $listing_is_featured ): ?>
									<span class="card__featured-tag"><?php esc_html_e( 'Featured', 'listable' ); ?></span>
									<?php endif; ?>

									<?php do_action('listable_job_listing_card_image_top', $post ); ?>

									<?php do_action('listable_job_listing_card_image_bottom', $post ); ?>

								</aside>

								<div class="card__content">
									<div class="author_details">
									
								<?php
								//get author data
								$author_id=$post->post_author;
								echo get_avatar($author_id,38);
								echo the_author_meta( 'display_name' , $author_id ); ?>
									</div>
									<h2 class="card__title" itemprop="name"><?php
										echo get_the_title();
										if ( $listing_is_claimed ) :
											echo '<span class="listing-claimed-icon">';
											get_template_part('assets/svg/checked-icon-small');
											echo '<span>';
										endif;
									?></h2>
									<div class="card__tagline"><?php the_company_tagline(); ?></div>
									<footer class="card__footer">
										<?php
										$rating = get_average_listing_rating( $post->ID, 0 );
										if ( ! empty( $rating ) ) {
										echo '<div class="star">';
										for($i=1;$i<=5;$i++)
										{
											if($i<=$rating)
												echo '<i class="fa fa-star" aria-hidden="true"></i>';
											else
												echo '<i class="fa fa-star star-disabled" aria-hidden="true"></i>';
										}
										echo  '<div class="count">('.$rating.')</div>';
										echo '</div>';
										}
										else
										{
											echo '<div class="star">';
											for($i=1;$i<=5;$i++)
											{
												echo '<i class="fa fa-star star-disabled" aria-hidden="true"></i>';
											}
											echo  '<div class="count">(0)</div>';
											echo '</div>';
											
										}
										
										/*if ( ! empty( $rating ) ) { ?>
											<div class="rating  card__rating">
												<span class="js-average-rating">
												<?php echo get_average_listing_rating( $post->ID, 1 ); ?>
												</span>
											</div>
										<?php } else {
											if ( get_post_meta( $post->ID, 'geolocation_street', true ) ) { ?>
												<div class="card__rating  card__pin">
													<?php get_template_part( 'assets/svg/pin-simple-svg' ) ?>
												</div>
											<?php }
										}*/ ?>
										
										<?php 
										$price=get_field('_price',$post->ID);
										if(trim($price,' '))
										{
											echo '<div class="price">';
											echo '$';
											echo $price;
											echo '</div>';
										}
										?>
										<?php 
										/*if ( ! is_wp_error( $terms ) && ( is_array( $terms ) || is_object( $terms ) ) ) : ?>

											<ul class="card__tags">
												<?php foreach ( $terms as $term ) {
													$icon_url      = listable_get_term_icon_url( $term->term_id );
													$attachment_id = listable_get_term_icon_id( $term->term_id );
													if ( empty( $icon_url ) ) {
														continue;
													} ?>
													<li>
														<div class="card__tag">
															<div class="pin__icon">
																<?php listable_display_image( $icon_url, '', true, $attachment_id ); ?>
															</div>
														</div>
													</li>
												<?php } ?>
											</ul>

										<?php endif; 
										*/
										?>
										
										
										<!--<div class="address  card__address">
											<?php// echo listable_display_formatted_address( $post ); ?>
										</div>-->
										
									</footer>
								</div><!-- .card__content -->
							</article><!-- .card.card--listing -->
						</a><!-- .grid_item -->

					<?php endwhile;

					wp_reset_postdata(); ?>

				</div>

			<?php endif; ?>

		</div>
		<?php echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 *
	 * @return null
	 */
	public function form( $instance ) {
		$original_instance = $instance;

		//Defaults
		$instance = wp_parse_args(
			(array) $instance,
			$this->defaults );

		$placeholders = $this->get_placeholder_strings();

		$title = esc_attr( $instance['title'] );
		//if the user is just creating the widget ($original_instance is empty)
		if ( empty( $original_instance ) && empty( $title ) ) {
			$title = $placeholders['title'];
		}

		$subtitle = esc_attr( $instance['subtitle'] );
		//if the user is just creating the widget ($original_instance is empty)
		if ( empty( $original_instance ) && empty( $subtitle ) ) {
			$subtitle = $placeholders['subtitle'];
		}

		$number_of_items = esc_attr( $instance['number_of_items'] );
		$items_ids       = esc_attr( $instance['items_ids'] );
		$categories_slug = esc_attr( $instance['categories_slug'] ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'listable' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" placeholder="<?php echo esc_attr( $placeholders['title'] ); ?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'subtitle' ); ?>"><?php esc_html_e( 'Subtitle:', 'listable' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'subtitle' ); ?>" name="<?php echo $this->get_field_name( 'subtitle' ); ?>" type="text" value="<?php echo $subtitle; ?>" placeholder="<?php echo esc_attr( $placeholders['subtitle'] ); ?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'number_of_items' ); ?>"><?php esc_html_e( 'Number of items to show:', 'listable' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'number_of_items' ); ?>" name="<?php echo $this->get_field_name( 'number_of_items' ); ?>" type="number" value="<?php echo $number_of_items; ?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'show' ); ?>"><?php esc_html_e( 'Show:', 'listable' ); ?></label>
			<select name="<?php echo $this->get_field_name( 'show' ); ?>" id="<?php echo $this->get_field_id( 'show' ); ?>" class="widefat">
				<option value="all"<?php selected( $instance['show'], 'all' ); ?>><?php esc_html_e( 'All Listings', 'listable' ); ?></option>
				<option value="featured"<?php selected( $instance['show'], 'featured' ); ?>><?php esc_html_e( 'Featured Listings', 'listable' ); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php esc_html_e( 'Order by:', 'listable' ); ?></label>
			<select name="<?php echo $this->get_field_name( 'orderby' ); ?>" id="<?php echo $this->get_field_id( 'orderby' ); ?>" class="widefat">
				<option value="date"<?php selected( $instance['orderby'], 'date' ); ?>><?php esc_html_e( 'Date', 'listable' ); ?></option>
				<option value="rand"<?php selected( $instance['orderby'], 'rand' ); ?>><?php esc_html_e( 'Random', 'listable' ); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'items_ids' ); ?>"><?php esc_html_e( 'Items IDs(optional):', 'listable' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'items_ids' ); ?>" name="<?php echo $this->get_field_name( 'items_ids' ); ?>" type="text" value="<?php echo $items_ids; ?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'categories_slug' ); ?>"><?php esc_html_e( 'Categories Slug(optional):', 'listable' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'categories_slug' ); ?>" name="<?php echo $this->get_field_name( 'categories_slug' ); ?>" type="text" value="<?php echo $categories_slug; ?>"/>
		</p>
		<?php
	}

	/**
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                    = $old_instance;
		$instance['title']           = strip_tags( $new_instance['title'] );
		$instance['subtitle']        = strip_tags( $new_instance['subtitle'] );
		$instance['number_of_items'] = strip_tags( $new_instance['number_of_items'] );
		//some sanity check
		if ( intval( $instance['number_of_items'] ) < 1 ) {
			$instance['number_of_items'] = '1';
		}
		$instance['items_ids']       = strip_tags( $new_instance['items_ids'] );
		$instance['categories_slug'] = strip_tags( $new_instance['categories_slug'] );

		if ( in_array( $new_instance['show'], array( 'all', 'featured' ) ) ) {
			$instance['show'] = $new_instance['show'];
		} else {
			$instance['show'] = 'all';
		}

		if ( in_array( $new_instance['orderby'], array( 'date', 'rand' ) ) ) {
			$instance['orderby'] = $new_instance['orderby'];
		} else {
			$instance['orderby'] = 'date';
		}

		return $instance;
	}

	private function get_placeholder_strings() {
		$placeholders = apply_filters( 'front_page_listing_cards_widget_backend_placeholders', array() );

		$placeholders = wp_parse_args(
			(array) $placeholders,
			array(
				'title'    => esc_html__( 'Listing Cards', 'listable' ),
				'subtitle' => esc_html__( 'Explore these lovely listings', 'listable' )
			) );

		return $placeholders;
	}
} // class Front_Page_Listing_Cards_Widget
//Explore tables around the world front page ends
?>