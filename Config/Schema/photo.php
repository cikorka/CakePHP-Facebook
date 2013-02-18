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
 * @file		photo.php
 */

/**
 * # Photo
 *
 * ## Permissions
 *
 * To read the photo table you need
 *
 * - any valid access_token if it is public and owned by the Page.
 * - `user_photos` permissions to access photos and albums uploaded by the user, and photos in which the user has been tagged.
 * - `friends_photos` permissions to access friends' photos and photos in which the user's friends have been tagged.
 *
 * ## Supported Base Where Clauses
 *
 * - SELECT ... FROM photo WHERE pid = A
 * - SELECT ... FROM photo WHERE aid = A
 * - SELECT ... FROM photo WHERE aid = A AND pid = B
 * - SELECT ... FROM photo WHERE album_object_id = A
 * - SELECT ... FROM photo WHERE object_id = A
 * - SELECT ... FROM photo WHERE owner = A
 *
 * Note: Additional filters on other columns can be specified but they may make the query less efficient.
 *
 * ### Notes
 *
 * Both object_id and pid uniquely identify a photo, and object_id is the preferred column to use.
 *
 * @var array
 * @link https://developers.facebook.com/docs/reference/fql/user
 */
	$schema = array(

	/**
	 * The ID of the album containing the photo being queried.
	 * The aid cannot be longer than 50 characters.
	 * Note: Because the aid is a string, you should always wrap the aid in quotes when referenced in a query.
	 */
		'aid' => array('type' => 'string', 'index' => true, 'primary' => true),
	/**
	 * A cursor used to paginated through a query that is indexed on the aid
	 */
		//'aid_cursor' => array('type' => 'string'),
	/**
	 * The object_id of the album the photo belongs to.
	 */
		'album_object_id' => array('type' => 'id', 'index' => true),
	/**
	 * A cursor used to paginate through a query that is indexed on the album_object_id
	 */
		//'album_object_id_cursor' => array('type' => 'string'),
	/**
	 * The time the photo was backdated to in Timeline
	 */
		'backdated_time' => array('type' => 'timestamp'),
	/**
	 * A string representing the backdated granularity. Valid values are year, month, day, hour, or minute
	 */
		'backdated_time_granularity' => array('type' => 'string'),
	/**
	 * true if the viewer is able to backdate the photo
	 */
		'can_backdate' => array('type' => 'bool'),
	/**
	 * true if the viewer is able to delete the photo
	 */
		'can_delete' => array('type' => 'bool'),
	/**
	 * true if the viewer is able to tag the photo
	 */
		'can_tag' => array('type' => 'bool'),
	/**
	 * The caption for the photo being queried.
	 */
		'caption' => array('type' => 'string'),
	/**
	 * An array indexed by offset of arrays of the tags in the caption of the photo,
	 * containing the id of the tagged object, the name of the tag, the offset of where the tag occurs in the
	 * message and the length of the tag.
	 */
		'caption_tags' => array('type' => 'array'),
	/**
	 * The comment information of the photo being queried. This is an object containing can_comment and comment_count
	 *
	 * - `can_comment` bool Whether the comments are allowed on the object
	 * - `comment_count` unsigned int32 The number of comments on this object.
	 */
		'comment_info' => array('type' => 'struct'),
	/**
	 * The date when the photo being queried was added.
	 */
		'created' => array('type' => 'timestamp'),
	/**
	 * An array of objects containing width, height, source each representing the various photo sizes.
	 */
		'images' => array('type' => 'array'),
	/**
	 * The like information of the photo being queried. This is an object containing can_like, like_count, and user_likes
	 *
	 * - `can_like` bool Whether the viewer can like the object.
	 * - `like_count` unsigned int32 The number of likes on this object.
	 * - `user_likes` bool Whether or not the viewer likes this object.
	 */
		'like_info' => array('type' => 'struct'),
	/**
	 * The URL to the page containing the photo being queried.
	 */
		'link' => array('type' => 'string'),
	/**
	 * The date when the photo being queried was last modified.
	 */
		'modified' => array('type' => 'timestamp'),
	/**
	 * The object_id of the photo.
	 */
		'object_id' => array('type' => 'id', 'index' => true),
	/**
	 * The offline_id is specificed when uploading a photo to track the upload status of it later
	 */
		'offline_id' => array('type' => 'integer'),
	/**
	 * The user ID of the owner of the photo being queried.
	 */
		'owner' => array('type' => 'id', 'index' => true),
	/**
	 * A cursor used to paginate through a query that is indexed on the owner
	 */
		//'owner_cursor' => array('type' => 'string'),
	/**
	 * The ID of the feed story about this photo if itbelongs to a page
	 */
		'page_story_id' => array('type' => 'string'),
	/**
	 * The ID of the photo being queried. The pid cannot be longer than 50 characters.
	 * Note: Because the pid is a string, you should always wrap the pid in quotes when referenced in a query.
	 */
		'pid' => array('type' => 'string', 'index' => true),
	/**
	 * Facebook ID of the place associated with the photo, if any.
	 */
		'place_id' => array('type' => 'id'),
	/**
	 * The position of the photo in the album.
	 */
		'position' => array('type' => 'integer'),
	/**
	 * The URL to the album view version of the photo being queried.
	 * The image can have a maximum width or height of 130px. This URL may be blank.
	 */
		'src' => array('type' => 'string'),
	/**
	 * The URL to the full-sized version of the photo being queried.
	 * The image can have a maximum width or height of 720px, increasing to 960px on 1st March 2012. This URL may be blank.
	 */
		'src_big' => array('type' => 'string'),
	/**
	 * Height of the full-sized version, in px. This field may be blank.
	 */
		'src_big_height' => array('type' => 'unsigned int32'),
	/**
	 * Width of the full-sized version, in px. This field may be blank.
	 */
		'src_big_width' => array('type' => 'unsigned int32'),
	/**
	 * Height of the album view version, in px. This field may be blank.
	 */
		'src_height' => array('type' => 'unsigned int32'),
	/**
	 * The URL to the thumbnail version of the photo being queried.
	 * The image can have a maximum width of 75px and a maximum height of 225px. This URL may be blank.
	 */
		'src_small' => array('type' => 'string'),
	/**
	 * Height of the thumbnail version, in px. This field may be blank.
	 */
		'src_small_height' => array('type' => 'unsigned int32'),
	/**
	 * Width of the thumbnail version, in px. This field may be blank.
	 */
		'src_small_width' => array('type' => 'unsigned int32'),
	/**
	 * Width of the album view version, in px. This field may be blank.
	 */
		'src_width' => array('type' => 'unsigned int32'),
	/**
	 * The ID of the target the photo is posted to
	 */
		'target_id' => array('type' => 'numeric string'),
	/**
	 * The type of target the photo is posted to
	 */
		'target_type' => array('type' => 'string'),
	);