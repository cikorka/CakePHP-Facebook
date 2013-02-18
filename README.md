# CakePHP Ultimate Facebook Plugin

Contains FacebookComponent, FacebookHelper, FacebookAuthenticate class, Facebook FQL Datasource and many model classes for CakePHP 2.x

## Install and Setup

* First download & unzip or clone the repository into your `app/Plugin/Facebook` directory

		git clone https://github.com/cikorka/CakePHP-Ultimate-Facebook-Plugin.git app/Plugin/Facebook

* Load the plugin in your `app/Config/bootstrap.php` file:

		//app/Config/bootstrap.php
		CakePlugin::load('Facebook');

* Add component in `app/Controller/AppController.php` file:

		//app/Controller/AppController.php
		public $components = array('Facebook.Facebook');

* Add configuration below to `app/Config/database.php` file:

		//app/Config/database.php
		public $facebook = array(
			'datasource' => 'Facebook.FQL',
			'app_id' => '4236257110xxxxx',
			'app_secret' => '0855cfd6592xxxxxxxxxxxxxxx'
		);

## Using

Helper and Autentificate class are loaded automaticly.

	// In the View. See FacebookHelper for avaible options
	$options = array('perms' => array('email', 'publish_stream', 'etc...'));
	echo $this->Facebook->login('Login', $options);
