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
 * @file		page_admin.php
 */

/**
 * # Page Admin
 *
 * ## Permissions
 *
 * To read the page_admin table you need
 *
 * - `manage_pages` [permissions][1] for the list of pages the current session user is an admin of.
 *
 * ## Supported Base Where Clauses
 *
 * - SELECT ... FROM page_admin WHERE uid = A
 * - SELECT ... FROM page_admin WHERE page_id = A
 *
 * Note: Additional filters on other columns can be specified but they may make the query less efficient.
 *
 * @var array
 * @link https://developers.facebook.com/docs/reference/fql/page
 */
	$schema = array(

	/**
	 * The UNIX timestamp of the last time the admin used this page
	 */
		'last_used_time' => array('type' => 'timestamp'),
	/**
	 * The ID of a Page
	 */
		'page_id' => array('type' => 'numeric string', 'index' => true),
	/**
	 * Admin's permission levels for this page
	 */
		'perms' => array('type' => 'array'),
	/**
	 * Admin's role types for this page
	 */
		'role' => array('type' => 'string'),
	/**
	 * The type of the Page
	 */
		'type' => array('type' => 'string'),
	/**
	 * The User ID of an admin of a Page
	 */
		'uid' => array('type' => 'numeric string', 'index' => true),
	);