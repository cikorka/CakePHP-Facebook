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
 * @file		stream.php
 */

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
	$schema = array(

	/**
	 * An array containing the text and URL for each action link
	 */
		'action_links' => array('type' => 'array'),
	
	/**
	 * The ID of the user, page, group, or event that published the post
	 */
		'actor_id' => array('type' => 'unsigned int32'),
	
	/**
	 * An array of app-specific information optionally supplied to create the attachment to the post

	 * `tbid` number Template bundle ID for the post
	 * `attachment_data` string Attachment data associated with the post
	 * `images` string` Images associated with the post
	 * `photo_ids` array Photo IDs associated with the post
	 */
		'app_data' => array('type' => 'struct'),
	
	/**
	 * For posts published by apps, the ID of that app. If the value is empty, it indicates a Facebook feature generated the post
	 */
		'app_id' => array('type' => 'id'),
	
	/**
	 * An array of information about the attachment to the post
	 *
	 * `media` array Rich media that provides visual content for the post
	 * `name` string The title of the post
	 * `href` string The URL of the attachment
	 * `caption` string A subtitle for the post
	 * `description` string Descriptive text about the story
	 * `properties` array A dictionary of key/value pairs that provide more information about the post
	 * `icon` string The icon for the attachment
	 * `fb_object_type` string Object type of the attachment
	 * `fb_object_id` string Object ID of the attachmenet
	 * `fb_checkin` struct A checkin object
	 * `tagged_ids` array Tagged user IDs
	 */
		'attachment' => array('type' => 'struct'),
	
	/**
	 * For posts published by apps, the full name of that app
	 */
		'attribution' => array('type' => 'string'),
	
	/**
	 * Count for how many people claimed the offer
	 */
		'claim_count' => array('type' => 'unsigned int32'),
	
	/**
	 * An array containing comments information
	 *
	 * `can_remove` bool Whether the comment has been removed
	 * `can_post` bool Whether the user can post comment on the post
	 * `count` unsigned int32 The number of comments for the post
	 * `comment_list` array The list of comments on the post
	 */
		'comments' => array('type' => 'struct'),
	
	/**
	 * The time the post was published, expressed as UNIX timestamp
	 */
		'created_time' => array('type' => 'timestamp'),
	
	/**
	 * Text of stories not intentionally generated by users, such as those generated when two users become friends. 
	 * You must have the "Include recent activity stories" migration enabled in your app to retrieve this field
	 */
		'description' => array('type' => 'string'),
	
	/**
	 * The list of tags in the post description
	 */
		'description_tags' => array('type' => 'array'),
	
	/**
	 * UNIX timestamp of when the offer expires
	 */
		'expiration_timestamp' => array('type' => 'string'),
	
	/**
	 * Feed targeting information
	 *
	 * `country` string Targeting country
	 * `cities` array Targeting cities
	 * `regions` array Targeting regions
	 * `zips` array Targeting locations' zip codes
	 * `genders` array Targeting genders
	 * `college_networks` array Targeting college networks
	 * `work_networks` array Targeting work networks
	 * `age_min` (number) or (numeric string) Targeting user minimum age
	 * `age_max` (number) or (numeric string) Targeting user maximum age
	 * `education_statuses` array Targeting user education statuses
	 * `college_years` array Targeting user year in college
	 * `college_majors` array Targeting user college majors
	 * `political_views` array Targeting user political views
	 * `relationship_statuses` array Targeting relationship statuses
	 * `keywords` array Targeting keywords
	 * `interested_in` array Targeting user interests
	 * `user_clusters` array Targeting user cluster
	 * `user_clusters2` array Second targeting user cluster
	 * `user_clusters3` array Third targeting user cluster
	 * `user_adclusters` array Targeting user ad clusters
	 * `excluded_user_adclusters` array Excluded user ad clusters
	 * `custom_audiences` array Custom list of users to target to
	 * `excluded_custom_audiences` array Custom list of users to exclude targeting to
	 * `retargeting_audiences` array List of users to retarget to
	 * `excluded_retargeting_audiences` array List of users to exclude retargeting to
	 * `locales` array Targeting user locales
	 * `radius` numeric string Targeting user located within a radius of the location
	 * `connections` array Targeting connections
	 * `excluded_connections` array Connections to exclude targeting to
	 * `friends_of_connections` array Friends of connections to target to
	 * `countries` array Targeting countries
	 * `excluded_user_clusters` array User clusters to exclude targeting to
	 * `adgroup_id` (number) or (numeric string) ID of the ad group
	 * `user_event` array User event
	 * `qrt_versions` array Targeting QRT versions
	 * `page_types` array Types of the pages to target to
	 * `app_types` array Types of apps to target to
	 * `broad_age` (number) or (numeric string) Whether or not to enable "broad-age-match" targeting. 
	 *  The default is 0 (disabled). Set to 1 to enable broad-age-match targeting
	 * `action_spec` string See the documentation page Action Spec Targeting for more details
	 * `action_spec_friend` string See the documentation page Action Spec Targeting for more details
	 * `action_spec_excluded` string See the documentation page Action Spec Targeting for more details
	 * `context` array Additional list of codes
	 */
		'feed_targeting' => array('type' => 'struct'),
	
	/**
	 * The filter key to fetch data with. 
	 * This key should be retrieved by querying the stream_filter FQL table or with the special values 'others' or 'owner'.
	 */
		'filter_key' => array('type' => 'string', 'index' => true),
	
	/**
	 * Number of impressions of this post. 
	 * This data is visible only if you have [read_insights](/docs/authentication/permissions) permission from a page owner
	 */
		'impressions' => array('type' => 'unsigned int32'),
	
	/**
	 * Whether the post is exportable
	 */
		'is_exportable' => array('type' => 'number'),
	
	/**
	 * Whether a post has been set to hidden
	 */
		'is_hidden' => array('type' => 'bool'),
	
	/**
	 * Whether the post is published
	 */
		'is_published' => array('type' => 'bool'),
	
	/**
	 * An array containing likes information
	 * 
	 * `href` string URL of the liked post
	 * `count` number The total number of likes
	 * `sample` array List of sample user IDs as who liked the post
	 * `friends` array List of friends' user IDs who liked the post
	 * `user_likes` bool Whether the user has liked this post
	 * `can_like` bool Whether the user is able to like this post
	 */
		'likes' => array('type' => 'struct'),
	
	/**
	 * The message written in the post
	 */
		'message' => array('type' => 'string'),
	
	/**
	 * The list of tags in the post message
	 */
		'message_tags' => array('type' => 'array'),
	
	/**
	 * ID of the parent post
	 */
		'parent_post_id' => array('type' => 'string'),
	
	/**
	 * The URL of the post
	 */
		'permalink' => array('type' => 'string'),
	
	/**
	 * ID of the place associated with the post
	 */
		'place' => array('type' => 'id'),
	
	/**
	 * The ID of the post
	 */
		'post_id' => array('type' => 'string', 'index' => true),
	
	/**
	 * The privacy settings for a post
	 * 
	 * `description` string A description of the privacy settings. 
	 *  For custom settings, it can contain names of users, networks, and friend lists.
	 * `value` string The privacy value for the object, one of: EVERYONE, CUSTOM, ALL_FRIENDS, NETWORKS_FRIENDS, FRIENDS_OF_FRIENDS, SELF.
	 * `friends` string Which users can see the object. 
	 *  Can be one of: EVERYONE, NETWORKS_FRIENDS, FRIENDS_OF_FRIENDS, ALL_FRIENDS, SOME_FRIENDS, SELF, NO_FRIENDS.
	 * `networks` string The ID of the network that can see the object, or 1 for all of the user's networks.
	 * `allow` string The UIDs of the specific users or the IDs of friendlists that can see the object (as a comma-separated string).
	 * `deny` string The UIDs of the specific users or the IDs of friendlists that cannot see the object (as a comma-separated string).
	 */
		'privacy' => array('type' => 'struct'),
	
	/**
	 * Status of the promotion, if the post was promoted
	 */
		'promotion_status' => array('type' => 'string'),
	
	/**
	 * UNIX timestamp of the scheduled publish time for the post
	 */
		'scheduled_publish_time' => array('type' => 'timestamp'),
	
	/**
	 * Number of times the post has been shared
	 */
		'share_count' => array('type' => 'unsigned int32'),
	
	/**
	 * The ID of the user, page, group, or event whose wall the post is on
	 */
		'source_id' => array('type' => 'id', 'index' => true),
	
	/**
	 * Whether user is subscribed to the post
	 */
		'subscribed' => array('type' => 'bool'),
	
	/**
	 * An array of IDs tagged in the message of the post.
	 */
		'tagged_ids' => array('type' => 'array'),
	
	/**
	 * The user, page, group, or event to whom the post was directed
	 */
		'target_id' => array('type' => 'id'),
	
	/**
	 * Ads targeting information of the post
	 *
	 * `country` string Targeting country
	 * `cities` array Targeting cities
	 * `regions` array Targeting regions
	 * `zips` array Targeting locations' zip codes
	 * `genders` array Targeting genders
	 * `college_networks` array Targeting college networks
	 * `work_networks` array Targeting work networks
	 * `age_min` (number) or (numeric string) Targeting user minimum age
	 * `age_max` (number) or (numeric string) Targeting user maximum age
	 * `education_statuses` array Targeting user education statuses
	 * `college_years` array Targeting user year in college
	 * `college_majors` array Targeting user college majors
	 * `political_views` array Targeting user political views
	 * `relationship_statuses` array Targeting relationship statuses
	 * `keywords` array Targeting keywords
	 * `interested_in` array Targeting user interests
	 * `user_clusters` array Targeting user cluster
	 * `user_clusters2` array Second targeting user cluster
	 * `user_clusters3` array Third targeting user cluster
	 * `user_adclusters` array Targeting user ad clusters
	 * `excluded_user_adclusters` array Excluded user ad clusters
	 * `custom_audiences` array Custom list of users to target to
	 * `excluded_custom_audiences` array Custom list of users to exclude targeting to
	 * `retargeting_audiences` array List of users to retarget to
	 * `excluded_retargeting_audiences` array List of users to exclude retargeting to
	 * `locales` array Targeting user locales
	 * `radius` numeric string Targeting user located within a radius of the location
	 * `connections` array Targeting connections
	 * `excluded_connections` array Connections to exclude targeting to
	 * `friends_of_connections` array Friends of connections to target to
	 * `countries` array Targeting countries
	 * `excluded_user_clusters` array User clusters to exclude targeting to
	 * `adgroup_id` (number) or (numeric string) ID of the ad group
	 * `user_event` array User event
	 * `qrt_versions` array Targeting QRT versions
	 * `page_types` array Types of the pages to target to
	 * `app_types` array Types of apps to target to
	 * `broad_age` (number) or (numeric string) 
	 *  Whether or not to enable "broad-age-match" targeting. The default is 0 (disabled). Set to 1 to enable broad-age-match targeting
	 * `action_spec` string See the documentation page Action Spec Targeting for more details
	 * `action_spec_friend` string See the documentation page Action Spec Targeting for more details
	 * `action_spec_excluded` string See the documentation page Action Spec Targeting for more details
	 * `context` array Additional list of codes
	 */
		'targeting' => array('type' => 'struct'),
	
	/**
	 * Timeline visibility information of the post
	 */
		'timeline_visibility' => array('type' => 'string'),
	
	/**
	 * The type of this story. 
	 * Possible values are:
	 * - `11` - Group created 
	 * - `12` - Event created 
	 * - `46` - Status update 
	 * - `56` - Post on wall from another user 
	 * - `66` - Note created 
	 * - `80` - Link posted 
	 * - `128` -Video posted 
	 * - `247` - Photos posted 
	 * - `237` - App story 
	 * - `257` - Comment created 
	 * - `272` - App story 
	 * - `285` - Checkin to a place 
	 * - `308` - Post in Group
	 */
		'type' => array('type' => 'int32'),
	
	/**
	 * The time the post was last updated, which occurs when a user comments on the post, expressed as a UNIX timestamp
	 */
		'updated_time' => array('type' => 'timestamp'),
	
	/**
	 * ID of the user or Page the post was shared from
	 */
		'via_id' => array('type' => 'numeric string'),
	
	/**
	 * The ID of the current session user
	 */
		'viewer_id' => array('type' => 'id'),
	
	/**
	 * ID of the location associated with the post
	 */
		'with_location' => array('type' => 'bool'),
	
	/**
	 * An array of IDs of entities (e.g. users) tagged in this post
	 */
		'with_tags' => array('type' => 'array'),
	
	/**
	 * When querying for the feed of a live stream box, this is the xid associated with the Live Stream box 
	 * (you can provide 'default' if one is not available)
	 */
		'xid' => array('type' => 'string', 'index' => true),
	);