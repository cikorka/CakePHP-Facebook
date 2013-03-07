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
 * @file		FacebookHelper.php
 */

App::uses('AppHelper', 'View/Helper');
App::uses('AuthComponent', 'Controller/Component');
App::uses('CakeSession', 'Model/Datasource');


class FacebookHelper extends AppHelper {

/**
 * Settings for this helper.
 *
 * - `perms` array asked permissions for $this->login()
 * - `url` string default Facebook oauth dialog url mask
 * - `app_id` string Facebook app id
 * - `channel_url` string
 * - `redirect_uri` string Redirect url after Facebook authorization (to login action)
 *
 * @var array
 */
	public $settings = array(
		'html5' => true,
		'perms' => array(),
		'css' => true,
		'autoLogin' => true
	);

/**
 * List of helpers used by this helper
 *
 * @var array
 */
	public $helpers = array('Html','Session', 'Js', 'Text');

/**
 * Before layout callback.	beforeLayout is called before the layout is rendered.
 *
 * Overridden in subclasses.
 *
 * @param string $layoutFile The layout about to be rendered.
 * @return void
 */
	public function beforeLayout($layoutFile) {
		$this->settings['locale'] = 'en_US';
		switch (Configure::read('Config.language')) {
			case 'cs' :
			case 'ces' :
				$this->settings['locale'] = 'cs_CZ';
			break;
		}

		$this->meta();

		if ($this->settings['css'] === true) {
			$this->Html->css('Facebook.zocial/zocial', array(), array('inline' => false));
		}

		$this->Js->buffer($this->_View->element('Facebook.init', $this->settings));
		$this->_View->output .= $this->Html->tag('div', '', array('id' => 'fb-root'));
	}

/**
 * Open Graph tags
 *
 * If you use Open Graph tags, the following six are required:

 * - `og:title` - The title of the entity.
 * - `og:type` - The type of entity. You must select a type from the list of Open Graph types.
 * - `og:image - The URL to an image that represents the entity.
 *    Images must be at least 50 pixels by 50 pixels (though minimum 200px by 200px is preferred).
 *    Square images work best, but you are allowed to use images up to three times as wide as they are tall.
 * - `og:url` - The canonical, permanent URL of the page representing the entity.
 *    When you use Open Graph tags, the Like button posts a link to the og:url instead of the URL in the Like button code.
 * - `og:site_name` - A human-readable name for your site, e.g., "IMDb".
 * - `fb:admins` or `fb:app_id` - A comma-separated list of either the Facebook IDs of page administrators or
 *    a Facebook Platform application ID. At a minimum, include only your own Facebook ID.
 *
 * More information on Open Graph tags and details on Administering your page can be found on the Open Graph protocol documentation .
 *
 *
 * ### Object types (og:type) <meta property="og:type" content="athlete" />
 *
 *
 * ### Activities
 *
 * - `activity`
 * - `sport`
 *
 * ### Businesses
 *
 * - `bar`
 * - `company`
 * - `cafe`
 * - `hotel`
 * - `restaurant`
 *
 * ### Groups
 *
 * - `cause`
 * - `sports_league`
 * - `sports_team`
 *
 * ### Organizations
 *
 * - `band`
 * - `government`
 * - `non_profit`
 * - `school`
 * - `university`
 *
 * ### People
 *
 * - `actor`
 * - `athlete`
 * - `author`
 * - `director`
 * - `musician`
 * - `politician`
 * - `public_figure`
 *
 * ### Places
 *
 * - `city`
 * - `country`
 * - `landmark`
 * - `state_province`
 *
 * ### Products and Entertainment
 *
 * - `album`
 * - `book`
 * - `drink`
 * - `food`
 * - `game`
 * - `product`
 * - `song`
 * - `movie`
 * - `tv_show`
 *
 * For products which have a UPC code or ISBN number, you can specify them using the og:upc and og:isbn properties.
 * These properties help uniquely identify products.
 *
 * ### Websites
 *
 * - `blog`
 * - `website`
 * - `article`
 *
 *
 * Use article for any URL that represents transient content - such as a news article, blog post, photo, video, etc.
 * Do not use website for this purpose. website and blog are designed to represent an entire site,
 * an og:type tag with types website or blog should usually only appear on the root of a domain.
 *
 * @link https://developers.facebook.com/docs/opengraphprotocol
 */
	public function meta($type = 'company', $tags = array(), $inline = false) {
		$tags += array(
			'og:title' => $this->_View->get('title_for_layout'),
			'og:type' => $type,
			'og:url' => Router::url(null, true),
			'og:image' => '',
			'og:site_name' => (isset($this->_View->viewVars['web']['Site']['name'])) ? $this->_View->viewVars['web']['Site']['name'] : null,
			'fb:app_id' => $this->settings['app_id'],
		);
		$meta = null;
		foreach ($tags as $property => $content) {
			$meta .= $this->Html->meta(array('property' => $property, 'content' => $content), null, array('inline' => $inline));
		}
		return $meta;
	}

/**
 * Generate Facebook login link
 *
 * ### Attributes
 *
 * - `client_id` - required - Your App ID. This is called client_id instead of app_id for this particular method in order
 *    to be compliant with the OAuth 2.0 specification.
 * - `redirect_uri` - required - The URL to redirect to after the user clicks a button in the dialog.
 *    The URL you specify must be a URL of with the same Base Domain as specified in your app's settings,
 *    a Canvas URL of the form https://apps.facebook.com/YOUR_APP_NAMESPACE or a Page Tab URL of the
 *    form https://www.facebook.com/PAGE_USERNAME/app_YOUR_APP_ID
 * - `scope` - A comma separated list of permission names which you would like the user to grant your application.
 *    Only the permissions which the user has not already granted your application will be shown
 * - `state` - A unique string used to maintain application state between the request and callback.
 *    When Facebook redirects the user back to your redirect_uri, this parameter's value will be included in the response.
 *    You should use this to protect against Cross-Site Request Forgery.
 * - `response_type` - The requested response type, one of code or token. Defaults to code.
 *    If left unset, or set to code the Dialog's response will include an OAuth code which can be exchanged
 *    for an access token as per the server-side authentication flow. If set to token, the Dialog's response
 *    will include an oauth user access token in the fragment of the URL the user is redirected to - as per
 *    the client-side authentication flow.
 * - `display` - The display mode with which to render the Dialog.
 *    One of page, popup or touch. Defaults to page when the user is using a desktop browser or the dialog is
 *    invoked on the www.facebook.com domain. Defaults to touch when the user is using a mobile browser or the dialog
 *    is invoked on the m.facebook.com domain. In page mode, the OAuth dialog is displayed in the full Facebook chrome.
 *    In 'popup' mode, the OAuth dialog is displayed in a form suitable for embedding in a popup window.
 *    This parameter is automatically specified by most Facebook SDK, so may not need to be set explicitly.
 *
 * ### Return Values
 *
 * If the user authorizes your application, the browser will redirect to the URL you specified in the redirect_uri parameter.
 *
 * If the response_type was left unset or was set to the value code, if the user authorizes your application,
 * the browser will be redirected to:
 *
 * `YOUR_REDIRECT_URI?code=OAUTH_CODE_GENERATED_BY_FACEBOOK&state=YOUR_STATE_VALUE`
 *
 * If the response_type was the value token, if the user authorizes your application, the browser will be redirected to:
 *
 * `YOUR_REDIRECT_URI#access_token=USER_ACCESS_TOKEN&expires_in=NUMBER_OF_SECONDS_UNTIL_TOKEN_EXPIRES&state=YOUR_STATE_VALUE`
 *
 * If the user does not authorize your application, the browser will redirect to:
 *
 * `YOUR_REDIRECT_URI?error_reason=user_denied&error=access_denied&error_description=The+user+denied+your+request.&state=YOUR_STATE_VALUE`
 *
 * @link https://developers.facebook.com/docs/reference/login/#permissions
 * @link https://developers.facebook.com/docs/reference/dialogs/oauth/
 * @return string
 */
	public function login($label = null, $options = array()) {
		$defaults = array('class' => 'zocial facebook');
		$options = array_merge($defaults, $options);
		$state = sprintf('fb_%s_state', $this->settings['app_id']);
		if (!CakeSession::read($state)) { // Facebook requests a csrf protection token
			 CakeSession::write($state, md5(uniqid(rand(), true)));
		}

		//$options['perms'] = ClassRegistry::init('Facebook.FacebookUser')->permissions();

		if (isset($options['perms']) && is_array($options['perms'])) {
			$this->settings['perms'] += $options['perms'];
			unset($options['perms']);
		}

		$params = array(
			'redirect_uri' => Router::url(array('plugin' => 'facebook', 'controller' => 'users', 'action' => 'login'), true),
			'state' => CakeSession::read($state),
			'scope' => implode(',', $this->settings['perms']),
		);

		if (isset($options['display']) && $options['display'] == 'popup') {
			$onclick = "FB.login(function(response){if(response.authResponse){top.location.href = ':redirect_uri';} else {}}, {scope: ':scope'});";
			$options += array('onclick' => $this->Text->insert($onclick, $params));
			return $this->Html->link(($label === null) ? __d('facebook', 'Facebook Login') : $label, '#', $options);
		}
		$url = Router::url(array('plugin' => 'facebook', 'controller' => 'users', 'action' => 'login'));
		return $this->Html->link(($label === null) ? __d('facebook', 'Facebook Login') : $label, $url, $options);
	}

/**
 * Login Button
 *
 * The Login Button shows profile pictures of the user's friends who have already signed up for your site in addition to a login button.
 *
 * You can specify the maximum number of rows of faces to display. The plugin dynamically sizes its height;
 * for example, if you specify a maximum of four rows of faces, and there are only enough friends to fill two rows,
 * the height of the plugin will be only what is needed for two rows of faces.
 *
 * ### Attributes
 *
 * - `show-faces` - specifies whether to show faces underneath the Login button.
 * - `width` - the width of the plugin in pixels. Default width: 200px.
 * - `max-rows` - the maximum number of rows of profile pictures to display. Default value: 1.
 * - `scope` - a comma separated list of extended permissions.
 *    By default the Login button prompts users for their public information.
 *    If your application needs to access other parts of the user's profile that may be private,
 *    your application can request extended permissions. A complete list of extended permissions can be found here.
 * - `registration-url` - registration page url. If the user has not registered for your site,
 *    they will be redirected to the URL you specify in the registration-url parameter.
 * - `size` - Different sized buttons: small, medium, large, xlarge (default: medium).
 *
 * @link https://developers.facebook.com/docs/reference/plugins/login/
 * @param array $settings
 * @return string
 */
	public function loginButton($settings = array()) {
		return $this->__tag('login-button', $settings);
	}


