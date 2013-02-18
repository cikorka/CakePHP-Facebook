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
 * @file		FacebookPage.php
 */

App::uses('FacebookAppModel', 'Facebook.Model');
 
class FacebookPage extends FacebookAppModel {

/**
 * Custom database table name, or null/false if no table association is desired.
 *
 * @var string
 * @link http://book.cakephp.org/2.0/en/models/model-attributes.html#usetable
 */
	public $useTable = 'page';

/**
 * The name of the primary key field for this model.
 *
 * @var string
 * @link http://book.cakephp.org/2.0/en/models/model-attributes.html#primaryKey
 */
	public $primaryKey = 'page_id';

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
		'FacebookUser' => array(
			'className' => 'Facebook.FacebookUser',
			'joinTable' => 'page_admin',
			'foreignKey'  => 'page_id',
			'associationForeignKey' => 'uid',
			'with' => 'Facebook.FacebookPageAdmin'
		)
	);

}