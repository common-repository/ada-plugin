<?php

// For future implementation, this helper can filter content and add special data files automatically, i.e.
// user don't need to add code tho their themes.
// Why not in use: some themes call the_content in some strange circunstances messing the output. I don't know how to solve this yet :,(

add_filter('get_header','add_pet_data_style');
function add_pet_data_style($result) {
 wp_enqueue_style('pet_data_style', WP_PLUGIN_URL ."/".WPADA_DIR_NAME."/creation/presentation/styles.css");
}


add_filter('the_content','add_pet_data');
function add_pet_data($result) {

    global $post;

     if ($post->post_type == 'pet') {
     include('data_pet.php');
     }
     return $result;
}

add_filter('the_content','add_lost_data');
function add_lost_data($result) {

    global $post;

     if ($post->post_type == 'lost') {
     include('data_lost.php');
     }
     return $result;
}




?>