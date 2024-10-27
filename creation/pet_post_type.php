<?php

class PETPostType {

	var $labels;
	var $post_type_options;
  var $post_type_taxonomies;

	function __construct() {
		$this->PETPostType();
	}

	function PETPostType() {
		$this->labels = array(
		'name' => __('Pets','wp-ada'),
		'singular_name' => _x('Pet', 'post type singular name', 'wp-ada'),
		'add_new' => __('Add Pet','wp-ada'),
		'add_new_item' => __('Add Pet','wp-ada'),
		'edit_item' => __('Edit Pet','wp-ada'),
		'new_item' => __('New Pet','wp-ada'),
		'view_item' => __('View Pets','wp-ada'),
		'search_items' => __('Search Pets','wp-ada'),
		'not_found' =>  __('Not Found!','wp-ada'),
		'not_found_in_trash' => __('Nothing found in Trash','wp-ada'),
		'parent_item_colon' => ''
		);

		$this->post_type_options = array(
			'labels'=>$this->labels,
			'public'=>true,
			'supports' => array('title','editor','author','thumbnail','comments'),
			'hierarchical' => true,
      'menu_position' => 120,
      'has_archive' => true,
			'rewrite' => array('slug' => 'pet', 'with_front' => FALSE)
		);

	}

	function register() {
	register_post_type('pet', $this->post_type_options);
  }

}

function create_type_taxonomies() // Add new Type taxonomy, hierarchical like categories //
{
  $labels = array(
    'name' => _x( 'Pet Types', 'taxonomy general name','wp-ada'),
    'singular_name' => _x( 'Type', 'taxonomy singular name','wp-ada'),
    'search_items' =>  __( 'Search Types','wp-ada'),
    'all_items' => __( 'All Types','wp-ada'),
    'edit_item' => __( 'Edit Type','wp-ada'),
    'update_item' => __( 'Update Type','wp-ada'),
    'add_new_item' => __( 'Add New Type','wp-ada'),
    'new_item_name' => __( 'New Type Name','wp-ada'),
    'menu_name' => __( 'Types','wp-ada'),
  );

  register_taxonomy('types','pet', array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'types', 'with_front' => FALSE ),
  ));
}


function create_color_taxonomies()  // Add new Colos taxonomy, like tags //
{
  $labels = array(
    'name' => _x( 'Pet Colors','pet colors title', 'wp-ada'),
    'singular_name' => _x( 'Color', 'taxonomy singular name','wp-ada'),
    'search_items' =>  __( 'Search Colors','wp-ada'),
    'all_items' => __( 'All Colors','wp-ada'),
    'edit_item' => __( 'Edit Color','wp-ada'),
    'update_item' => __( 'Update Color','wp-ada'),
    'add_new_item' => __( 'Add New Color','wp-ada'),
    'new_item_name' => __( 'New Color Name','wp-ada'),
    'menu_name' => __( 'Colors','wp-ada'),
  );

  register_taxonomy('colors','pet', array(
    'hierarchical' => false,
    'labels' => $labels,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'colors', 'with_front' => FALSE ),
  ));
}

function create_state_taxonomy()  // Add new Colos taxonomy, like tags //
{
  $labels = array(
    'name' => _x( 'State','pet place', 'wp-ada'),
    'singular_name' => _x( 'State', 'taxonomy singular name','wp-ada'),
    'menu_name' => __( 'States','wp-ada'),
  );

  register_taxonomy('state','pet', array(
    'hierarchical' => false,
    'labels' => $labels,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'state', 'with_front' => FALSE ),
  ));
}


// Special Status taxonomy creation //

function create_status_taxonomy() {
	if (!taxonomy_exists('status')) {

    $labels = array('name' => _x( 'Pet Status', 'pet status title','wp-ada'),'menu_name' => __( 'Status','wp-ada'), );
	  register_taxonomy( 'status', 'pet', array( 'hierarchical' => true, 'labels' => $labels, 'query_var' => 'status', 'rewrite' => array( 'slug' => 'status', 'with_front' => FALSE ) ) );
	}
}


function add_pet_status_box() {
  remove_meta_box('tagsdiv-status','pet','high');
    remove_meta_box('tagsdiv-colors','pet','high');
    remove_meta_box('typesdiv','pet','high');
    remove_meta_box('tagsdiv-st','pet','high');
}

function add_status_menus() {

	if ( ! is_admin() )
	return;
	add_action('admin_menu', 'add_pet_status_box');
}
add_status_menus();



function ada_parse_query_useronly( $wp_query ) {
    if ( strpos( $_SERVER[ 'REQUEST_URI' ], '/wp-admin/edit.php' ) !== false ) {
        if ( !current_user_can( 'level_10' ) ) {
            global $current_user;
            $wp_query->set( 'author', $current_user->id );
        }
    }
}

add_filter('parse_query', 'ada_parse_query_useronly' );


add_action('manage_users_columns','site_colluns');
function site_colluns($column_headers) {
    unset($column_headers['posts']);
    $column_headers['custom_posts'] = 'Publica&ccedil;&otilde;es';
    return $column_headers;
}

add_action('manage_users_custom_column','site_colluns_gerencia',10,3);
function site_colluns_gerencia($custom_column,$column_name,$user_id) {
    if ($column_name=='custom_posts') {
        $counts = _contagem_get_author_post_type();
        $custom_column = array();
        if (isset($counts[$user_id]) && is_array($counts[$user_id]))
            foreach($counts[$user_id] as $count) {
                $link = admin_url() . "edit.php/?post_type=" . $count['type']. "&author=".$user_id;
                // admin_url() . "edit.php?author=" . $user->ID;
                $custom_column[] = "\t<tr><th style='font:normal 12px arial'>{$count['label']}</th><td><a href={$link} title='Ver {$count['label']} de ".get_the_author_meta('user_login',$user_id)."'>{$count['count']}</a></td></tr>";
            }
        $custom_column = implode("\n",$custom_column);
        if (empty($custom_column))
            $custom_column = "<th style='font:normal 12px arial'>Nenhuma publica&ccedil;&atilde;o</th>";
        $custom_column = "<table>\n{$custom_column}\n</table>";
    }
    return $custom_column;
}

function _contagem_get_author_post_type() {
    static $counts;
    if (!isset($counts)) {
        global $wpdb;
        global $wp_post_types;
        $sql = <<<SQL
        SELECT
        post_type,
        post_author,
        COUNT(*) AS post_count
        FROM
        {$wpdb->posts}
        WHERE 1=1
        AND post_type NOT IN ('revision','nav_menu_item')
        AND post_status IN ('publish','pending', 'draft')
        GROUP BY
        post_type,
        post_author
SQL;
        $posts = $wpdb->get_results($sql);
        foreach($posts as $post) {
            $post_type_object = $wp_post_types[$post_type = $post->post_type];
            if (!empty($post_type_object->label))
                $label = $post_type_object->label;
            else if (!empty($post_type_object->labels->name))
                $label = $post_type_object->labels->name;
            else
                $label = ucfirst(str_replace(array('-','_'),' ',$post_type));
            if (!isset($counts[$post_author = $post->post_author]))
                $counts[$post_author] = array();
            $counts[$post_author][] = array(
                'label' => $label,
                'count' => $post->post_count,
                'type' => $post->post_type,
                );
        }
    }
    return $counts;
}


?>