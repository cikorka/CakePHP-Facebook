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
 * @file		page.php
 */

/**
 * # Page
 *
 * ## Permissions
 *
 * To read the page table you need
 *
 * - no access_token to get public information, or information about public pages that are not demographically restricted.
 * - any valid access_token to get all information about a page that the current session user is able to see.
 *
 * ## Supported Base Where Clauses
 *
 * - SELECT ... FROM page WHERE page_id = A
 * - SELECT ... FROM page WHERE name = A
 * - SELECT ... FROM page WHERE username = A
 * - SELECT ... FROM page WHERE keywords = A
 *
 * Note: Additional filters on other columns can be specified but they may make the query less efficient.
 *
 * @var array
 * @link https://developers.facebook.com/docs/reference/fql/page
 */
	$schema = array(

	/**
	 * The About section of the Page
	 */
		'about' => array('type' => 'string'),
	/**
	 * The access token you can use to act as the Page
	 */
		'access_token' => array('type' => 'string'),
	/**
	 * Affiliation of this person. Applicable to Pages representing People
	 */
		'affiliation' => array('type' => 'string'),
	/**
	 * App ID for app-owned pages and app pages
	 */
		'app_id' => array('type' => 'numeric string'),
	/**
	 * Artists the band likes. Applicable to Bands
	 */
		'artists_we_like' => array('type' => 'string'),
	/**
	 * Dress code of the business. Applicable to Restaurants or Nightlife. Can be one of Casual, Dressy or Unspecified
	 */
		'attire' => array('type' => 'string'),
	/**
	 * The awards information of the film. Applicable to Films
	 */
		'awards' => array('type' => 'string'),
	/**
	 * Band interests. Applicable to Bands
	 */
		'band_interests' => array('type' => 'string'),
	/**
	 * Members of the band. Applicable to Bands
	 */
		'band_members' => array('type' => 'string'),
	/**
	 * Biography of the band. Applicable to Bands
	 */
		'bio' => array('type' => 'string'),
	/**
	 * Birthday of this person. Applicable to Pages representing People
	 */
		'birthday' => array('type' => 'string'),
	/**
	 * Booking agent of the band. Applicable to Bands
	 */
		'booking_agent' => array('type' => 'string'),
	/**
	 * The budget recommendations for a promoted post
	 *
	 *  - `budget_list` array The list of budgets
	 */
		//'budget_recs' => array('type' => 'struct'),
	/**
	 * Built of the vehicle. Applicable to Vehicles
	 */
		'built' => array('type' => 'string'),
	/**
	 * Indicates whether the current session user can post on this Page
	 */
		'can_post' => array('type' => 'bool'),
	/**
	 * The categories of the Page
	 */
		'categories' => array('type' => 'array'),
	/**
	 * Number of checkins at a place represented by a Page
	 */
		'checkins' => array('type' => 'unsigned int32'),
	/**
	 * The company overview. Applicable to Companies
	 */
		'company_overview' => array('type' => 'string'),
	/**
	 * Culinary team of the business. Applicable to Restaurants or Nightlife
	 */
		'culinary_team' => array('type' => 'string'),
	/**
	 * Current location of the band. Applicable to Bands
	 */
		'current_location' => array('type' => 'string'),
	/**
	 * The description of the Page
	 */
		'description' => array('type' => 'string'),
	/**
	 * The description of the Page in raw HTML
	 */
		'description_html' => array('type' => 'string'),
	/**
	 * The director of the film. Applicable to Films
	 */
		'directed_by' => array('type' => 'string'),
	/**
	 * The number of people who like the Page
	 */
		'fan_count' => array('type' => 'unsigned int32'),
	/**
	 * Features of the vehicle. Applicable to Vehicles
	 */
		'features' => array('type' => 'string'),
	/**
	 * The restaurant's food styles. Applicable to Restaurants
	 */
		'food_styles' => array('type' => 'array'),
	/**
	 * When the company is founded. Applicable to Companies
	 */
		'founded' => array('type' => 'string'),
	/**
	 * General information provided by the Page
	 */
		'general_info' => array('type' => 'string'),
	/**
	 * General manager of the business. Applicable to Restaurants or Nightlife
	 */
		'general_manager' => array('type' => 'string'),
	/**
	 * The genre of the film. Applicable to Films
	 */
		'genre' => array('type' => 'string'),
	/**
	 * The name of the Page with country codes appended for Global Brand Pages. Only viewable by the Page admin
	 */
		'global_brand_page_name' => array('type' => 'string'),
	/**
	 * The id of this brand's global (parent) Page
	 */
		'global_brand_parent_page_id' => array('type' => 'numeric string'),
	/**
	 * Indicates whether this Page has added the app making the query in a Page tab
	 */
		'has_added_app' => array('type' => 'bool'),
	/**
	 * Hometown of the band. Applicable to Bands
	 */
		'hometown' => array('type' => 'string'),
	/**
	 * Hours of operation. Applicable to Businesses and Places
	 *
	 *  - `mon_1_open` 	string 	Monday opening hour
	 *  - `mon_1_close` string 	Monday closing hour
	 *  - `tue_1_open` 	string 	Tuesday opening hour
	 *  - `tue_1_close` string 	Tuesday closing hour
	 *  - `wed_1_open` 	string 	Wednesday opening hour
	 *  - `wed_1_close` string 	Wednesday closing hour
	 *  - `thu_1_open` 	string 	Thursday opening hour
	 *  - `thu_1_close` string 	Thursday closing hour
	 *  - `fri_1_open`	string  Friday opening hour
	 *  - `fri_1_close`	string	Friday closing hour
	 *  - `sat_1_open`	string	Saturday opening hour
	 *  - `sat_1_close`	string	Saturday closing hour
	 *  - `sun_1_open`	string	Sunday opening hour
	 *  - `sun_1_close`	string	Sunday closing hour
	 *  - `mon_2_open`	string	Monday second opening hour
	 *  - `mon_2_close`	string	Monday second closing hour
	 *  - `tue_2_open`	string	Tuesday second opening hour
	 *  - `tue_2_close`	string	Tuesday second closing hour
	 *  - `wed_2_open`	string	Wednesday second opening hour
	 *  - `wed_2_close`	string	Wednesday second closing hour
	 *  - `thu_2_open`	string	Thursday second opening hour
	 *  - `thu_2_close`	string	Thursday second closing hour
	 *  - `fri_2_open`	string	Friday second opening hour
	 *  - `fri_2_close`	string	Friday second closing hour
	 *  - `sat_2_open`	string	Saturday second opening hour
	 *  - `sat_2_close`	string	Saturday second closing hour
	 *  - `sun_2_open`	string	Sunday second opening hour
	 *  - `sun_2_close`	string	Sunday second closing hour
	 */
		'hours' => array('type' => 'struct'),
	/**
	 * Influences on the band. Applicable to Bands
	 */
		'influences' => array('type' => 'string'),
	/**
	 * Indicates whether the Page is a community Page
	 */
		'is_community_page' => array('type' => 'bool'),
	/**
	 * Indicates whether the Page is published and visible to non-admins
	 */
		'is_published' => array('type' => 'bool'),
	/**
	 * Keywords for the Page
	 */
		'keywords' => array('type' => 'string', 'index' => true),
	/**
	 * The location of this place. Applicable to all Places
	 *
	 *  - `street`		string	Street of the location
	 *  - `city`		string	City of the location
	 *  - `state`		string	State of the location
	 *  - `country`		string	Country of the location
	 *  - `zip`			string	Zip code of the location
	 *  - `latitude`	float	Latitude of the location
	 *  - `longitude`	float	Longitude of the location
	 *  - `id`			id		ID of the location
	 *  - `name`		string	Name of the location
	 *  - `located_in`	id		ID of the parent location of this location
	 */
		'location' => array('type' => 'struct'),
	/**
	 * Members of this org. Applicable to Pages representing Team Orgs
	 */
		'members' => array('type' => 'string'),
	/**
	 * The company mission. Applicable to Companies
	 */
		'mission' => array('type' => 'string'),
	/**
	 * MPG of the vehicle. Applicable to Vehicles
	 */
		'mpg' => array('type' => 'string'),
	/**
	 * The name of the Page.
	 */
		'name' => array('type' => 'string', 'index' => true),
	/**
	 * The TV network for the TV show. Applicable to TV Shows
	 */
		'network' => array('type' => 'string'),
	/**
	 * The number of people who have liked the Page, since the last login. Only viewable by the page admin
	 */
		'new_like_count' => array('type' => 'integer'),
	/**
	 * Offer eligibility status
	 */
		'offer_eligible' => array('type' => 'bool'),
	/**
	 * The ID of the Page.
	 */
		'page_id' => array('type' => 'id', 'index' => true, 'primary' => true),
	/**
	 * The absolute URL to the Page
	 */
		'page_url' => array('type' => 'string'),
	/**
	 * Parent Page for this Page
	 */
		'parent_page' => array('type' => 'id'),
	/**
	 * Parking information. Applicable to Businesses and Places. Can be one of street, lot or valet
	 *
	 *  - `street`	number (min: 0) (max: 1)	Whether street parking is available
	 *  - `lot`		number (min: 0) (max: 1)		Whether lot parking is available
	 *  - `valet`	number (min: 0) (max: 1)	Whether valet parking is available
	 */
		'parking' => array('type' => 'struct'),
	/**
	 * Payment options accepted by the business. Applicable to Restaurants or Nightlife
	 *
	 *  - `cash_only`	number (min: 0) (max: 1)	Whether the business accepts cash only as a payment option
	 *  - `visa`		number (min: 0) (max: 1)	Whether the business accepts Visa as a payment option
	 *  - `amex`		number (min: 0) (max: 1)	Whether the business accepts American Express as a payment option
	 *  - `mastercard`	number (min: 0) (max: 1)	Whether the business accepts MasterCard as a payment option
	 *  - `discover`	number (min: 0) (max: 1)	Whether the business accepts Discover as a payment option
	 */
		'payment_options' => array('type' => 'struct'),
	/**
	 * Personal information. Applicable to Pages representing People
	 */
		'personal_info' => array('type' => 'string'),
	/**
	 * Personal interests. Applicable to Pages representing People
	 */
		'personal_interests' => array('type' => 'string'),
	/**
	 * Pharmacy safety information. Applicable to Pharmaceutical companies
	 */
		'pharma_safety_info' => array('type' => 'string'),
	/**
	 * Phone number provided by a Page
	 */
		'phone' => array('type' => 'string'),
	/**
	 * The URL to the medium-sized profile picture for the Page.
	 * The image can have a maximum width of 100px and a maximum height of 300px. This URL may be blank
	 */
		'pic' => array('type' => 'string'),
	/**
	 * The URL to the large-sized profile picture for the Page.
	 * The image can have a maximum width of 200px and a maximum height of 600px. This URL may be blank
	 */
		'pic_big' => array('type' => 'string'),
	/**
	 * The JSON object containing three fields: cover_id (the ID of the cover photo), source (the URL for the cover photo),
	 * and offset_y (indicating percentage offset from top [0-100])
	 *
	 *  - `cover_id`	id		The ID of the cover photo
	 *  - `source`		string	The source of the cover photo
	 *  - `offset_y`	float	The offset percentage of the total image height from top [0-100]
	 */
		'pic_cover' => array('type' => 'struct'),
	/**
	 * The URL to the largest-sized profile picture for the Page.
	 * The image can have a maximum width of 396px and a maximum height of 1188px. This URL may be blank
	 */
		'pic_large' => array('type' => 'string'),
	/**
	 * The URL to the small-sized picture for the Page.
	 * The image can have a maximum width of 50px and a maximum height of 150px. This URL may be blank
	 */
		'pic_small' => array('type' => 'string'),
	/**
	 * The URL to the square profile picture for the Page. The image can have a maximum width and height of 50px. This URL may be blank
	 */
		'pic_square' => array('type' => 'string'),
	/**
	 * The plot outline of the film. Applicable to Films
	 */
		'plot_outline' => array('type' => 'string'),
	/**
	 * Press contact information of the band. Applicable to Bands
	 */
		'press_contact' => array('type' => 'string'),
	/**
	 * Price range of the business. Applicable to Restaurants or Nightlife.
	 * Can be one of \$ (0-10), \$\$ (10-30), \$\$\$ (30-50), \$\$\$\$ (50+) or Unspecified
	 */
		'price_range' => array('type' => 'string'),
	/**
	 * The productor of the film. Applicable to Films
	 */
		'produced_by' => array('type' => 'string'),
	/**
	 * The products of this company. Applicable to Companies
	 */
		'products' => array('type' => 'string'),
	/**
	 * Boosted posts eligibility status
	 */
		'promotion_eligible' => array('type' => 'bool'),
	/**
	 * Reason boosted posts not eligible
	 */
		'promotion_ineligible_reason' => array('type' => 'string'),
	/**
	 * Public transit to the business. Applicable to Restaurants or Nightlife
	 */
		'public_transit' => array('type' => 'string'),
	/**
	 * Record label of the band. Applicable to Bands
	 */
		'record_label' => array('type' => 'string'),
	/**
	 * The film's release data. Applicable to Films
	 */
		'release_date' => array('type' => 'string'),
	/**
	 * Services the restaurant provides. Applicable to Restaurants
	 *
	 *  - `reserve`		number (min: 0) (max: 1)	Whether the restaurant takes reservations
	 *  - `walkins`		number (min: 0) (max: 1)	Whether the restaurant welcomes walkins
	 *  - `groups`		number (min: 0) (max: 1)	Whether the restaurant is group friendly
	 *  - `kids`		number (min: 0) (max: 1)	Whether the restaurant is kids friendsly
	 *  - `takeout`		number (min: 0) (max: 1)	Whether the restaurant has takeout service
	 *  - `delivery`	number (min: 0) (max: 1)	Whether the restaurant has delivery service
	 *  - `catering`	number (min: 0) (max: 1)	Whether the restaurant has catering service
	 *  - `waiter`		number (min: 0) (max: 1)	Whether the restaurant has waiters
	 *  - `outdoor`		number (min: 0) (max: 1)	Whether the restaurant has outdoor seatings
	 */
		'restaurant_services' => array('type' => 'struct'),
	/**
	 * The restaurant's specialties. Applicable to Restaurants
	 *
	 *  - `breakfast`	number (min: 0) (max: 1)	Whether the restaurant serves breakfast
	 *  - `lunch`		number (min: 0) (max: 1)	Whether the restaurant serves lunch
	 *  - `dinner`		number (min: 0) (max: 1)	Whether the restaurant serves dinner
	 *  - `coffee`		number (min: 0) (max: 1)	Whether the restaurant serves coffee
	 *  - `drinks`		number (min: 0) (max: 1)	Whether the restaurant serves drinks
	 */
		'restaurant_specialties' => array('type' => 'struct'),
	/**
	 * The air schedule of the TV show. Applicable to TV Shows
	 */
		'schedule' => array('type' => 'string'),
	/**
	 * The screenwriter of the film. Applicable to Films
	 */
		'screenplay_by' => array('type' => 'string'),
	/**
	 * The season information of the TV Show. Applicable to TV Shows
	 */
		'season' => array('type' => 'string'),
	/**
	 * The cast of the film. Applicable to Films
	 */
		'starring' => array('type' => 'string'),
	/**
	 * The studio for the film production. Applicable to Films
	 */
		'studio' => array('type' => 'string'),
	/**
	 * The count for the number of people Talking-about-this for the Page
	 */
		'talking_about_count' => array('type' => 'unsigned int32'),
	/**
	 * The type of Page. e.g. Product/Service, Computers/Technology
	 */
		'type' => array('type' => 'string'),
	/**
	 * Unread message count for the Page
	 */
		'unread_message_count' => array('type' => 'integer'),
	/**
	 * Unseen message count for the Page
	 */
		'unseen_message_count' => array('type' => 'integer'),
	/**
	 * Number of unseen notifications. Only viewable by the page admin
	 */
		'unseen_notif_count' => array('type' => 'unsigned int32'),
	/**
	 * The alias of the Page, eg. For www.facebook.com/platform the username is 'platform'
	 */
		'username' => array('type' => 'string', 'index' => true),
	/**
	 * The URL to the Web site of the Page
	 */
		'website' => array('type' => 'string'),
	/**
	 * The count for the number of visits for the Page
	 */
		'were_here_count' => array('type' => 'unsigned int32'),
	/**
	 * The writer of the TV show. Applicable to TV Shows
	 */
		'written_by' => array('type' => 'string'),
	);