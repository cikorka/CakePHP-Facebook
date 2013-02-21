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
 * @file		permissions.php
 */

/**
 * # Permissions
 *
 * ## Permissions
 *
 * To read the permissions table you need one of
 *
 * - a user access_token for a user of your app.
 * - an app access_token for your app (described under App Login on the Authentication page.)
 *
 * ## Supported Base Where Clauses
 *
 * - SELECT ... FROM permissions WHERE uid = A
 *
 * Note: Additional filters on other columns can be specified but they may make the query less efficient.
 *
 * ## Notes
 *
 * The User object in the Graph API has an equivalent permissions connection which is an associative array of the granted permissions.
 *
 * The permissions granted to your app by a User can also be subscribed to via real time updates in the Graph API.
 *
 * The 'Columns' list below is abbreviated.
 * In addition to the uid column, there is one virtual column in this table for each permission which is available
 * to be requested by applications.
 * A common use of this table is checking which of a set of desired permissions have been granted to your application.
 *
 * Your queries should be of the form
 *
 * `select <permissions> from permissions where uid = me()`
 *
 * where <permissions> is a comma separated list of the permissions you wish to check.
 *
 * @var array
 * @link https://developers.facebook.com/docs/reference/fql/permissions
 * @link https://developers.facebook.com/docs/authentication/permissions
 */
	$schema = array(

	/**
	 * Provides the ability to manage ads and call the Facebook Ads API on behalf of a user.
	 */
		'ads_management' => array('type' => 'boolean'),
		//'bookmarked' => array('type' => 'boolean'),

	/**
	 * Enables your application to create and modify events on the user's behalf
	 */
		'create_event' => array('type' => 'boolean'),
		'create_note' => array('type' => 'boolean'),

	/**
	 * Provides access to the user's primary email address in the email property.
	 * Do not spam users. Your use of email must comply both with Facebook policies and with the CAN-SPAM Act.
	 *
	 * @link https://www.facebook.com/terms.php
	 * @link http://www.ftc.gov/bcp/edu/pubs/business/ecommerce/bus61.shtm
	 */
		'email' => array('type' => 'boolean'),
		'export_stream' => array('type' => 'boolean'),

	/**
	 * Provides access to the "About Me" section of the profile in the about property
	 */
		'friends_about_me' => array('type' => 'boolean'),

	/**
	 * Provides access to the user's list of activities as the activities connection
	 */
		'friends_activities' => array('type' => 'boolean'),

	/**
	 * Provides access to the birthday with year as the birthday property.
	 * Note that your app may determine if a user is "old enough" to use an app by obtaining the age_range public profile property
	 *
	 * @link https://developers.facebook.com/docs/reference/login/public-profile-and-friend-list/
	 */
		'friends_birthday' => array('type' => 'boolean'),

	/**
	 * Provides read access to the authorized user's check-ins or a friend's check-ins that the user can see.
	 * This permission is superseded by user_status for new applications as of March, 2012.
	 */
		'friends_checkins' => array('type' => 'boolean'),

	/**
	 * Provides access to education history as the education property
	 */
		'friends_education_history' => array('type' => 'boolean'),

	/**
	 * Provides access to the list of events the user is attending as the events connection
	 */
		'friends_events' => array('type' => 'boolean'),

	/**
	 * NOT DESCRIPTED ON FACEBOOK DEVELOPERS PORTAL
	 *
	 * Provide acces to games activity property
	 *
	 */
		'friends_games_activity' => array('type' => 'boolean'),

	/**
	 * Provides access to the list of groups the user is a member of as the groups connection
	 */
		'friends_groups' => array('type' => 'boolean'),

	/**
	 * Provides access to the user's hometown in the hometown property
	 */
		'friends_hometown' => array('type' => 'boolean'),

	/**
	 * Provides access to the user's list of interests as the interests connection
	 */
		'friends_interests' => array('type' => 'boolean'),

	/**
	 * Provides access to the list of all of the pages the user has liked as the likes connection
	 */
		'friends_likes' => array('type' => 'boolean'),

	/**
	 * Provides access to the user's current city as the location property
	 */
		'friends_location' => array('type' => 'boolean'),

	/**
	 * Provides access to the user's notes as the notes connection
	 */
		'friends_notes' => array('type' => 'boolean'),

	/**
	 * Provides access to the user's friend's online/offline presence
	 */
		'friends_online_presence' => array('type' => 'boolean'),

	/**
	 * NOT DESCRIPTED ON FACEBOOK DEVELOPERS PORTAL
	 */
		'friends_photo_video_tags' => array('type' => 'boolean'),

	/**
	 * Provides access to the photos the user has uploaded, and photos the user has been tagged in
	 */
		'friends_photos' => array('type' => 'boolean'),

	/**
	 * Provides access to the questions the user or friend has asked
	 */
		'friends_questions' => array('type' => 'boolean'),

	/**
	 * Provides access to the user's relationship preferences
	 */
		'friends_relationship_details' => array('type' => 'boolean'),

	/**
	 * Provides access to the user's family and personal relationships and relationship status
	 */
		'friends_relationships' => array('type' => 'boolean'),

	/**
	 * Provides access to the user's religious and political affiliations
	 */
		'friends_religion_politics' => array('type' => 'boolean'),

	/**
	 * Provides access to the user's status messages and checkins.
	 * Please see the documentation for the location_post table for information on how this
	 * permission may affect retrieval of information about the locations associated with posts.
	 *
	 * @link https://developers.facebook.com/docs/reference/fql/location_post/
	 */
		'friends_status' => array('type' => 'boolean'),

	/**
	 * Provides access to the user's subscribers and subscribees
	 */
		'friends_subscriptions' => array('type' => 'boolean'),

	/**
	 * Provides access to the videos the user has uploaded, and videos the user has been tagged in
	 */
		'friends_videos' => array('type' => 'boolean'),

	/**
	 * Provides access to the user's web site URL
	 */
		'friends_website' => array('type' => 'boolean'),

	/**
	 * Provides access to work history as the work property
	 */
		'friends_work_history' => array('type' => 'boolean'),

	/**
	 * Enables your app to create and edit the user's friend lists.
	 */
		'manage_friendlists' => array('type' => 'boolean'),

	/**
	 * Enables your app to read notifications and mark them as read.
	 *
	 * ### Intended usage
	 *
	 * This permission should be used to let users read and act on their notifications;
	 * it should not be used to for the purposes of modeling user behavior or data mining.
	 * Apps that misuse this permission may be banned from requesting it.
	 */
		'manage_notifications' => array('type' => 'boolean'),

	/**
	 * Enables your application to retrieve access_tokens for Pages and Applications that the user administrates.
	 * The access tokens can be queried by calling /<user_id>/accounts via the Graph API.
	 * See here for generating long-lived Page access tokens that do not expire after 60 days.
	 *
	 * @link https://developers.facebook.com/roadmap/offline-access-removal/#page_access_token
	 */
		'manage_pages' => array('type' => 'boolean'),
		'photo_upload' => array('type' => 'boolean'),
		'publish_actions' => array('type' => 'boolean'),

	/**
	 * Enables your app to perform checkins on behalf of the user.
	 */
		'publish_checkins' => array('type' => 'boolean'),

	/**
	 * Enables your app to post content, comments, and likes to a user's stream and to the streams of the user's friends.
	 * This is a superset publishing permission which also includes publish_actions.
	 * However, please note that Facebook recommends a user-initiated sharing model.
	 * Please read the Platform Policies to ensure you understand how to properly use this permission.
	 * Note, you do not need to request the publish_stream permission in order to use the Feed Dialog,
	 * the Requests Dialog or the Send Dialog.
	 *
	 * @link https://developers.facebook.com/docs/publishing/
	 * @link https://developers.facebook.com/policy/
	 * @link https://developers.facebook.com/docs/reference/dialogs/feed/
	 * @link https://developers.facebook.com/docs/reference/dialogs/requests/
	 * @link https://developers.facebook.com/docs/reference/dialogs/send/
	 */
		'publish_stream' => array('type' => 'boolean'),

	/**
	 * Provides access to any friend lists the user created.
	 * All user's friends are provided as part of basic data,
	 * this extended permission grants access to the lists of friends a user has created,
	 * and should only be requested if your application utilizes lists of friends.
	 */
		'read_friendlists' => array('type' => 'boolean'),

	/**
	 * Provides read access to the Insights data for pages, applications, and domains the user owns.
	 */
		'read_insights' => array('type' => 'boolean'),

	/**
	 * Provides the ability to read from a user's Facebook Inbox.
	 */
		'read_mailbox' => array('type' => 'boolean'),
		'read_page_mailboxes' => array('type' => 'boolean'),

	/**
	 * Provides read access to the user's friend requests
	 */
		'read_requests' => array('type' => 'boolean'),

	/**
	 * Provides access to all the posts in the user's News Feed and enables your application to
	 * perform searches against the user's News Feed
	 */
		'read_stream' => array('type' => 'boolean'),

	/**
	 * Enables your application to RSVP to events on the user's behalf
	 */
		'rsvp_event' => array('type' => 'boolean'),
		'share_item' => array('type' => 'boolean'),
		'sms' => array('type' => 'boolean'),
		'status_update' => array('type' => 'boolean'),
		//'tab_added' => array('type' => 'boolean'),
		//'uid' => array('index' => true),

	/**
	 * Provides access to the "About Me" section of the profile in the about property
	 */
		'user_about_me' => array('type' => 'boolean'),

	/**
	 * Provides access to the user's list of activities as the activities connection
	 */
		'user_activities' => array('type' => 'boolean'),

	/**
	 * Provides access to the birthday with year as the birthday property.
	 * Note that your app may determine if a user is "old enough" to use an app by obtaining the age_range public profile property
	 *
	 * @link https://developers.facebook.com/docs/reference/login/public-profile-and-friend-list/
	 */
		'user_birthday' => array('type' => 'boolean'),

	/**
	 * Provides read access to the authorized user's check-ins or a friend's check-ins that the user can see.
	 * This permission is superseded by user_status for new applications as of March, 2012.
	 */
		'user_checkins' => array('type' => 'boolean'),

	/**
	 * Provides access to education history as the education property
	 */
		'user_education_history' => array('type' => 'boolean'),

	/**
	 * Provides access to the list of events the user is attending as the events connection
	 */
		'user_events' => array('type' => 'boolean'),

	/**
	 * NOT DESCRIPTED ON FACEBOOK DEVELOPERS PORTAL
	 *
	 * Provide acces to games activity property
	 *
	 */
		'user_games_activity' => array('type' => 'boolean'),

	/**
	 * Provides access to the list of groups the user is a member of as the groups connection
	 */
		'user_groups' => array('type' => 'boolean'),

	/**
	 * Provides access to the user's hometown in the hometown property
	 */
		'user_hometown' => array('type' => 'boolean'),

	/**
	 * Provides access to the user's list of interests as the interests connection
	 */
		'user_interests' => array('type' => 'boolean'),

	/**
	 * Provides access to the list of all of the pages the user has liked as the likes connection
	 */
		'user_likes' => array('type' => 'boolean'),

	/**
	 * Provides access to the user's current city as the location property
	 */
		'user_location' => array('type' => 'boolean'),

	/**
	 * Provides access to the user's notes as the notes connection
	 */
		'user_notes' => array('type' => 'boolean'),

	/**
	 * Provides access to the user's online/offline presence
	 */
		'user_online_presence' => array('type' => 'boolean'),

	/**
	 * NOT DESCRIPTED ON FACEBOOK DEVELOPERS PORTAL
	 */
		'user_photo_video_tags' => array('type' => 'boolean'),

	/**
	 * Provides access to the photos the user has uploaded, and photos the user has been tagged in
	 */
		'user_photos' => array('type' => 'boolean'),

	/**
	 * Provides access to the questions the user or friend has asked
	 */
		'user_questions' => array('type' => 'boolean'),

	/**
	 * Provides access to the user's relationship preferences
	 */
		'user_relationship_details' => array('type' => 'boolean'),

	/**
	 * Provides access to the user's family and personal relationships and relationship status
	 */
		'user_relationships' => array('type' => 'boolean'),

	/**
	 * Provides access to the user's religious and political affiliations
	 */
		'user_religion_politics' => array('type' => 'boolean'),

	/**
	 * Provides access to the user's status messages and checkins.
	 * Please see the documentation for the location_post table for information on how this
	 * permission may affect retrieval of information about the locations associated with posts.
	 *
	 * @link https://developers.facebook.com/docs/reference/fql/location_post/
	 */
		'user_status' => array('type' => 'boolean'),

	/**
	 * Provides access to the user's subscribers and subscribees
	 */
		'user_subscriptions' => array('type' => 'boolean'),

	/**
	 * Provides access to the videos the user has uploaded, and videos the user has been tagged in
	 */
		'user_videos' => array('type' => 'boolean'),

	/**
	 * Provides access to the user's web site URL
	 */
		'user_website' => array('type' => 'boolean'),

	/**
	 * Provides access to work history as the work property
	 */
		'user_work_history' => array('type' => 'boolean'),
		'video_upload' => array('type' => 'boolean'),

	/**
	 * Provides applications that integrate with Facebook Chat the ability to log in users.
	 */
		'xmpp_login' => array('type' => 'boolean'),
	);