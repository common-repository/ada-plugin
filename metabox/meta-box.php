<?php
/**
 * Create meta box for editing pages in WordPress
 *
 * Compatible with custom post types since WordPress 3.0
 * Support input types: text, textarea, checkbox, checkbox list, radio box, select, wysiwyg, file, image, date, time, color
 *
 * @author Rilwis <rilwis@gmail.com>
 * @link http://www.deluxeblogtips.com/p/meta-box-script-for-wordpress.html
 * @example meta-box-usage.php Sample declaration and usage of meta boxes
 * @version: 3.2
 *
 * @license GNU General Public License v3.0
 */

/**
 * Meta Box class
 */
class RW_Meta_Box {

	protected $_meta_box;
	protected $_fields;

	// Create meta box based on given data
	function __construct($meta_box) {
		if (!is_admin()) return;

		// assign meta box values to local variables and add it's missed values
		$this->_meta_box = $meta_box;
		$this->_fields = &$this->_meta_box['fields'];
		$this->add_missed_values();

		add_action('add_meta_boxes', array(&$this, 'add'));	// add meta box, using 'add_meta_boxes' for WP 3.0+
		add_action('save_post', array(&$this, 'save'));		// save meta box's data

		// check for some special fields and add needed actions for them
		$this->check_field_upload();
		$this->check_field_color();
		$this->check_field_date();
		$this->check_field_time();

		// load common js, css files
		// must enqueue for all pages as we need js for the media upload, too
		add_action('admin_print_styles', array(&$this, 'js_css'));
	}

	// Load common js, css files for the script
	function js_css() {
		$path = dirname(__FILE__);											// get the path to the directory of current file

		// get URL of the directory of current file
		$base_url = str_replace(WP_PLUGIN_DIR, WP_PLUGIN_URL, $path);		// if this file is placed under a plugin
		$base_url = str_replace(WP_CONTENT_DIR, WP_CONTENT_URL, $base_url);	// or inside a theme (note that this statement WON'T do anything if the above statement was successful)

		wp_enqueue_style('rw-meta-box', $base_url . '/meta-box.css');
		wp_enqueue_script('rw-meta-box', $base_url . '/meta-box.js', array('jquery'), null, true);
	}

	/******************** BEGIN UPLOAD **********************/

	// Check field upload and add needed actions
	function check_field_upload() {
		if (!$this->has_field('image') && !$this->has_field('file')) return;

		add_action('post_edit_form_tag', array(&$this, 'add_enctype'));				// add data encoding type for file uploading

		// make upload feature works even when custom post type doesn't support 'editor'
		wp_enqueue_script('media-upload');
		add_thickbox();
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-sortable');

		add_filter('media_upload_gallery', array(&$this, 'insert_images'));			// process adding multiple images to image meta field
		add_filter('media_upload_library', array(&$this, 'insert_images'));
		add_filter('media_upload_image', array(&$this, 'insert_images'));

		// add_action('delete_post', array(&$this, 'delete_attachments'));			// delete all attachments when delete post
		add_action('wp_ajax_rw_delete_file', array(&$this, 'delete_file'));			// ajax delete files
		add_action('wp_ajax_rw_reorder_images', array(&$this, 'reorder_images'));	// ajax reorder images
	}

	// Add data encoding type for file uploading
	function add_enctype() {
		echo ' enctype="multipart/form-data"';
	}

