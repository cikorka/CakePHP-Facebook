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
 * @file		FacebookStream.php
 */

App::uses('FacebookAppModel', 'Facebook.Model');

/**
 * # Stream
 *
 * ## Permissions
 *
 * To read the stream table you need
 *
 * - `read_stream` permissions for all posts that the current session user is able to view
 * - `read_insights` permissions to see post impressions for any posts made by a Page owned by the current session user
 *
 * ## Supported Base Where Clauses
 *
 * - `SELECT ... FROM stream WHERE post_id = A`
 * - `SELECT ... FROM stream WHERE source_id = A`
 * - `SELECT ... FROM stream WHERE filter_key = A`
 * - `SELECT ... FROM stream WHERE xid = A`
 *
 * Note: Additional filters on other columns can be specified but they may make the query less efficient.
 *
 * ### Examples
 *
 * Use a filter_key to retrieve the current session user's News Feed (Try this query):
 *
 * - `SELECT post_id, actor_id, target_id, message FROM stream WHERE filter_key in
 *     (SELECT filter_key FROM stream_filter WHERE uid=me() AND type='newsfeed') AND is_hidden = 0`
 *
 * Retrieve posts made by the current session user before December 30, 2009 at 12am EST:
 *
 * - `SELECT post_id, actor_id, target_id, message FROM stream WHERE source_id = me() AND created_time < 1262196000 LIMIT 50`
 *
 * Retrieve the action links in a user's stream, and remove any HTML markup and encoding:
 *
 * - `SELECT strip_tags(action_links) FROM stream WHERE source_id = me()`
 *
 * Retrieve Insights for Facebook Page posts (impressions field only available when the current session user is the Page owner):
 *
 * - `SELECT post_id, actor_id, message, impressions FROM stream WHERE actor_id = 19292868552 and source_id = 19292868552`
 *
 * See posts by others on the current session user's profile
 *
 * - `SELECT post_id, actor_id, target_id, message FROM stream WHERE filter_key = 'others' AND source_id = me()`
 *
 * ### Notes
 *
 * The User, Page, Application and Group Graph API objects have equivalent feed connections containing
 * Post objects that represent their walls. In addition the User and Page objects have a connection named posts containing
 * Post objects made by the User and the Page respectively.
 *
 * If you specify a filter_key from the stream_filter FQL table or multiple users, results returned will behave like the user's
 * homepage news feed. If only one user is specified as the source_id, you will receive the profile view of the user or page.
 * You can filter these profile view posts by specifying filter_key 'others' (return only posts that are by someone other
 * than the specified user) or 'owner' (return only posts made by the specified user).
 * The profile view, unlike the homepage view, returns older data from our databases. In the case of a Page,
 * the profile view also includes posts by fans.
 *
 * Each query of the stream table is limited to the previous 30 days or 50 posts, whichever is greater,
 * however you can use time-specific fields such as created_time along with FQL operators (such as < or >) to retrieve a
 * much greater range of posts.
 *
 * @var array
 * @link https://developers.facebook.com/docs/reference/fql/stream
 */

class FacebookStream extends FacebookAppModel {

/**
 * Custom database table name, or null/false if no table association is desired.
 *
 * @var string
 * @link http://book.cakephp.org/2.0/en/models/model-attributes.html#usetable
 */
	public $useTable = 'stream';

/**
 * The name of the primary key field for this model.
 *
 * @var string
 * @link http://book.cakephp.org/2.0/en/models/model-attributes.html#primaryKey
 */
	public $primaryKey = 'post_id';

}