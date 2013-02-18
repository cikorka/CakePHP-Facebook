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
 * @file		UsersController.php
 */

App::uses('FacebookAppController', 'Facebook.Controller');
 
class UsersController extends FacebookAppController {

/**
 * An array containing the class names of models this controller uses.
 *
 * Example: `public $uses = array('Product', 'Post', 'Comment');`
 *
 * Can be set to several values to express different options:
 *
 * - `true` Use the default inflected model name.
 * - `array()` Use only models defined in the parent class.
 * - `false` Use no models at all, do not merge with parent class either.
 * - `array('Post', 'Comment')` Use only the Post and Comment models. Models
 *	 Will also be merged with the parent class.
 *
 * The default value is `true`.
 *
 * @var mixed A single name as a string or a list of names as an array.
 * @link http://book.cakephp.org/2.0/en/controllers.html#components-helpers-and-uses
 */
	public $uses = array('Facebook.FacebookUser');

/**
 * Used to define methods a controller that will be cached. To cache a
 * single action, the value is set to an array containing keys that match
 * action names and values that denote cache expiration times (in seconds).
 *
 * Example:
 *
 * {{{
 * public $cacheAction = array(
 *		'view/23/' => 21600,
 *		'recalled/' => 86400
 *	);
 * }}}
 *
 * $cacheAction can also be set to a strtotime() compatible string. This
 * marks all the actions in the controller for view caching.
 *
 * @var mixed
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/cache.html#additional-configuration-options
 */
	public $cacheAction = false;

/**
 * Called before the controller action.	 You can use this method to configure and customize components
 * or perform logic that needs to happen before each controller action.
 *
 * @return void
 * @link http://book.cakephp.org/2.0/en/controllers.html#request-life-cycle-callbacks
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow();
	}

/**
 * Login Facebook user
 *
 * 
 */
	public function login() {
		if ($this->Auth->login()) {
			return $this->redirect($this->Auth->redirect());
		}
		return $this->Facebook->login();
	}

/**
 * Logout user
 *
 *
 */
	public function logout() {
		return $this->redirect($this->Auth->logout());
	}

/**
 * Revoke Facebook permission - disconnect user from Facebook App and logout user
 *
 *
 */
	public function disconnect() {
		if ($this->FacebookUser->FacebookPermission->delete('*')) {
			return $this->redirect($this->Auth->logout());
		}
	}

}