	public function registration($settings = array()) {
	}

	public function logout($label = null, $options = array()) {
		if (AuthComponent::user()) {
			$url = Router::url(array('plugin' => 'facebook', 'controller' => 'users', 'action' => 'logout'), true);
			$options += array('onclick' => "FB.logout(function(response) {top.location.href = '$url';});");
			return $this->Html->link(($label === null) ? __d('facebook', 'Logout') : $label, $url, $options);
		}
	}

	public function disconnect($label = null, $options = array()) {
		$url = array('plugin' => 'facebook', 'controller' => 'users', 'action' => 'disconnect');
		return $this->Html->link(($label === null) ? __d('facebook', 'Disconnect') : $label, $url, $options);
	}

/**
 * Like Button
 *
 * The Like button lets a user share your content with friends on Facebook.
 * When the user clicks the Like button on your site, a story appears in the user's friends' News Feed with a link back to your website.
 *
 * ### Attributes
 *
 * - `href` - the URL to like. The XFBML version defaults to the current page.
 * - `send` - specifies whether to include a Send button with the Like button. This only works with the XFBML version.
 * - `layout` - there are three options:
 * 		- `standard` - 	displays social text to the right of the button and friends' profile photos below.
 *		   Minimum width: 225 pixels. Minimum increases by 40px if action is 'recommend' by and increases by 60px if send is 'true'.
 *		   Default width: 450 pixels. Height: 35 pixels (without photos) or 80 pixels (with photos).
 *		- `button_count` - displays the total number of likes to the right of the button.
 * 		   Minimum width: 90 pixels. Default width: 90 pixels. Height: 20 pixels.
 * 		- `box_count` - displays the total number of likes above the button.
 *		   Minimum width: 55 pixels. Default width: 55 pixels. Height: 65 pixels.
 * - `show_faces` - specifies whether to display profile photos below the button (standard layout only)
 * - `width` - the width of the Like button.
 * - `action` - the verb to display on the button. Options: 'like', 'recommend'
 * - `font` - the font to display in the button. Options: 'arial', 'lucida grande', 'segoe ui', 'tahoma', 'trebuchet ms', 'verdana'
 * - `colorscheme` - the color scheme for the like button. Options: 'light', 'dark'
 * - `ref` - a label for tracking referrals;
 *    must be less than 50 characters and can contain alphanumeric characters and some punctuation (currently +/=-.:_).
 *    The ref attribute causes two parameters to be added to the referrer URL when a user clicks a link from a stream story about a Like action:
 * 		- `fb_ref` - the ref parameter
 * 		- `fb_source` - the stream type ('home', 'profile', 'search', 'ticker', 'tickerdialog' or 'other')
 *		   in which the click occurred and the story type ('oneline' or 'multiline'), concatenated with an underscore.
 *
 * @link https://developers.facebook.com/docs/reference/plugins/like
 * @param array $settings
 * @return string
 */
	public function like($settings = array()) {
		$settings += array('send' => true);
		return $this->__tag('like', $settings);
	}

/**
 * Send Button
 *
 * The Send Button allows users to easily send content to their friends.
 * People will have the option to send your URL in a message to their Facebook friends,
 * to the group wall of one of their Facebook groups, and as an email to any email address.
 * While the Like Button allows users to share content with all of their friends,
 * the Send Button allows them to send a private message to just a few friends.
 *
 * The message will include a link to the URL specified in the send button, along with a title, image, and short description of the link.
 * You can specify what is shown for the title, image, and description by using Open Graph meta tags.
 *
 * ### Attributes
 *
 * - `href` - the URL to send.
 * - `font` - the font to display in the button. Options: 'arial', 'lucida grande', 'segoe ui', 'tahoma', 'trebuchet ms', 'verdana'
 * - `colorscheme` - the color scheme for the like button. Options: 'light', 'dark'
 * - `ref` - a label for tracking referrals;
 *    must be less than 50 characters and can contain alphanumeric characters and some punctuation (currently +/=-.:_).
 *    The ref attribute causes two parameters to be added to the referrer URL when a user clicks a link from a stream story about a Send action:
 * 		- `fb_ref` - the ref parameter
 * 		- `fb_source` - the story type ('message', 'group', 'email') in which the click occurred.
 *
 * @link https://developers.facebook.com/docs/reference/plugins/send
 * @param array $settings
 * @return string
 */
	public function send($settings = array()) {
		return $this->__tag('send', $settings);
	}

/**
 * Follow Button
 *
 * The Follow button lets a user follow your public updates on Facebook.
 * There are two Follow button implementations: XFBML and Iframe.
 * The XFBML (also available in HTML5-compliant markup) version is more versatile, and requires use of the JavaScript SDK.
 * The XFBML dynamically re-sizes its height according to whether there are profile pictures to display.
 *
 * ### Attributes
 *
 * - `href` - profile URL of the user to follow. This must be a facebook.com profile URL.
 * - `layout` - there are three options:
 * 		- `standard` - 	displays social text to the right of the button and friends' profile photos below.
 *		   Minimum width: 225 pixels. Minimum increases by 40px if action is 'recommend' by and increases by 60px if send is 'true'.
 *		   Default width: 450 pixels. Height: 35 pixels (without photos) or 80 pixels (with photos).
 *		- `button_count` - displays the total number of likes to the right of the button.
 * 		   Minimum width: 90 pixels. Default width: 90 pixels. Height: 20 pixels.
 * 		- `box_count` - displays the total number of likes above the button.
 *		   Minimum width: 55 pixels. Default width: 55 pixels. Height: 65 pixels.
 * - `show_faces` - specifies whether to display profile photos below the button (standard layout only)
 * - `width` - the width of the plugin.
 * - `font` - the font to display in the button. Options: 'arial', 'lucida grande', 'segoe ui', 'tahoma', 'trebuchet ms', 'verdana'
 * - `colorscheme` - the color scheme for the like button. Options: 'light', 'dark'
 *
 * @link https://developers.facebook.com/docs/reference/plugins/follow
 * @param array $settings
 * @return string
 */
	public function follow($settings = array()) {
		return $this->__tag('follow', $settings);
	}

/**
 * Comments
 *
 * Comments Box is a social plugin that enables user commenting on your site. Features include moderation tools and distribution.
 *
 * ## Social Relevance
 * Comments Box uses social signals to surface the highest quality comments for each user.
 * Comments are ordered to show users the most relevant comments from friends, friends of friends,
 * and the most liked or active discussion threads, while comments marked as spam are hidden from view.
 *
 * ## Social Relevance
 *
 * Comments are easily shared with friends or with people who like your Page on Facebook.
 * If a user leaves the “Post to Facebook” box checked when she posts a comment, a story appears on
 * her friends’ News Feed indicating that she’s made a comment on your website, which will also link back to your site.
 *
 *
 * Friends and people who like the Page can then respond to the discussion by liking or replying to the comment
 * directly in the News Feed on Facebook or in the Comments Box on your site.
 * Threads stay synced across Facebook and on the Comments Box on your site regardless of where the comment was made.
 *
 * The mobile version will automatically show up when a mobile device user agent is detected.
 * You can turn this behavior off by setting the mobile parameter to false.
 * Please note: the mobile version ignores the width parameter, and instead has a fluid width of 100% in
 * order to resize well in portrait/landscape switching situations. You may need to adjust your CSS for your
 * mobile site to take advantage of this behavior. If preferred, you can still control the width via a container element.
 *
 *
 *
 * ## Moderation tools
 *
 * Admins can choose to make the default for new comments entered either “visible to everyone” or
 * “has limited visibility” on the site (i.e., the comment is only visible to the commenter and their friends),
 * to help mitigate irrelevant content.
 *
 * Admins can also blacklist words and ban users.
 * If a new comment is published from a banned user or contains a blacklisted word, this comment will automatically have limited visibility.
 *
 * To moderate, you need to list yourself as an admin.
 * To do this, simply include open graph meta tags on the URL specified as the href parameter of the plugin.
 * These tags must be included in the <head> of the document.
 *
 * Include:
 *
 * `<meta property="fb:admins" content="{YOUR_FACEBOOK_USER_ID}"/>`
 * To add multiple moderators, separate the uids by comma without spaces.
 *
 * If your site has many comments boxes, we strongly recommend you specify a Facebook app id as the administrator
 * (all administrators of the app will be able to moderate comments).
 * Doing this enables a moderator interface on Facebook where comments from all plugins administered
 * by your app id can be easily moderated together. This tag should be specified in the <head>.

 * `<meta property="fb:app_id" content="{YOUR_APPLICATION_ID}"/>`
 * You can moderate comments from just this plugin inline.
 * If you have specified your app id as the admin, you can moderate all your plugins at http://developers.facebook.com/tools/comments.
 *
 * When you implement multiple comments boxes on your site and tie them together using an app_id,
 * the moderation settings you choose will apply to all your comments boxes.
 * For example, changing the moderation setting to 'has limited visibility' will affect all comments boxes under the same app_id.
 * If you need to apply different moderation paradigms in different areas of your site, you should use two or more app_ids.
 * You may then apply different moderation settings each group of comments boxes.
 *
 *
 * ### Attributes
 *
 * - `href` - the URL for this Comments plugin. News feed stories on Facebook will link to this URL.
 * - `width` - the width of the plugin in pixels. Minimum recommended width: 400px.
 * - `colorscheme` - the color scheme for the like button. Options: 'light', 'dark'
 * - `num_posts` - the number of comments to show by default. Default: 10. Minimum: 1
 * - `order_by` -  the order to use when displaying comments. Options: 'social', 'reverse_time', 'time'. Default: 'social'
 * - `mobile` -  whether to show the mobile-optimized version. Default: auto-detect.
 *
 *
 * @link https://developers.facebook.com/docs/reference/plugins/comments/
 * @param array $settings
 * @return string
 */
	public function comments($settings = array()) {
		return $this->__tag('comments', $settings);
	}

/**
 * Activity Feed
 *
 * The Activity Feed plugin displays the most interesting recent activity taking place on your site.
 * Since the content is hosted by Facebook, the plugin can display personalized content whether or not the user has logged into your site.
 * The activity feed displays stories when users interact with content on your site, such as like, watch,
 * read, play or any custom action. Activity is also displayed when users share content from your site in Facebook
 * or if they comment on a page on your site in the Comments box. If a user is logged into Facebook,
 * the plugin will be personalized to highlight content from their friends. If the user is logged out,
 * the activity feed will show recommendations from across your site, and give the user the option to log in to Facebook.
 *
 * The plugin is filled with activity from the user's friends.
 * If there isn't enough friend activity to fill the plugin, it is backfilled with recommendations.
 * If you set the recommendations param to true, the plugin is split in half, showing friends activity in the top half,
 * and recommendations in the bottom half. If there is not enough friends activity to fill half of the plugin,
 * it will include more recommendations.
 *
 * The Activity Feed plugin can be configured in the following ways:
 *
 * ## App ID
 *
 * If you specify an App ID for the Activity Feed plugin,
 * we will display all actions (built-in and custom) specified by the associated app ID.
 * Note: if you are using the xfbml version of the plugin, you need to specify your
 * application id when you initiate the Javascript library.
 * If you are using the iframe version of the plugin you should pass in the id, as the 'app_id' parameter to the plugin.
 *
 * `<fb:activity site="http://www.jerrycain.com" app_id="118280394918580"></fb:activity>`
 *
 * ## One or more action types
 *
 * To specify one or more action types to display in the Activity Feed plugin, you can specify a comma separated list of action types.
 * The list of action types can include both built-in and custom actions.
 *
 * `<fb:activity site="http://www.jerrycain.com" action="critiqueapp:despise,critiqueapp:review,critiqueapp:grade"></fb:activity>`
 *
 * ## Domain
 *
 * By specifying a domain to show activity for, we will display all built-in actions for the specified domain: like, read, watch, play, listen.
 * The domain is matched exactly, so a plugin with site=facebook.com would not include activity from
 * developers.facebook.com or www.facebook.com. You cannot currently aggregate across multiple domains.
 *
 * `<fb:activity site="http://www.jerrycain.com"></fb:activity>`
 *
 * ### Attributes
 *
 * `site` - the domain for which to show activity. The XFBML version defaults to the current domain.
 * `action` - a comma separated list of actions to show activities for.
 * `app_id` - will display all actions, custom and built-in, associated with this app_id.
 * `width` - the width of the plugin in pixels. Default width: 300px.
 * `height` - the height of the plugin in pixels. Default height: 300px.
 * `header` - specifies whether to show the Facebook header.
 * `colorscheme` - the color scheme for the plugin. Options: 'light', 'dark'
 * `font` - the font to display in the plugin. Options: 'arial', 'lucida grande', 'segoe ui', 'tahoma', 'trebuchet ms', 'verdana'
 * `recommendations` - specifies whether to always show recommendations in the plugin.
 *  If recommendations is set to true, the plugin will display recommendations in the bottom half.
 * `filter` - allows you to filter which URLs are shown in the plugin.
 *  The plugin will only include URLs which contain the filter string in the first two path parameters of the URL.
 *  If nothing in the first two path parameters of the URL matches the filter, the URL will not be included.
 *  For example, if the 'site' parameter is set to 'www.example.com' and the 'filter' parameter
 *  was set to '/section1/section2' then only pages which matched 'http://www.example.com/section1/section2/*'
 *  would be included in the activity feed section of this plugin.
 *  The filter parameter does not apply to any recommendations which may appear in this plugin (see above);
 *  Recommendations are based only on 'site' parameter.
 * `linktarget` - This specifies the context in which content links are opened.
 *  By default all links within the plugin will open a new window.
 *  If you want the content links to open in the same window, you can set this parameter to _top or _parent.
 *  Links to Facebook URLs will always open in a new window.
 * `ref` - a label for tracking referrals; must be less than 50 characters and can contain
 *  alphanumeric characters and some punctuation (currently +/=-.:_).
 *  Specifying a value for the ref attribute adds the 'fb_ref' parameter to the any links back to
 *  your site which are clicked from within the plugin. Using different values for the ref parameter for
 *  different positions and configurations of this plugin within your pages allows you to track which instances are performing the best.
 * `max_age` - a limit on recommendation and creation time of articles that are surfaced in the plugins,
 *  the default is 0 (we don’t take age into account). Otherwise the valid values are 1-180, which specifies the number of days.
 *
 * ### What is the best way to know which plugin on my site generated the traffic?
 *
 * Add the 'ref' parameter to the plugin (see "Attributes" above).
 *
 * Examples:
 *
 * `<fb:activity ref="homepage"></fb:activity>`
 * When a user clicks a link on the plugin, we will pass back the ref value as a fb_ref parameter in the referrer URL.
 * Example:http://www.facebook.com/l.php?fb_ref=homepage
 *
 * @link https://developers.facebook.com/docs/reference/plugins/activity/
 * @param array $settings
 * @return string
 */
	public function activity($settings = array()) {
		return $this->__tag('activity', $settings);
	}

