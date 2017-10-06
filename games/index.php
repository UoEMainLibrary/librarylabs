<?php
        session_start();
?>

<?php
include 'config/vars.php';

// connect to db

$error = '';
$link = mysqli_connect($dbserver, $username, $password, $database);
//@mysqli_select_db($database) ;#or die( "Unable to select database".$database);
// foreach ($_SERVER as $key => $value) {
//     echo ($key . "+". $value);
// }
// $uun = $_SESSION['uun'] = $_SERVER["REMOTE_USER"];
// var_dump($_SESSION['uun']);
// var_dump($uun);


//echo "<br />UUN IS: " . $_SERVER['REMOTE_USER'];

$sql = "SELECT * FROM orders.USER WHERE uun ='".$uun."';";

$result = mysqli_query($sql,$link);// ;#or die( "A MySQL error has occurred.<br />Your Query: " . $sql . "<br /> Error: (" . mysqli_errno() . ") " . mysqli_error());
$row = mysqli_fetch_array($result);
//$row="";
if($row == null && FALSE) //we want to stop this bit locally because MAMP can sod off
{
    echo 'I am inserting through LDAP but I have no UUN';

    $ds = ldap_connect("ldaps://authorise.is.ed.ac.uk");
    if($ds) {

        //var_dump($uun);
        //echo "<br />";

        $ldaprdn  = 'dc=authorise,dc=ed,dc=ac,dc=uk';

        ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

        $r = ldap_bind($ds);      // this is an "anonymous" bind, typically

        $sr = ldap_search($ds, "ou=people,ou=central,dc=authorise,dc=ed,dc=ac,dc=uk", "uid=".$uun, array('*'));

        $info = ldap_get_entries($ds, $sr); // get entries in form of array

        //var_dump($info);

        if($info["count"] > 0)
        {
            for ($i=0; $i<$info["count"]; $i++)
            {
                $first_name =  $_SESSION['first_name'] = $info[$i]["givenname"][0];
                $surname = $_SESSION['surname'] = $info[$i]["sn"][0];
                $email = $_SESSION['email'] = $info[$i]["mail"][0];
                $affiliation = $info[$i]["edupersonaffiliation"][0];
            }
        }
        else
        {
            $first_name = $_SESSION['first_name'] = $uun;
            $email = $_SESSION['email'] = $uun;
            $affiliation = "visitorstudent";
        }

        //echo "LDAP INFO: " . $first_name . " " . $surname . " with email address " . $email . " and affiliation " . $affiliation . ". <br />";

        ldap_close($ds);

        switch($affiliation)
        {
            case "staff":
            case "visitorstaff":
                $type = "S";
                break;
            case "studentug":
            case "studentpg":
            case "alumni":
            case "visitorstudent":
                $type = "U";
                break;
            default:
                $type = "U";
                break;
        }

        //echo "Affiliation " . $affiliation . " gives type " . $type . "<br />";

        $sql = "INSERT INTO orders.USER (email, uun, surname, first_name, status, type) VALUES ('".$email."', '".$uun."', '".$surname."', '".$first_name."', 'P', '".$type."');";
        $result = mysqli_query($sql,$link); //;#or die( "A MySQL error has occurred.<br />Your Query: " . $sql . "<br /> Error: (" . mysqli_errno() . ") " . mysqli_error());

        $status = $_SESSION['status'] = 'P';
        $_SESSION['type'] = $type;

        // reset counts so people can play again
        unset($_SESSION["images"]);
        unset($_SESSION["vimages"]);
        unset($_SESSION["points"]);

    }

    else {

        echo "FAILED to connect";

    }



} // end if the user doesn't exist
else // the user does exist
{

    if($row["first_name"] != "")
    {
        $first_name = $_SESSION['first_name'] = $row["first_name"];
        $email = $_SESSION['email'] = $row["email"];
    }
    else // it's an ease friend so set first name and email to uun (email address)
    {
        $first_name = $_SESSION['first_name'] = $row["uun"];
        $email = $_SESSION['email'] = $row["uun"];
    }

    $surname = $_SESSION['surname'] = $row["surname"];
    $status = $_SESSION['status'] = $row["status"];
    $type = $_SESSION['type'] = $row["type"];

    // reset counts so people can play again
    unset($_SESSION["images"]);
    unset($_SESSION["vimages"]);
    unset($_SESSION["points"]);

}

// close mysqli connection
mysqli_close($link);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Homepage for Library Labs">
    <meta name="author" content="University of Edinburgh, Library Digital Development Team">
    <link rel="shortcut icon" href="./../favicon.ico">
    <!-- Google Sign-In -->
    <meta name="google-signin-scope" content="profile email">
    <script src="https://apis.google.com/js/platform.js?onload=onLoadCallback" async defer></script>
    <meta name="google-signin-client_id" content="682814395284-jif8e1b4hijg22lnckeslth5gc4bil3q.apps.googleusercontent.com">
    <!-- Bootstrap -->
    <link href="./../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./../css/style.css">
    <script type="text/javascript" src="//connect.facebook.net/en_US/sdk.js"></script>
    <title>Library Labs Metadata Games</title>
    <style type="text/css">
        body {
            padding-top: 50px; 
        }
        div.contentBlock {
            /*background-image: url("../login/login_background.jpg");*/
            overflow: auto;
/*            min-height: 900px;*/
        }
        #gamesBlock {
            width: 400px;
            margin: auto;
            overflow: auto;
            padding-top: 20px;
            background:rgba(255,255,255,0.5);
        }
        @media (min-width: 1200px) {
            #gamesBlock {
                width: 100%;
                height: 400px;
                margin: auto;
/*                margin-top: 230px;*/

                background:rgba(255,255,255,0.2);
            }
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
        }
        .games {
            display: block;
            width: 300px;
            margin: auto;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        }
    </style>
