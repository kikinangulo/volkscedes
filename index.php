<?php

/**
 * This sample app is provided to kickstart your experience using Facebook's
 * resources for developers.  This sample app provides examples of several
 * key concepts, including authentication, the Graph API, and FQL (Facebook
 * Query Language). Please visit the docs at 'developers.facebook.com/docs'
 * to learn more about the resources available to you
 */

// Provides access to app specific values such as your app id and app secret.
// Defined in 'AppInfo.php'
require_once('AppInfo.php');

// Enforce https on production
if (substr(AppInfo::getUrl(), 0, 8) != 'https://' && $_SERVER['REMOTE_ADDR'] != '127.0.0.1') {
  header('Location: https://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
  exit();
}

// This provides access to helper functions defined in 'utils.php'
require_once('utils.php');


/*****************************************************************************
 *
 * The content below provides examples of how to fetch Facebook data using the
 * Graph API and FQL.  It uses the helper functions defined in 'utils.php' to
 * do so.  You should change this section so that it prepares all of the
 * information that you want to display to the user.
 *
 ****************************************************************************/

require_once('sdk/src/facebook.php');

$facebook = new Facebook(array(
  'appId'  => AppInfo::appID(),
  'secret' => AppInfo::appSecret(),
));

$user_id = $facebook->getUser();
if ($user_id) {
  try {
    // Fetch the viewer's basic information
    $basic = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    // If the call fails we check if we still have a user. The user will be
    // cleared if the error is because of an invalid accesstoken
    if (!$facebook->getUser()) {
      header('Location: '. AppInfo::getUrl($_SERVER['REQUEST_URI']));
      exit();
    }
  }

  // This fetches some things that you like . 'limit=*" only returns * values.
  // To see the format of the data you are retrieving, use the "Graph API
  // Explorer" which is at https://developers.facebook.com/tools/explorer/
  $likes = idx($facebook->api('/me/likes?limit=4'), 'data', array());

  // This fetches 4 of your friends.
  $friends = idx($facebook->api('/me/friends?limit=4'), 'data', array());

  // And this returns 16 of your photos.
  $photos = idx($facebook->api('/me/photos?limit=16'), 'data', array());

  // Here is an example of a FQL call that fetches all of your friends that are
  // using this app
  $app_using_friends = $facebook->api(array(
    'method' => 'fql.query',
    'query' => 'SELECT uid, name FROM user WHERE uid IN(SELECT uid2 FROM friend WHERE uid1 = me()) AND is_app_user = 1'
  ));
}

// Fetch the basic info of the app that they are using
$app_info = $facebook->api('/'. AppInfo::appID());

$app_name = idx($app_info, 'name', '');

?>
<!DOCTYPE html>
<html xmlns:fb="http://ogp.me/ns/fb#" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0, user-scalable=yes" />

    <title><?php echo he($app_name); ?></title>
    <link rel="stylesheet" href="stylesheets/screen.css" media="Screen" type="text/css" />
    <link rel="stylesheet" href="stylesheets/mobile.css" media="handheld, only screen and (max-width: 480px), only screen and (max-device-width: 480px)" type="text/css" />

    <!--[if IEMobile]>
    <link rel="stylesheet" href="mobile.css" media="screen" type="text/css"  />
    <![endif]-->

    <!-- These are Open Graph tags.  They add meta data to your  -->
    <!-- site that facebook uses when your content is shared     -->
    <!-- over facebook.  You should fill these tags in with      -->
    <!-- your data.  To learn more about Open Graph, visit       -->
    <!-- 'https://developers.facebook.com/docs/opengraph/'       -->
    <meta property="og:title" content="<?php echo he($app_name); ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?php echo AppInfo::getUrl(); ?>" />
    <meta property="og:image" content="<?php echo AppInfo::getUrl('/logo.png'); ?>" />
    <meta property="og:site_name" content="<?php echo he($app_name); ?>" />
    <meta property="og:description" content="My first app" />
    <meta property="fb:app_id" content="<?php echo AppInfo::appID(); ?>" />

    <script type="text/javascript" src="/javascript/jquery-1.7.1.min.js"></script>

    <script type="text/javascript">
      function logResponse(response) {
        if (console && console.log) {
          console.log('The response was', response);
        }
      }

      $(function(){
        // Set up so we handle click on the buttons
        $('#postToWall').click(function() {
          FB.ui(
            {
              method : 'feed',
              link   : $(this).attr('data-url')
            },
            function (response) {
              // If response is null the user canceled the dialog
              if (response != null) {
                logResponse(response);
              }
            }
          );
        });

        $('#sendToFriends').click(function() {
          FB.ui(
            {
              method : 'send',
              link   : $(this).attr('data-url')
            },
            function (response) {
              // If response is null the user canceled the dialog
              if (response != null) {
                logResponse(response);
              }
            }
          );
        });

        $('#sendRequest').click(function() {
          FB.ui(
            {
              method  : 'apprequests',
              message : $(this).attr('data-message')
            },
            function (response) {
              // If response is null the user canceled the dialog
              if (response != null) {
                logResponse(response);
              }
            }
          );
        });
      });
    </script>

    <!--[if IE]>
      <script type="text/javascript">
        var tags = ['header', 'section'];
        while(tags.length)
          document.createElement(tags.pop());
      </script>
    <![endif]-->
    <link rel="stylesheet" href="master.css" type="text/css" media="screen" />
    <script language="javascript">
		var lists = new Array();
		lists['Audi'] = new Array();
		lists['Audi'][0] = new Array('A1','A2','A3','A4','A5','A6','A7','A8','Q3','Q5','Q7','R8','RS4','RS5','RS6','S3','S4','S5','S6','S8','TT');
		lists['Audi'][1] = new Array('A1','A2','A3','A4','A5','A6','A7','A8','Q3','Q5','Q7','R8','RS4','RS5','RS6','S3','S4','S5','S6','S8','TT');
		
		lists['Mercedes'] = new Array();
		lists['Mercedes'][0] = new Array('A-Class','B-Class','C-Class','CE-Class','CL-Class','CLC-Class','CLK-Class','M-Class','R-Class','S-Class','SL-Class','SLK-Class','SLR McLaren','V-Class');
		lists['Mercedes'][1] = new Array('A-Class','B-Class','C-Class','CE-Class','CL-Class','CLC-Class','CLK-Class','M-Class','R-Class','S-Class','SL-Class','SLK-Class','SLR McLaren','V-Class');
		
		lists['Volkswagen'] = new Array();
		lists['Volkswagen'][0] = new Array('Amarok','Beetle','Bora','Caddy','Caravelle','Corrado','Eos','Fox','Golf','Golf Plus','Jetta','Karmann Ghia','Lupo','Passat','Phaeton','Polo','Scirocco','Sharan','Tiguan','Touareg','Touran');
		lists['Volkswagen'][1] = new Array('Amarok','Beetle','Bora','Caddy','Caravelle','Corrado','Eos','Fox','Golf','Golf Plus','Jetta','Karmann Ghia','Lupo','Passat','Phaeton','Polo','Scirocco','Sharan','Tiguan','Touareg','Touran');
	</script>
    
    <script language="javascript">
		function emptyList(box) {
			while ( box.options.length ) box.options[0] = null;
		}

		function fillList( box, arr ) {
		
			for ( i = 0; i < arr[0].length; i++ ) {
		
				option = new Option( arr[0][i], arr[1][i] );
		
				box.options[box.length] = option;
			}

		box.selectedIndex=0;
		}
		
		function changeList(box) {
			list = lists[box.options[box.selectedIndex].value];
			emptyList(box.form.model);
			fillList( box.form.model, list );
		}
	</script>
    
  </head>
  <body onload="changeList(document.forms['book'].make)">
    <div id="fb-root"></div>
    <script type="text/javascript">
      window.fbAsyncInit = function() {
        FB.init({
          appId      : '<?php echo AppInfo::appID(); ?>', // App ID
          channelUrl : '//<?php echo $_SERVER["HTTP_HOST"]; ?>/channel.html', // Channel File
          status     : true, // check login status
          cookie     : true, // enable cookies to allow the server to access the session
          xfbml      : true // parse XFBML
        });

        // Listen to the auth.login which will be called when the user logs in
        // using the Login button
        FB.Event.subscribe('auth.login', function(response) {
          // We want to reload the page now so PHP can read the cookie that the
          // Javascript SDK sat. But we don't want to use
          // window.location.reload() because if this is in a canvas there was a
          // post made to this page and a reload will trigger a message to the
          // user asking if they want to send data again.
          window.location = window.location;
        });

        FB.Canvas.setAutoGrow();
      };

      // Load the SDK Asynchronously
      (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/all.js";
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));
    </script>

    <header class="clearfix">
      <?php if (isset($basic)) { ?>
      <p id="picture" style="background-image: url(https://graph.facebook.com/<?php echo he($user_id); ?>/picture?type=normal)"></p>

      <div>
        <h1>Welcome, <strong><?php echo he(idx($basic, 'name')); ?></strong></h1>
        <p class="tagline">
          This is your app
          <a href="<?php echo he(idx($app_info, 'link'));?>" target="_top"><?php echo he($app_name); ?></a>
        </p>
      </div>
      <?php } else { ?>
      <div>
        <h1>Welcome</h1>
        <div class="fb-login-button" data-scope="user_likes,user_photos"></div>
      </div>
      <?php } ?>
    </header>
    <section>
    <div id="content">
		<div id="serviceContent">
			<section>
				<article class="main">
					<header>
						<h3>You can book a service online by filling out the form below:</h3>
					</header>
					<div>
						<form action="booking_success.php" method="post">
						<p>
							<label for="service">Select Service Type:</label>
							<select name="service">
							<option value="25 - Health Check">&euro;25 - Health Check</option>
							<option value="70 - Early Booking">&euro;70 - Early Booking</option>
							<option value="100 - NCT Prep A">&euro;100 - NCT Prep A</option>
							<option value="150 - NCT Prep B">&euro;150 - NCT Prep B</option>
							<option value="250 - Full Service">&euro;250 - Full Service</option>
							</select>
						<p>
							<label for="fname">First Name:</label>
							<input type="text" name="fname" maxlength="60">
						</p>
						<p>
							<label for="sname">Surname:</label>
							<input type="text" name="sname" maxlength="60">
						</p>
						<p>
							<label for="add1">Address:</label>
							<input type="text" name="add1" maxlength="60">
						</p>
						<p>
							<label for="add2">&nbsp;</label>
							<input type="text" name="add2" maxlength="60">
						</p>
						<p>
							<label for="add3">&nbsp;</label>
							<input type="text" name="add3" maxlength="60">
						</p>
						<p>
							<label for="email">Email:</label>
							<input type="text" name="email" maxlength="60">
						</p>
						<p>
							<label for="phone">Phone:</label>
							<input type="text" name="phone" maxlength="60">
						</p>
						<p>
							<label for="make">Make:</label>
							<select name="make" onchange="changeList(this)">
							<option value="Audi">Audi</option>
							<option value="Mercedes">Mercedes</option>
							<option value="Volkswagen">Volkswagen</option>
							</select>
						</p>
						<p>
							<label for="model">Model:</label>
							<select name="model"></select>
						</p>
						<p>
							<label for="milage">Milage:</label>
							<input type="text" name="milage" maxlength="60">
						</p>
						<p>
							<label for="km_m">&nbsp;</label>
							<select name="km_m">
							<option value="Miles">Miles</option>
							<option value="Kilometres">Kilometres</option>
							</select>
						</p>
						<p>
							<label for="reg">Reg. Number:</label>
							<input type="text" name="reg" maxlength="60">
						</p>
						<p>
							<label for="comments">Comments:</label>
							<input type="textarea" name="comments" maxlength="60">
						</p>
						<p>
							<label for="previous">Have we serviced your car before?</label>
							<input type="hidden" name="previous" value="No" />
							<input type="checkbox" name="previous" value="Yes">
						<p><input type="submit" name="submit" value="Submit Booking Request"></p>
						</form>
				</article>
    </section>
  </body>
</html>
