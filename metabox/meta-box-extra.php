<?php

$prefix = 'dbt_';

$metabox = array(
	'id' => 'control-pet',
	'title' => __('Pet Control', 'wp-ada'),
	'pages' => array('pet'),
	'context' => 'side',
	'priority' => 'low',
	'update' => true,
	'fields' => array(

		array(
			'name' => __('Reference No.', 'wp-ada'),					// field name
			'desc' => __('Reference or code for control', 'wp-ada'),	// field description, optional
			'id' => $prefix . 'ref',				// field id, i.e. the meta key
			'type' => 'text'						// text box
		),
		array(
			'name' => __('Fee $', 'wp-ada'),					// field name
			'desc' => __('Example: 12,00', 'wp-ada'),	// field description, optional
			'id' => $prefix . 'fee',				// field id, i.e. the meta key
			'type' => 'text'						// text box
		)
	)

);


new Advanced_Meta_Box($metabox);

?>