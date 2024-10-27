<?php
/**
 * Create meta box for editing pages in WordPress
 * Extends Meta Box class by Rilwis
 * Compatible with custom post types since WordPress 3.0
 * Support input types: text, textarea, checkbox, checkbox list, radio box, select, wysiwyg, file, image, date, time, color, embed, date-select, taxonomy, parent
 *
 * @author Funkatron <funkatronic@gmail.com@gmail.com>
 * @link 
 * @example 
 * @version: 1.6
 *
 * @license GNU General Public License
 */
/*
 *Changes: 1.6:	-Added date-select, embed, taxonomy and parent
 				-Added nesting of fields to have them on the same line
 				-Moved calls to show_field_begin and show_field_end to accomedate nesting
				-Added get_meta_field system to allow custom retrieval of meta data on a type basis
				-Added load_scripts and load_styles functions for addition of custom scripts and styles.  
				-Most scripts now relegated to seperate class-advanced-meta-box.js file
				-Added get_field_name and get_field_id functions for custom names and id's for fields
				-Changed imaging system to match closer to Rilwis's original code.  Clicking images now leads to edit page
				-Option Ajax updating added
				-multiple textboxes via multiple tag added
				-Add new terms to taxonomies
 */
/**
 * Advanced Meta Box class
 */
require_once("meta-box.php");

class Advanced_Meta_Box extends RW_Meta_Box {

	function __construct($metabox) {
		if (!is_admin()) return;
		parent::__construct($metabox);
 	add_action('admin_print_styles', array(__CLASS__, 'js_css'));
		add_action('wp_ajax_'.$this->_meta_box['id'].'_update', array(&$this, 'update'),5, 1);
		$this->check_field_embed();
		$this->check_field_taxonomy();
	}
	
	function check_field_taxonomy() {
		add_action('wp_ajax_'.$this->_meta_box['id'].'_new_term', array(&$this, 'add_term'),5, 1);
	}
	
	function add_term() {
		if (!isset($_POST['data'])) die(1);
		//check_admin_referer('rw_add_term');
		
		$post_id = $_POST['post_id'];
		$tax = $_POST['tax'];
		$term = $_POST['data'];
		$parent = $_POST['parent'];

		wp_insert_term($term, $tax, array('parent'=>$parent));
		
		$field = false;
		foreach($this->_fields as $f) {
			if($f['type'] === 'taxonomy' && $f['options']['taxonomy'] === $tax) {
				$field = $f;
				break;	
			}
		}	
		if(!$field) die(1);
		$meta = wp_get_object_terms($post_id, $tax);

		if(is_wp_error($meta)) die(1);
		if (!is_array($meta)) $meta = (array) $meta;	
		$this->walk_taxonomy($field,$meta,$post_id, false);
		die(0);	
	}
	/******************** BEGIN EMBED **********************/
	function check_field_embed() {
		if (!$this->has_field('embed')) return;	

		//AJAX calls
		add_action('wp_ajax_advanced_show_embed', array(&$this, 'show_embed'));	
		add_action('wp_ajax_advanced_update_images', array(&$this, 'update_images'));
	}
	function is_embedible($url) {
		//Include oEmbed class
		require_once( ABSPATH . WPINC . '/class-oembed.php' );

		//Init
		$oembed = _wp_oembed_get_object();
		$providers = $oembed->providers;
		
		//Check if provider
		foreach ( $providers as $matchmask => $data ) {
			list( $providerurl, $regex ) = $data;

			// Turn the asterisk-type provider URLs into regex
			if ( !$regex )
				$matchmask = '#' . str_replace( '___wildcard___', '(.+)', preg_quote( str_replace( '*', '___wildcard___', $matchmask ), '#' ) ) . '#i';

			if ( preg_match( $matchmask, $url ) ) {
				return true;
			}
		}
		
		return false;
		
	}
	
	function get_embed_html($url) {
		require_once( ABSPATH . WPINC . '/class-oembed.php' );
		$oembed = _wp_oembed_get_object();
		return $oembed->get_html($url);		
	}
	//EMBED AJAX CALL
	function show_embed() {
		if (!isset($_POST['data'])) die(1);
		$result = $this->get_embed_html($_POST['data']);
		if(!empty($result)){
			echo $result;
		} else {
			die("Stream not embedible");	
		}
		die();
	}
	
