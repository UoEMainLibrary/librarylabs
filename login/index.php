<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Homepage for Library Labs">
    <meta name="author" content="University of Edinburgh, Library Digital Development Team">
    <link rel="shortcut icon" href="../favicon.ico">

    <!-- Bootstrap -->
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <title>Welcome to Library Labs</title>

    <!-- Google Sign-In -->
    <meta name="google-signin-scope" content="profile email">
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <meta name="google-signin-client_id" content="682814395284-jif8e1b4hijg22lnckeslth5gc4bil3q.apps.googleusercontent.com">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <style type="text/css">
/*        div.title {
            margin-left: 10px;
            color: white;
        }
        div.title h1 {
            margin-left: 10px;
            margin-top: 20px;
            font-size: 5em;
            color: white;
        }*/
        #titleDiv {
            width: 80%;
            height: auto;
            margin: auto;
        }
        div.login {
            margin: auto;
        }
        div.login {
            overflow: auto;
            background-image: url("login_background.jpg");
            margin-top: 50px;
        }
        div.loginBlock {
            overflow: auto;
            background:rgba(255,255,255,0.2);
            width: 500px;
            margin: auto;
            height: 630px;
            margin-top: 100px;
            margin-bottom: 150px;
/*            border-radius: 25px;*/
        }
        div.Facebook {
            width:350px;
            margin: auto;
            margin-bottom: 40px;
            margin-top: 40px
        }
        div.Google {
            width:350px;
            margin: auto;
            margin-bottom: 40px;
        }
        div.Ease {
            width:350px;
            margin: auto;
            margin-bottom: 60px;      
        }
        div.loginIcon {
            width: 200px;
            margin: auto;
            margin-top: 60px;
        }

        @media (max-width: 640px) {
            #footer img {
                display: none;
            }
            div.footer-links {
                float: none;
                width: 98%;
                margin: auto;
            }
            div.loginBlock {
                width: 100%;
            }

        }

        @media (max-width: 320px) {
            div.Facebook {
                width:300px;
                margin: auto;
                margin-bottom: 40px;
                margin-top: 40px
            }
            div.Google {
                width:300px;
                margin: auto;
                margin-bottom: 40px;
            }
            div.Ease {
                width:300px;
                margin: auto;
                margin-bottom: 60px;      
            }
            div.loginIcon {
                width: 200px;
                margin: auto;
                margin-top: 60px;
            }          
        }
    </style>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script type="text/javascript" src="//connect.facebook.net/en_US/sdk.js"></script>

</head>
<body>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '161051451104614',
      status     : true,
      cookie     : true,
      xfbml      : true,
      version    : 'v2.8'
    });
    FB.AppEvents.logPageView(); 
    
    FB.getLoginStatus(function(response) {
        console.log("test login status");
        console.log(response);
        statusChangeCallback(response);
    }, { scope: 'name, email' });

  };

    function testAPI() {
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me?fields=name, email', function(response) {
        window.location = "https://test.librarylabs.ed.ac.uk/games/index.php?name=" + response.name + "&email=" + response.email;
        // console.log("emailTest" + response);
//     for(var propName in response) {

//     console.log(propName);
// }
// console.log(response.id);
    });
  }

  function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);
    if (response.status === 'connected') {
        //alert("connected, not logged into facebook, we don't know");
        console.log(response.authResponse);

        testAPI();

    } else if (response.status === 'not_authorized') {
        //alert("not_authorized, not logged into facebook, we don't know");
      FB.login(function(response) {
        statusChangeCallback2(response);
      }, {scope: 'public_profile,email'});

    } else {
      //alert("not connected, not logged into facebook, we don't know");
    }
  }
 




function checkLoginState() {
    console.log("test login state");
    FB.getLoginStatus(function(response) {
        statusChangeCallback(response);

    });
} 

      
  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));


</script>

<!-- Facebook Sign-In -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.9";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<!---->

