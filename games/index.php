<?php
        session_start();
        session_unset();
        session_destroy();
        session_write_close();
        setcookie(session_name(),'',0,'/');
        session_regenerate_id(true);

?>

<?php
include 'config/vars.php';

$link = mysql_connect($dbserver, $username, $password);
@mysql_select_db($database) or die( "Unable to select database".$database);

$uun = $_SERVER["REMOTE_USER"];

$sql = "SELECT * FROM orders.USER WHERE uun ='".$uun."';";

$result = mysql_query($sql,$link) or die( "A MySQL error has occurred.<br />Your Query: " . $sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
$row = mysql_fetch_array($result);

if($row == null)
{

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

        //echo "LDAP INFO: " . $first_name . " " . $surname . " with email address " . $email . " and affiliation " . $affiliation . ". <br />";

        ldap_close($ds);

        switch($affiliation)
        {
            case "staff":
            case "visitorstaff":
                $type = "U";
                break;
            case "studentug":
            case "studentpg":
            case "alumni":
            case "visitorstudent":
                $type = "S";
                break;
            default:
                $type = "S";
                break;
        }

        //echo "Affiliation " . $affiliation . " gives type " . $type . "<br />";

        $sql = "INSERT INTO orders.USER (email, uun, surname, first_name, status, type) VALUES ('".$email."', '".$uun."', '".$surname."', '".$first_name."', 'P', '".$type."');";
        $result = mysql_query($sql) or die( "A MySQL error has occurred.<br />Your Query: " . $sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

        $status = $_SESSION['status'] = 'P';
        $_SESSION['type'] = $type;

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

}

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

    <!-- Bootstrap -->
    <link href="./../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./../css/style.css">
    <title>Library Labs Metadata Games</title>
</head>
<body>

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
        <nav class="navbar navbar-default">
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
                                <li><a href="./gameMenu.php?theme=art">Dive into Art</a></li>
                                <li><a href="./gameMenu.php">Class metadata games</a></li>
                                <li><a href="./gameMenu.php?theme=photo">Research Tagging</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="./">Contact</span></a></li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div><!--/.container-fluid -->
        </nav>

    </header>


    <div class="row">
        <div class="col-lg-4">
            <div class="link-box box-left">
                <a href="./gameMenu.php?theme=art">
                    <img title="Dive into Art" src="./../css/images/DiveIntoTile.png">
                </a>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="link-box box-middle">
                <a href="./gameMenu.php">
                    <img title="Tag Images Online" src="./../css/images/TagImagesTile.png">
                </a>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="link-box box-right">
                <a href="./gameMenu.php?theme=photo">
                    <img title="Research Zone" src="./../css/images/ResearchTile.png">
                </a>
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

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="./../assets/bootstrap/js/bootstrap.min.js"></script>

</body>
</html>