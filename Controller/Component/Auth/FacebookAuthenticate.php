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
 * @file		OauthAuthenticate.php
 */

App::uses('BaseAuthenticate', 'Controller/Component/Auth');
App::uses('ClassRegistry', 'Utility');
App::uses('CakeSession', 'Model/Datasource');
App::uses('HttpSocket', 'Network/Http');

class FacebookAuthenticate extends BaseAuthenticate {

/**
 * Settings for this object.
 *
 * - `fields` The fields to use to identify a user by.
 * - `userModel` The model name of the User, defaults to User.
 * - `scope` Additional conditions to use when looking up and authenticating users,
 *    i.e. `array('User.is_active' => 1).`
 * - `recursive` The value of the recursive key passed to find(). Defaults to 0.
 * - `contain` Extra models to contain and store in session.
 *
 * @var array
 */
	public $settings = array(
		'callback' => 'registerFacebookUser',
		'field' => 'facebook_id',
		'fields' => array(
			'username' => 'username',
			'password' => 'password',
		),
		'userModel' => 'User',
		'scope' => array(),
		'recursive' => 0,
		'contain' => null,
	);

/**
 * Instance of FacebookUser Model
 *
 * @var object
 */
	public $FacebookUser = null;

/**
 * Instance of User Model
 *
 * @var object
 */
	public $User = null;

/**
 * Constructor
 *
 * @param ComponentCollection $collection The Component collection used on this request.
 * @param array $settings Array of settings to use.
 */
	public function __construct(ComponentCollection $collection, $settings) {
		$settings += array(
			'redirect_uri' => Router::url(array('controller' => 'users', 'action' => 'login'), true),
		);
		parent::__construct($collection, $settings);

		$this->FacebookUser = ClassRegistry::init('Facebook.FacebookUser');
		$this->User = ClassRegistry::init($this->settings['userModel']);
	}

/**
 * Authenticate a user based on the request information.
 *
 * @param CakeRequest $Request Request to get authentication information from.
 * @param CakeResponse $Response A response object that can have headers added.
 * @return mixed Either false on failure, or an array of user data on success.
 */
	public function authenticate(CakeRequest $Request, CakeResponse $Response) {
		$state = sprintf('fb_%s_state', $this->settings['app_id']);
		if (
			isset($Request->query) &&
			isset($Request->query['code']) &&
			isset($Request->query['state']) &&
			$Request->query['state'] == CakeSession::read($state)
		) {
			$HttpSocket = new HttpSocket();
			$query = array(
				'client_id' => $this->settings['app_id'],
				'redirect_uri' => $this->settings['redirect_uri'],
				'client_secret' => $this->settings['app_secret'],
				'code' => $Request->query['code'],
			);

			$fbResponse = $HttpSocket->get('https://graph.facebook.com/oauth/access_token', $query);

			$params = null;
			parse_str($fbResponse->body, $params);

			if (isset($params['access_token'])) {
				CakeSession::write('access_token', $params['access_token']); // Saves acces_token in Session
				if (isset($params['expires'])) {
					Configure::write('Session.timeout', $params['expires']);
				}

				$this->FacebookUser->contain();
				$this->FacebookUser->id = 'me()';
				$fbUser = $this->FacebookUser->read(); // Get's user data from Facebook

				$alias = $this->FacebookUser->alias;
				$key = $this->FacebookUser->primaryKey;

				$user = $this->_findUser(array($this->User->alias . '.' . $this->settings['field'] => $fbUser[$alias][$key]));

				// Checks if user exists, if not run callback for saves it in db
				if ($user === false) {
					if (method_exists($this->User, $this->settings['callback'])) {
						call_user_func(array($this->User, $this->settings['callback']), $fbUser);
					}
					$user = $this->_findUser(array($this->User->alias . '.' . $this->settings['field'] => $fbUser[$alias][$key]));
				}
				return $user;
			}
		}
		return false;
	}

}