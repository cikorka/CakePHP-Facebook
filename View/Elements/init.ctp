window.fbAsyncInit = function() {
	// init the FB JS SDK
	FB.init({
		appId		: '<?php echo $app_id; ?>', // App ID from the App Dashboard
		channelUrl	: '<?php echo Router::url(array('plugin' => 'facebook', 'controller' => 'cache'), true); ?>',
		status		: true, // check the login status upon init?
		cookie		: true, // set sessions cookies to allow your server to access the session?
		xfbml		: true // parse XFBML tags on this page?
	});

	// Additional initialization code such as adding Event Listeners goes here
<?php 

/**
 * Integration with Google Analytics social tracking
 *
 * @link http://developers.google.com/analytics/devguides/collection/gajs/gaTrackingSocial
 */

if (isset($web) && $web['Site']['google_analytics'] === true) : ?>

	FB.Event.subscribe('edge.create', function(targetUrl) {
		_gaq.push(['_trackSocial', 'facebook', 'like', targetUrl]);
	});

	FB.Event.subscribe('edge.remove', function(targetUrl) {
		_gaq.push(['_trackSocial', 'facebook', 'unlike', targetUrl]);
	});

	FB.Event.subscribe('message.send', function(targetUrl) {
		_gaq.push(['_trackSocial', 'facebook', 'send', targetUrl]);
	});
	
<?php endif; ?>

	// Checks whether the user is logged in
	FB.getLoginStatus(function(response) {
		if (response.status === 'connected') {
			// connected
			// alert('connected');
			<?php
				if (!AuthComponent::user() && $autoLogin === true) {
					echo "top.location.href = '" . Router::url(array('plugin' => 'facebook', 'controller' => 'users', 'action' => 'login')) . "';";					
				}
			?>
		} else if (response.status === 'not_authorized') {
			// not_authorized
			// alert('not_authorized');
		} else {
			// not_logged_in
			// alert('not_logged_in');
		}
	});
};

// Load the SDK's source Asynchronously
// Note that the debug version is being actively developed and might
// contain some type checks that are overly strict.
// Please report such bugs using the bugs tool.
(function(d, debug){
 var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
 if (d.getElementById(id)) {return;}
 js = d.createElement('script'); js.id = id; js.async = true;
 js.src = "//connect.facebook.net/<?php echo $locale; ?>/all" + (debug ? "/debug" : "") + ".js";
 ref.parentNode.insertBefore(js, ref);
}(document, /*debug*/ false));