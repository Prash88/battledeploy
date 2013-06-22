<?php
require '/fb/src/facebook.php';
    
$facebook = new Facebook(array(
  'appId'  => '596197220411691',
  'secret' => '3c517bc3f209ca28effa0223755bd023',
  'cookie' => true,
));
// Get User ID
$user = $facebook->getUser();

// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}

// Login or logout url will be needed depending on current user state.
if ($user) {
  $fb_logoutUrl = $facebook->getLogoutUrl();
} else {
  $fb_loginUrl = $facebook->getLoginUrl();
}

// This call will always work since we are fetching public data.
$naitik = $facebook->api('/naitik');
?>

<!doctype html>


<html>
    <head>
        <meta charset="UTF-8" />
        <title>jQTouch &beta;</title>
        <link rel="stylesheet" href="includes/jqtouch/themes/css/jqtouch.css" title="jQTouch">
		<link rel="stylesheet" href="includes/css/style-mobile.css">

        <script src="includes/jqtouch/src/lib/zepto.min.js" type="text/javascript" charset="utf-8"></script>
        <script src="includes/jqtouch/src/jqtouch.min.js" type="text/javascript" charset="utf-8"></script>
        <!-- Uncomment the following two lines (and comment out the previous two) to use jQuery instead of Zepto. -->
        <!-- <script src="../../src/lib/jquery-1.7.min.js" type="application/x-javascript" charset="utf-8"></script> -->
        <!-- <script src="../../src/jqtouch-jquery.min.js" type="application/x-javascript" charset="utf-8"></script> -->

        <script src="includes/jqtouch/extensions/jqt.themeswitcher.min.js" type="application/x-javascript" charset="utf-8"></script>

        <script type="text/javascript" charset="utf-8">
            var jQT = new $.jQTouch({
                icon: 'jqtouch.png',
                icon4: 'jqtouch4.png',
                addGlossToIcon: false,
                startupScreen: 'jqt_startup.png',
                statusBar: 'black-translucent',
                themeSelectionSelector: '#jqt #themes ul',
                preloadImages: []
            });

            // Some sample Javascript functions:
            $(function(){

                // Show a swipe event on swipe test
                $('#swipeme').swipe(function(evt, data) {
                    var details = !data ? '': '<strong>' + data.direction + '/' + data.deltaX +':' + data.deltaY + '</strong>!';
                    $(this).html('You swiped ' + details );
                    $(this).parent().after('<li>swiped!</li>')
                });

                $('#tapme').tap(function(){
                    $(this).parent().after('<li>tapped!</li>')
                });

                $('a[target="_blank"]').bind('click', function() {
                    if (confirm('This link opens in a new window.')) {
                        return true;
                    } else {
                        return false;
                    }
                });

                // Page animation callback events
                $('#pageevents').
                    bind('pageAnimationStart', function(e, info){ 
                        $(this).find('.info').append('Started animating ' + info.direction + '&hellip;  And the link ' +
                            'had this custom data: ' + $(this).data('referrer').data('custom') + '<br>');
                    }).
                    bind('pageAnimationEnd', function(e, info){
                        $(this).find('.info').append('Finished animating ' + info.direction + '.<br><br>');

                    });
                
                // Page animations end with AJAX callback event, example 1 (load remote HTML only first time)
                $('#callback').bind('pageAnimationEnd', function(e, info){
                    // Make sure the data hasn't already been loaded (we'll set 'loaded' to true a couple lines further down)
                    if (!$(this).data('loaded')) {
                        // Append a placeholder in case the remote HTML takes its sweet time making it back
                        // Then, overwrite the "Loading" placeholder text with the remote HTML
                        $(this).append($('<div>Loading</div>').load('ajax.html .info', function() {        
                            // Set the 'loaded' var to true so we know not to reload
                            // the HTML next time the #callback div animation ends
                            $(this).parent().data('loaded', true);  
                        }));
                    }
                });
                // Orientation callback event
                $('#jqt').bind('turn', function(e, data){
                    $('#orient').html('Orientation: ' + data.orientation);
                });
                
            });
        </script>
        <style type="text/css" media="screen">
            #jqt.fullscreen #home .info {
                display: none;
            }
            div#jqt #about {
                padding: 100px 10px 40px;
                text-shadow: rgba(0, 0, 0, 0.3) 0px -1px 0;
                color: #999;
                font-size: 13px;
                text-align: center;
                background: #161618;
            }
            div#jqt #about p {
                margin-bottom: 8px;
            }
            div#jqt #about a {
                color: #fff;
                font-weight: bold;
                text-decoration: none;
            }
        </style>
    </head>
    <body>
        <div id="jqt">
            <div id="about" class="selectable">
                    <p><img src="jqtouch.png" /></p>
                    <p><strong>jQTouch</strong><br>Version 1.0 beta<br>
                        <a href="http://www.davidkaneda.com">By David Kaneda</a></p>
                    <p><em>Create powerful mobile apps with<br> just HTML, CSS, and jQuery.</em></p>
                    <p>
                        <a target="_blank" href="http://twitter.com/jqtouch">@jQTouch on Twitter</a>
                    </p>
                    <p><br><br><a href="#" class="grayButton goback">Close</a></p>
            </div>
            <div id="ajax">
                <div class="toolbar">
                    <h1>AJAX</h1>
                    <a class="back" href="#home">Home</a>
                </div>
                <div class="scroll">
                    <ul class="rounded">
                        <li class="arrow"><a href="#ajax_post">POST Form Example</a></li>
                        <li class="arrow"><a href="ajax.html">GET Example</a></li>
                        <li class="arrow"><a href="ajax_long.html">Long GET Example</a></li>
                        <li class="arrow"><a href="#callback">With Callback</a></li>
                    </ul>
                </div>
            </div>
         
            <div id="login" class="current">
                <div class="toolbar">
                    <h1>Battleship</h1>
                    <a class="button slideup" id="infoButton" href="#about">About</a>
                </div>
                <div class="scroll">
                    <ul class="rounded">
                        <li class="arrow"><a href="#register">Register </a> </li>
                    </ul>
                    <h2>External Links</h2>
					<ul class="individual">
						<?php //If the user is not logged into facebook, provide the link to login.
							if(!($user))
							{
									echo '<li><a target="_blank" id="login-facebook" href="'.$fb_loginUrl.'" class="blueButton">Facebook Login</a></li>';
							}
							else
							{
								echo '<li><a target="_blank" id="logout-facebook" href="'.$fb_logoutUrl.'" class="blueButton">Facebook Logout</a></li>';
							}	
						?>
						<li><a id="login-twitter"  href="http://www.twitter.com/" class="whiteButton">Twitter</a></li>
					</ul>
                    <div class="info">

    <h3>PHP Session</h3>
    <pre><?php print_r($_SESSION); ?></pre>

    <?php if ($user): ?>
		<a href="<?php echo $logoutUrl; ?>">Logout</a>
      <h3>You</h3>
      <img src="https://graph.facebook.com/<?php echo $user; ?>/picture">

						  <h3>Your User Object (/me)</h3>
						  <pre><?php print_r($user_profile); ?></pre>
						<?php else: ?>
						  <strong><em>You are not Connected.</em></strong>
						<?php endif ?>

						<h3>Public profile of Naitik</h3>
						<img src="https://graph.facebook.com/naitik/picture">
						<?php echo $naitik['name']; ?>
                    </div>
                </div>
            </div>
            <div id="register">
                <div class="toolbar">
                    <h1>Register</h1>
                    <a class="back" href="#">Login</a>
                </div>
              <form class="scroll">
                    <ul class="edit rounded">
                        <li><input type="text" name="username" placeholder="Username" id="some_name" /></li>     
                        <li><input type="email" name="email" placeholder="Email" id="some_name" /></li>
                        <li><input type="password" name="password" placeholder="password" id="password" /></li>
						<li><input type="password" name="password" placeholder="repeat password" id="repeat_password" /></li>
                    </ul>
						<a id="register_submit" style="margin-top: 10px; margin-bottom: 10px; color:rgba(0,0,0,.9)" href="#" class="submit whiteButton">Register</a>
                </form>
            </div>
            <form id="ajax_post" action="ajax_post.php" method="POST" class="form">
                <div class="toolbar">
                    <h1>Post Demo</h1>
                    <a class="back" href="#">Ajax</a>
                </div>
                <div class="scroll">
                    <ul class="rounded">
                        <li><input type="text" name="zip" value="" placeholder="Zip Code" /></li>
                    </ul>
                    <h2>Favorite color?</h2>
                    <ul class="rounded">
                        <li><input type="radio" name="color" value="green" title="Green" /></li>
                        <li><input type="radio" name="color" value="blue" title="Blue" /></li>
                        <li><input type="radio" name="color" value="red" title="Red" /></li>
                    </ul>
                    <a style="margin-top: 10px; margin-bottom: 10px; color:rgba(0,0,0,.9)" href="#" class="submit whiteButton">Submit</a>
                </div>
            </form>
        </div>
    </body>
</html>