</head>
<body>

<!-- Facebook initialization -->
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

<?php
foreach ($_GET as $key => $value) {
    echo ($key . "+". $value);
}
if(isset($_GET['email'])) {
    // $_SESSION['facebook'] = 'connected';
    // $_SESSION['name'] = $_GET['name'];
    // $_SESSION['email'] = $_GET['email'];
    $name = $_GET['name'];
    $email = $_GET['email'];
    $uunD = $email;
    $_SESSION['uun'] = $uunD;
    var_dump($uunD);
    // echo($name . '<br>' . $email);
    $keywords = preg_split("/[\s,]+/", $name);
    $_SESSION['first_name'] = $keywords[0];

    $servername = "localhost";
    $username = "root";
    $password = "Lepwom8";
    $dbname = "orders";


    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    $sql = "INSERT IGNORE INTO USER (uun, first_name, surname, email)
    VALUES ('$uunD','$keywords[0]', '$keywords[1]', '$email')";

    if ($conn->query($sql) === TRUE) {
        // echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
<?php include_once("./../analyticstracking.php") ?>
<?php
    //$token = $_REQUEST['idtoken'];
    //echo $token; 
    //var_dump($_SESSION['idtoken']);
    //var_dump($_POST);
    //print_r($token);
    // echo "cool";
?>
<div class="container">
    <header>
        <div class="container-fluid">
            <div class="row header-row">
                <div class="header-image">
                    <img src="./../css/images/librarylabsheader.png" class="img-responsive">
                </div>
            </div>
        </div>
        <!-- Static navbar -->
        <nav class="navbar navbar-default navbar-fixed-top navbar-inverse">
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
                        <li><a href="./../">Home</a></li>
                        <li><a href="./../about">About</a></li>
                        <li class="dropdown active">
                            <a href="./" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Metadata Games <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="./gameMenu.php?theme=art">Tag It! Find It!</a></li>
                                <li><a href="./gameMenu.php?theme=classic">Class metadata index.phpmysqli_select_db</a></li>
                                <li><a href="./gameMenu.php?theme=photo">Research Tagging</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                    <!-- <div class="fb-login-button" data-max-rows="1" data-size="large" data-button-type="continue_with" data-show-faces="false" data-auto-logout-link="true" data-use-continue-as="false" onclick="console.log('test logout')"></div> -->
                        <li><a href="./../contact">Contact</span></a></li>

                        <li id="logoutlink"><a href="https://www.ease.ed.ac.uk/logout/logout.cgi">Logout</a></li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div><!--/.container-fluid -->
        </nav>

    </header>

    <?php //echo "We have uun: " . $_SESSION['uun'] . " with first name: " . $_SESSION['first_name'] . " and surname: " . $_SESSION['surname'] . " and email address: " . $_SESSION['email'] . "<br /> They have status: " . $_SESSION['status'] . " and type: " . $_SESSION['type']; ?>
<div class="contentBlock">
    <div class="row" id="gamesBlock">
        <div class="col-lg-4">
            <div class="link-box box-left index.phpmysqli_select_db-box">
                <a href="./gameMenu.php?theme=art">
                    <img class="games" title="Tag It! Find It!" src="./../css/images/TagItTile.png">
                </a>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="link-box box-middle index.phpmysqli_select_db-box">
                <a href="./gameMenu.php?theme=classic">
                    <img class="games" title="Tag Images Online" src="./../css/images/TagImagesTile.png">
                </a>
            </div>
        </div>
       <!-- <div class="col-lg-3">
            <div class="link-box box-right index.phpmysqli_select_db-box">
                <a href="./gameMenu.php?theme=photo">
                    <img title="Research Zone" src="./../css/images/ResearchTile.png">
                </a>
            </div>
        </div>-->
        <div class="col-lg-4">
            <div class="link-box box-right index.phpmysqli_select_db-box">
                <a href="./gameMenu.php?theme=roslin">
                    <img class="games" title="Roslin Game" src="./../css/images/DollyTile.jpg">
                </a>
            </div>
        </div>
    </div>
</div>

    <footer id="footer">
        <div class="container">
            <div class="uoe-logo">
                <a target="_blank" href="http://www.ed.ac.uk/">
                    <img title="The University of Edinburgh" src="./../css/images/UoELogo.png">
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
                    <img title="Library and University Collections Blog" src="./../css/images/L&UCLogo.png">
                </a>
            </div>
        </div>
    </footer>

</div>

<?php

    // close mysqli connection
    mysqli_close($link);

?>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="./../assets/bootstrap/js/bootstrap.min.js"></script>

</body>
</html>
