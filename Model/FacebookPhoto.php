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
 * @file		FacebookPhoto.php
 */

App::uses('FacebookAppModel', 'Facebook.Model');

class FacebookPhoto extends FacebookAppModel {

/**
 * Custom database table name, or null/false if no table association is desired.
 *
 * @var string
 * @link http://book.cakephp.org/2.0/en/models/model-attributes.html#usetable
 */
	public $useTable = 'photo';

/**
 * The name of the primary key field for this model.
 *
 * @var string
 * @link http://book.cakephp.org/2.0/en/models/model-attributes.html#primaryKey
 */
	public $primaryKey = 'pid';

/**
 * Detailed list of belongsTo associations.
 *
 * ### Basic usage
 *
 * `public $belongsTo = array('Group', 'Department');`
 *
 * ### Detailed configuration
 *
 * {{{
 * public $belongsTo = array(
 *     'Group',
 *     'Department' => array(
 *         'className' => 'Department',
 *         'foreignKey' => 'department_id'
 *     )
 * );
 * }}}
 *
 * ### Possible keys in association
 *
 * - `className`: the classname of the model being associated to the current model.
 *   If you're defining a 'Profile belongsTo User' relationship, the className key should equal 'User.'
 * - `foreignKey`: the name of the foreign key found in the current model. This is
 *   especially handy if you need to define multiple belongsTo relationships. The default
 *   value for this key is the underscored, singular name of the other model, suffixed with '_id'.
 * - `conditions`: An SQL fragment used to filter related model records. It's good
 *   practice to use model names in SQL fragments: 'User.active = 1' is always
 *   better than just 'active = 1.'
 * - `type`: the type of the join to use in the SQL query, default is LEFT which
 *   may not fit your needs in all situations, INNER may be helpful when you want
 *   everything from your main and associated models or nothing at all!(effective
 *   when used with some conditions of course). (NB: type value is in lower case - i.e. left, inner)
 * - `fields`: A list of fields to be retrieved when the associated model data is
 *   fetched. Returns all fields by default.
 * - `order`: An SQL fragment that defines the sorting order for the returned associated rows.
 * - `counterCache`: If set to true the associated Model will automatically increase or
 *   decrease the "[singular_model_name]_count" field in the foreign table whenever you do
 *   a save() or delete(). If its a string then its the field name to use. The value in the
 *   counter field represents the number of related rows.
 * - `counterScope`: Optional conditions array to use for updating counter cache field.
 *
 * @var array
 * @link http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#belongsto
 */
	public $belongsTo = array(
		'FacebookAlbum' => array(
			'className' => 'Facebook.FacebookAlbum',
			'foreignKey' => 'aid'
		)
	);

}