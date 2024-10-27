<?php
/**
 * Registering meta boxes
 *
 * In this file, I'll show you how to extend the class to add more field type (in this case, the 'taxonomy' type)
 * All the definitions of meta boxes are listed below with comments, please read them carefully.
 * Note that each validation method of the Validation Class MUST return value instead of boolean as before
 *
 *
 */

/********************* BEGIN EXTENDING CLASS ***********************/
 
/**
 * Extend RW_Meta_Box class
 * Add field type: 'taxonomy'
 */
class RW_Meta_Box_Taxonomy extends RW_Meta_Box {



	function add_missed_values() {
		parent::add_missed_values();

		// add 'multiple' option to taxonomy field with checkbox_list type
		foreach ($this->_meta_box['fields'] as $key => $field) {
			if ('taxonomy' == $field['type'] && 'checkbox_list' == $field['options']['type']) {
				$this->_meta_box['fields'][$key]['multiple'] = true;
			}
		}
	}

	// show taxonomy list
	function show_field_taxonomy($field, $meta) {
		global $post;

		if (!is_array($meta)) $meta = (array) $meta;

		$this->show_field_begin($field, $meta);

		$options = $field['options'];
		$terms = get_terms($options['taxonomy'], $options['args']);

		// checkbox_list
		if ('checkbox_list' == $options['type']) {
			foreach ($terms as $term) {
				echo "<input type='checkbox' name='{$field['id']}[]' value='$term->slug'" . checked(in_array($term->slug, $meta), true, false) . " /> $term->name<br/>";
			}
		}
		// select
		else {
			echo "<select name='{$field['id']}" . ($field['multiple'] ? "[]' multiple='multiple' style='height:auto'" : "'") . ">";

			foreach ($terms as $term) {
				echo "<option value='$term->slug'" . selected(in_array($term->slug, $meta), true, false) . ">$term->name</option>";
			}
			echo "</select>";
		}

		$this->show_field_end($field, $meta);
	}
}

/********************* END EXTENDING CLASS ***********************/

/********************* BEGIN DEFINITION OF META BOXES ***********************/

// prefix of meta keys, optional
// use underscore (_) at the beginning to make keys hidden, for example $prefix = '_rw_';
// you also can make prefix empty to disable it
$prefix = 'dbt_';

$meta_boxes = array();