	// Process adding images to image meta field, modifiy from 'Faster image insert' plugin
	function insert_images() {
		if (!isset($_POST['rw-insert']) || empty($_POST['attachments'])) return;

		check_admin_referer('media-form');

		$nonce = wp_create_nonce('rw_ajax_delete');
		$post_id = $_POST['post_id'];
		$id = $_POST['field_id'];

		// modify the insertion string
		$html = '';
		foreach ($_POST['attachments'] as $attachment_id => $attachment) {
			$attachment = stripslashes_deep($attachment);
			if (empty($attachment['selected']) || empty($attachment['url'])) continue;

			$li = "<li id='item_$attachment_id'>";
			$li .= "<img src='{$attachment['url']}' />";
			$li .= "<a title='" . __('Delete this image') . "' class='rw-delete-file' href='#' rel='$nonce|$post_id|$id|$attachment_id'>" . __('Delete') . "</a>";
			$li .= "<input type='hidden' name='{$id}[]' value='$attachment_id' />";
			$li .= "</li>";
			$html .= $li;
		}

		media_send_to_editor($html);
	}

	// Delete all attachments when delete post
	function delete_attachments($post_id) {
		$attachments = get_posts(array(
			'numberposts' => -1,
			'post_type' => 'attachment',
			'post_parent' => $post_id
		));
		if (!empty($attachments)) {
			foreach ($attachments as $att) {
				wp_delete_attachment($att->ID);
			}
		}
	}

	// Ajax callback for deleting files. Modified from a function used by "Verve Meta Boxes" plugin (http://goo.gl/LzYSq)
	function delete_file() {
		if (!isset($_POST['data'])) die();

		list($nonce, $post_id, $key, $attach_id) = explode('|', $_POST['data']);

		if (!wp_verify_nonce($nonce, 'rw_ajax_delete')) die('1');

		// wp_delete_attachment($attach_id);
		delete_post_meta($post_id, $key, $attach_id);

		die('0');
	}

	// Ajax callback for reordering images
	function reorder_images() {
		if (!isset($_POST['data'])) die();

		list($order, $post_id, $key, $nonce) = explode('|',$_POST['data']);

		if (!wp_verify_nonce($nonce, 'rw_ajax_reorder')) die('1');

		parse_str($order, $items);
		$items = $items['item'];
		$order = 1;
		foreach ($items as $item) {
			wp_update_post(array(
				'ID' => $item,
				'post_parent' => $post_id,
				'menu_order' => $order
			));
			$order++;
		}

		die('0');
	}

	/******************** END UPLOAD **********************/

	/******************** BEGIN OTHER FIELDS **********************/

	// Check field color
	function check_field_color() {
		if ($this->has_field('color') && $this->is_edit_page()) {
			wp_enqueue_style('farbtastic');												// enqueue built-in script and style for color picker
			wp_enqueue_script('farbtastic');
		}
	}

	// Check field date
	function check_field_date() {
		if ($this->has_field('date') && $this->is_edit_page()) {
			// add style and script, use proper jQuery UI version
			wp_enqueue_style('rw-jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/' . $this->get_jqueryui_ver() . '/themes/base/jquery-ui.css');
			wp_enqueue_script('rw-jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/' . $this->get_jqueryui_ver() . '/jquery-ui.min.js', array('jquery'));
		}
	}

	// Check field time
	function check_field_time() {
		if ($this->has_field('time') && $this->is_edit_page()) {
			// add style and script, use proper jQuery UI version
			wp_enqueue_style('rw-jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/' . $this->get_jqueryui_ver() . '/themes/base/jquery-ui.css');
			wp_enqueue_script('rw-jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/' . $this->get_jqueryui_ver() . '/jquery-ui.min.js', array('jquery'));
			wp_enqueue_script('rw-timepicker', 'https://github.com/trentrichardson/jQuery-Timepicker-Addon/raw/master/jquery-ui-timepicker-addon.js', array('rw-jquery-ui'));
		}
	}

	/******************** END OTHER FIELDS **********************/

	/******************** BEGIN META BOX PAGE **********************/

	// Add meta box for multiple post types
	function add() {
		foreach ($this->_meta_box['pages'] as $page) {
			add_meta_box($this->_meta_box['id'], $this->_meta_box['title'], array(&$this, 'show'), $page, $this->_meta_box['context'], $this->_meta_box['priority']);
		}
	}

