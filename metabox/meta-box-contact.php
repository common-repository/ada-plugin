<?php

$prefix = 'dbt_';

$metabox = array(
	'id' => 'contact-info',
	'title' => __('Contact Information', 'wp-ada'),
	'pages' => array('pet'),
	'context' => 'normal',
	'priority' => 'high',
	'update' => true,
	'fields' => array(

    array(
        'name' => __('Contact Information', 'wp-ada'),
        'id' => $prefix . 'contact',
        'type' => 'textarea'
        ),
		array(
        'name' => __('E-mail', 'wp-ada'),
        'id' => $prefix . 'mail',
        'type' => 'text',
        'desc'=> __('<strong>Warning:</strong> All These informations will be visible in the website. Be careful sharing personal information such e-mails and addresses.', 'wp-ada')
        ),
		array(
			'name' => __('State', 'wp-ada'),
			'id' => 'state_category',
			'type' => 'taxonomy',
			'options' => array(
				'taxonomy' => 'state',			//Name of the taxonomy
				'type' => 'select',					//Type of field: select or checkbox_list
				'args' => array('hide_empty'=>0),	//Added hide_empty arg to show all available terms, not just ones that are being used
				'remove_box'=>true,					//Add this to remove the default Category metabox
				'add_new' => false 					//Lets you add more terms
			)
		),
  array(
    'name' => __('City', 'wp-ada'),
    'id' => $prefix . 'city',
    'type' => 'text'
    )

	)

);



new Advanced_Meta_Box($metabox);

?>