// first meta box
$meta_boxes[] = array(
	'id' => 'animal-info',							// meta box id, unique per meta box
	'title' =>  __('Animal Informations', 'wp-ada'),			// meta box title
	'pages' => array('pet'),	// post types, accept custom post types as well, default is array('post'); optional
	'context' => 'normal',						// where the meta box appear: normal (default), advanced, side; optional
	'priority' => 'high',						// order of meta box: high (default), low; optional

	'fields' => array(							// list of meta fields

		array(
			'name' => __('Gender', 'wp-ada'),
			'id' => $prefix . 'genero',
			'type' => 'radio',						// radio box
			'options' => array(						// array of key => value pairs for radio options
				__('Male', 'wp-ada') => __('Male', 'wp-ada'),
				__('Female', 'wp-ada') => __('Female', 'wp-ada'),
        __('Various', 'wp-ada') => __('Various', 'wp-ada')
			)
		),
		array(
			'name' => __('Spayed/Neutered', 'wp-ada'),
			'id' => $prefix . 'cast',
			'type' => 'radio',						// radio box
			'options' => array(						// array of key => value pairs for radio options
				__('Yes', 'wp-ada') => __('Yes', 'wp-ada'),
				__('No', 'wp-ada') => __('No', 'wp-ada')
			)
		),
		array(
			'name' => __('Age', 'wp-ada'),
			'id' => $prefix . 'idade',
			'type' => 'select',						// select box
			'options' => array(						// array of key => value pairs for select box
				__('Baby (Under 1 year)', 'wp-ada') => __('Baby (Under 1 year)', 'wp-ada'),
				__('Adult (2 to 9 years)', 'wp-ada') => __('Adult (2 to 9 years)', 'wp-ada'),
				__('Senior (More than 10 years)', 'wp-ada') => __('Senior (More than 10 years)', 'wp-ada')
			),
			'multiple' => false				// default value, can be string (single value) or array (for both single and multiple values)
		),
		array(
			'name' => __('Breed(s)', 'wp-ada'),					// field name
			'desc' => __('One or more breeds separated by commas. Example: Poodle, Unknown', 'wp-ada'),
			'id' => $prefix . 'raca',				// field id, i.e. the meta key
      'type' => 'text'
		),
		array(
			'name' => __('Size', 'wp-ada'),
			'id' => $prefix . 'porte',
			'type' => 'select',						// select box
			'options' => array(						// array of key => value pairs for select box
				__('Newborn (Imprecise)', 'wp-ada') => __('Newborn (Imprecise)', 'wp-ada'),
				__('Mini', 'wp-ada') => __('Mini', 'wp-ada'),
				__('Small', 'wp-ada') => __('Small', 'wp-ada'),
				__('Medium', 'wp-ada') => __('Medium', 'wp-ada'),
				__('Large', 'wp-ada') => __('Large', 'wp-ada'),
				__('Huge', 'wp-ada') => __('Huge', 'wp-ada')
			),
			'multiple' => false
		),
		array(
			'name' => __('Hair', 'wp-ada'),
			'id' => $prefix . 'pelagem',
			'type' => 'select',						// select box
			'options' => array(						// array of key => value pairs for select box
				__('None', 'wp-ada') => __('None', 'wp-ada'),
				__('Short', 'wp-ada') => __('Short', 'wp-ada'),
        __('Medium', 'wp-ada') => __('Medium', 'wp-ada'),
        __('Long', 'wp-ada') => __('Long', 'wp-ada'),
        __('Mixed', 'wp-ada') => __('Mixed', 'wp-ada'),
			),
			'multiple' => false
		),

		array(
			'name' => __('Pattern', 'wp-ada'),
			'id' => $prefix . 'padrao',
			'type' => 'select',						// select box
			'options' => array(						// array of key => value pairs for select box
				__('Solid', 'wp-ada') => __('Solid', 'wp-ada'),
				__('Brindle', 'wp-ada') => __('Brindle', 'wp-ada'),
				__('Patches', 'wp-ada') => __('Patches', 'wp-ada'),
        __('Spotted', 'wp-ada') => __('Spotted', 'wp-ada')
			),
			'multiple' => true,
      'desc'=>__('Ctrl-click for multi-select', 'wp-ada')
		),
		array(
			'name' => __('Vaccines', 'wp-ada'),
			'id' => $prefix . 'vacinas',
			'type' => 'radio',						// radio box
			'options' => array(						// array of key => value pairs for radio options
				__('Vaccinated', 'wp-ada') => __('Vaccinated', 'wp-ada'),
				__('None', 'wp-ada') => __('None', 'wp-ada'),
				__('Unknown', 'wp-ada') => __('Unknown', 'wp-ada'),
				__('Dose Interval', 'wp-ada') => __('Dose Interval', 'wp-ada')
			)
		),
		array(
			'name' => __('Notes (optional)', 'wp-ada'),
			'desc' => __('Physical conditions, aspects or any other info', 'wp-ada'),
			'id' => $prefix . 'ob',
			'type' => 'textarea'
		)
	)
);