	function check_embedible() {
		if (!isset($_POST['data'])) die(1);
		if($this->is_embedible($_POST['data'])) {
			echo "";
		} else {
			
		}
	}
	/******************** END EMBED **********************/
	
	/******************** BEGIN SCRIPTS AND STYLE**********************/
  function js_css() {
		parent::js_css();
		// change '\' to '/' in case using Windows
		$content_dir = str_replace('\\', '/', WP_CONTENT_DIR);
		$script_dir = str_replace('\\', '/', dirname(__FILE__));
		// get URL of the directory of current file, this works in both theme or plugin
		$base_url = str_replace($content_dir, WP_CONTENT_URL, $script_dir);
		wp_enqueue_script('advanced-metabox',$base_url .'/class-advanced-meta-box.js');
		wp_enqueue_style('advanced-meta-box', $base_url . '/class-advanced-meta-box.css');	
	}
	
	function insert_images() {
		if (!isset($_POST['rw-insert']) || empty($_POST['attachments'])) return;

		check_admin_referer('media-form');

		$nonce = wp_create_nonce('rw_ajax_delete');
		$post_id = $_POST['post_id'];
		$id = $_POST['field_id'];

		// modify the insertion string
		$html = '';
		foreach ($_POST['attachments'] as $attachment_id => $attachment) {
			if (empty($attachment['selected']) || empty($attachment['url'])) continue;
			$src = wp_get_attachment_image_src($attachment_id,'thumbnail');
			$attachment_url = $src[0];
			$post_link = get_edit_post_link($attachment_id);
			
			$li = "<li id='item_$attachment_id'>";
			$li .= "<a href='{$post_link}'>";
			$li .= "<img src='{$attachment_url}' />";
			$li .= "</a>";
			$li .= "<a title='" . __('Delete this image') . "' class='rw-delete-file' href='#' rel='{$nonce}|{$post_id}|{$id}|{$attachment_id}'>" . __('Delete') . "</a>";
			$li .= "<input type='hidden' name='{$id}[]' value='$attachment_id' />";
			$li .= "</li>";
			$html .= $li;
		}
		media_send_to_editor($html);
	}

	/******************** END SCRIPTS AND STYLE**********************/
	
	/******************** BEGIN META BOX PAGE **********************/
	// Add meta box for multiple post types.  Extended to remove taxonomy metaboxes
	function add() {
		//collect taxonomies 
		$tax_boxes = $this->collect_tax_boxes($this->_fields);
		foreach ($this->_meta_box['pages'] as $page) {
			add_meta_box($this->_meta_box['id'], $this->_meta_box['title'], array(&$this, 'show'), $page, $this->_meta_box['context'], $this->_meta_box['priority']);
			foreach ((array)$tax_boxes as $box) {
				remove_meta_box('tagsdiv-'.$box,$page,'side');
				remove_meta_box($box.'div' ,$page,'side');						
			}
		}
	}
	// Callback function to show fields in meta box
	function show() {
		global $post;
		$nonce = wp_create_nonce( 'advanced_meta_box_nonce' );
		wp_nonce_field(basename(__FILE__), 'advanced_meta_box_nonce');
		echo '<table class="form-table">';

		foreach ($this->_fields as $field) {
			echo "<tr>";
			$meta = $this->get_meta($post->ID, $field, !$field['multiple']);			
			// call separated methods for displaying each type of field
			$this->show_field_begin($field, $meta, $post->ID);
			$this->show_field($field, $meta, $post->ID);
			if(isset($field['fields'])) {
				foreach ($field['fields'] as $f) {
					$meta = get_post_meta($post->ID, $f['id'], !$f['multiple']);
					echo '<label for="', $f['id'], '">', $f['name'], '</label>';
					$this->show_field($f, $meta, $post->ID);
					echo "\t";
				}
			}
			$this->show_field_end($field, $meta);
			echo "</tr>";
		}
		if($this->_meta_box['update']) {
			echo "<tr><td colspan = '2'>";
			echo '<a href="#" class="update_data button-secondary" rel = "'.$this->_meta_box['id'].'_update|',$nonce,'|',$post->ID,'|',$post->post_type,'">'.__('Update').'</a>';
			echo '<a href="#" class="clear_data button-secondary">'.__('Clear').'</a>';
			echo "</td></tr>";
		}
		echo '</table>';
	}
	/******************** END META BOX PAGE **********************/
	
