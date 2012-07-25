<?php

require 'sdk/src/facebook.php';

$facebook = new Facebook(array(
  'appId'  => getenv('FACEBOOK_APP_ID'),
  'secret' => getenv('FACEBOOK_SECRET'),
));

// See if there is a user from a cookie
$user = $facebook->getUser();

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
    $logoutUrl = $facebook->getLogoutUrl();
  } catch (FacebookApiException $e) {
    $user = null;
  }
} else {
    $loginUrl = $facebook->getLoginUrl();
}

?>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Facebook PHP SDK</title>
    </head>
    <body>
        <fb:login-button size="small" onlogin="after_login_button()" scope="email, user_about_me, user_birthday, user_status, publish_stream, user_photos, read_stream, friends_likes">Login with facebook</fb:login-button>
        <div id="fb-root"></div>
        <script>
            window.fbAsyncInit = function() {
            FB.init({
              appId: '<?php echo $facebook->getAppID() ?>',
              cookie: true,
              xfbml: true,
              oauth: true
            });

            /* This is used with facebook button */
            FB.Event.subscribe('auth.login', function(response) {
              if (response.authResponse) {
                 // Specify the login page (the page in which your fb login button is situated)
                 window.location = 'main.php';
              }
            });
            FB.Event.subscribe('auth.logout', function(response) {
                window.location = 'logout.php';
            });
          };
          (function() {
            var e = document.createElement('script'); e.async = true;
            e.src = document.location.protocol +
              '//connect.facebook.net/en_US/all.js';
            document.getElementById('fb-root').appendChild(e);
          }());

          function after_login_button(){
            FB.getLoginStatus(function(response) {
                if (response.status=="connected") {
                    // If user is connected, redirect to this page
                    window.location = 'main.php';
                }
            }, true);
          }
        </script>
    </body>
</html>