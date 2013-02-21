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
 * @file		GraphApi.php
 */

App::uses('HttpSocket', 'Network/Http');

/**
 * DataSource base class
 *
 * @package       Cake.Model.Datasource
 */
class GraphApi extends DataSource {

/**
 * Holds references to descriptions loaded by the DataSource
 *
 * @var array
 */
	protected $_descriptions = array();

/**
 * Holds a list of sources (tables) contained in the DataSource
 *
 * @var array
 */
	protected $_sources = null;

/**
 * The DataSource configuration
 *
 * @var array
 */
	public $config = array('api' => 'https://graph.facebook.com');

	protected $_mapTables = array(
		'permissions' => 'me/permissions'
	);

/**
 * Whether or not source data like available tables and schema descriptions
 * should be cached
 *
 * @var boolean
 */
	public $cacheSources = false;

	public $Http = null;

/**
 * Constructor.
 *
 * @param array $config Array of configuration information for the datasource.
 */
	public function __construct($config = array()) {
		parent::__construct($config);
		$this->Http = new HttpSocket();
		$this->query = array('access_token' => CakeSession::read('access_token'));
	}

/**
 * Returns a Model description (metadata) or null if none found.
 *
 * @param Model|string $model
 * @return array Array of Metadata for the $model
 */
	public function describe($model) {
		return $model->__schema;
	}

/**
 * calculate() is for determining how we will count the records and is
 * required to get ``update()`` and ``delete()`` to work.
 *
 * We don't count the records here but return a string to be passed to
 * ``read()`` which will do the actual counting. The easiest way is to just
 * return the string 'COUNT' and check for it in ``read()`` where
 * ``$data['fields'] == 'COUNT'``.
 */
	public function calculate(Model $model, $func, $params = array()) {
		return 'COUNT';
	}

/**
 * Used to create new records. The "C" CRUD.
 *
 * To-be-overridden in subclasses.
 *
 * @param Model $model The Model to be created.
 * @param array $fields An Array of fields to be saved.
 * @param array $values An Array of values to save.
 * @return boolean success
 */
	public function create(Model $model, $fields = null, $values = null) {
		$fbResponse = $this->Http->post($this->__url($model), $this->query + array_combine($fields, $values));
		$results = json_decode($fbResponse->body, true);
		if (isset($results['error'])) {
			throw new CakeException($results['error']['type'] . ' : ' . $results['error']['message'], $results['error']['code']);
		}
		$model->id = $results['id'];
		return true;
	}

/**
 * Used to read records from the Datasource. The "R" in CRUD
 *
 * To-be-overridden in subclasses.
 *
 * @param Model $model The model being read.
 * @param array $queryData An array of query data used to find the data you want
 * @param integer $recursive Number of levels of association
 * @return mixed
 */
	public function read(Model $model, $queryData = array(), $recursive = null) {
		/**
		 * Here we do the actual count as instructed by our calculate()
		 * method above. We could either check the remote source or some
		 * other way to get the record count. Here we'll simply return 1 so
		 * ``update()`` and ``delete()`` will assume the record exists.
		 */
		if ($queryData['fields'] == 'COUNT') {
			return array(array(array('count' => 1)));
		}

		$results = $this->__fetch($model, $queryData, $recursive);
		foreach ($results as $i => $result) {
			foreach (array_keys($model->hasMany) as $assoc) {
				$assocResults = $this->__fetch($model->{$assoc}, $model->hasMany[$assoc], $recursive);
				$_assocResults = array();
				foreach ($assocResults as $assocResult) {
					$_assocResults[] = $assocResult[$model->{$assoc}->alias];
				}
				$results[$i][$assoc] = $_assocResults;
			}
		}
		return $results;
	}

/**
 * Update a record(s) in the datasource.
 *
 * To-be-overridden in subclasses.
 *
 * @param Model $model Instance of the model class being updated
 * @param array $fields Array of fields to be updated
 * @param array $values Array of values to be update $fields to.
 * @param mixed $conditions
 * @return boolean Success
 */
	public function update(Model $model, $fields = null, $values = null, $conditions = null) { pr('update');
		return false;
	}

	public function useTable(Model $Model) {
		if (isset($this->_mapTables[$Model->useTable])) {
			return $this->_mapTables[$Model->useTable];
		}
		return $Mode->useTable;
	}

/**
 * Delete a record(s) in the datasource.
 *
 * To-be-overridden in subclasses.
 *
 * @param Model $model The model class having record(s) deleted
 * @param mixed $conditions The conditions to use for deleting.
 * @return boolean Success
 */
	public function delete(Model $Model, $id = null) {
		$fbResponse = $this->Http->delete($this->__url($Model), $this->query);
		$results = json_decode($fbResponse->body, true);
		return $results;
	}

	private function __url(Model $Model) {
		return implode('/', array($this->config['api'], $this->useTable($Model)));
	}

	private function __fetch(Model $model, $queryData = array(), $recursive = null) {
		$fields = null;
		if (is_array($queryData['fields'])) {
			$fields = implode(',', $queryData['fields']);
		}

		$fbResponse = $this->Http->get($this->__url($model), $this->query + array('fields' => $fields));

		$results = json_decode($fbResponse->body, true);

		if (isset($results['error'])) {
			throw new Exception($results['error']['type'] . ' : ' . $results['error']['message'], $results['error']['code']);
		}
		if (isset($results['data'])) {
			$results = $results['data'];
		}

		if (isset($results[0])) {
			foreach ($results as $result) {
				$resultSet[] = array($model->alias => $result);
			}
		} else {
			$resultSet = array(array($model->alias => $results));
		}
		return $resultSet;
	}

}