	// Callback function to show fields in meta box
	function show() {
		global $post;

		wp_nonce_field(basename(__FILE__), 'rw_meta_box_nonce');
		echo '<table class="form-table">';

		foreach ($this->_fields as $field) {
			$meta = get_post_meta($post->ID, $field['id'], !$field['multiple']);
			$meta = ($meta !== '') ? $meta : $field['std'];

			$meta = is_array($meta) ? array_map('esc_attr', $meta) : esc_attr($meta);

			echo '<tr>';
			// call separated methods for displaying each type of field
			call_user_func(array(&$this, 'show_field_' . $field['type']), $field, $meta);
			echo '</tr>';
		}
		echo '</table>';
	}

	/******************** END META BOX PAGE **********************/

	/******************** BEGIN META BOX FIELDS **********************/

	function show_field_begin($field, $meta) {
		echo "<th class='rw-label'><label for='{$field['id']}'>{$field['name']}</label></th><td class='rw-field'>";
	}

	function show_field_end($field, $meta) {
		echo "<br />{$field['desc']}</td>";
	}

	function show_field_text($field, $meta) {
		$this->show_field_begin($field, $meta);
		echo "<input type='text' class='rw-text' name='{$field['id']}' id='{$field['id']}' value='$meta' size='30' />";
		$this->show_field_end($field, $meta);
	}

	function show_field_textarea($field, $meta) {
		$this->show_field_begin($field, $meta);
		echo "<textarea class='rw-textarea large-text' name='{$field['id']}' id='{$field['id']}' cols='60' rows='10'>$meta</textarea>";
		$this->show_field_end($field, $meta);
	}

	function show_field_select($field, $meta) {
		if (!is_array($meta)) $meta = (array) $meta;
		$this->show_field_begin($field, $meta);
		echo "<select class='rw-select' name='{$field['id']}" . ($field['multiple'] ? "[]' id='{$field['id']}' multiple='multiple'" : "'") . ">";
		foreach ($field['options'] as $key => $value) {
			echo "<option value='$key'" . selected(in_array($key, $meta), true, false) . ">$value</option>";
		}
		echo "</select>";
		$this->show_field_end($field, $meta);
	}

	function show_field_radio($field, $meta) {
		$this->show_field_begin($field, $meta);
		foreach ($field['options'] as $key => $value) {
			echo "<input type='radio' class='rw-radio' name='{$field['id']}' value='$key'" . checked($meta, $key, false) . " /> $value ";
		}
		$this->show_field_end($field, $meta);
	}

	function show_field_checkbox($field, $meta) {
		$this->show_field_begin($field, $meta);
		echo "<input type='checkbox' class='rw-checkbox' name='{$field['id']}' id='{$field['id']}'" . checked(!empty($meta), true, false) . " /> {$field['desc']}</td>";
	}

	function show_field_wysiwyg($field, $meta) {
		$this->show_field_begin($field, $meta);
		echo "<textarea class='rw-wysiwyg theEditor large-text' name='{$field['id']}' id='{$field['id']}' cols='60' rows='10'>$meta</textarea>";
		$this->show_field_end($field, $meta);
	}

	function show_field_file($field, $meta) {
		global $post;

		if (!is_array($meta)) $meta = (array) $meta;

		$this->show_field_begin($field, $meta);
		echo "{$field['desc']}<br />";

		if (!empty($meta)) {
			$nonce = wp_create_nonce('rw_ajax_delete');
			echo '<div style="margin-bottom: 10px"><strong>' . __('Uploaded files') . '</strong></div>';
			echo '<ol class="rw-upload">';
			foreach ($meta as $att) {
				// if (wp_attachment_is_image($att)) continue; // what's image uploader for?
				echo "<li>" . wp_get_attachment_link($att, '' , false, false, ' ') . " (<a class='rw-delete-file' href='#' rel='$nonce|{$post->ID}|{$field['id']}|$att'>" . __('Delete') . "</a>)</li>";
			}
			echo '</ol>';
		}

		// show form upload
		echo "<div style='clear: both'><strong>" . __('Upload new files') . "</strong></div>
			<div class='new-files'>
				<div class='file-input'><input type='file' name='{$field['id']}[]' /></div>
				<a class='rw-add-file' href='#'>" . __('Add more file') . "</a>
			</div>
		</td>";
	}