<!-- Google Sign-In -->
<script>
      function onSignIn(googleUser) {
        // Useful data for your client-side scripts:
        var profile = googleUser.getBasicProfile();
        console.log("ID: " + profile.getId()); // Don't send this directly to your server!
        console.log('Full Name: ' + profile.getName());
        console.log('Given Name: ' + profile.getGivenName());
        console.log('Family Name: ' + profile.getFamilyName());
        console.log("Image URL: " + profile.getImageUrl());
        console.log("Email: " + profile.getEmail());

        // The ID token you need to pass to your backend:
        // var id_token = googleUser.getAuthResponse().id_token;
        // console.log("ID Token: " + id_token);
        // var xhr = new XMLHttpRequest();
        // xhr.open('POST', 'testAJAX.php');
        // xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        // xhr.onload = function() {
        //     console.log('Signed in as: ' + xhr.responseText);
        // };
        // var mess = "idtoken="+id_token;
        // xhr.send(mess);
      };
</script>
<!---->

<!--     <div class="g-signin2" data-onsuccess="onSignIn" data-theme="dark"></div>
    <script>
      function onSignIn(googleUser) {
        // Useful data for your client-side scripts:
        var profile = googleUser.getBasicProfile();
        console.log("ID: " + profile.getId()); // Don't send this directly to your server!
        console.log('Full Name: ' + profile.getName());
        console.log('Given Name: ' + profile.getGivenName());
        console.log('Family Name: ' + profile.getFamilyName());
        console.log("Image URL: " + profile.getImageUrl());
        console.log("Email: " + profile.getEmail());

        // The ID token you need to pass to your backend:
        var id_token = googleUser.getAuthResponse().id_token;
        console.log("ID Token: " + id_token);
        var auth3 = gapi.auth2.getAuthInstance();
        if(auth3.isSignedIn.get()) {
            alert("cool");
        }
      };
    </script>
    <a href="#" onclick="signOut();">Sign out</a> -->
<!-- <script>
  function signOut() {
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
      console.log('User signed out.');
    });
  }
</script> -->

<!-- <script type="text/javascript" src="./fbsdk/fb-login.js" ></script> -->
<?php include_once("./analyticstracking.php") ?>
<div class="container">
    <header>
        <div class="container-fluid">
            <div class="row header-row">

<!--                 <div class="header-image">
                    <img src="../css/images/librarylabsheader.png" class="img-responsive">
                </div> -->
            </div>
        </div>
        <!-- Static navbar -->
        <nav class="navbar navbar-default navbar-fixed-top navbar-inverse" style="display: block; clear: both;">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="../">Home</a></li>
                        <li><a href="../about">About</a></li>
                        <li class="dropdown">
                            <a href="../games" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Metadata Games <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="../games/gameMenu.php?theme=art">Tag It! Find It!</a></li>
                                <li><a href="../games/gameMenu.php?theme=classic">Class metadata games</a></li>
                                <li><a href="../games/gameMenu.php?theme=photo">Research Tagging</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="../contact">Contact</span></a></li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div><!--/.container-fluid -->
        </nav>

    </header>

<!--     <div class="fb-login-button" onlogin = "window.location = 'https://test.librarylabs.ed.ac.uk/games/';"></div> -->
<!--     <div class="fb-login-button" onlogin = "window.location = 'https://test.librarylabs.ed.ac.uk/games/';"></div> -->
<!--     <button class="loginBtn loginBtn--facebook">
        Login with Facebook
    </button> -->
<div class="login">

