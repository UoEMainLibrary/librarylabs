<?php
if ($_SESSION['theme'] != 'roslin')
{
echo'<hr/>';
}
?>
<script src="https://apis.google.com/js/platform.js?onload=onLoadCallback" async defer></script>
<div id="fb-root"></div>
<script>
    window.fbAsyncInit = function() {
      FB.init({
        appId      : '161051451104614',
        status     : true,
        cookie     : true,
        xfbml      : true,
        version    : 'v2.8'
      });

    FB.Event.subscribe("auth.logout", function() {window.location = 'https://test.librarylabs.ed.ac.uk/login'});
    FB.Event.subscribe("auth.statusChange", function(response) {
        console.log("login_event");
        console.log(response.status);
        console.log(response);
    });

    FB.getLoginStatus(function(response) {
        if (response.status === 'connected') {
            // the user is logged in and has authenticated your
            // app, and response.authResponse supplies
            // the user's ID, a valid access token, a signed
            // request, and the time the access token 
            // and signed request each expire
            document.getElementById('logoutlink').innerHTML = "<a href='#' onclick='fbLogout()'>Logout</a>";
            var uid = response.authResponse.userID;
            var accessToken = response.authResponse.accessToken;
        } else if (response.status === 'not_authorized') {
            // the user is logged in to Facebook, 
            // but has not authenticated your app
        } else {
            // the user isn't logged in to Facebook.
        }
    });
  };


  function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);
    if (response.status === 'connected') {

    } else {
      alert("not connected, not logged into facebook, we don't know");
    }
  }

    function fbLogout() {
        FB.getAccessToken();
        FB.logout(function(response){
            console.log("logout");
            window.location = "https://test.librarylabs.ed.ac.uk/login";
        });
    }


(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.9&appId=161051451104614";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));


</script>
<!-- Google initialization -->
<script>
window.onLoadCallback = function(){
    gapi.load('auth2', function() {

      gapi.auth2.init({

        client_id: '682814395284-jif8e1b4hijg22lnckeslth5gc4bil3q.apps.googleusercontent.com',

      }).then(function(){

        auth2 = gapi.auth2.getAuthInstance();
        console.log(auth2.isSignedIn.get()); //now this always returns correctly
        if(auth2.isSignedIn.get()) {
            document.getElementById('logoutlink').innerHTML = "<a href='#' onclick='signOut();'>Logout</a>";
        }        

      });
    });
}
</script>

<script defer>


  function signOut() {
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
      console.log('User signed out.');
      window.location = "https://test.librarylabs.ed.ac.uk/login";
    });
  }
</script>
<div class="central">
<footer id="footer">
    <div class="container">
        <div class="uoe-logo">
            <a target="_blank" href="http://www.ed.ac.uk/">
                <img title="The University of Edinburgh" src="./../css/images/UoELogoFooter.png">
            </a>
        </div>
        <div class="footer-links">
            <ul class="links-top">
                <li>
                    <a href="http://librarylabs.ed.ac.uk/games" class="footer-link">Game Menu</a></a>
                </li>
                <li>
                    <a href="http://librarylabs.ed.ac.uk" class="footer-link">Library Labs Home</a>
                </li>
                <li id="logoutlink" >
                    <a href="https://www.ease.ed.ac.uk/logout/logout.cgi" class="footer-link">Logout</a>
                </li>
                <li>
                    <a href="http://librarylabs.ed.ac.uk/games/copyright.php" class="footer-link" target="_blank">Licensing &amp; Copyright</a>
                </li>
            </ul>
            <ul class="links-bottom">
                <li>
                    <a href="http://www.ed.ac.uk/about/website/website&#45;terms&#45;conditions" class="footer-link" target="_blank">Terms &amp; conditions</a>
                </li>
                <li>
                    <a href="http://www.ed.ac.uk/about/website/privacy" class="footer-link" target="_blank">Privacy &amp; cookies</a></li>

                <li>
                    <a href="http://www.ed.ac.uk/about/website/accessibility" class="footer-link" target="_blank">Website accessibility</a>
                </li>

                <li>
                    <a href="http://www.ed.ac.uk/about/website/freedom&#45;information" class="footer-link" title="Freedom of Information Publication Scheme" target="_blank">Freedom of Information</a>
                </li>
            </ul>
        </div>
        <div class="luc-logo">
            <a target="_blank" href="http://libraryblogs.is.ed.ac.uk/">
                <?php

                if($_SESSION['theme'] == 'photo')
                {
                ?>
                    <img title="Library and University Collections Blog" src="./../css/images/L&UCLogoFooter.png" border="0">
                <?php
                }
                else
                {
                ?>
                    <img title="Library and University Collections Blog" src="./../css/images/L&UCLogoFooter2.png" border="0">
                <?php
                }
                ?>
            </a>
        </div>
    </div>
</footer>
</div>