	/******************** BEGIN GET FIELD NAME AND ID  **********************/
	function get_field_name($field, $post_id) {
		$name = $field['id'] . (($field['multiple'] || $field['type'] === 'taxonomy') ? "[]" : "");
		return $name;
	}
	
	function get_field_id($field, $post_id) {
		return $field['id'];
	}
	/******************** END GET FIELD NAME AND ID **********************/
	
	/******************** BEGIN GET META  **********************/
	
	//Generic meta retrieval
	function get_meta($post_id, $field, $single = true) {
		$meta_func = 'get_meta_'.$field['type'];
		if (method_exists($this, $meta_func)) {
			$meta = call_user_func(array(&$this, 'get_meta_' . $field['type']), $post_id, $field);
		} else {
			$meta = get_post_meta($post_id, $field['id'], $single);
		}
		$meta = !empty($meta) ? $meta : $field['std'];
		return $meta;
	}
	
	//Custom meta retrieval for taxonomies
	function get_meta_taxonomy($post_id, $field) {		
		$meta = wp_get_object_terms($post_id, $field['options']['taxonomy']);
		if(is_wp_error($meta)){
			return;
		} else {
			return $meta;
		}
	}
	//Custom meta retrieval for images
	/*function get_meta_image($post_id, $field) {
		global $wpdb;
		$query = "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'attachment' AND post_parent = %d ORDER BY menu_order; ";
		return $wpdb->get_col($wpdb->prepare($query,$post_id));
	}
	//Custom meta retrieval for files
	function get_meta_file ($post_id,$field) {
		return get_meta_image($post_id, $field);
	}*/
	/******************** END GET META  **********************/
	
	/******************** BEGIN META BOX FIELDS **********************/
	function show_field($field, $meta, $post_id='') {
		echo "<input type='hidden' name='rw_field_info[]' class='adv_field_info' value = '{$field['id']}' />";
		call_user_func(array(&$this, 'show_field_' . $field['type']), $field, $meta, $post_id);
	}
	
	function show_field_begin($field, $meta, $post_id) {
		echo "<th class='rw-label'><label for='",$this->get_field_id($field,$post_id),"'>{$field['name']}</label></th><td class='rw-field'>";
	}

	function show_field_end($field, $meta) {
		echo "<div style='clear: both; margin-top: 10px'>{$field['desc']}</div></td>";
	}
	
	function show_field_text($field, $meta, $post_id='') {		
		$id = $this->get_field_id($field, $post_id);
		$name = $this->get_field_name($field, $post_id);
		$size = (isset($field['length']) ? $field['length'] : "30' style='width:97%");
		if($field['multiple']) {
			if (!is_array($meta)) $meta = (array) $meta;
			echo "<ul class='rw-list'>";
			foreach ($meta as $m) {
				echo "<li>";		
				echo "<input type='text' class = 'rw-text {$field['id']}' name='{$name}' id='{$id}' value='{$m}' size='{$size}' ",  (isset($field['maxlength']) ? "maxlength = '{$field['maxlength']}'" : '');
				echo "/>";
				echo "<a href='#' class='button-secondary rw-list-delete'>Delete</a>";
				echo "</li>";
			}
			echo "</ul>";
			echo "<a href='#' class='button-secondary rw-list-add'>Add</a>";
		} else {
			echo "<input type='text' class = 'rw-text {$field['id']}' name='{$name}' id='{$id}' value='{$meta}' size='{$size}' ",  (isset($field['maxlength']) ? "maxlength = '{$field['maxlength']}'" : '');
			echo "/>";
		}
	}

	function show_field_textarea($field, $meta, $post_id='') {		
		echo "<textarea class = 'rw-textarea {$field['id']} large-text' name='",$this->get_field_name($field, $post_id),"' id = '",$this->get_field_id($field, $post_id),"' cols='60' rows='15' style='width:97%'>$meta</textarea>";		
	}

	function show_field_select($field, $meta, $post_id='') {
		if (!is_array($meta)) $meta = (array) $meta;
		
		echo "<select class = 'rw-select {$field['id']}' name='",$this->get_field_name($field, $post_id), ($field['multiple'] ? "[]'  multiple='multiple'" : "'") ,"  id = '",$this->get_field_id($field, $post_id),"' >";
		foreach ($field['options'] as $key => $value) {
			echo "<option value='$key'" . selected(in_array($key, $meta), true, false) . ">$value</option>";
		}
		echo "</select>";
		
	}