	public function box($settings = array()) {
	}

	public function bar($settings = array()) {
	}

	public function facepile($settings = array()) {
	}

/**
 * fb:name
 *
 * Renders the name of the user specified, optionally linked to his or her profile.
 *
 * ### Attributes
 *
 * - `uid - The ID of the user or Page whose name you want to show.
 *    Alternately, you can use "profileowner" only on a user's profile; you can use "loggedinuser" only on canvas pages.
 * - `firstnameonly - Show only the user's first name. Default value is false
 * - `linked - Link to the user's profile. Default value is true
 * - `lastnameonly - Show only the user's last name. Default value is false
 * - `capitalize - Capitalize the text if useyou is true and loggedinuser is uid. Default value is false
 * - `subjectid - The Facebook ID of the subject of the sentence where this name is the object of the verb of the sentence.
 *    Will use the reflexive when appropriate.
 *    When ''subjectid'' is used, ''uid'' is considered to be the object and ''uid'''s name is produced.
 * - `possessive - Make the user's name possessive (e.g. Joe's instead of Joe). Default value is false.
 * - `reflexive - Use "yourself" if useyou is true. Default value is false.
 * - `shownetwork - Displays the primary educational network for the uid, if applicable. Other networks do not show. Default value is false.
 * - `useyou - Use "you" if uid matches the logged in user. Default value is true.
 * - `ifcantsee - Alternate text to display if the logged in user cannot access the user specified.
 *    To specify an empty string instead of the default, use ifcantsee="". Default value is Facebook User *
 *
 * @link https://developers.facebook.com/docs/reference/plugins/send
 * @param array $settings
 * @return string
 */
	public function name($settings = array()) {
		$settings += array('useyou' => false, 'uid' => AuthComponent::user('facebook_id'));
		return $this->__tag('name', $settings);
	}

/**
 * fb:profile-pic
 *
 * Turns into an img tag for the specified user's or Facebook Page's profile picture.
 *
 * The tag itself is treated like a standard img tag, so attributes valid for img are valid with fb:profile-pic as well.
 * So you could specify width and height settings instead of using the size attribute, for example.
 *
 * ### Attributes
 *
 * - `uid` - The user ID of the profile or Facebook Page for the picture you want to display.
 *    On a canvas page, you can also use "loggedinuser".
 * - `size` - The size of the image to display. Default value is thumb.
 *    Other valid values are thumb (t) (50px wide), small (s) (100px wide), normal (n) (200px wide), and square (q) (50px by 50px).
 *    Or, you can specify width and height settings instead, as described below.
 * - `linked` - Make the image a link to the user's profile. Default value is true
 * - `Facebook-logo` - (For use with Facebook Connect only.)
 *    When set to true, it returns the Facebook favicon image, which gets overlaid on top of the user's profile picture on a site.
 * - `width` - Specifies the desired width, in pixels, of the image (like an img tag).
 * - `height` - Specifies the desired height, in pixels, of the image (like an img tag).
 *
 * @link https://developers.facebook.com/docs/reference/plugins/send
 * @param array $settings
 * @return string
 */
	public function picture($settings = array()) {
		$settings += array('uid' => AuthComponent::user('facebook_id'));
		return $this->__tag('profile-pic', $settings);
	}

/*	protected function _permissions($map = null) {
		if ($map !== null) {
			return $this->_perms[$map];
		}
		$perms = array();
		foreach ($this->_perms as $map => $_perms) {
			$perms = array_merge($perms, $_perms);
		}
		return $perms;
	}
*/
/**
 * Generate Facebook XFBML tag
 *
 * @param string $tag
 * @param array $settings
 * @return string
 */
	private function __tag($tag, $options) {
		foreach ($options as $option => $value) {
			if ($value === false) {
				$options[$option] = 'false';
			}
		}
		if ($this->settings['html5'] === true) {
			$settings = array();
			foreach ($options as $attr => $value) {
				$settings[sprintf('data-%s', $attr)] = $value;
			}
			return $this->Html->div(sprintf('fb-%s', $tag), '', $settings);
		}
		return $this->Html->tag(sprintf('fb:%s', $tag), '', $options);
	}

}