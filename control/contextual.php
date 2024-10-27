<?php //display contextual help


function plugin_header() {
	global $post_type, $page;
	?>


<style>
<?php if ($page == 'ada-top-level-handle') : ?>
#icon-edit, .top-level-page-ada { background:transparent url('icons.png') no-repeat -110px -70px; }
<?php endif; ?>
#adminmenu li.menu-top toplevel_page_ada-top-level-handle menu-top-first {background:transparent url('<?php echo WP_PLUGIN_URL ."/".WPADA_DIR_NAME . '/control/icons.png' ;?>') no-repeat -4px -32px; }
#adminmenu #toplevel_page_ada-top-level-handle:hover div.wp-menu-image,#adminmenu #toplevel_page_ada-top-level-handle.current div.wp-menu-image {background:transparent url('<?php echo WP_PLUGIN_URL ."/".WPADA_DIR_NAME . '/control/icons.png' ;?>')  no-repeat -4px -1px; }
</style>

<style>
<?php if ($post_type == 'pet') : ?>
#icon-edit, .meta-pet { background:transparent url('<?php echo WP_PLUGIN_URL ."/".WPADA_DIR_NAME . '/control/icons.png' ;?>') no-repeat -110px -70px; }
<?php endif; ?>
#adminmenu #menu-posts-pet div.wp-menu-image{background:transparent url('<?php echo WP_PLUGIN_URL ."/".WPADA_DIR_NAME . '/control/icons.png' ;?>') no-repeat -105px -33px; }
#adminmenu #menu-posts-pet:hover div.wp-menu-image,#adminmenu #menu-posts-pet.wp-has-current-submenu div.wp-menu-image{background:transparent url('<?php echo WP_PLUGIN_URL ."/".WPADA_DIR_NAME . '/control/icons.png' ;?>')  no-repeat -105px -1px; }
</style>


<?php  }

function add_help_text($contextual_help, $screen_id, $screen) {
  //$contextual_help .= var_dump($screen); // use this to help determine $screen->id
  if ('pet' == $screen->id ) {
    $contextual_help =
      '<h3 class="ada-admin">' . __('ADA Plugin Help', 'wp-ada') . '</h3>' .
      '<p>' . __('In this screen, add a pet available for adoption, lost, found pet etc. All pets stores individual data such breed, colors, size and more.', 'wp-ada') . '</p>' .
      '<ul>' .
      '<li>' . __('Post Title - fill with the animal name','wp-ada') . '</li>' .
      '<li>' . __('Text Area - Add extended info, pictures, videos, links anything else about the animal.', 'wp-ada') . '</li>' .
      '</ul>' .
      '<p>' . __('Also, you can find some boxes that handle special informations:', 'wp-ada') . '</p>' .
      '<ul>' .
      '<li>' . __('<strong>Animal Informations</strong> - most important informations about a pet. Here you inform the type, status, size etc.', 'wp-ada') . '</li>' .
      '<li>' . __('<strong>Contact Information</strong> - if you run a site not involved in the process, you must inform where visitors should ask about this pet.', 'wp-ada') . '</li>' .
      '<li>' . __('<strong>Pet Control</strong> - helps keeping a side control and also stores process fee, if any.', 'wp-ada') . '</li>' .
      '<li>' . __('<strong>Lost & Found</strong> - questions regarding lost and found pets.', 'wp-ada') . '</li>' .
      '</ul>' .
      '<p>' . __('<strong>Note on roles and capabilities:</strong> some features such add new pet types, states etc are restrict to Authors and Administrators.', 'wp-ada') . '</p>' .
      '<p><strong>' . __('More Help with ADA plugin:', 'wp-ada') . '</strong></p>'.
      '<p>' . __('<a href="http://arquivo.tk/dev/ada" target="_blank">ADA Plugin site</a>', 'wp-ada') . '</p>' ;
  }
  return $contextual_help;
}  


?>