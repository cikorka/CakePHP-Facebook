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
 * @file		FQL.php
 */

App::uses('DboSource', 'Model/Datasource');
App::uses('HttpSocket', 'Network/Http');
App::uses('CakeSession', 'Model/Datasource');
App::uses('Folder', 'Utility');


class FQL extends DboSource {

/**
 * Datasource description
 *
 * @var string
 */
	public $description = 'Facebook FQL Datasource';

/**
 * Database keyword used to assign aliases to identifiers.
 *
 * @var string
 */
	public $alias = null;

/**
 * The starting character that this DataSource uses for quoted identifiers.
 *
 * @var string
 */
	public $startQuote = null;

/**
 * The ending character that this DataSource uses for quoted identifiers.
 *
 * @var string
 */
	public $endQuote = null;

/**
 * FQL column definition
 *
 * @var array
 */
	public $columns = array(
		'primary_key' => array('name' => 'NOT NULL AUTO_INCREMENT'),
		'array' => array('name' => 'text'),
		'struct' => array('name' => 'text'),
		'string' => array('name' => 'varchar', 'limit' => '255'),
		'text' => array('name' => 'text'),
		'biginteger' => array('name' => 'bigint', 'limit' => '20'),
		'integer' => array('name' => 'int', 'limit' => '11', 'formatter' => 'intval'),
		'float' => array('name' => 'float', 'formatter' => 'floatval'),
		'datetime' => array('name' => 'datetime', 'format' => 'Y-m-d H:i:s', 'formatter' => 'date'),
		'timestamp' => array('name' => 'timestamp', 'format' => 'Y-m-d H:i:s', 'formatter' => 'date'),
		'time' => array('name' => 'time', 'format' => 'H:i:s', 'formatter' => 'date'),
		'date' => array('name' => 'date', 'format' => 'Y-m-d', 'formatter' => 'date'),
		'binary' => array('name' => 'blob'),
		'boolean' => array('name' => 'tinyint', 'limit' => '1')
	);

/**
 * Used for memory caching queries
 *
 * @var array
 */
	protected $_cache = array();

/**
 * Base configuration settings for FQL driver
 *
 * @var array
 */
	protected $_baseConfig = array('database' => 'facebook');

/**
 * Connects to the database using options in the given configuration array.
 *
 * @return boolean True if the database could be connected, else false
 * @throws MissingConnectionException
 */
	public function connect() {
		$this->_connection = new HttpSocket();
		return $this->connected = true;
	}

/**
 * Returns an array of sources (tables) in the database.
 *
 * @param mixed $data
 * @return array Array of table names in the database
 */
	public function listSources($data = null) {
		$cache = parent::listSources();
		if ($cache) {
			return $cache;
		}

		$pluginName = basename(dirname(dirname(dirname(__FILE__))));
		$Folder = new Folder(App::pluginPath($pluginName) . 'Config' . DS . 'Schema' . DS);
		$schema = $Folder->find('.*\.php');

		$tables = array();
		foreach ($schema as $table) {
			list($table, $ext) = explode('.', $table);
			$tables[] = $table;
		}

		unset($data, $cache, $Folder, $schema, $table, $ext);

		parent::listSources($tables);
		return $tables;
	}

/**
 * Returns a Model description (metadata) or null if none found.
 *
 * @param Model|string $model
 * @throws CakeException
 * @return array Array of Metadata for the $model
 */
	public function describe($model) {
		if (is_string($model)) {
			$table = $model;
		} else {
			$table = $model->useTable;
		}

		if (isset($this->_descriptions[$table])) {
			return $this->_descriptions[$table];
		}

		$pluginName = basename(dirname(dirname(dirname(__FILE__))));
		$file = App::pluginPath($pluginName) . 'Config' . DS . 'Schema' . DS . $table . '.php';
		if (file_exists($file)) {
			include_once ($file);
			if (isset($schema)) {
				return $this->_descriptions[$table] = $schema;
			}
		}

		throw new CakeException(__('Schema for table %s not found.', $table));
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
 * Returns a quoted and escaped string of $data for use in an SQL statement.
 *
 * @param string $data String to be prepared for use in an SQL statement
 * @param string $column The column into which this data will be inserted
 * @return string Quoted and escaped data
 */
	public function value($data, $column = null) {
		if (is_array($data) && !empty($data)) {
			return array_map(
				array(&$this, 'value'),
				$data, array_fill(0, count($data), $column)
			);
		} elseif (is_object($data) && isset($data->type, $data->value)) {
			if ($data->type == 'identifier') {
				return $this->name($data->value);
			} elseif ($data->type == 'expression') {
				return $data->value;
			}
		} elseif (in_array($data, array('{$__cakeID__$}', '{$__cakeForeignKey__$}'), true)) {
			return $data;
		}

		if ($data === null || (is_array($data) && empty($data))) {
			return null;
		}

		if (empty($column)) {
			$column = $this->introspectType($data);
		}

		switch ($column) {
			case 'string':
			case 'text':
				return sprintf("'%s'", $data);
			default:
				if ($data === '') {
					return 'NULL';
				}
				if (is_float($data)) {
					return str_replace(',', '.', strval($data));
				}
				if ((is_int($data) || $data === '0') || (
					is_numeric($data) && strpos($data, ',') === false &&
					$data[0] != '0' && strpos($data, 'e') === false)
				) {
					return $data;
				}
				return $data;
		}
	}

/**
 * Executes given FQL statement.
 *
 * @param string $fql FQL statement
 * @param array $params list of params to be bound to query
 * @param array $prepareOptions Options to be used in the prepare statement
 * @return mixed PDOStatement if query executes with no problem, true as the result of a successful, false on error
 * query returning no rows, such as a CREATE statement, false otherwise
 */
	protected function _execute($fql, $params = array(), $prepareOptions = array()) {
		$params += array('format' => 'json-strings', 'access_token' => CakeSession::read('access_token'));
		$cacheName = md5(serialize($params) . $fql);

		if (!isset($this->_cache[$cacheName])) {
			$this->_cache[$cacheName] = false;
			$results = json_decode($this->_connection->get(sprintf('https://graph.facebook.com/fql?q={%s}', $fql), $params)->body, true);
			if (isset($results['data'])) {
				$this->_cache[$cacheName] = Hash::combine($results['data'], '{n}.name', '{n}.fql_result_set');
			}
		}

		if (isset($results['error'])) {
			CakeLog::error($results['error']['message']);
		}
		$this->_restults = $this->_cache[$cacheName];
		unset($fql, $params, $prepareOptions, $cacheName, $results);
		return $this->_restults;
	}

/**
 * Checks if the result is valid
 *
 * @return boolean True if the result is valid else false
 */
	public function hasResult() {
		return !empty($this->_result);
	}

/**
 * Returns an array of all result rows for a given SQL query.
 * Returns false if no rows matched.
 *
 *
 * ### Options
 *
 * - `cache` - Returns the cached version of the query, if exists and stores the result in cache.
 *   This is a non-persistent cache, and only lasts for a single request. This option
 *   defaults to true. If you are directly calling this method, you can disable caching
 *   by setting $options to `false`
 *
 * @param string $fql SQL statement
 * @param array $params parameters to be bound as values for the SQL statement
 * @param array $options additional options for the query.
 * @return array Array of resultset rows, or false if no rows matched
 */
	public function fetchAll($fql, $params = array(), $options = array()) {
		if (is_string($options)) {
			$options = array('modelName' => $options);
		}
		if (is_bool($params)) {
			$options['cache'] = $params;
			$params = array();
		}
		$options += array('cache' => true);
		$cache = $options['cache'];
		if ($cache && ($cached = $this->getQueryCache($fql, $params)) !== false) {
			return $cached;
		}
		if ($result = $this->execute($fql, array(), $params)) {
			$out = array();
			if ($this->hasResult()) {
				$assocAlias = null;
				$modelAlias = key($result);
				foreach (array_shift($result) as $row) {
					if ($assocAlias === null) {
						$assocAlias = key($result);
						$assocRow = array_pop($result);
					}
					if (empty($assocAlias)) {
						$out[] = array($modelAlias => $row);
					} else {
						$out[] = array($modelAlias => $row, $assocAlias => $assocRow[0]);
					}
				}
			}

			if (!is_bool($result) && $cache) {
				$this->_writeQueryCache($fql, $out, $params);
			}

			unset($fql, $params, $options, $cache, $cached, $result, $assocAlias, $modelAlias, $row, $assocRow);

			if (empty($out) && is_bool($this->_result)) {
				return $this->_result;
			}
			return $out;
		}
		return false;
	}

/**
 * Returns number of affected rows in previous database operation. If no previous operation exists,
 * this returns false.
 *
 * @param mixed $source
 * @return integer Number of affected rows
 */
	public function lastAffected($source = null) {
		if ($this->hasResult()) {
			//implement
		}
		return 0;
	}

/**
 * Returns a quoted name of $data for use in an FQL statement.
 * Strips fields out of FQL functions before quoting.
 *
 * Results of this method are stored in a memory cache.  This improves performance, but
 * because the method uses a hashing algorithm it can have collisions.
 * Setting DboSource::$cacheMethods to false will disable the memory cache.
 *
 * @param mixed $data Either a string with a column to quote. An array of columns to quote or an
 *   object from DboSource::expression() or DboSource::identifier()
 * @return string FQL field
 */
	public function name($data) {
		$data = parent::name($data);
		if (strpos($data, '.')) {
			list(, $data) = explode('.', $data);
		}
		return $data;
	}

/**
 * Generates an array representing a query or part of a query from a single model or two associated models
 *
 * @param Model $model
 * @param Model $linkModel
 * @param string $type
 * @param string $association
 * @param array $assocData
 * @param array $queryData
 * @param boolean $external
 * @param array $resultSet
 * @return mixed
 */
	public function generateAssociationQuery(Model $model, $linkModel, $type, $association, $assocData, &$queryData, $external, &$resultSet) {
		$external = true;
		$queryData = $this->_scrubQueryData($queryData);
		$assocData = $this->_scrubQueryData($assocData);
		$modelAlias = $model->alias;

		if (empty($queryData['fields'])) {
			$queryData['fields'] = $this->fields($model, $modelAlias);
		} elseif (!empty($model->hasMany) && $model->recursive > -1) {
			$assocFields = $this->fields($model, $modelAlias, array("{$modelAlias}.{$model->primaryKey}"));
			$passedFields = $queryData['fields'];
			if (count($passedFields) === 1) {
				if (strpos($passedFields[0], $assocFields[0]) === false && !preg_match('/^[a-z]+\(/i', $passedFields[0])) {
					$queryData['fields'] = array_merge($passedFields, $assocFields);
				} else {
					$queryData['fields'] = $passedFields;
				}
			} else {
				$queryData['fields'] = array_merge($passedFields, $assocFields);
			}
			unset($assocFields, $passedFields);
		}

		if (!empty($model->belongsTo) && isset($assocData['foreignKey'])) {
			$assocFields = $this->fields($model, $modelAlias, array("{$modelAlias}.{$assocData['foreignKey']}"));
			$queryData['fields'] = array_merge($queryData['fields'], $assocFields);
		}

		if ($linkModel === null) {
			return $this->buildStatement(
				array(
					'fields' => array_unique($queryData['fields']),
					'table' => $this->fullTableName($model),
					'alias' => $modelAlias,
					'limit' => $queryData['limit'],
					'offset' => $queryData['offset'],
					'joins' => $queryData['joins'],
					'conditions' => $queryData['conditions'],
					'order' => $queryData['order'],
					'group' => $queryData['group']
				),
				$model
			);
		}
		if ($external && !empty($assocData['finderQuery'])) {
			return $assocData['finderQuery'];
		}

		$self = $model->name === $linkModel->name;
		$fields = array();

		if ($external || (in_array($type, array('hasOne', 'belongsTo')) && $assocData['fields'] !== false)) {
			$fields = $this->fields($linkModel, $association, $assocData['fields']);
		}
		if (empty($assocData['offset']) && !empty($assocData['page'])) {
			$assocData['offset'] = ($assocData['page'] - 1) * $assocData['limit'];
		}
		$assocData['limit'] = $this->limit($assocData['limit'], $assocData['offset']);

		switch ($type) {
			case 'hasOne':
			case 'belongsTo':
				$conditions = $this->_mergeConditions(
					$assocData['conditions'],
					$this->getConstraint($type, $model, $linkModel, $association, array_merge($assocData, compact('external', 'self')))
				);

				if (!$self && $external) {
					foreach ($conditions as $key => $condition) {
						if (is_numeric($key) && strpos($condition, $modelAlias . '.') !== false) {
							unset($conditions[$key]);
						}
					}
				}

				if ($external) {
					$query = array_merge($assocData, array(
						'conditions' => $conditions,
						'table' => $this->fullTableName($linkModel),
						'fields' => $fields,
						'alias' => $association,
						'group' => null
					));
					$query += array('order' => $assocData['order'], 'limit' => $assocData['limit']);
				} else {
					$join = array(
						'table' => $linkModel,
						'alias' => $association,
						'fields' => $this->fields($linkModel, $association, $assocData['fields']),
						'type' => isset($assocData['type']) ? $assocData['type'] : 'LEFT',
						'conditions' => $this->_mergeConditions($this->getConstraint('belongsTo', $model, $linkModel, $association, $assocData), $assocData['conditions'])

					);
					$queryData['fields'] = array_merge($queryData['fields'], $fields);

					if (!empty($assocData['order'])) {
						$queryData['order'][] = $assocData['order'];
					}
					if (!in_array($join, $queryData['joins'])) {
						$queryData['joins'][] = $join;
					}
					return true;
				}
			break;
			case 'hasMany':
				$assocData['fields'] = $this->fields($linkModel, $association, $assocData['fields']);
				if (!empty($assocData['foreignKey'])) {
					$assocData['fields'] = array_merge($assocData['fields'], $this->fields($linkModel, $association, array("{$association}.{$assocData['foreignKey']}")));
				}
				$query = array(
					'conditions' => $this->_mergeConditions($this->getConstraint('hasMany', $model, $linkModel, $association, $assocData), $assocData['conditions']),
					'fields' => array_unique($assocData['fields']),
					'table' => $this->fullTableName($linkModel),
					'alias' => $association,
					'order' => $assocData['order'],
					'limit' => $assocData['limit'],
					'group' => null
				);
			break;
			case 'hasAndBelongsToMany':
				$joinFields = array();
				$joinAssoc = null;

				if (isset($assocData['with']) && !empty($assocData['with'])) {
					$joinKeys = array($assocData['foreignKey'], $assocData['associationForeignKey']);
					list($with, $joinFields) = $model->joinModel($assocData['with'], $joinKeys);

					$joinTbl = $model->{$with};
					$joinAlias = $joinTbl;

					if (is_array($joinFields) && !empty($joinFields)) {
						$joinAssoc = $joinAlias = $model->{$with}->alias;
						$joinFields = $this->fields($model->{$with}, $joinAlias, $joinFields);
					} else {
						$joinFields = array();
					}
				} else {
					$joinTbl = $assocData['joinTable'];
					$joinAlias = $this->fullTableName($assocData['joinTable']);
				}
				$query = array(
					'conditions' => $assocData['conditions'],
					'limit' => $assocData['limit'],
					'table' => $this->fullTableName($linkModel),
					'alias' => $association,
					'fields' => $this->fields($linkModel, $association, $assocData['fields']),
					'order' => $assocData['order'],
					'group' => null,
					'joins' => array(array(
						'table' => $joinTbl,
						'alias' => $joinAssoc,
						'fields' => $joinFields,
						'conditions' => $this->getConstraint('hasAndBelongsToMany', $model, $linkModel, $joinAlias, $assocData, $association)
					))
				);
			break;
		}
		if (isset($query)) {
			return $this->buildStatement($query, $model);
		}
		return null;
	}

/**
 * Returns a conditions array for the constraint between two models
 *
 * @param string $type Association type
 * @param Model $model Model object
 * @param string $linkModel
 * @param string $alias
 * @param array $assoc
 * @param string $alias2
 * @return array Conditions array defining the constraint between $model and $association
 */
	public function getConstraint($type, $model, $linkModel, $alias, $assoc, $alias2 = null) {
		$assoc += array('external' => false, 'self' => false);

		if (empty($assoc['foreignKey'])) {
			return array();
		}

		switch (true) {
			case ($assoc['external'] && $type === 'hasOne'):
				return array("{$alias}.{$assoc['foreignKey']}" => '{$__cakeID__$}');
			case ($assoc['external'] && $type === 'belongsTo'):
				return array("{$alias}.{$linkModel->primaryKey}" => '{$__cakeForeignKey__$}');
			case (!$assoc['external'] && $type === 'hasOne'):
				return array("{$alias}.{$assoc['foreignKey']}" => $this->identifier("{$model->alias}.{$model->primaryKey}"));

			case (!$assoc['external'] && $type === 'belongsTo'):
				return array("{$linkModel->primaryKey}" => array("SELECT+{$assoc['foreignKey']}+FROM+%23{$model->alias}", null));

			case ($type === 'hasMany'):
				return array("{$alias}.{$assoc['foreignKey']}" => array('{$__cakeID__$}'));
			case ($type === 'hasAndBelongsToMany'):
				return array(array("{$alias}.{$assoc['foreignKey']}" => '{$__cakeID__$}'));
		}
		return array();
	}

/**
 * Builds and generates an FQL statement from an array. Handles final clean-up before conversion.
 *
 * @param array $query An array defining an FQL query
 * @param Model $model The model object which initiated the query
 * @return string An executable FQL statement
 */
	public function buildStatement($query, $model) {
		$query = array_merge($this->_queryDefaults, $query);
		if (!empty($query['joins'])) {
			$count = count($query['joins']);
			for ($i = 0; $i < $count; $i++) {
				if (is_array($query['joins'][$i])) {
					$join = $query['joins'][$i];
					$join['table'] = $query['joins'][$i]['table']->useTable;
					$query['joins'][$i] = $this->buildStatement($join, $query['joins'][$i]['table']);
				}
			}
		}
		return $this->renderStatement('select', array(
			'conditions' => $this->conditions($query['conditions'], true, true, $model),
			'fields' => implode(', ', $query['fields']),
			'table' => $query['table'],
			'alias' => $this->alias . $this->name($query['alias']),
			'order' => $this->order($query['order'], 'ASC', $model),
			'limit' => $this->limit($query['limit'], $query['offset']),
			'joins' => implode(' ', $query['joins']),
			'group' => $this->group($query['group'], $model)
		));
	}

/**
 * Renders a final FQL statement by putting together the component parts in the correct order
 *
 * @param string $type type of query being run.  e.g select, create, update, delete, schema, alter.
 * @param array $data Array of data to insert into the query.
 * @throws NotImplementedException
 * @return string Rendered FQL expression to be run.
 */
	public function renderStatement($type, $data) {
		extract($data);
		switch (strtolower($type)) {
			case 'select':
				$conditions = trim($conditions);
				$fields = str_replace(' ', null, $fields);
				$fql = "SELECT {$fields} FROM {$table} {$conditions}{$limit}";
				$fql = sprintf('"%s":"%s"', $alias, $fql);
				if (!empty($joins)) {
					$fql .= ',' . $joins;
				}
				unset($type, $data, $conditions, $fields, $table, $alias, $order, $limit, $joins, $group);
				return $fql;
			break;
			default:
				throw new NotImplementedException(__('Only select query can be render in %s. Other statements not implemented in %s', __FUNCTION__, get_class()));
			break;
		}
	}

}