	function show_field_radio($field, $meta, $post_id='') {		
		foreach ($field['options'] as $key => $value) {
			echo "<input type='radio' class='rw-radio {$field['id']}' name='",$this->get_field_name($field, $post_id),"' value='$key'" . checked($meta, $key, false) . " /> $value ";
		}		
	}

	function show_field_checkbox($field, $meta, $post_id='') {
		echo "<input type='checkbox' class='rw-checkbox {$field['id']}'  name='",$this->get_field_name($field, $post_id),"' id = '",$this->get_field_id($field, $post_id),"'" . checked(!empty($meta), true, false) . " />";
	}

	function show_field_wysiwyg($field, $meta, $post_id='') {
		echo "<textarea class='rw-wysiwyg {$field['id']} theEditor large-text' name='",$this->get_field_name($field, $post_id),"'  cols='60' rows='15' style='width:97%'>{$meta}</textarea>";
	}

	function show_field_file($field, $meta, $post_id='') {
		if (!is_array($meta)) $meta = (array) $meta;
		echo "{$field['desc']}<br />";

		if (!empty($meta)) {
			$nonce = wp_create_nonce('rw_ajax_delete');
			echo '<div style="margin-bottom: 10px"><strong>' . __('Uploaded files') . '</strong></div>';
			echo '<ol class="rw-upload">';
			foreach ($meta as $att) {
				// if (wp_attachment_is_image($att)) continue; // what's image uploader for?
				echo "<li>" . wp_get_attachment_link($att, '' , false, false, ' ') . " (<a class='rw-delete-file' href='#' rel='$nonce|{$post_id}|{$field['id']}|$att'>" . __('Delete') . "</a>)</li>";
			}
			echo '</ol>';
		}

		// show form upload
		echo "<div style='clear: both'><strong>" . __('Upload new files') . "</strong></div>
			<div class='new-files'>
				<div class='file-input'><input type='file' name='{$field['id']}[]' /></div>
				<a class='rw-add-file' href='#'>" . __('Add more file') . "</a>
			</div>";
	}

