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
 * @file		FacebookComponent.php
 */

App::uses('Component', 'Component');
App::uses('ConnectionManager','Model');

/**
 * Base class for an individual Component. Components provide reusable bits of
 * controller logic that can be composed into a controller. Components also
 * provide request life-cycle callbacks for injecting logic at specific points.
 *
 * ## Life cycle callbacks
 *
 * Components can provide several callbacks that are fired at various stages of the request
 * cycle. The available callbacks are:
 *
 * - `initialize()` - Fired before the controller's beforeFilter method.
 * - `startup()` - Fired after the controller's beforeFilter method.
 * - `beforeRender()` - Fired before the view + layout are rendered.
 * - `shutdown()` - Fired after the action is complete and the view has been rendered
 *    but before Controller::afterFilter().
 * - `beforeRedirect()` - Fired before a redirect() is done.
 *
 * @package       Cake.Controller
 * @link          http://book.cakephp.org/2.0/en/controllers/components.html
 * @see Controller::$components
 */

class FacebookComponent extends Component {

/**
 * Settings for this Component
 *
 * @var array
 */
	public $settings = array();

/**
 * Other Components this component uses.
 *
 * @var array
 */
	public $components = array();

/**
 * Constructor
 *
 * Append settings from ConnectionManager
 *
 * @param ComponentCollection $collection A ComponentCollection this component can use to lazy load its components
 * @param array $settings Array of configuration settings.
 */
	public function __construct(ComponentCollection $collection, $settings = array()) {
		$settings += ConnectionManager::getDataSource('facebook')->config;
		parent::__construct($collection, $settings);
	}

/**
 * Called before the Controller::beforeFilter().
 *
 * @param Controller $controller Controller with components to initialize
 * @return void
 * @link http://book.cakephp.org/2.0/en/controllers/components.html#Component::initialize
 */
	public function initialize(Controller $controller) {
		$this->_Controller = $controller;
		$controller->Auth->authenticate += array('Facebook.Facebook' => $this->settings);
		$controller->helpers += array('Facebook.Facebook' => $this->settings);
	}

/**
 * Called after the Controller::beforeFilter() and before the controller action
 *
 * @param Controller $controller Controller with components to startup
 * @return void
 * @link http://book.cakephp.org/2.0/en/controllers/components.html#Component::startup
 */
	public function startup(Controller $controller) {
	}

/**
 * Called before the Controller::beforeRender(), and before
 * the view class is loaded, and before Controller::render()
 *
 * @param Controller $controller Controller with components to beforeRender
 * @return void
 * @link http://book.cakephp.org/2.0/en/controllers/components.html#Component::beforeRender
 */
	public function beforeRender(Controller $controller) {
	}

/**
 * Called after Controller::render() and before the output is printed to the browser.
 *
 * @param Controller $controller Controller with components to shutdown
 * @return void
 * @link http://book.cakephp.org/2.0/en/controllers/components.html#Component::shutdown
 */
	public function shutdown(Controller $controller) {
	}

/**
 * Called before Controller::redirect(). Allows you to replace the url that will
 * be redirected to with a new url. The return of this method can either be an array or a string.
 *
 * If the return is an array and contains a 'url' key. You may also supply the following:
 *
 * - `status` The status code for the redirect
 * - `exit` Whether or not the redirect should exit.
 *
 * If your response is a string or an array that does not contain a 'url' key it will
 * be used as the new url to redirect to.
 *
 * @param Controller $controller Controller with components to beforeRedirect
 * @param string|array $url Either the string or url array that is being redirected to.
 * @param integer $status The status code of the redirect
 * @param boolean $exit Will the script exit.
 * @return array|void Either an array or null.
 * @link @link http://book.cakephp.org/2.0/en/controllers/components.html#Component::beforeRedirect
 */
	public function beforeRedirect(Controller $controller, $url, $status = null, $exit = true) {
	}

	public function login($permissions = array()) {
		if (!isset($this->_Controller->request->query['state'])) {
			$params = array(
				'client_id' => $this->settings['app_id'],
				'redirect_uri' => Router::url(
					array(
						'plugin' => 'facebook',
						'controller' => 'users',
						'action' => 'login',
						'admin' => false
						), true
					),
				'state' => CakeSession::read(sprintf('fb_%s_state', $this->settings['app_id'])),
				'scope' => implode(',', $permissions + array_keys(ClassRegistry::init('Facebook.FacebookPermission')->schema())),
			);

			$url = null;
			foreach ($params as $param => $value) {
				$url .= sprintf('%s=%s&', $param, urlencode($value));
			}
			unset($permissions, $params, $param, $value);
			return $this->_Controller->redirect('https://www.facebook.com/dialog/oauth?' . $url);
		}
	}

	/*
	public function post($data = array()) {
		$FacebookPost = ClassRegistry::init('Facebook.FacebookPost');
		$FacebookPost->create();

		if (!is_array($data)) {
			$field = 'message';
			if ((str_replace('http://', null, $data) !== $data)) {
				$field = 'link';
			}
			$data = array($field => $data);
		}

		$data += array('link' => null, 'message' => null, 'name' => null, 'caption' => null, 'description' => null);

		$FacebookPost->create();
		return $FacebookPost->save(array('FacebookPost' => $data));
	}
	*/

}