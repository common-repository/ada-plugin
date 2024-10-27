<?php

$prefix = 'dbt_';

$metabox = array(
	'id' => 'lost-pet',
	'title' => __('Lost & Found', 'wp-ada'),
	'pages' => array('pet'),
	'context' => 'side',
	'priority' => 'low',
	'update' => true,
	'fields' => array(

		array(
			'name' => __('Date', 'wp-ada'),					// field name
			'desc' => __('Example:  01/05/2011', 'wp-ada'),	// field description, optional
			'id' => $prefix . 'date',				// field id, i.e. the meta key
			'type' => 'text'						// text box
		),
		array(
			'name' => __('Time', 'wp-ada'),					// field name
			'desc' => __('Example:  11:29 pm', 'wp-ada'),	// field description, optional
			'id' => $prefix . 'time',				// field id, i.e. the meta key
			'type' => 'text'						// text box
		),

		array(
			'name' => __('Place', 'wp-ada'),					// field name
			'desc' => __('Example: Lincoln Park', 'wp-ada'),	// field description, optional
			'id' => $prefix . 'place',				// field id, i.e. the meta key
			'type' => 'text'						// text box
		)
	)

);


new Advanced_Meta_Box($metabox);

?>