	function show_field_image($field, $meta) {
		global $wpdb, $post;

		if (!is_array($meta)) $meta = (array) $meta;

		$this->show_field_begin($field, $meta);
		echo "{$field['desc']}<br />";

		$nonce_delete = wp_create_nonce('rw_ajax_delete');
		$nonce_sort = wp_create_nonce('rw_ajax_reorder');

		echo "<input type='hidden' class='rw-images-data' value='{$post->ID}|{$field['id']}|$nonce_sort' />
			  <ul class='rw-images rw-upload' id='rw-images-{$field['id']}'>";

		// re-arrange images with 'menu_order', thanks Onur
		$meta = implode(',', $meta);
		$images = $wpdb->get_col("
			SELECT ID FROM $wpdb->posts
			WHERE post_type = 'attachment'
			AND post_parent = $post->ID
			AND ID in ($meta)
			ORDER BY menu_order ASC
		");
		foreach ($images as $image) {
			$src = wp_get_attachment_image_src($image);
			$src = $src[0];

			echo "<li id='item_$image'>
					<img src='$src' />
					<a title='" . __('Delete this image') . "' class='rw-delete-file' href='#' rel='$nonce_delete|{$post->ID}|{$field['id']}|$image'>" . __('Delete') . "</a>
					<input type='hidden' name='{$field['id']}[]' value='$image' />
				</li>";
		}
		echo '</ul>';

		echo "<a href='#' class='rw-upload-button button' rel='{$post->ID}|{$field['id']}'>" . __('Add more images') . "</a>";
	}

	function show_field_color($field, $meta) {
		if (empty($meta)) $meta = '#';
		$this->show_field_begin($field, $meta);
		echo "<input class='rw-color' type='text' name='{$field['id']}' id='{$field['id']}' value='$meta' size='8' />
			  <a href='#' class='rw-color-select' rel='{$field['id']}'>" . __('Select a color') . "</a>
			  <div style='display:none' class='rw-color-picker' rel='{$field['id']}'></div>";
		$this->show_field_end($field, $meta);
	}

	function show_field_checkbox_list($field, $meta) {
		if (!is_array($meta)) $meta = (array) $meta;
		$this->show_field_begin($field, $meta);
		$html = array();
		foreach ($field['options'] as $key => $value) {
			$html[] = "<input type='checkbox' class='rw-checkbox_list' name='{$field['id']}[]' value='$key'" . checked(in_array($key, $meta), true, false) . " /> $value";
		}
		echo implode('<br />', $html);
		$this->show_field_end($field, $meta);
	}

	function show_field_date($field, $meta) {
		$this->show_field_begin($field, $meta);
		echo "<input type='text' class='rw-date' name='{$field['id']}' id='{$field['id']}' rel='{$field['format']}' value='$meta' size='30' />";
		$this->show_field_end($field, $meta);
	}

	function show_field_time($field, $meta) {
		$this->show_field_begin($field, $meta);
		echo "<input type='text' class='rw-time' name='{$field['id']}' id='{$field['id']}' rel='{$field['format']}' value='$meta' size='30' />";
		$this->show_field_end($field, $meta);
	}

	/******************** END META BOX FIELDS **********************/

	/******************** BEGIN META BOX SAVE **********************/

	// Save data from meta box
	function save($post_id) {
		global $post_type;
		$post_type_object = get_post_type_object($post_type);

		if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)						// check autosave
		|| (!isset($_POST['post_ID']) || $post_id != $_POST['post_ID'])			// check revision
		|| (!in_array($post_type, $this->_meta_box['pages']))					// check if current post type is supported
		|| (!check_admin_referer(basename(__FILE__), 'rw_meta_box_nonce'))		// verify nonce
		|| (!current_user_can($post_type_object->cap->edit_post, $post_id))) {	// check permission
			return $post_id;
		}

