<?php // Hook for adding admin menus
add_action('admin_menu', 'ada_add_pages');

// action function for above hook
function ada_add_pages() {

// Add a new top-level menu (ill-advised):
add_menu_page(__('ADA','wp-ada'), __('ADA','wp-ada'), 'manage_options', 'ada-top-level-handle', 'ada_toplevel_page', WP_PLUGIN_URL ."/".WPADA_DIR_NAME . '/control/ada.png', '100' );

// Add a new top-level menu (ill-advised):
//add_submenu_page('ada-top-level-handle', __('ADA Options','wp-ada'), __('ADA Options','wp-ada'), 'manage_options', 'sub-page', 'ada_sublevel_page2');


}


// ada_toplevel_page() displays the page content for the custom Test Toplevel menu
function ada_toplevel_page() {
    include('ada_info.php');
}


//function ada_sublevel_page2() {
//    include('ada_options.php');
//}

?>