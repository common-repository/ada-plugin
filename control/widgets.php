<?php

// Widget for Tagcloud //
class ADA_Widget_Tagcloud extends WP_Widget {

	function ADA_Widget_Tagcloud() {
		$widget_ops = array('classname' => 'ada_widget_tagcloud', 'description' => __( 'Display a pet color tagcloud','wp-ada') );
		$this->WP_Widget('ada_tagcloud', __('ADA Color Tagcloud','wp-ada'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		if ( !empty($instance['title']) ) {
			$title = $instance['title'];
		} else {
			if ( 'post_tag') {
				$title = __('Pet colors tagcloud','wp-ada');
			} else {

			}
		}
		$title = apply_filters('widget_title', $title, $instance, $this->id_base);

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
		echo '<div class="tagcloud">';
		wp_tag_cloud( array( 'taxonomy' => 'colors', 'smallest'=>'10', 'largest'=>'12' ) );
		echo "</div>\n";
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		return $instance;
	}

	function form( $instance ) {

?>
	<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title') ?></label>
	<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php if (isset ( $instance['title'])) {echo esc_attr( $instance['title'] );} ?>" /></p>
	<?php
	}


}

// Widget for search form //
class ADA_Widget_Searchform extends WP_Widget {

	function ADA_Widget_Searchform() {
		$widget_ops = array('classname' => 'ada_widget_searchform', 'description' => __( 'Display a pet searchform','wp-ada') );
		$this->WP_Widget('ada_widget_searchform', __('ADA Search Form','wp-ada'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		if ( !empty($instance['title']) ) {
			$title = $instance['title'];
		} else {
			if ( 'ada_search') {
				$title = __('ADA Search Form','wp-ada');
			} else {

				$title = $tax->labels->name;
			}
		}
		$title = apply_filters('widget_title', $title, $instance, $this->id_base);

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
		echo '<div class="ada_search_container">';

    echo '<form method="get" id="ada_search" action="'. home_url() . '/">';
    echo '<input type="hidden" name="post_type" value="pet" />';
    echo '<select name="status" id="drop-status">';
            $terms = get_terms('status', array('hide_empty' => 1 ));
            foreach ($terms as $term) {
            echo "<option value='$term->slug'" . selected($term->slug, true, false) . ">$term->name</option>";
        		 }
    echo '</select>&nbsp;';
    echo '<select name="types" id="drop-type">';
            $terms = get_terms('types', array('hide_empty' => 1 ));
            foreach ($terms as $term) {
            echo "<option value='$term->slug'" . selected($term->slug, true, false) . ">$term->name</option>";
        		 }
    echo '</select>&nbsp;';
    echo '<input id="ada_search_submit" type="submit" value="'. __('Go','wp-ada') . '"/>';
    echo '</form>';
		echo '</div>';
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		return $instance;
	}

	function form( $instance ) {
?>
	<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title') ?></label>
	<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php if (isset ( $instance['title'])) {echo esc_attr( $instance['title'] );} ?>" /></p>
	<?php
	}
}

/**
 * ADAWidget Class
 */
class ADAWidget extends WP_Widget {

    /** constructor */
    function ADAWidget() {
        parent::WP_Widget(false, $name = 'AdaPet', $widget_options = array('name' => __('ADA Pets', 'wp-ada'),'description' => _x('Display pets for adoption','widget pet','wp-ada')));;
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {
        extract( $args );
        $title = apply_filters('widget_title', empty($instance['title']) ? '&nbsp;' : $instance['title'], $instance, $this->id_base);
        $text = apply_filters( 'widget_text', $instance['text'], $instance );
        $sortby = empty( $instance['sortby'] ) ? 'comment_count' : $instance['sortby'];
        $r = $instance['rss'] ? '1' : '0';
        $status = isset($instance['status']) ? $instance['status'] : false;
        $number = isset($instance['number']) ? $instance['number'] : false;
        $category = isset($instance['category']) ? $instance['category'] : false;
        $q = new WP_Query(array('post_type'=>'pet', 'posts_per_page'=>$number, 'orderby'=>$sortby, 'status' => $status,'types' => $category));
        ?>

        <?php echo $before_widget; ?>
        <?php if ( $title ) echo $before_title . $title . $after_title; ?>

         <div class="ada-widget-container">
         <?php echo $instance['filter'] ? wpautop($text) : $text; ?>
         <?php if ( $r ) {  ?>
		     <p class="ada-rss"><a href="<?php echo home_url(); ?>/?feed=rss2&amp;post_type=pet"><img src="<?php echo WP_PLUGIN_URL ."/".WPADA_DIR_NAME . '/creation/presentation' ;?>/rss.png" alt="rss" title="<?php _e('RSS'); ?>" />&nbsp;RSS</a></p><?php } ?>

     		  <ul class="pet-list">
     		  <?php  while ($q->have_posts()) : $q->the_post(); ?>
            <li><span class="ada-thumb"><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail('ada_thumb'); ?></a></span>
             <ul class="pet-data-list">
             <li class="pet-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></li>
             <li><strong><?php _e('In', 'wp-ada'); ?></strong>: <?php echo get_the_term_list( get_the_ID(), 'types' , " " ) ?></li>
             <li><strong><?php _e('Gender', 'wp-ada'); ?></strong>: <?php  $pd=get_the_ID(); echo get_post_meta($pd, 'dbt_gender','true'); ?></li>
             </ul>
            </li>
            <li class="ada-btn">
            <span class="ada-action">
            <a href="<?php the_permalink() ?>" title="<?php _e('Read about', 'wp-ada'); ?> <?php the_title(); ?>">
            <?php  $pd=get_the_ID(); $themes = wp_get_object_terms($pd, 'status') ; foreach ($themes as $theme) { echo $theme->name ; }  ?></a>
            </span></li>
            <li class="divisor">&nbsp;</li>
     		  <?php endwhile; ?>
     		  </ul>

        </div>
		<?php echo $after_widget; ?>

    <?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();
		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('widget_recent_posts', $cache, 'widget');   }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
    	$instance = $old_instance;
      $instance['title'] = strip_tags($new_instance['title']);
      $instance['number'] = ($new_instance['number']);
      $instance['status'] = ($new_instance['status']);
      $instance['category'] = ($new_instance['category']);
      $instance['rss'] = !empty($new_instance['rss']) ? 1 : 0;

	 		if ( current_user_can('unfiltered_html') )
			$instance['text'] =  $new_instance['text'];
		    else
			$instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed
		  $instance['filter'] = isset($new_instance['filter']);

    	if ( in_array( $new_instance['sortby'], array( 'title', 'date', 'author', 'ID', 'rand', 'modified', 'comment_count' ) ) ) {
			$instance['sortby'] = $new_instance['sortby'];
	    	} else {
			$instance['sortby'] = 'comment_count';
	    	}
     return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
    $title = apply_filters('widget_title', empty($instance['title']) ? '&nbsp;' : $instance['title'], $instance, $this->id_base);
    $instance = wp_parse_args( (array) $instance, array(  'text' => '', 'sortby' => 'comment_count','category' => false,'status' => false, 'number'=> false ) );
    $text = esc_textarea($instance['text']);
    $rss = isset($instance['rss']) ? (bool) $instance['rss'] :false;
    $link_cats = get_terms('types', array('hide_empty' => 1));
    $lost_sts = get_terms('status', array('hide_empty' => 1));
    $items = array('1','2','3','4','5');
    ?>
        <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title'); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
         <p>
          <label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Arbitrary text or HTML'); ?></label>
	      	<textarea class="widefat" rows="6" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>
        </p>

	      <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('How many items would you like to display?'); ?></label>
          	<select id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>">
        		<?php
          	foreach ( $items as $item ) {
        			echo '<option value="' . $item . '"'
        				. ( $item == $instance['number'] ? ' selected="selected"' : '' )
        				. '>' . $item . "</option>\n"; } ?>
        	  </select>
        </p>

    		<p>
    			<label for="<?php echo $this->get_field_id('sortby'); ?>"><?php _e( 'Sort by:' ); ?></label>
    			<select name="<?php echo $this->get_field_name('sortby'); ?>" id="<?php echo $this->get_field_id('sortby'); ?>" class="widefat">
    				<option value="title"<?php selected( $instance['sortby'], 'title' ); ?>><?php _e('Post title','wp-ada'); ?></option>
    				<option value="date"<?php selected( $instance['sortby'], 'date' ); ?>><?php _e('Post data','wp-ada'); ?></option>
    				<option value="author"<?php selected( $instance['sortby'], 'author' ); ?>><?php _e( 'Post author','wp-ada'); ?></option>
    				<option value="ID"<?php selected( $instance['sortby'], 'ID' ); ?>><?php _e( 'Post ID','wp-ada'); ?></option>
    				<option value="rand"<?php selected( $instance['sortby'], 'rand' ); ?>><?php _e( 'Random' ); ?></option>
    				<option value="modified"<?php selected( $instance['sortby'], 'modified' ); ?>><?php _e( 'Modified date','wp-ada'); ?></option>
    				<option value="comment_count"<?php selected( $instance['sortby'], 'comment_count' ); ?>><?php _e( 'Popularity','wp-ada'); ?></option>
    			</select>
    		</p>

      	<p>
          <label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Category'); ?>:</label>
      		<select class="widefat" id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>">
          <option value=""><?php _e('All','wp-ada'); ?></option>
       		<?php
       		foreach ( $link_cats as $link_cat ) {
       			echo '<option value="' . ($link_cat->slug) . '"'
       				. ( $link_cat->slug == $instance['category'] ? ' selected="selected"' : '' )
       				. '>' . $link_cat->name ." (". $link_cat->count .")"."</option>\n";  } ?>
      	 </select>
        </p>

      	<p>
          <label for="<?php echo $this->get_field_id('status'); ?>"><?php _e('Pet Status','wp-ada'); ?>:</label>
      		<select class="widefat" id="<?php echo $this->get_field_id('status'); ?>" name="<?php echo $this->get_field_name('status'); ?>">
      		<option value=""><?php _e('All','wp-ada'); ?></option>
      	 	<?php
      		foreach ( $lost_sts as $lost_st ) {
      			echo '<option value="' . ($lost_st->slug) . '"'
      				. ( $lost_st->slug == $instance['status'] ? ' selected="selected"' : '' )
      				. '>' . $lost_st->name ." (". $lost_st->count .")"."</option>\n";
      		}
      		?>
      		</select>
        </p>

    <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('rss'); ?>" name="<?php echo $this->get_field_name('rss'); ?>"<?php checked( $rss ); ?> />
		<label for="<?php echo $this->get_field_id('rss'); ?>"><?php _e( 'Show RSS link','wp-ada' ); ?></label><br />
        <?php
    }

} // class ADAWidget


//Pega cats
class ADA_Categories extends WP_Widget {

	function ADA_Categories() {
		$widget_ops = array('classname' => 'ADA_Categories', 'description' => __('List pets categories with post number', 'wp-ada'));
		$this->WP_Widget('ada_categories', __('ADA Pet Categories', 'wp-ada'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		if ( !empty($instance['title']) ) {
			$title = $instance['title'];
		} else {
			if ( 'ada_categories') {
				$title = __('ADA Pet Categories', 'wp-ada');
			} else {

			}
		}
		$title = apply_filters('widget_title', $title, $instance, $this->id_base);

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
	  	echo '<div class="widget_archive"><ul>';


        $terms = get_terms('types', array('hide_empty' => 1 ));
        foreach ($terms as $term) {
        echo '<li><a href="'.get_bloginfo('url').'/types/'.$term->slug.'" title="'.$term->count.'&nbsp;'.$term->name.' ">';
        echo $term->name.'</a> ('.$term->count.') </li>';

        }


		echo "<ul></div>\n";

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		return $instance;
	}

	function form( $instance ) {

?>
	<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title') ?></label>
	<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php if (isset ( $instance['title'])) {echo esc_attr( $instance['title'] );} ?>" /></p>
	<?php
	}


}

?>