	function show_field_image($field, $meta, $post_id='') {
		global $wpdb;

		if (!is_array($meta)) $meta = (array) $meta;
		$name = $this->get_field_name($field, $post_id);

		$nonce_delete = wp_create_nonce('rw_ajax_delete');
		$nonce_sort = wp_create_nonce('rw_ajax_reorder');

		echo "<input type='hidden' class='rw-images-data' value='{$post_id}|{$field['id']}|$nonce_sort' />
			  <ul class='rw-images rw-upload' id='rw-images-{$field['id']}'>";

		// re-arrange images with 'menu_order', thanks Onur
		if (!empty($meta)) {
			$meta = implode(',', $meta);
			$images = $wpdb->get_col("
				SELECT ID FROM $wpdb->posts
				WHERE post_type = 'attachment'
				AND ID in ($meta)
				ORDER BY menu_order ASC
			");
			foreach ($images as $image) {
				$src = wp_get_attachment_image_src($image,'thumbnail');
				$src = $src[0];
				$post_link = get_edit_post_link($image);

				echo "<li id='item_$image'>
						<a href='{$post_link}'>
						<img src='$src' />
						</a>
						<a title='" . __('Delete this image') . "' class='rw-delete-file' href='#' rel='$nonce_delete|{$post_id}|{$name}|$image'>" . __('Delete') . "</a>
						<input type='hidden' class = '{$field['id']}' name='{$name}' value='$image' />
					</li>";
			}
		}
		echo '</ul>';

		echo "<a href='#' class='rw-upload-button button' rel='{$post_id}|{$field['id']}'>" . __('Add more images') . "</a>";
	}

	function show_field_color($field, $meta, $post_id='') {
		if (empty($meta)) $meta = '#';
		
		echo "<input type='text' name='",$this->get_field_name($field, $post_id),"' class = 'rw-color {$field['id']}' id = '",$this->get_field_id($field, $post_id),"' value='$meta' size='8' />
			  <a href='#' class='rw-color-select' rel='{$field['id']}'>" . __('Select a color') . "</a>
			  <div style='display:none' class='rw-color-picker' rel='{$field['id']}'></div>";
		
	}

	function show_field_checkbox_list($field, $meta, $post_id='') {
		if (!is_array($meta)) $meta = (array) $meta;
		
		$html = array();
		foreach ($field['options'] as $key => $value) {
			$html[] = "<input type='checkbox' class='rw-checkbox_list {$field['id']}' name='".$this->get_field_name($field, $post_id)."[]' value='$key'" . checked(in_array($key, $meta), true, false) . " /> $value";
		}
		echo implode('<br />', $html);
		
	}
	
	//Show date_select
	function show_field_date_select($field, $meta, $post_id='') {
		echo "<input type='hidden' name='rw_field_info[]' class='adv_field_info' value = '{$field['id']}-month' />";
		echo "<input type='hidden' name='rw_field_info[]' class='adv_field_info' value = '{$field['id']}-day' />";
		echo "<input type='hidden' name='rw_field_info[]' class='adv_field_info' value = '{$field['id']}-year' />";
		if($meta) {
			$meta_month = date('n', $meta);
			$meta_day = date("j", $meta);
			$meta_year = date("Y", $meta);
		} else {
			$meta_month = $meta_day = $meta_year =  NULL;
		}
		$years = range(isset($field['year_range']['max']) ? $field['year_range']['max'] :(int) date('Y'),
			 isset($field['year_range']['min']) ? $field['year_range']['min'] : 1950);
		$months = array(1 => 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
		$days = range(1,31);
		echo '<select class="rw-date-month '. $field['id'].'-month" name="'.$this->get_field_name($field, $post_id).'[month]">';
		echo '<option value = "">Month</option>';
		for ($month =1; $month<=12; $month++) {
				echo '<option value="', $month, '"', (int) $meta_month == (int) $month ? ' selected="selected"' : '', '>', $months[$month], '</option>';
		}
		echo '</select>';
		echo '<select class="rw-date-day '. $field['id'].'-day" name="', $this->get_field_name($field, $post_id), '[day]">';
		echo '<option value = "">Day </option>';
		for ($day = 1; $day <=31; $day++) {
				echo '<option value="', $day, '"', (int) $meta_day == (int) $day ? ' selected="selected"' : '', '>', $day, '</option>';
		}
		echo '</select>';
		echo '<select class="rw-date-year '. $field['id'].'-year" name="', $this->get_field_name($field, $post_id), '[year]">';
		echo '<option value = "">Year </option>';
		foreach ($years as $year) {
				echo '<option value="', $year, '"', (int) $meta_year == (int) $year ? ' selected="selected"' : '', '>', $year, '</option>';
		}
		echo '</select>';
	}
	
	// show taxonomy list
	function show_field_taxonomy($field, $meta, $post_id='') {
		if (!is_array($meta)) $meta = (array) $meta;	
		echo "<div class='rw-terms'>";
		$this->walk_taxonomy($field,$meta,$post_id, false);
		echo "</div>";
		$action = $this->_meta_box['id'] . '_new_term';
		$nonce = wp_create_nonce('rw_add_term');
		
		if($field['options']['add_new']) {
			echo "<div class='hidden rw-new-term'>";
			echo "<label>". __('Name')." <input type='text' class='rw-new-term' name='rw-new-term[]' /></label><br />";
			if(is_taxonomy_hierarchical($field['options']['taxonomy']))			{
				echo "<label>Parent: <select class='rw-tax-parent' >";
				echo '<option value = "">None</option>';
				$this->walk_taxonomy_option($field['options']['taxonomy'],0,0);
				echo "</select></label>";
			}
			echo "<div style='margin-top: 10px; margin-bottom: 10px;'><a href='#' rel='{$action}|{$nonce}|{$post_id}|{$field['options']['taxonomy']}' class='button-secondary rw-add-new-term'>". __('Add') ."</a>";
			echo "<a href='#'  class='button-secondary rw-term-cancel'>". __('Cancel') . "</a>";
			echo "</div></div>";
			echo "<a href='#' class='rw-show-add-term'>". __('Add New Color', 'wp-ada'). "</a>";
		}
				
	}
	function walk_taxonomy($field, $meta, $post_id='', $disabled = false) {
		$options = $field['options'];
		$terms = get_terms($options['taxonomy'], $options['args']);
		$selected_child = null;
		// checkbox_list
		if ('checkbox_list' == $options['type']) {
			foreach ($terms as $term) {
				echo "<input type='checkbox' class = 'rw-taxonomy {$field['id']}' name='",$this->get_field_name($field, $post_id),"'  value='$term->slug'";
				$selected_child = false;
				if(!is_wp_error($meta) && !empty($meta)) {
					foreach($meta as $name) {
						if(!strcmp($term->slug, $name->slug)) {
							 echo 'checked="checked"';
							 $selected_child = true;
							 continue;
						} 
					}
				}
				if($disabled) {
					echo ' disabled="disabled"';	
				}
				echo " /> $term->name<br/>";
				if(isset($field['options']['child'])) {
					$f = $field;
					$child = $field['options']['child'];
					$f['desc'] = isset($child['desc']) ? $child['desc'] : '' ;
					if(isset($child['child'])) {
						$f['options']['child'] = $child['child'];
					} else {
						unset($f['options']['child']);
					}
					if(isset($child['type'])) {
						$f['options']['type'] = $child['type'];
					} else {
						$f['options']['type'] = $field['type'];	
					}
					$f['options']['args']['parent'] = $term->term_id;
					echo '<div id = "term_'.$term->slug.'" class="tax-child', ($selected_child ? '' : ' hidden'),'" >';
					$this->walk_taxonomy($f,$meta, $post_id, ($selected_child ? false : true));
					echo '</div>';				
				}
			}
		}
		// select
		else {
			echo "<select class = 'rw-taxonomy {$field['id']}' name='",$this->get_field_name($field, $post_id),"'" . ($field['multiple'] ? " multiple='multiple' style='height:auto'" : "");
			if($disabled) {
				echo ' disabled="disabled"';	
			}
			echo " >";
			if($field['options']['optional']) {
				echo "<option value=''>None</option>";
			}
			foreach ($terms as $term) {
				echo "<option value='$term->slug'" ; selected(in_array($term->slug, $meta), true, false); 
				if(!is_wp_error($meta) && !empty($meta)) {
					foreach($meta as $name) {
						if(!strcmp($term->slug, $name->slug)) {
							 echo 'selected="selected"';
							 $selected_child = $name->slug;
							 continue;
						}
					}
				}
				echo  ">$term->name</option>";
			}
			echo "</select>";
			if(isset($field['options']['child'])) {
				$f = $field;
				$child = $field['options']['child'];
				$f['desc'] = isset($child['desc']) ? $child['desc'] : '' ;
				if(isset($child['child'])) {
					$f['options']['child'] = $child['child'];
				} else {
					unset($f['options']['child']);
				}
				if(isset($child['type'])) {
					$f['options']['type'] = $child['type'];
				} else {
					$f['options']['type'] = $field['type'];	
				}
				foreach($terms as $term) {
					$f['options']['args']['parent'] = $term->term_id;
					echo '<div id = "term_'.$term->slug.'" class="tax-child',((!strcmp($term->slug, $selected_child))? '' : ' hidden'),'" >';
					$this->walk_taxonomy($f,$meta, $post_id, ((!strcmp($term->slug, $selected_child))? false : true));
					echo '</div>';
				}
				
			}
		}	
	}
	
	function walk_taxonomy_option($taxonomy, $parent = 0, $level = 0) {
		$tabs = '';
		for($i = 0; $i < $level; $i++) {
			$tabs .= '&nbsp;&nbsp;&nbsp;';	
		}
		$terms = get_terms($taxonomy, array('parent'=>$parent, 'orderby' => 'slug', 'hide_empty' => false)); 
		
		if(!is_wp_error($terms) && !empty($terms)) {
			foreach($terms as $term) {
				echo "<option value='{$term->term_id}'>{$tabs}{$term->name}</option>" ;
				$this->walk_taxonomy_option($taxonomy,$term->term_id,$level+1);
			}
			
		}
		
	}
	//Creates a select box to choose a parent of a particular post type
	function show_field_parent($field, $meta, $post_id='') {
		//regular meta data only.  Do not use for an attachment metabox
		global $post;
		global $wpdb;
		$query = "SELECT ID, post_title FROM $wpdb->posts WHERE post_type = '{$field['parent_type']}' AND post_status = 'publish' ORDER BY post_title";
		$results = $wpdb->get_results($query, OBJECT);
		echo '<select name="parent_id" id="parent_id" class="rw-parent ',$field['id'],'">';
		if($field['optional']) {
			echo '<option value = "0">None</option>';
		}
		foreach ($results as $r) {
			echo '<option value="', $r->ID, '"', $r->ID == $post->post_parent ? ' selected="selected"' : '', '>', $r->post_title, '</option>';
		}
		echo '</select>';
	}
	
	function show_field_date($field, $meta) {
		echo "<input type='text' class='rw-date {$field['id']}' name='",$this->get_field_name($field, $post_id),"' id='",$this->get_field_id($field, $post_id),"' rel='{$field['format']}' value='$meta' size='30' />";
	}

	function show_field_time($field, $meta) {
		echo "<input type='text' class='rw-time {$field['id']}' name='",$this->get_field_name($field, $post_id),"' id='",$this->get_field_id($field, $post_id),"' rel='{$field['format']}' value='$meta' size='30' />";
	}
	
	function show_field_embed($field, $meta, $post_id='') {	
		$nonce = wp_create_nonce('veda_show_embed');	
		echo "<input type='text' name='",$this->get_field_name($field, $post_id),"' id='",$this->get_field_id($field, $post_id),"' class='embed_url rw-embed {$field['id']}' value='$meta' size='"; 
		if(isset($field['length']))
			echo "{$field['length']}' ";
		else 
			echo "30' style='width:97%' ";
		if(isset($field['maxlength']))
			echo "maxlength = '{$field['maxlength']}'";
		echo "/>";
		echo "<input type='hidden' value='{$nonce}' name='embed_nonce' class='embed_nonce' />";

		echo "<div class = 'veda_control_embed' style='margin-top: 10px; margin-bottom: 10px;'>";
		echo "<a  href='#' class='veda_view_embed button'>View Embed </a>";
		echo "<a  href='#' class='veda_remove_embed button'>Hide Embed </a>";
		echo "</div>";			
		
		echo "<div class='veda_embed'>";
		echo "</div>";		
	}
	/******************** END META BOX FIELDS **********************/
		
	/******************** BEGIN META BOX SAVE **********************/
	// Save data from meta box
	function save($post_id) {
		$post_type_object = get_post_type_object($_POST['post_type']);

		if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)						// check autosave
		|| (!isset($_POST['post_ID']) || $post_id != $_POST['post_ID'])			// check revision
		|| (!in_array($_POST['post_type'], $this->_meta_box['pages']))			// check if current post type is supported
		|| (!check_admin_referer(basename(__FILE__), 'advanced_meta_box_nonce'))		// verify nonce
		|| (!current_user_can($post_type_object->cap->edit_post, $post_id))) {	// check permission
			return $post_id;
		}

		foreach ($this->_fields as $field) {
			$name = $field['id'];
			$type = $field['type'];
			$old  = $this->get_meta($post->ID, $field);
			$new = isset($_POST[$name]) ? $_POST[$name] : ($field['multiple'] ? array() : '');

			// validate meta value
			if (class_exists('RW_Meta_Box_Validate') && method_exists('RW_Meta_Box_Validate', $field['validate_func'])) {
				$new = call_user_func(array('RW_Meta_Box_Validate', $field['validate_func']), $new);
			}

			// call defined method to save meta value, if there's no methods, call common one
			$save_func = 'save_field_' . $type;
			if (method_exists($this, $save_func)) {
				call_user_func(array(&$this, 'save_field_' . $type), $post_id, $field, $old, $new);
			} else {
				$this->save_field($post_id, $field, $old, $new);
			}
			
			if(isset($field['fields'])) {
				foreach ($field['fields'] as $f) {
					$name = $f['id'];
					$type = $f['type'];
					$old  = $this->get_meta($post->ID, $f);
					$new = isset($_POST[$name]) ? $_POST[$name] : ($f['multiple'] ? array() : '');
		
					// validate meta value
					if (class_exists('RW_Meta_Box_Validate') && method_exists('RW_Meta_Box_Validate', $f['validate_func'])) {
						$new = call_user_func(array('RW_Meta_Box_Validate', $f['validate_func']), $new);
					}
		
					// call defined method to save meta value, if there's no methods, call common one
					$save_func = 'save_field_' . $type;
					if (method_exists($this, $save_func)) {
						call_user_func(array(&$this, 'save_field_' . $type), $post_id, $f, $old, $new);
					} else {
						$this->save_field($post_id, $f, $old, $new);
					}
				}		
			}
		}
	}
	
