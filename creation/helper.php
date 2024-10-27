<?php

function add_pet_data_style($result) {
 wp_enqueue_style('ADA_pet_data_style', WP_PLUGIN_URL ."/".WPADA_DIR_NAME."/creation/presentation/ada-styles.css");
}

function rss_post_thumbnail($content) {
  	global $post;

      if ($post->post_type == 'pet')
      {
       include('data_pet.php');
      }
      else {
       the_excerpt();
      };

}

function ada_pet() {
      global $post;

      if ($post->post_type == 'pet')
      {
        include('data_pet.php');
      }

      }

// The filters

add_filter('get_header','add_pet_data_style');
add_filter('the_excerpt_rss', 'rss_post_thumbnail');
add_filter('the_content_feed', 'rss_post_thumbnail');




?>