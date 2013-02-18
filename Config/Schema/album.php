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
 * @file		album.php
 */

/**
 * # Album
 *
 * ## Permissions
 *
 * To read the album table you need
 *
 * - `user_photos` [permissions][1] if it is not public and belongs to the user.
 * - `friends_photos` [permissions][1] if it is not public and belongs to a user's friend.
 *
 * ## Supported Base Where Clauses
 *
 * - SELECT ... FROM album WHERE object_id = A
 * - SELECT ... FROM album WHERE owner = A
 * - SELECT ... FROM album WHERE aid = A
 *
 * Note: Additional filters on other columns can be specified but they may make the query less efficient.
 *
 * ### Notes
 *
 * The User, Page and Application Graph API objects have an equivalent albums connection of type Album.
 *
 * @var array
 * @link https://developers.facebook.com/docs/reference/fql/album
 */
	$schema = array(

	/**
	 * The album ID
	 */
		'aid' => array('type' => 'string', 'index' => true, 'primary' => true),
	/**
	 * Time that the album is backdated to
	 */
		'backdated_time' => array('type' => 'timestamp'),
	/**
	 * Can the album be backdated on Timeline
	 */
		'can_backdate' => array('type' => 'can_backdate'),
	/**
	 * Determines whether a given UID can upload to the album. It is true if the following conditions are met:
	 * The user owns the album, the album is not a special album like the profile pic album, the album is not full.
	 */
		'can_upload' => array('type' => 'bool'),
	/**
	 * The comment information of the album being queried. This is an object containing can_comment and comment_count
	 *
	 * - `can_comment` bool Whether the comments are allowed on the object
	 * - `comment_count` unsigned int32 The number of comments on this object.
	 */
		'comment_info' => array('type' => 'struct'),
	/**
	 * The album cover photo object_id
	 */
		'cover_object_id' => array('type' => 'numeric string'),
	/**
	 * The album cover photo ID string
	 */
		'cover_pid' => array('type' => 'string'),
	/**
	 * The time the photo album was initially created expressed as UNIX time.
	 */
		'created' => array('type' => 'timestamp'),
	/**
	 * The description of the album
	 */
		'description' => array('type' => 'string'),
	/**
	 * The URL for editing the album
	 */
		'edit_link' => array('type' => 'string'),
	/**
	 * Determines whether or not the album should be shown to users.
	 */
		'is_user_facing' => array('type' => 'bool'),
	/**
	 * The like information of the album being queried. This is an object containing can_like, like_count, and user_likes
	 *
	 * - `can_like` bool Whether the viewer can like the object.
	 * - `like_count` unsigned int32 The number of likes on this object.
	 * - `user_likes` bool Whether or not the viewer likes this object.
	 */
		'like_info' => array('type' => 'struct'),
	/**
	 * A link to this album on Facebook
	 */
		'link' => array('type' => 'string'),
	/**
	 * The location of the album
	 */
		'location' => array('type' => 'string'),
	/**
	 * The last time the photo album was updated expressed as UNIX time.
	 */
		'modified' => array('type' => 'timestamp'),
	/**
	 * Indicates the time a major update (like addition of photos) was last made to the album expressed as UNIX time.
	 */
		'modified_major' => array('type' => 'timestamp'),
	/**
	 * The title of the album
	 */
		'name' => array('type' => 'string'),
	/**
	 * The object_id of the album on Facebook. This is used to identify the equivalent Album object in the Graph API.
	 * You can also use the object_id to let users comment on an album with the Graph API Comments
	 */
		'object_id' => array('type' => 'id', 'index' => true),
	/**
	 * The user ID of the owner of the album
	 */
		'owner' => array('type' => 'id', 'index' => true),
	/**
	 * Cursor for the owner field
	 */
		//'owner_cursor' => array('type' => 'string'),
	/**
	 * The number of photos in the album
	 */
		'photo_count' => array('type' => 'unsigned int32'),
	/**
	 * Facebook ID of the place associated with the album, if any.
	 */
		'place_id' => array('type' => 'numeric string'),
	/**
	 * The type of photo album. Can be one of profile: The album containing profile pictures, mobile:
	 * The album containing mobile uploaded photos, wall: The album containing photos posted to a user's Wall, normal:
	 * For all other albums.
	 */
		'type' => array('type' => 'string'),
	/**
	 * The number of videos in the album
	 */
		'video_count' => array('type' => 'unsigned int32'),
	/**
	 * Visible only to the album owner. Indicates who can see the album.
	 * The value can be one of friends, friends-of-friends, networks, everyone, custom
	 * (if the visibility doesn't match any of the other values)
	 */
		'visible' => array('type' => 'visible'),
	);