$meta_boxes[] = array(
	'id' => 'lost=pet-info',
	'title' => __('Lost Pet Informations', 'wp-ada'),
	'pages' => array('lost'),

	'fields' => array(
		array(
			'name' => __('Gender', 'wp-ada'),
			'id' => $prefix . 'p-genero',
			'type' => 'radio',						// radio box
			'options' => array(						// array of key => value pairs for radio options
				__('Male', 'wp-ada') => __('Male', 'wp-ada'),
				__('Female', 'wp-ada') => __('Female', 'wp-ada')
			)
		),
		array(
			'name' => __('Spayed/Neutered', 'wp-ada'),
			'id' => $prefix . 'p-cast',
			'type' => 'radio',						// radio box
			'options' => array(						// array of key => value pairs for radio options
				__('Yes', 'wp-ada') => __('Yes', 'wp-ada'),
				__('No', 'wp-ada') => __('No', 'wp-ada')
			)
		),
		array(
			'name' => __('Age', 'wp-ada'),
			'id' => $prefix . 'p-idade',
			'type' => 'select',						// select box
			'options' => array(						// array of key => value pairs for select box
				__('Baby (Under 1 year)', 'wp-ada') => __('Baby (Under 1 year)', 'wp-ada'),
				__('Adult (2 to 9 years)', 'wp-ada') => __('Adult (2 to 9 years)', 'wp-ada'),
				__('Senior (More than 10 years)', 'wp-ada') => __('Senior (More than 10 years)', 'wp-ada')
			),
			'multiple' => false				// default value, can be string (single value) or array (for both single and multiple values)
		),
		array(
			'name' => __('Breed(s)', 'wp-ada'),					// field name
			'desc' => __('One or more breeds separated by commas. Example: Poodle, Unknown', 'wp-ada'),
			'id' => $prefix . 'p-raca',				// field id, i.e. the meta key
      'type' => 'text',
		),
		array(
			'name' => __('Size', 'wp-ada'),
			'id' => $prefix . 'p-porte',
			'type' => 'select',						// select box
			'options' => array(						// array of key => value pairs for select box
				__('Newborn (Imprecise)', 'wp-ada') => __('Newborn (Imprecise)', 'wp-ada'),
				__('Mini', 'wp-ada') => __('Mini', 'wp-ada'),
				__('Small', 'wp-ada') => __('Small', 'wp-ada'),
				__('Medium', 'wp-ada') => __('Medium', 'wp-ada'),
				__('Large', 'wp-ada') => __('Large', 'wp-ada'),
				__('Huge', 'wp-ada') => __('Huge', 'wp-ada')
			),
			'multiple' => false
		),
		array(
			'name' => __('Color(s)', 'wp-ada'),					// field name
			'desc' => __('One or more color separated by commas. Example: Black, White', 'wp-ada'),
			'id' => $prefix . 'p-cor',				// field id, i.e. the meta key
      'type' => 'text'
		),
		array(
			'name' => __('Hair', 'wp-ada'),
			'id' => $prefix . 'p-pelagem',
			'type' => 'select',						// select box
			'options' => array(
				__('None', 'wp-ada') => __('None', 'wp-ada'),      				// array of key => value pairs for select box
				__('Short', 'wp-ada') => __('Short', 'wp-ada'),
				__('Medium', 'wp-ada') => __('Medium', 'wp-ada'),
				__('Long', 'wp-ada') => __('Long', 'wp-ada'),
        __('Mixed', 'wp-ada') => __('Mixed', 'wp-ada')
			),
			'multiple' => false
		),
		array(
			'name' => __('Pattern', 'wp-ada'),
			'id' => $prefix . 'p-padrao',
			'type' => 'select',						// select box
			'options' => array(						// array of key => value pairs for select box
				__('Solid', 'wp-ada') => __('Solid', 'wp-ada'),
				__('Brindle', 'wp-ada') => __('Brindle', 'wp-ada'),
				__('Patches', 'wp-ada') => __('Patches', 'wp-ada'),
        __('Spotted', 'wp-ada') => __('Spotted', 'wp-ada')
			),
			'multiple' => true,
      'desc'=>__('Ctrl-click for multi-select', 'wp-ada')
		),
		array(
			'name' => 'Vaccines',
			'id' => $prefix . 'vacinas',
			'type' => 'radio',						// radio box
			'options' => array(						// array of key => value pairs for radio options
				__('Vaccinated', 'wp-ada') => __('Vaccinated', 'wp-ada'),
				__('None', 'wp-ada') => __('None', 'wp-ada'),
				__('Unknown', 'wp-ada') => __('Unknown', 'wp-ada'),
				__('Dose Interval', 'wp-ada') => __('Dose Interval', 'wp-ada')
			)
		),
		array(
			'name' => __('Date lost', 'wp-ada'),
			'id' => $prefix . 'p-data',
			'type' => 'text',						// time
			'desc' => __('Format MM/DD/YYYY', 'wp-ada'),					// time format, default hh:mm. Optional. See more formats here: http://goo.gl/hXHWz
		),
		array(
			'name' => __('Place & Time', 'wp-ada'),					// field name
			'desc' => __('The last place and time. Example: Lincoln Park at 11:29 pm', 'wp-ada'),
			'id' => $prefix . 'p-rua',				// field id, i.e. the meta key
			'type' => 'text'						// text box

		),
		array(
			'name' => __('Pictures', 'wp-ada'),
			'desc' => __('Recomended width: 400px. Please note: the admin will choose a limited amount of images.', 'wp-ada'),
			'id' => $prefix . 'p-pic',
			'type' => 'image'						// image upload
		)
  )
);

// second meta box
$meta_boxes[] = array(
	'id' => 'contact-info',
	'title' => __('Contact Information', 'wp-ada'),
	'pages' => array('pet','lost'),
	'fields' => array(
    array(
			'name' => __('Contact Information', 'wp-ada'),
			'id' => $prefix . 'contato',
			'type' => 'textarea'					// WYSIWYG editor
		),
		array(
			'name' => __('E-mail', 'wp-ada'),					// field name
			'desc' => __('Example: name@server.com', 'wp-ada'),
			'id' => $prefix . 'email',				// field id, i.e. the meta key
      'type' => 'text',
      'desc'=> __('<strong>Warning:</strong> All These informations will be visible in the website. Be careful sharing personal information such e-mails and addresses.', 'wp-ada')
		)
	)
);

foreach ($meta_boxes as $meta_box) {
	$my_box = new RW_Meta_Box_Taxonomy($meta_box);


}

/********************* END DEFINITION OF META BOXES ***********************/

 

?>
