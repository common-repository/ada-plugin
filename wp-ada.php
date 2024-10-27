<?php
/*
Plugin Name: ADA
Text Domain: wp-ada
Domain Path: /lang
Plugin URI: http://arquivo.tk/dev/ada
Description: ADA (Adopting Animals) offers a neat and easy way to keep pets for adoptions or lost pets database with special info for every pet.
Version: 1.8
Author: Diana K. Cury
Author URI: http://arquivo.tk
*/


    //include component controllers
    include('creation/pet_post_type.php');
    include('creation/helper.php');
    include('control/pages.php');
    include('control/contextual.php');
    include('control/widgets.php');


class WP_ADA {
	function __construct() {
		$this->WP_ADA();
	}

	function WP_ADA() {
		global $wp_version;

		// plugin urls/paths
		define('WPADA_FILE_PATH', dirname(__FILE__));
		define('WPADA_DIR_NAME', basename(WPADA_FILE_PATH));

    add_theme_support( 'post-thumbnails' );
    add_image_size( 'ada_pet', 300, 300, true );
    add_image_size( 'ada_thumb', 150, 150, true );
	  }
  }

  function meta_box_script() {
   include('metabox/class-advanced-meta-box.php');
   include('metabox/meta-box-info.php');
   include('metabox/meta-box-contact.php');
   include('metabox/meta-box-extra.php');
   include('metabox/meta-box-lost.php');
   $base_url = WP_PLUGIN_DIR;
   $base_url = str_replace(WP_CONTENT_DIR, WP_CONTENT_URL, $base_url);
   wp_enqueue_style('ada-metabox', $base_url . '/wp-ada/control/ada-styles.css');
  }


  function ada_shortcode() {
    do_action('wp_head','ada_form');
    include('control/form.php');
  }

  function ada_form() {
    include('control/form-action.php');
  }
  add_filter('get_header','ada_form'); 


  //Starts everything
  add_action( 'init', 'adafunc_setup',1 );

  function adafunc_setup(){
    //Load the text domain, first of all
    load_plugin_textdomain('wp-ada', true, dirname( plugin_basename( __FILE__ ) ) . '/lang' );

    //Enables Pet and Lost Types
    $PETPostType = new PETPostType();

		//Register the post type
		add_action('init', array($PETPostType,'register'),3 );

    //Register pet type taxonomies
    add_action('init', 'create_type_taxonomies', 5 );
    add_action('init', 'create_color_taxonomies', 6 );
    add_action('init', 'create_status_taxonomy', 7 );
    add_action('init', 'create_state_taxonomy', 8 );

    //Metabox script
    add_action ('init', 'meta_box_script', 10);

    //Contextual Help
    add_action('admin_head', 'plugin_header');
    add_action('contextual_help', 'add_help_text', 10, 3 );

    //Widgets
    add_action('widgets_init', create_function('', 'return register_widget("ADA_Widget_Tagcloud");'));
    add_action('widgets_init', create_function('', 'return register_widget("ADA_Widget_Searchform");'));
    add_action('widgets_init', create_function('', 'return register_widget("ADAWidget");'));
    add_action('widgets_init', create_function('', 'return register_widget("ADA_Categories");'));
    add_shortcode( 'ada', 'ada_shortcode' );
  }


$WP_ADA = new WP_ADA();


?>