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
 * @file		user.php
 */

/**
 * # User
 *
 * ## Supported Base Where Clauses
 *
 * `SELECT ... FROM user WHERE uid = A`
 * `SELECT ... FROM user WHERE username = A`
 * `SELECT ... FROM user WHERE name = A`
 * `SELECT ... FROM user WHERE third_party_id = A`
 *
 * Note: Additional filters on other columns can be specified but they may make the query less efficient.
 *
 * ## Notes
 *
 *  - You can cache this data and subscribe to real time updates on any of its fields which are also fields
 *    in the corresponding Graph API version.
 *  - No data will be returned by this table if the user has turned off access to Facebook Platform.
 *  - If a user has allowed an application to view certain restricted data then this data will be available
 *    the viewer using the same application with any valid access_token. Note that site privacy settings take precedence.
 *    So if the viewer cannot see this data on the site then they will not be able to see this via FQL.
 *  - A friend can control the information accessible to applications you use through privacy settings on the website.
 *    So for example, if ''Religious and political views'' are set to not be accessible to apps friends use, then even
 *    asking for friends_religion_politics permission and querying the friend's uid for political data will not return a result.
 *
 * @link https://developers.facebook.com/docs/reference/fql/user
 */
	$schema = array(

	/**
	 * More information about the user being queried
	 */
		'about_me' => array('type' => 'string', 'perms' => array('user_about_me', 'friends_about_me')),
	/**
	 * The user's activities
	 */
		'activities' => array('type' => 'string', 'perms' => array('user_activities', 'friends_activities')),
	/**
	 * The networks to which the user being queried belongs. The status field within this field will only return results in English
	 */
		'affiliations' => array('type' => 'string'),
	/**
	 * The user's age range. May be 13-17, 18-20 or 21+.
	 *
	 * `min` unsigned int32 The lower bound of the age range (inclusive).
	 * `max` unsigned int32 The upper bound of the age range (inclusive).Isn't set in case of 21+
	 */
		'age_range' => array('type' => 'struct'),
	/**
	 * A comma-delimited list of demographic restriction types a user is allowed to access.
	 * Currently, alcohol is the only type that can get returned
	 */
		'allowed_restrictions' => array('type' => 'string'),
	/**
	 * The user's birthday. The format of this date varies based on the user's locale
	 */
		'birthday' => array('type' => 'string', 'perms' => array('user_birthday', 'friends_birthday')),
	/**
	 * The user's birthday in MM/DD/YYYY format
	 */
		'birthday_date' => array('type' => 'string', 'perms' => array('user_birthday', 'friends_birthday')),
	/**
	 * The user's favorite books
	 */
		'books' => array('type' => 'string', 'perms' => array('user_likes', 'friends_likes')),
	/**
	 * Whether the user can send a message to another user
	 */
		'can_message' => array('type' => 'boolean'),
	/**
	 * Whether or not the viewer can post to the user's Wall
	 */
		'can_post' => array('type' => 'boolean'),
	/**
	 * A string containing the user's primary Facebook email address.
	 * If the user shared his or her primary email address with you, this address also appears in the email field (see below).
	 * Facebook recommends you query the email field to get the email address shared with your application
	 */
		'contact_email' => array('type' => 'string', 'perms' => array('email')),
	/**
	 * The user's default currencty
	 *
	 * `user_currency` string the ISO-4217-3 code for the user's preferred currency (defaulting to USD if the user hasn't set one)
	 * `currency_exchange` number the number of Facebook Credits that equate in value to one unit of user_currency
	 * `currency_exchange_inverse` number the number of units of user_currency that equate in value to one Credit
	 * `currency_offset` number the number by which a price should be divided for display in user_currency units
	 */
		'currency' => array('type' => 'struct'),
	/**
	 * The current address of the user
	 *
	 * `street` string Street of the location
	 * `city` string City of the location
	 * `state` string State of the location
	 * `country` string Country of the location
	 * `zip` string Zip code of the location
	 * `latitude` float Latitude of the location
	 * `longitude` float Longitude of the location
	 * `id` id ID of the location
	 * `name` string Name of the location
	 * `located_in` id ID of the parent location of this location
	 *
	 * @todo ??perms
	 */
		'current_address' => array('type' => 'struct'),
	/**
	 * The user's current location
	 *
	 * `street` string Street of the location
	 * `city` string City of the location
	 * `state` string State of the location
	 * `country` string Country of the location
	 * `zip` string Zip code of the location
	 * `latitude` float Latitude of the location
	 * `longitude` float Longitude of the location
	 * `id` id ID of the location
	 * `name` string Name of the location
	 * `located_in` id ID of the parent location of this location
	 */
		'current_location' => array('type' => 'struct', 'perms' => array('user_location', 'friends_location')),
	/**
	 * An array of objects containing fields os, which may be a value of 'iOS' or 'Android',
	 * along with an additional field hardware which may be a value of 'iPad' or 'iPhone', if present.
	 * However, hardware may not be returned if we are unable to determine the exact hardware model and only the os
	 */
		'devices' => array('type' => 'array'),
	/**
	 * A list of the user's education history.
	 * Contains year and type fields, and school object (name, id, type, and optional year, degree,
	 * concentration array, classes array, and with array )
	 */
		'education' => array('type' => 'array', 'perms' => array('user_education_history', 'friends_education_history')),
	/**
	 * A string containing the user's primary Facebook email address or the user's proxied email address,
	 * whichever address the user granted your application. Facebook recommends you query this field to ¨
	 * get the email address shared with your application
	 */
		'email' => array('type' => 'string', 'perms' => array('email')),
	/**
	 * An array containing a set of confirmed email hashes for the user.
	 * The format of each email hash is the crc32 and md5 hashes of the email address combined with an underscore (_)
	 */
		'email_hashes' => array('type' => 'array'),
	/**
	 * The user's first name
	 */
		'first_name' => array('type' => 'string'),
	/**
	 * Count of all the user's friends
	 */
		'friend_count' => array('type' => 'integer'),
	/**
	 * The user's number of outstanding friend requests
	 *
	 * @todo ??perms
	 */
		'friend_request_count' => array('type' => 'integer'),
	/**
	 * Whether the user has a timeline or linkable profile
	 */
		'has_timeline' => array('type' => 'boolean'),
	/**
	 * The user's hometown (and state)
	 *
	 * `street` string Street of the location
	 * `city` string City of the location
	 * `state` string State of the location
	 * `country` string Country of the location
	 * `zip` string Zip code of the location
	 * `latitude` float Latitude of the location
	 * `longitude` float Longitude of the location
	 * `id` id ID of the location
	 * `name` string Name of the location
	 * `located_in` id ID of the parent location of this location
	 */
		'hometown_location' => array('type' => 'struct', 'perms' => array('user_hometown', 'friends_hometown')),
	/**
	 * The people who inspire the user
	 */
		'inspirational_people' => array('type' => 'array', 'perms' => array('user_likes', 'friends_likes')),
	/**
	 * App install type of the user
	 */
		'install_type' => array('type' => 'string'),
	/**
	 * The user's interests
	 */
		'interests' => array('type' => 'string', 'perms' => array('user_interests', 'friends_interests')),
	/**
	 * Indicates whether the user being queried has logged in to the current application
	 */
		'is_app_user' => array('type' => 'boolean'),
	/**
	 * Whether the user is blocked by the current session user
	 */
		'is_blocked' => array('type' => 'boolean'),
	/**
	 * Whether the user is a minor
	 *
	 * @todo ??perms
	 */
		'is_minor' => array('type' => 'boolean'),
	/**
	 * The user's languages
	 */
		'languages' => array('type' => 'array'),
	/**
	 * The user's last name
	 */
		'last_name' => array('type' => 'string'),
	/**
	 * Count of all the pages this user has liked
	 */
		'likes_count' => array('type' => 'integer', 'perms' => array('user_likes', 'friends_likes')),
	/**
	 * The two-letter language code and the two-letter country code representing the user's [locale]
	 * (http://www.facebook.com/translations/FacebookLocales.xml).
	 * Country codes are taken from the [ISO 3166 alpha 2 code](http://www.iso.org/iso/iso-3166-1_decoding_table.htmllist)
	 */
		'locale' => array('type' => 'string'),
	/**
	 * A list of the reasons the user being queried wants to meet someone
	 *
	 */
		'meeting_for' => array('type' => 'array', 'perms' => array('user_relationship_details', 'friends_relationship_details')),
	/**
	 * A list of the genders the person the user being queried wants to meet
	 */
		'meeting_sex' => array('type' => 'array', 'perms' => array('user_relationship_details', 'friends_relationship_details')),
	/**
	 * The user's middle name
	 */
		'middle_name' => array('type' => 'string'),
	/**
	 * The user's favorite movies
	 */
		'movies' => array('type' => 'string', 'perms' => array('user_likes', 'friends_likes')),
	/**
	 * The user's favorite music
	 */
		'music' => array('type' => 'string'),
	/**
	 * The number of mutual friends shared by the user being queried and the session user
	 */
		'mutual_friend_count' => array('type' => 'integer'),
	/**
	 * The user's full name
	 */
		'name' => array('type' => 'string', 'index' => true),
	/**
	 * The user's name formatted to correctly handle Chinese, Japanese, Korean ordering
	 */
		'name_format' => array('type' => 'string'),
	/**
	 * The number of notes created by the user being queried
	 *
	 * (number) or (bool)
	 */
		'notes_count' => array('type' => 'integer', 'perms' => array('user_notes', 'friends_notes')),
	/**
	 * The user's Facebook Chat status.
	 * Returns a string, one of active, idle, offline, or error (when Facebook can't determine presence information on the server side).
	 * The query does not return the user's Facebook Chat status when that information is restricted for privacy reasons
	 */
		'online_presence' => array('type' => 'string', 'perms' => array('user_online_presence', 'friends_online_presence')),
	/**
	 * The user's (hashed) payment instruments
	 */
		//'payment_instruments' => array('type' => 'array', 'perms' => array('pay_with_facebook')),
	/**
	 * The user's payment price points
	 */
		//'payment_pricepoints' => array('type' => 'array', 'perms' => array('pay_with_facebook')),
	/**
	 * The URL to the medium-sized profile picture for the user being queried.
	 * The image can have a maximum width of 100px and a maximum height of 300px.
	 * This URL may be blank
	 */
		'pic' => array('type' => 'string'),
	/**
	 * The URL to the largest-sized profile picture for the user being queried.
	 * The image can have a maximum width of 200px and a maximum height of 600px.
	 *
	 * This URL may be blank
	 */
		'pic_big' => array('type' => 'string'),
	/**
	 * The URL to the largest-sized profile picture for the user being queried.
	 * The image can have a maximum width of 200px and a maximum height of 600px, and is overlaid with the Facebook favicon.
	 *
	 * This URL may be blank
	 */
		'pic_big_with_logo' => array('type' => 'string'),
	/**
	 * An array containing the keys cover_id, source, and offset_y which refer to the user's cover photo
	 *
	 * `cover_id` id The ID of the cover photo
	 * `source` string The source of the cover photo
	 * `offset_y` float The offset percentage of the total image height from top [0-100]
	 */
		'pic_cover' => array('type' => 'struct'),
	/**
	 * The URL to the small-sized profile picture for the user being queried.
	 * The image can have a maximum width of 50px and a maximum height of 150px.
	 *
	 * This URL may be blank
	 */
		'pic_small' => array('type' => 'string'),
	/**
	 * The URL to the small-sized profile picture for the user being queried.
	 * The image can have a maximum width of 50px and a maximum height of 150px, and is overlaid with the Facebook favicon.
	 *
	 * This URL may be blank
	 */
		'pic_small_with_logo' => array('type' => 'string'),
	/**
	 * The URL to the square profile picture for the user being queried.
	 * The image can have a maximum width and height of 50px.
	 *
	 * This URL may be blank
	 */
		'pic_square' => array('type' => 'string'),
	/**
	 * The URL to the square profile picture for the user being queried.
	 * The image can have a maximum width and height of 50px, and is overlaid with the Facebook favicon.
	 *
	 * This URL may be blank
	 */
		'pic_square_with_logo' => array('type' => 'string'),
	/**
	 * The URL to the medium-sized profile picture for the user being queried.
	 * The image can have a maximum width of 100px and a maximum height of 300px, and is overlaid with the Facebook favicon.
	 *
	 * This URL may be blank
	 */
		'pic_with_logo' => array('type' => 'string'),
	/**
	 * The user's political views
	 */
		'political' => array('type' => 'string', 'perms' => array('user_religion_politics', 'friends_religion_politics')),
	/**
	 * This string contains the contents of the text box under a user's profile picture
	 */
		'profile_blurb' => array('type' => 'string'),
	/**
	 * The time the profile was most recently updated (UNIX timestamp).
	 * If the user's profile has not been updated in the past three days, this value will be 0
	 */
		'profile_update_time' => array('type' => 'integer'),

	/**
	 * The URL to a user's profile
	 */
		'profile_url' => array('type' => 'string'),
	/**
	 * The proxied wrapper for a user's email address.
	 * If the user shared a proxied email address instead of his or her primary email address with you,
	 * this address also appears in the email field (see above). Facebook recommends you query the email
	 * field to get the email address shared with your application
	 */
		'proxied_email' => array('type' => 'string', 'perms' => array('email')),
	/**
	 * The user's favorite quotes
	 */
		'quotes' => array('type' => 'string'),
	/**
	 * The type of relationship for the user being queried
	 */
		'relationship_status' => array('type' => 'string', 'perms' => array('user_relationships', 'friends_relationships')),
	/**
	 * The user's religion
	 */
		'religion' => array('type' => 'string', 'perms' => array('user_religion_politics', 'friends_religion_politics')),
	/**
	 * The search tokens for the user
	 */
		'search_tokens' => array('type' => 'array'),
	/**
	 * Security settings of the user
	 *
	 * `secure_browsing` struct User's secure browsing settings
	 */
		'security_settings' => array('type' => 'struct'),
	/**
	 * The user's gender
	 */
		'sex' => array('type' => 'string'),
	/**
	 * The user's shipping information
	 */
		//'shipping_information' => array('type' => 'array', 'perms' => array('pay_with_facebook')),
	/**
	 * The user ID of the partner (for example, husband, wife, boyfriend, girlfriend)
	 */
		'significant_other_id' => array('type' => 'integer', 'perms' => array('user_relationships', 'friends_relationships')),
	/**
	 * The user's first name, if the user has a Japanese name
	 */
		'sort_first_name' => array('type' => 'string'),
	/**
	 * The user's last name, if the user has a Japanese name
	 */
		'sort_last_name' => array('type' => 'string'),
	/**
	 * The sports that the user plays. The array objects contain id and name fields
	 */
		'sports' => array('type' => 'array', 'perms' => array('user_likes', 'friends_likes')),
	/**
	 * The user's current status
	 *
	 * `message` string Message of the status
	 * `time` timestamp Time of when the status was posted (UNIX timestamp)
	 * `status_id` id ID of the status
	 * `source` string URL of the status
	 * `uid` id User's ID
	 * `comment_count` unsigned int32 Number of comments on the status
	 */
		'status' => array('type' => 'struct', 'perms' => array('user_status', 'friends_status')),
	/**
	 * The user's total number of subscribers
	 */
		'subscriber_count' => array('type' => 'integer'),
	/**
	 * A string containing an anonymous, but unique identifier for the user. You can use this identifier with third-parties
	 */
		'third_party_id' => array('type' => 'string', 'index' => true),
	/**
	 * The user's timezone offset from UTC
	 */
		'timezone' => array('type' => 'integer'),
	/**
	 * The user's favorite television shows
	 */
		'tv' => array('type' => 'string', 'perms' => array('user_likes', 'friends_likes')),
	/**
	 * The user ID
	 */
		'uid' =>  array('type' => 'integer', 'index' => true),
	/**
	 * The user's username
	 */
		'username' => array('type' => 'string', 'index' => true),
	/**
	 * Indicates whether or not Facebook has verified the user
	 */
		'verified' => array('type' => 'boolean'),
	/**
	 * The size of the video file and the length of the video that a user can upload. This object contains length and size of video
	 *
	 * `length` unsigned int32 Length limit of the video
	 * `size` unsigned int32 Size limit of the video
	 */
		'video_upload_limits' => array('type' => 'struct'),
	/**
	 * Whether the viewer can send gift to this user
	 */
		'viewer_can_send_gift' => array('type' => 'boolean'),
	/**
	 * The number of Wall posts for the user being queried
	 */
		'wall_count' => array('type' => 'integer'),
	/**
	 * The website
	 */
		'website' => array('type' => 'string', 'perms' => array('user_website', 'friends_website')),
	/**
	 * A list of the user's work history. Contains employer, location, position, start_date and end_date fields
	 */
		'work' => array('type' => 'array', 'perms' => array('user_work_history', 'friends_work_history')),
	);