<!--     <div class="title">
        <h1>Metadata Games</h1>
    </div> -->
                <div id="titleDiv">
                    <img id="title" src="../title_new.svg">
                </div>
    <div class="loginBlock">
    <div class="loginIcon">
        <img src="login_icon_white.svg" style="width: 150px; display: block; margin: auto;">
        <div style="text-align: center; color: white; margin-top: 10px;">Choose a way to log in</div>
    </div>

    <!-- Facebook Sign-In Button -->
    <div class="Facebook">
        <?php //if($_SESSION['facebook'] == 'connected'){echo "<script>window.location = 'https://test.librarylabs.ed.ac.uk/games/index.php';</script>";} ?>
        <div class="fb-login-button" data-max-rows="3" data-size="large" data-width="350px" data-button-type="login_with" data-show-faces="false" scope="public_profile,email" onlogin="checkLoginState();" data-auto-logout-link="true" data-use-continue-as="true"></div>
        <div id="status"></div>
    </div>

    <!-- Google Sign-In Button -->
    <div class="Google">
    <!-- <div class="g-signin2" data-onsuccess="onSignIn" data-theme="dark"></div> -->
        <div id="my-signin2"></div>
            <script>
                function onSuccess(googleUser) {
                    var profile = googleUser.getBasicProfile();
                    console.log('Logged in as: ' + profile.getName());
                    window.location = 'https://test.librarylabs.ed.ac.uk/games/index.php?' + 'name=' + profile.getName() + '&email=' + profile.getEmail();
                }
                function onFailure(error) {
                    console.log(error);
                }
                function renderButton() {
                    gapi.signin2.render('my-signin2', {
                    'scope': 'profile email',
                    'width': 350,
                    'height': 40,
                    'longtitle': true,
                    'theme': 'light',
                    'onsuccess': onSuccess,
                    'onfailure': onFailure
                    });
                }
            </script>

            <script src="https://apis.google.com/js/platform.js?onload=renderButton" async defer></script>
            <!-- backend connection -->
            <script type="text/javascript">

            </script>
            <!-- <a href="#" onclick="signOut();">Sign out</a> -->
            <script>
                function signOut() {
                    var auth2 = gapi.auth2.getAuthInstance();
                    auth2.signOut().then(function () {
                        console.log('User signed out.');
                    });
                }
            </script>
       <!-- ZT6iOMrGc9dGgMxiIWNagICV -->
    </div>

    <div class="Ease">
        <a class="btn btn-info" href="../games" role="button" style="width: 350px; height: 40px;border: none; font-size: 18px;">Log in with EASE</a>

    </div>
    </div>
</div>

<!--     <div class="row"> -->
<!--         <div class="col-lg-4">
            <div class="link-box box-left login-box">
                <a href="../games">
                    <img title="Login with EASE" src="../css/images/EaseLoginTile.png">
                </a>
            </div>
        </div> -->
<!--         <div class="col-lg-4">
            <div class="link-box box-middle login-box">
                <a href="https://www.ease.ed.ac.uk/register/" target="_blank">
                    <img title="Register with EASE (Edinburgh)" src="../css/images/EaseRegisterTile.png">
                </a>
            </div>
        </div> -->
<!--         <div class="col-lg-4">
            <div class="link-box box-right login-box">
                <a href="https://www.ease.ed.ac.uk/friend/" target="_blank">
                    <img title="Register with EASE (Non-Edinburgh)" src="../css/images/EaseFriendTile.png">
                </a>
            </div>
        </div> -->
<!--     </div> -->

    <footer id="footer">
        <div class="container">
            <div class="uoe-logo">
                <a target="_blank" href="http://www.ed.ac.uk/">
                    <img title="The University of Edinburgh" src="../css/images/UoELogo.png">
                </a>
            </div>
            <div class="footer-links">
                <ul>
                    <li>
                        <a href="http://www.ed.ac.uk.ezproxy.is.ed.ac.uk/about/website/website&#45;terms&#45;conditions">Terms &amp; conditions </a>
                    </li>

                    <li>
                        <a href="http://www.ed.ac.uk.ezproxy.is.ed.ac.uk/about/website/privacy">Privacy &amp; cookies </a></li>

                    <li>
                        <a href="http://www.ed.ac.uk.ezproxy.is.ed.ac.uk/about/website/accessibility">Website accessibility </a>
                    </li>

                    <li>
                        <a href="http://www.ed.ac.uk.ezproxy.is.ed.ac.uk/about/website/freedom&#45;information">Freedom of Information Publication Scheme </a>
                    </li>
                </ul>
            </div>
            <div class="luc-logo">
                <a target="_blank" href="http://libraryblogs.is.ed.ac.uk/">
                    <img title="Library and University Collections Blog" src="../css/images/L&UCLogo.png">
                </a>
            </div>
        </div>
    </footer>

</div>


<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="../assets/bootstrap/js/bootstrap.min.js"></script>

</body>
</html>
