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
 * @file		CacheController.php
 */

App::uses('AppController', 'Controller');
 
class CacheController extends AppController {

/**
 * Constructor
 *
 * @param CakeRequest $request
 * @param CakeResponse $response
 */
	public function __construct($request = null, $response = null) {
		parent::__construct($request, $response);
		if (
			count(Router::extensions()) &&
			!array_key_exists('RequestHandler', $this->components) &&
			!in_array('RequestHandler', $this->components, true)
		) {
			$this->components[] = 'RequestHandler';
		}
		$this->constructClasses();
		if ($this->Components->enabled('Auth')) {
			$this->Components->disable('Auth');
		}
		if ($this->Components->enabled('Security')) {
			$this->Components->disable('Security');
		}
		$this->startupProcess();
	}

/**
 * Called before the controller action.	 You can use this method to configure and customize components
 * or perform logic that needs to happen before each controller action.
 *
 * @return void
 * @link http://book.cakephp.org/2.0/en/controllers.html#request-life-cycle-callbacks
 */
	public function beforeFilter() {
	}

/**
 * Called after the controller action is run, but before the view is rendered. You can use this method
 * to perform logic or set view variables that are required on every request.
 *
 * @return void
 * @link http://book.cakephp.org/2.0/en/controllers.html#request-life-cycle-callbacks
 */
	public function beforeRender() {
		$this->helpers = array();
	}

/**
 * Displays cache in a blank view
 */
	public function index() {
		$this->response->header('Pragma', 'public');
		$this->response->cache('-1 minute', '+ 10 years');
		$this->response->expires('+ 10 years');
		$this->layout = false;
		$this->set('locale', 'en_US');
	}

}