	function update() {
		$post_type_object = get_post_type_object($_POST['post_type']);
		if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)						// check autosave
		|| (!isset($_POST['post_ID']))			// check revision
		|| (!in_array($_POST['post_type'], $this->_meta_box['pages']))			// check if current post type is supported
		|| (!wp_verify_nonce($_POST['nonce'], 'advanced_meta_box_nonce'))		// verify nonce
		|| (!current_user_can($post_type_object->cap->edit_post, $_POST['post_ID']))
		|| (empty($_POST['data']))) {	// check permission
			exit(-1);
		}
		$post_id = $_POST['post_ID'];
		$data = $_POST['data'][0];
		print_r($data);
		foreach ($this->_fields as $field) {
			$name = $field['id'];
			$type = $field['type'];
			$old  = $this->get_meta($post_id, $field);
			$new = isset($data[$name]) ? $data[$name] : ($field['multiple'] ? array() : '');

			// validate meta value
			if (class_exists('RW_Meta_Box_Validate') && method_exists('RW_Meta_Box_Validate', $field['validate_func'])) {
				$new = call_user_func(array('RW_Meta_Box_Validate', $field['validate_func']), $new);
			}

			// call defined method to save meta value, if there's no methods, call common one
			$save_func = 'save_field_' . $type;
			if (method_exists($this, $save_func)) {
				call_user_func(array(&$this, 'save_field_' . $type), $post_id, $field, $old, $new);
			} else {
				$this->save_field($post_id, $field, $old, $new);
			}
			
			if(isset($field['fields'])) {
				foreach ($field['fields'] as $f) {
					$name = $f['id'];
					$type = $f['type'];
					$old  = $this->get_meta($post_id, $f);
					$new = isset($data[$name]) ? $data[$name] : ($f['multiple'] ? array() : '');
		
					// validate meta value
					if (class_exists('RW_Meta_Box_Validate') && method_exists('RW_Meta_Box_Validate', $f['validate_func'])) {
						$new = call_user_func(array('RW_Meta_Box_Validate', $f['validate_func']), $new);
					}
		
					// call defined method to save meta value, if there's no methods, call common one
					$save_func = 'save_field_' . $type;
					if (method_exists($this, $save_func)) {
						call_user_func(array(&$this, 'save_field_' . $type), $post_id, $f, $old, $new);
					} else {
						$this->save_field($post_id, $f, $old, $new);
					}
				}		
			}
		}
		
		echo __('Updated.');
		die(0);
		
	}
	
	function save_field_taxonomy($post_id, $field, $old, $new) {
		wp_set_object_terms( $post_id, $new, $field['options']['taxonomy']);	
	}
	
	function save_field_date_select($post_id, $field, $old, $new) {
		if(!is_null($new['month']) || !is_null($new['day']) || !is_null($new['year'])) {
			$new = strtotime($new['month'].'/'.$new['day'].'/'.$new['year']);
		} else {
			$new = null;
		}
		$this->save_field($post_id,$field,$old,$new);
	}
	
	/******************** END META BOX SAVE **********************/
	
	/******************** BEGIN HELPER FUNCTIONS **********************/
	
		//Add Taxonomy extension
	function add_missed_values() {
		parent::add_missed_values();
		
		// add 'multiple' option to taxonomy field with checkbox_list type
		foreach ($this->_fields as $key => $field) {
			
			if ('taxonomy' === $field['type'] && 'checkbox_list' === $field['options']['type']) {
				$this->_fields[$key]['multiple'] = true;
			}
		}
	}
	
	//Helper functions to remove taxonomy boxes
	 function collect_tax_boxes($fields = array()) {
		$tax_boxes = array();	
		foreach($fields as $field) {
			if($field['type'] == 'taxonomy' && $field['options']['remove_box']) {
				$tax_boxes[] = $field['options']['taxonomy'];			
			} 
			if (isset($field['fields'])) {
				$tax_boxes = array_merge($this->collect_tax_boxes($field['fields']),$tax_boxes);	
			}
		}
		return $tax_boxes;
	}
	
	/******************** END HELPER FUNCTIONS **********************/
}

?>