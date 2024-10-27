<?php

$prefix = 'dbt_';

$metabox = array(
	'id' => 'animal-info',
	'title' => __('Animal Informations', 'wp-ada'),
	'pages' => array('pet'),
	'context' => 'normal',
	'priority' => 'high',
	'update' => true,								//Adding this will give you a button that allows you to update without refreshing the page.
	'fields' => array(

		array(
			'name' => __('Type', 'wp-ada'),
			'id' => 'types_category',
			'type' => 'taxonomy',
			'options' => array(
				'taxonomy' => 'types',			//Name of the taxonomy
				'type' => 'select',					//Type of field: select or checkbox_list
				'args' => array('hide_empty'=>0),	//Added hide_empty arg to show all available terms, not just ones that are being used
				'remove_box'=>true,					//Add this to remove the default Category metabox
				'add_new' => false 					//Lets you add more terms
			)
		),
		array(
			'name' => __('Status', 'wp-ada'),
			'id' => 'status_category',
			'type' => 'taxonomy',
			'options' => array(
				'taxonomy' => 'status',			//Name of the taxonomy
				'type' => 'select',					//Type of field: select or checkbox_list
				'args' => array('hide_empty'=>0),	//Added hide_empty arg to show all available terms, not just ones that are being used
				'remove_box'=>true,					//Add this to remove the default Category metabox
				'add_new' => false 					//Lets you add more terms
			)
		),
		array(
			'name' => __('Gender', 'wp-ada'),
			'id' => $prefix . 'gender',
			'type' => 'radio',						// radio box
			'options' => array(						// array of key => value pairs for radio options
				__('Male', 'wp-ada') => __('Male', 'wp-ada'),
				__('Female', 'wp-ada') => __('Female', 'wp-ada'),
				__('Various', 'wp-ada') => __('Various', 'wp-ada')
			)
		),
		array(
			'name' => __('Spayed/Neutered', 'wp-ada'),
			'id' => $prefix . 'neut',
			'type' => 'radio',						// radio box
			'options' => array(						// array of key => value pairs for radio options
				__('Yes', 'wp-ada') => __('Yes', 'wp-ada'),
				__('No', 'wp-ada') => __('No', 'wp-ada'),
			)
		),
		array(
			'name' => __('Age', 'wp-ada'),
			'id' => $prefix . 'age',
			'type' => 'select',						// select box
			'options' => array(						// array of key => value pairs for select box
				__('Baby (Under 1 year)', 'wp-ada') => __('Baby (Under 1 year)', 'wp-ada'),
				__('Adult (2 to 9 years)', 'wp-ada') => __('Adult (2 to 9 years)', 'wp-ada'),
				__('Senior (More than 10 years)', 'wp-ada') => __('Senior (More than 10 years)', 'wp-ada')
			)
		),
		array(
			'name' => __('Breed(s)', 'wp-ada'),					// field name
			'desc' => __('One or more breeds separated by commas. Example: Poodle, Unknown', 'wp-ada'),	// field description, optional
			'id' => $prefix . 'breed',				// field id, i.e. the meta key
			'type' => 'text'						// text box
		),
		array(
			'name' => __('Vaccines', 'wp-ada'),
			'id' => $prefix . 'vac',
			'type' => 'radio',						// radio box
			'options' => array(						// array of key => value pairs for radio options
				__('None', 'wp-ada') => __('None', 'wp-ada'),
				__('Unknown', 'wp-ada') => __('Unknown', 'wp-ada'),
				__('Vaccinated', 'wp-ada') => __('Vaccinated', 'wp-ada'),
				__('Dose Interval', 'wp-ada') => __('Dose Interval', 'wp-ada')
			)
		),
		array(
			'name' => __('Size', 'wp-ada'),
			'id' => $prefix . 'size',
			'type' => 'select',						// select box
			'options' => array(						// array of key => value pairs for select box
				__('Newborn (Imprecise)', 'wp-ada') => __('Newborn (Imprecise)', 'wp-ada'),
				__('Mini', 'wp-ada') => __('Mini', 'wp-ada'),
				__('Small', 'wp-ada') => __('Small', 'wp-ada'),
				__('Medium', 'wp-ada') => __('Medium', 'wp-ada'),
				__('Large', 'wp-ada') => __('Large', 'wp-ada'),
				__('Huge', 'wp-ada') => __('Huge', 'wp-ada'),
			)
		),
		array(
			'name' => __('Hair', 'wp-ada'),
			'id' => $prefix . 'hair',
			'type' => 'select',						// select box
			'options' => array(						// array of key => value pairs for select box
				__('None', 'wp-ada') => __('None', 'wp-ada'),
				__('Short', 'wp-ada') => __('Short', 'wp-ada'),
				__('Medium', 'wp-ada') => __('Medium', 'wp-ada'),
				__('Long', 'wp-ada') => __('Long', 'wp-ada'),
				__('Mixed', 'wp-ada') => __('Mixed', 'wp-ada')
			)
		),
		array(
			'name' => __('Pattern', 'wp-ada'),
			'id' => $prefix . 'patt',
			'type' => 'select',						// select box
			'options' => array(						// array of key => value pairs for select box
				__('Solid', 'wp-ada') => __('Solid', 'wp-ada'),
				__('Brindle', 'wp-ada') => __('Brindle', 'wp-ada'),
				__('Patches', 'wp-ada') => __('Patches', 'wp-ada'),
				__('Spotted', 'wp-ada') => __('Spotted', 'wp-ada')
			)
		),
		array(
			'name' => __('Color', 'wp-ada'),
			'id' => 'test_tags',
			'type' => 'taxonomy',
			'options' => array(
				'taxonomy' => 'colors',
				'type' => 'checkbox_list',
				'args' => array('hide_empty'=>0),
				'remove_box'=>true,
				'child' => array(
					'type' => 'checkbox_list'
				), 'add_new' => true
			)
		),
		array(
			'name' => __('Notes (optional)', 'wp-ada'),
			'desc' => __('Physical conditions, aspects or any other info', 'wp-ada'),
			'id' => $prefix . 'notes',
			'type' => 'textarea'					// textarea
		)
	)

);





new Advanced_Meta_Box($metabox);

?>