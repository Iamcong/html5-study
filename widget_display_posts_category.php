	<?php
	// Thiết lập widget	hiển thị bài viết mới nhất theo category
		add_action('widgets_init', 'post_category');
		function post_category(){
			register_widget('display_post_category');
		}
		class display_post_category extends WP_Widget{
			// Hiển thị thông tin widget
			function __construct(){
				parent::__construct(
					'post_category', // ID widget
					'Chuyên mục bài viết', // Tên Widget
					array('description' => 'Widget hiển thị bài viết theo category')	
				);
			}
			// Hiển thị form điền dữ liệu
			function form($instance){
				$default = array(
					'title' => 'Tiêu đề chính',
					'title1' => 'Tiêu phụ phụ 1',
					'title2' => 'Tiêu phụ phụ 2',
					'title3' => 'Tiêu phụ phụ 3',
					'post_number' => 4,
					'category' => '',
					);
				$instance = wp_parse_args($instance, $default);
				echo 'Tiêu đề chính: <input type="text" class="widefat" name="'.$this->get_field_name('title').'" value="'.$instance['title'].'" />';
				echo 'Tiêu phụ phụ 1: <input type="text" class="widefat" name="'.$this->get_field_name('title1').'" value="'.$instance['title1'].'" />';
				echo 'Tiêu phụ phụ 2: <input type="text" class="widefat" name="'.$this->get_field_name('title2').'" value="'.$instance['title2'].'" />';
				echo 'Tiêu phụ phụ 3: <input type="text" class="widefat" name="'.$this->get_field_name('title3').'" value="'.$instance['title3'].'" />';
				echo 'Số bài viết: <input type="text" class="widefat" name="'.$this->get_field_name('post_number').'" value="'.$instance['post_number'].'" />';
	?>
			<p>
				<label for="<?php echo $this->get_field_id("category"); ?>">
					<?php _e("Danh mục:", "dtt"); ?>
					<br/>
					<?php wp_dropdown_categories(array('hide_empty' => 1,'taxonomy' => 'category', 'name' => $this->get_field_name("category"), 'selected' => $instance["category"])); ?>
				</label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'style' ); ?>"><?php _e( 'Style:' ); ?></label>
				<select id="<?php echo $this->get_field_id('style'); ?>" name="<?php echo $this->get_field_name('style'); ?>" class="widefat">
					<option <?php selected( $instance['style'], '1'); ?> value="1">1</option>
					<option <?php selected( $instance['style'], '2'); ?> value="2">2</option>
					<option <?php selected( $instance['style'], '3'); ?> value="3">3</option>
					<option <?php selected( $instance['style'], '4'); ?> value="4">4</option>
					<option <?php selected( $instance['style'], '5'); ?> value="5">5</option>
					<option <?php selected( $instance['style'], '6'); ?> value="6">6</option>  
				</select>
			</p>
		<?php
		}
		// Lưu trữ dữ liệu
		function update($new_instance, $old_instance){
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['title1'] = strip_tags($new_instance['title1']);
			$instance['title2'] = strip_tags($new_instance['title2']);
			$instance['title3'] = strip_tags($new_instance['title3']);
			$instance['post_number'] = strip_tags($new_instance['post_number']);
			$instance['category'] = strip_tags($new_instance['category']);
			$instance['style'] = (int) $new_instance['style'];
			return $instance;
		}
		// Hiển thị ra màn hình
		function widget($args, $instance){
			$style = ( ! empty( $instance['style'] ) ) ? absint( $instance['style'] ) : 1;
			$args = array(
				'post_type' => 'post',
				'post_status' => 'publish',
				'tax_query' => array(
					array(
						'taxonomy'  => 'category',
						'field'     => 'id',
						'terms'     => $instance ['category']
					)
				),						
				'orderby' => 'id',
				'order' => 'DESC',
				'posts_per_page'=> $instance['post_number'],
				);				
			$query = new WP_Query($args);
		?>
			<?php if ( $style == 1 ) { ?>
                <div class="block_cate">
                    <div class="cate_title">
                        <h2><a href="#" title=""><?php echo $instance['title']; ?></a></h2>
                        <ul class="list-unstyled list-inline">
                            <li><a href="#" title=""><?php echo $instance['title1']; ?></a></li>
                            <li><a href="#" title=""><?php echo $instance['title2']; ?></a></li>
                            <li><a href="#" title=""><?php echo $instance['title3']; ?></a></li>
                        </ul>
                    </div>
                    <div class="post_thumb">
                    	<?php if($query->have_posts()) : while($query->have_posts()) : $query->the_post(); ?>
                        <div class="post_thumb_img">
                            <?php the_post_thumbnail('bai-viet-thumbnail', array('class'=>'img-responsive')); ?>
                        </div>
                        <div class="post_caption">
                            <h3>
                                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                            </h3>
                            <?php the_excerpt(); ?>
                        </div>
                        <div class="clearfix"></div>
                        <?php endwhile; wp_reset_postdata(); endif; ?>
                    </div>
			<?php } elseif( $style == 2 ) { ?>
				<div class="related_post">
	                    <?php if($query->have_posts()) : while($query->have_posts()) : $query->the_post(); ?>
	                    <p>
	                        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
	                            <i class="fa fa-angle-double-right"></i><?php the_title(); ?>
	                        </a>
	                    </p>
	                     <?php endwhile; wp_reset_postdata(); endif; ?>
	            </div>
	        </div>
	        <?php } elseif( $style == 3 ) { ?>
	        	<div class="post_wrap">
					<?php if($query->have_posts()) : while($query->have_posts()) : $query->the_post(); ?>
			        	<div class="news_post_img">
		                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
		                        <?php the_post_thumbnail('archive_post_thumb', array('class' => 'img-responsive')); ?>
		                    </a>
		                </div>
		                <div class="post_caption">
		                    <h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
		                    <span><i class="fa fa-clock-o"></i><?php the_date(); ?></span>
		                </div>
	                <?php endwhile; wp_reset_postdata(); endif; ?>
	            </div>
             <?php } elseif( $style == 4 ) { ?>
	             	<div class="related_post">
	             		<?php if($query->have_posts()) : while($query->have_posts()) : $query->the_post(); ?>
	                		<p>
		                		<a href="<?php the_permalink(); ?>" title="">
		                			<i class="fa fa-angle-double-right"></i><?php the_title(); ?>
		                		</a>
	                		</p>
	                	 <?php endwhile; wp_reset_postdata(); endif; ?>
	                </div>
	         <?php } elseif( $style == 6 ) { ?>
	         <?php if($query->have_posts()) : while($query->have_posts()) : $query->the_post(); ?>
	         	<div class="item">
                        <div class="post_thumb">
                            <div class="post_thumb_img">
                                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail('archive_post_thumb', array('class' => 'img-responsive')); ?></a>
                            </div>
                            <div class="post_caption">
                                <h3>
                                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                                </h3>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                </div>
            <?php endwhile; wp_reset_postdata(); endif; ?>
			<?php } else {?>
				<div class="cate_title cate_featured_title">
					<h2><a href="#" title=""><?php echo $instance['title']; ?></a></h2>
				</div>
				<?php if($query->have_posts()) : while($query->have_posts()) : $query->the_post(); ?>
					<div class="post_thumb">
						<div class="post_thumb_img">
							 <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail('bai-viet-thumbnail', array('class'=>'img-responsive')); ?></a>
						</div>
						<div class="post_caption">
							<h3>
								<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
							</h3>
						</div>
						<div class="clearfix"></div>
					</div>
				<?php endwhile; wp_reset_postdata(); endif; ?>
		<?php } } } ?>
