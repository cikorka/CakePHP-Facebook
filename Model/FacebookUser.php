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
 * @file		FacebookUser.php
 */

App::uses('FacebookAppModel', 'Facebook.Model');

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

class FacebookUser extends FacebookAppModel {

/**
 * Custom database table name, or null/false if no table association is desired.
 *
 * @var string
 * @link http://book.cakephp.org/2.0/en/models/model-attributes.html#usetable
 */
	public $useTable = 'user';

/**
 * The name of the primary key field for this model.
 *
 * @var string
 * @link http://book.cakephp.org/2.0/en/models/model-attributes.html#primaryKey
 */
	public $primaryKey = 'uid';

/**
 * Detailed list of hasAndBelongsToMany associations.
 *
 * ### Basic usage
 *
 * `public $hasAndBelongsToMany = array('Role', 'Address');`
 *
 * ### Detailed configuration
 *
 * {{{
 * public $hasAndBelongsToMany = array(
 *	   'Role',
 *	   'Address' => array(
 *		   'className' => 'Address',
 *		   'foreignKey' => 'user_id',
 *		   'associationForeignKey' => 'address_id',
 *		   'joinTable' => 'addresses_users'
 *	   )
 * );
 * }}}
 *
 * ### Possible keys in association
 *
 * - `className`: the classname of the model being associated to the current model.
 *	 If you're defining a 'Recipe HABTM Tag' relationship, the className key should equal 'Tag.'
 * - `joinTable`: The name of the join table used in this association (if the
 *	 current table doesn't adhere to the naming convention for HABTM join tables).
 * - `with`: Defines the name of the model for the join table. By default CakePHP
 *	 will auto-create a model for you. Using the example above it would be called
 *	 RecipesTag. By using this key you can override this default name. The join
 *	 table model can be used just like any "regular" model to access the join table directly.
 * - `foreignKey`: the name of the foreign key found in the current model.
 *	 This is especially handy if you need to define multiple HABTM relationships.
 *	 The default value for this key is the underscored, singular name of the
 *	 current model, suffixed with '_id'.
 * - `associationForeignKey`: the name of the foreign key found in the other model.
 *	 This is especially handy if you need to define multiple HABTM relationships.
 *	 The default value for this key is the underscored, singular name of the other
 *	 model, suffixed with '_id'.
 * - `unique`: If true (default value) cake will first delete existing relationship
 *	 records in the foreign keys table before inserting new ones, when updating a
 *	 record. So existing associations need to be passed again when updating.
 *	 To prevent deletion of existing relationship records, set this key to a string 'keepExisting'.
 * - `conditions`: An SQL fragment used to filter related model records. It's good
 *	 practice to use model names in SQL fragments: "Comment.status = 1" is always
 *	 better than just "status = 1."
 * - `fields`: A list of fields to be retrieved when the associated model data is
 *	 fetched. Returns all fields by default.
 * - `order`: An SQL fragment that defines the sorting order for the returned associated rows.
 * - `limit`: The maximum number of associated rows you want returned.
 * - `offset`: The number of associated rows to skip over (given the current
 *	 conditions and order) before fetching and associating.
 * - `finderQuery`, `deleteQuery`, `insertQuery`: A complete SQL query CakePHP
 *	 can use to fetch, delete, or create new associated model records. This should
 *	 be used in situations that require very custom results.
 *
 * @var array
 * @link http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#hasandbelongstomany-habtm
 */
	public $hasAndBelongsToMany = array(
		'FacebookPage' => array(
			'className' => 'Facebook.FacebookPage',
			'joinTable' => 'page',
			'foreignKey' => 'uid',
			'associationForeignKey' => 'page_id',
			'with' => 'Facebook.FacebookPageAdmin',
			'conditions' => array('page_id IN (SELECT page_id FROM %23FacebookPageAdmin)'),
		),
		'FacebookStream' => array(
			'className' => 'Facebook.FacebookStream',
			'joinTable' => 'stream',
			'foreignKey' => 'uid',
			'associationForeignKey' => 'page_id',
			'with' => 'Facebook.FacebookStreamFilter',
			'conditions' => array('filter_key IN (SELECT filter_key FROM %23FacebookStreamFilter)'),
			'fields' => array('filter_key'),
		)
	);

/**
 * Detailed list of hasMany associations.
 *
 * ### Basic usage
 *
 * `public $hasMany = array('Comment', 'Task');`
 *
 * ### Detailed configuration
 *
 * {{{
 * public $hasMany = array(
 *	   'Comment',
 *	   'Task' => array(
 *		   'className' => 'Task',
 *		   'foreignKey' => 'user_id'
 *	   )
 * );
 * }}}
 *
 * ### Possible keys in association
 *
 * - `className`: the classname of the model being associated to the current model.
 *	 If you're defining a 'User hasMany Comment' relationship, the className key should equal 'Comment.'
 * - `foreignKey`: the name of the foreign key found in the other model. This is
 *	 especially handy if you need to define multiple hasMany relationships. The default
 *	 value for this key is the underscored, singular name of the actual model, suffixed with '_id'.
 * - `conditions`: An SQL fragment used to filter related model records. It's good
 *	 practice to use model names in SQL fragments: "Comment.status = 1" is always
 *	 better than just "status = 1."
 * - `fields`: A list of fields to be retrieved when the associated model data is
 *	 fetched. Returns all fields by default.
 * - `order`: An SQL fragment that defines the sorting order for the returned associated rows.
 * - `limit`: The maximum number of associated rows you want returned.
 * - `offset`: The number of associated rows to skip over (given the current
 *	 conditions and order) before fetching and associating.
 * - `dependent`: When dependent is set to true, recursive model deletion is
 *	 possible. In this example, Comment records will be deleted when their
 *	 associated User record has been deleted.
 * - `exclusive`: When exclusive is set to true, recursive model deletion does
 *	 the delete with a deleteAll() call, instead of deleting each entity separately.
 *	 This greatly improves performance, but may not be ideal for all circumstances.
 * - `finderQuery`: A complete SQL query CakePHP can use to fetch associated model
 *	 records. This should be used in situations that require very custom results.
 *
 * @var array
 * @link http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#hasmany
 */
	public $hasMany = array(
		'FacebookAlbum' => array(
			'className' => 'Facebook.FacebookAlbum',
			'foreignKey' => 'owner',
			'fields' => array('name', 'aid'),
		),
		'FacebookPermission' => array(
			'className' => 'Facebook.FacebookPermission',
			'foreignKey' => 'uid',
			'fields' => array('manage_pages'),
		),
	);

/**
 * Return current logged in user data
 * Its important that `$this->recursive = 0;` because associations with foreignKey value `me()` is not work!
 *
 * @return array user data fetched from Facebook
 */
	public function getLoginData() {
		$this->contain();
		return $this->find('first', array('conditions' => array('FacebookUser.uid = me()' => null)));
	}

}