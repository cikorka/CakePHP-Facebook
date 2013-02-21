<?php

/**
 *
 * PHP 5
 *
 * CakePHP(™) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Petr Jeřábek : CakePHP Ultimate Facebook Plugin
 * Copyright 2013, Petr Jeřábek (http://github.com/cikorka)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @copyright	Copyright 2013, Petr Jeřábek  (http://github.com/cikorka)
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @file		stream_filter.php
 */

/**
 * # Stream Filter
 *
 * ## Permissions
 *
 * To read the stream_filter table you need:
 *
 * - a valid access token with basic permissions
 * - `read_stream` permissions if used in combination with stream table).
 *
 * ## Supported Base Where Clauses
 *
 * - `SELECT ... FROM stream_filter WHERE uid = A`
 *
 * Note: Additional filters on other columns can be specified but they may make the query less efficient.
 *
 * ### Examples
 *
 * Use this query to list all stream filters available to the user:
 *
 * `SELECT filter_key FROM stream_filter WHERE uid=<uid>`
 *
 * Use this query to list all stream filters of type 'application' available to this user:
 *
 * `SELECT filter_key FROM stream_filter WHERE uid=<uid> and type="application"`
 *
 * Use this query to get the latest posts of a user for a specific filter, in this case retrieving the news feed of user 3:*
 * (! the extended permission 'read_stream' is required to see results for this query):
 *
 * `SELECT post_id, actor_id, target_id, message FROM stream WHERE filter_key in
 *      (SELECT filter_key FROM stream_filter WHERE uid = 3 AND type = 'newsfeed')`
 *
 * @var array
 * @link https://developers.facebook.com/docs/reference/fql/stream_filter
 */

	$schema = array(

	/**
	 * A key identifying a particular filter for a user's stream
	 */
		'filter_key' => array('type' => 'string'),

	/**
	 * The URL to the filter icon. For applications, this is the same as the application's icon
	 */
		'icon_url' => array('type' => 'string'),

	/**
	 * If true, indicates that the filter is visible on the home page. If false, the filter is hidden in the **More** link
	 */
		'is_visible' => array('type' => 'bool'),

	/**
	 * The name of the filter as it appears on the home page
	 */
		'name' => array('type' => 'string'),

	/**
	 * The rank of where the filter appears in sort on News Feed
	 */
		'rank' => array('type' => 'type'),

	/**
	 * The type of filter. One of `application`, `newsfeed`, `friendlist`, `network`, or `public_profiles`
	 */
		'type' => array('type' => 'string'),

	/**
	 * The ID of the user whose filters you are querying
	 */
		'uid' => array('type' => 'id', 'index' => true),

	/**
	 * ID for the filter type
	 */
		'value' => array('type' => 'number'),

	);