		foreach ($this->_fields as $field) {
			$name = $field['id'];
			$type = $field['type'];
			$old = get_post_meta($post_id, $name, !$field['multiple']);
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
		}
	}

	// Common functions for saving field
	function save_field($post_id, $field, $old, $new) {
		$name = $field['id'];

		delete_post_meta($post_id, $name);
		if ($new === '' || $new === array()) return;

		if ($field['multiple']) {
			foreach ($new as $add_new) {
				add_post_meta($post_id, $name, $add_new, false);
			}
		} else {
			update_post_meta($post_id, $name, $new);
		}

	}

	function save_field_wysiwyg($post_id, $field, $old, $new) {
		$new = wpautop($new);
		$this->save_field($post_id, $field, $old, $new);
	}

	function save_field_file($post_id, $field, $old, $new) {
		$name = $field['id'];
		if (empty($_FILES[$name])) return;

		$this->fix_file_array($_FILES[$name]);

		foreach ($_FILES[$name] as $position => $fileitem) {
			$file = wp_handle_upload($fileitem, array('test_form' => false));

			if (empty($file['file'])) continue;
			$filename = $file['file'];

			$attachment = array(
				'post_mime_type' => $file['type'],
				'guid' => $file['url'],
				'post_parent' => $post_id,
				'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
				'post_content' => ''
			);
			$id = wp_insert_attachment($attachment, $filename, $post_id);
			if (!is_wp_error($id)) {
				wp_update_attachment_metadata($id, wp_generate_attachment_metadata($id, $filename));
				add_post_meta($post_id, $name, $id, false);	// save file's url in meta fields
			}
		}
	}

	/******************** END META BOX SAVE **********************/

	/******************** BEGIN HELPER FUNCTIONS **********************/

	// Add missed values for meta box
	function add_missed_values() {
		// default values for meta box
		$this->_meta_box = array_merge(array(
			'context' => 'normal',
			'priority' => 'high',
			'pages' => array('post')
		), $this->_meta_box);

		// default values for fields
		foreach ($this->_fields as &$field) {
			$multiple = in_array($field['type'], array('checkbox_list', 'file', 'image'));
			$std = $multiple ? array() : '';
			$format = 'date' == $field['type'] ? 'yy-mm-dd' : ('time' == $field['type'] ? 'hh:mm' : '');

			$field = array_merge(array(
				'multiple' => $multiple,
				'std' => $std,
				'desc' => '',
				'format' => $format,
				'validate_func' => ''
			), $field);
		}
	}

	// Check if field with $type exists
	function has_field($type) {
		foreach ($this->_fields as $field) {
			if ($type == $field['type']) return true;
		}
		return false;
	}

	// Check if current page is edit page
	function is_edit_page() {
		global $pagenow;
		return in_array($pagenow, array('post.php', 'post-new.php'));
	}

	/**
	 * Fixes the odd indexing of multiple file uploads from the format:
	 *	 $_FILES['field']['key']['index']
	 * To the more standard and appropriate:
	 *	 $_FILES['field']['index']['key']
	 */
	function fix_file_array(&$files) {
		$output = array();
		foreach ($files as $key => $list) {
			foreach ($list as $index => $value) {
				$output[$index][$key] = $value;
			}
		}
		$files = $output;
	}

	// Get proper jQuery UI version to not conflict with WP admin scripts
	function get_jqueryui_ver() {
		global $wp_version;
		if (version_compare($wp_version, '3.1', '>=')) {
			return '1.8.10';
		}

		return '1.7.3';
	}

	/******************** END HELPER FUNCTIONS **********************/
}

?>
