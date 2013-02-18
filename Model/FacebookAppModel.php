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
 * @file		FacebookAppModel.php
 */

App::uses('AppModel', 'Model');

class FacebookAppModel extends AppModel {

/**
 * The name of the DataSource connection that this Model uses
 *
 * The value must be an attribute name that you defined in `app/Config/database.php`
 * or created using `ConnectionManager::create()`.
 *
 * @var string
 * @link http://book.cakephp.org/2.0/en/models/model-attributes.html#usedbconfig
 */
	public $useDbConfig = 'facebook';

/**
 * Whether or not to cache queries for this model. This enables in-memory
 * caching only, the results are not stored beyond the current request.
 *
 * @var boolean
 * @link http://book.cakephp.org/2.0/en/models/model-attributes.html#cachequeries
 */
	public $cacheQueries = true;

/**
 * Called before each find operation. Return false if you want to halt the find
 * call, otherwise return the (modified) query data.
 *
 * @param array $queryData Data used to execute this query, i.e. conditions, order, etc.
 * @return mixed true if the operation should continue, false if it should abort; or, modified
 *               $queryData to continue with new $queryData
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#beforefind
 */
	public function beforeFind($queryData) {
		return true;
	}

/**
 * Called after each find operation. Can be used to modify any results returned by find().
 * Return value should be the (modified) results.
 *
 * @param mixed $results The results of the find operation
 * @param boolean $primary Whether this model is being queried directly (vs. being queried as an association)
 * @return mixed Result of the find operation
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#afterfind
 */
	public function afterFind($results, $primary = false) {
		return $results;
	}

/**
 * Called before each save operation, after validation. Return a non-true result
 * to halt the save.
 *
 * @param array $options
 * @return boolean True if the operation should continue, false if it should abort
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#beforesave
 */
	public function beforeSave($options = array()) {
		$this->useDbConfig = 'facebook2';
		return true;
	}

/**
 * Called after each successful save operation.
 *
 * @param boolean $created True if this save created a new record
 * @return void
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#aftersave
 */
	public function afterSave($created) {
		parent::afterSave($created);
	}

/**
 * Called before every deletion operation.
 *
 * @param boolean $cascade If true records that depend on this record will also be deleted
 * @return boolean True if the operation should continue, false if it should abort
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#beforedelete
 */
	public function beforeDelete($cascade = true) {
		$this->useDbConfig = 'facebook2';
		return true;
	}

/**
 * Called after every deletion operation.
 *
 * @return void
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#afterdelete
 */
	public function afterDelete() {
	}

/**
 * Called during validation operations, before validation. Please note that custom
 * validation rules can be defined in $validate.
 *
 * @param array $options Options passed from model::save(), see $options of model::save().
 * @return boolean True if validate operation should continue, false to abort
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#beforevalidate
 */
	public function beforeValidate($options = array()) {
		return true;
	}

/**
 * Called after data has been checked for errors
 *
 * @return void
 */
	public function afterValidate() {
	}

/**
 * Called when a DataSource-level error occurs.
 *
 * @return void
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#onerror
 */
	public function onError() {
	}

	public function permissions() {
		$perms = array();
		foreach ($this->schema() as $perm) {
			if (isset($perm['perms'])) {
				$perms = array_merge($perms, $perm['perms']);
			}
		}
		$perms = array_unique($perms);
		array_values($perms);
		return $perms;
	}

}