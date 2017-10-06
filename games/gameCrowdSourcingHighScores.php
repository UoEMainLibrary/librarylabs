<?php

include 'config/vars.php';
session_start();
$page = $_SERVER['PHP_SELF'];
$sec = "5";
$uun = $_SESSION['uun'];

if(!isset($_SESSION['theme']) || isset($_REQUEST['theme']))
{
    $_SESSION['theme'] = $_REQUEST['theme'];

    if ($_SESSION['theme'] == 'art') {
        $_SESSION['stylesheet'] = '<link rel="stylesheet" type ="text/css" href="css/art.css">';
        $_SESSION['banner'] = "./images/artbanner.jpg";
        $_SESSION['game'] = 'A';
    }
    else if ($_SESSION['theme'] =='roslin')
    {
        $_SESSION['stylesheet'] = '<link rel="stylesheet" type ="text/css" href="css/roslin.css">';
        $_SESSION['banner'] = "./images/rosbanner.jpg";
        $_SESSION['game'] = 'R';
    }
    else // classic and default
    {
        $_SESSION['stylesheet'] = '<link rel="stylesheet" type ="text/css" href="css/crowd.css">';
        $_SESSION['banner'] = "./images/crowdbanner.gif";
        $_SESSION['game'] = 'D';
    }
}

// Connect To Database
$error = '';
$link = mysqli_connect($dbserver, $username, $password, $database);
@mysqli_select_db($database) ;

if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
?>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta name="viewport" content="user-scalable=no" />
    <title>Metadata Games</title>
    <?php echo $_SESSION['stylesheet']; ?>
    <meta name="author" content="Library Digital Development" />
    <meta http-equiv="refresh" content="<?php echo $sec?>;URL='<?php echo $page?>'">
    <meta name="description" content="Edinburgh University Library Crowd Sourcing"/>
    <meta name="distribution" content="global"/>
    <meta name="resource-type" content="document"/>
    <meta http-equiv="Content-Type" content="text/html; charset=us-ascii"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <link href='http://fonts.googleapis.com/css?family="Comic Sans MS"' rel='stylesheet' type='text/css'>
    <script src="../js/jquery-3.2.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <style>
        * {
            color: white;
        }
        img {
            margin: 0;
            margin: auto;
            float: none;
            display: block;
        }
        #openseadragon1 {
            margin: 0 auto;
            margin-left: 8%;
            margin-right: 8%;
            width: auto;
            height: 1000px;
            display: block;
        }
        #toolbarDiv {
            z-index: 50;
        }

        .toolbarItem {
            width: 51px;
            height: 50px;
        }

        #zoom-in {
            background-image: url(./images/buttons/zoomin_rest.png);
        }

        #zoom-out {
            background-image: url(./images/buttons/zoomout_rest.png);
        }

        #home {
            background-image: url(./images/buttons/home_rest.png);
        }

        #full-page {
            background-image: url(./images/buttons/fullpage_rest.png);
        }

        #next {
            display: inline-block;
            position: relative;
            background-image: url(./images/buttons/next_rest.png);
        }

        #prev {
            display: inline-block;
            position: relative;
            background-image: url(./images/buttons/previous_rest.png);
        }

        .displayregion {
            border: 2px solid #630d0d;
        }
        #toolbarDiv {
            height: 1px;
        }
        h4 {
            margin-top: 5px;
        }
        div.progress {
            width: 95%;
            margin: auto;
        }
        div.information {
            color: white;
            margin-bottom: 10px;
        }
        div.detaileddescription {
            margin: 6px 10%;
        }
        div.description {
            color: #3e8f3e;
        }
        div.progress {
            margin-bottom: 20px;
        }
        /* iPhone 6 in portrait  */
        @media only screen
        and (min-device-width : 375px)
        and (max-device-width : 667px)
        and (orientation : portrait) {
            * {
                background-color: #333333;
            }
            div.heading {
                margin-top: 3rem;
                margin-bottom: 5rem;
            }
            img.img-responsive {

            }
            div.central {
                box-sizing: content-box;
            }
            h4 {
                font-weight: bold;
                font-size: 3rem;
            }
            a {
                font-size: 1.3rem;
            }
            input.btn {
                width: 60rem !important;
                height: 10rem;
                font-size: 3rem;
            }
            div.information {
                margin-left: 2rem;
                margin-right: 2rem;
                font-size: 3rem;
            }
            input.form-control.form-inline {
                margin-top: 5rem;
                width: 60rem !important;
                height: 6rem !important;
                font-size: 3rem;
            }
            td.menutext {
                margin-left: 2rem;
                margin-right: 2rem;
                margin-bottom: 20px;
                font-size: 2rem;
            }
            #arrow {
                width: 4rem;
                height: 4rem;
            }
            #footer a{
                font-size: 2rem;
                padding-bottom: 3rem;
            }
            #footer div.uoe-logo img{
                float: left;
                width: 8rem;
            }
            #footer div.luc-logo img{
                float: right;
                width: 8rem;
            }
            #footer {
                overflow: auto;
            }
        }
    </style>
    <?php
    if($_SESSION['theme'] == 'roslin') {
        ?>
        <style type="text/css">
            .all {
                height: 1200px;
                max-width: 1000px;
            }
            .col-lg-12.main-image {
                margin-bottom: 50px;
            }
            @media (max-width: 1000px) {
                .all {
                    background: #2c2c2a;
                }
            }
        </style>
    <?php } ?>

</head>

<body>
<?php include_once("./../analyticstracking.php") ?>
<div class="all">
    <div class="central">
        <div class="heading">
            <a href="gameMenu.php" title="Metadata Games Menu">
                <img class="img-responsive" margin=auto src="<?php echo $_SESSION['banner']; ?>" alt="The University of Edinburgh Image Collections"
                     width="800" height="80" border="0"/>

            </a>
            <hr/>
            <h2>HELP US DESCRIBE OUR IMAGES!</h2>
            <hr/>
        </div>
        <?php

        //variables passed in from order form
        $link = mysqli_connect($dbserver, $username, $password, $database);
        @mysqli_select_db($database); #or die( "Unable to select database");

        // $sql = "
        // 	select
        // 	 u.first_name,
        // 	 u.surname,
        // 	 u.uun
        // 	from
        // 	orders.USER u
        // 	where type = '" . $_SESSION["type"]. "'
        // 	;
        // 	";
        $sql = "
                        select
                         u.first_name,
                         u.surname,
                         u.uun
                        from
                        orders.USER u;
                        ";

        $result=mysqli_query($link, $sql); #or die( "A MySQL error has occurred.<br />Your Query: " . $rand_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
        $count = mysqli_num_rows($result);

        $i = 0;

        echo '
                   <div class = "sourcebox">

                	<div class = "heading">
						<h3>+++++++++++++++</h3>
						<h3>+        Our Leaders       +</h3>
						<h3>+++++++++++++++</h3>
					</div>
					<div class = "box">
                    <table>
                    <tr>
                    <td>Pos</td>
                    <td width = "300">Name</td>
                    <td>Points</td>
                    </tr>
                    <tr>
                    <td>===</td>
                    <td>====</td>
                    <td>======</td>
                    </tr>
                    ';
        $pos = 0;
        // while ($i < $count)
        // {
        while ($row = $result->fetch_assoc()) {
            $firstname = strtoupper($row['first_name']);
            $surname = strtoupper($row['surname']);
            $uun = $row['uun'];
            // $firstname = strtoupper(mysql_result($result, $i, 'first_name'));
            // $surname = strtoupper(mysql_result($result, $i, 'surname'));

            // $uun = mysql_result($result, $i, 'uun');
            $pos = $i + 1;

            $mpointssql = "select count(*) as mtotal from CROWD where uun = '".$uun."' and status = 'M' and game = '" . $_SESSION["game"] . "' and date(date_created) = CURDATE() ;";
            $mpointsresult = mysqli_query($link,$mpointssql) ;#or die("A MySQL error has occurred.<br />Your Query: " . $mpointssql . "<br /> Error: (" . mysqli_errno() . ") " . mysqli_error());
            while ($row = $mpointsresult->fetch_assoc()) {
                $mpoints = $row['mtotal'];
            }

            //$mpoints = mysql_result($mpointsresult, 0, 'mtotal');

            $vpointssql = "select count(*) as vtotal from VOTES where voter = '".$uun."' and game = '" . $_SESSION["game"] . "' and date(date_created) = CURDATE() ;";
            $vpointsresult = mysqli_query($link,$vpointssql) ;#or die("A MySQL error has occurred.<br />Your Query: " . $vpointssql . "<br /> Error: (" . mysqli_errno() . ") " . mysqli_error());
            while ($row = $vpointsresult->fetch_assoc()) {
                $vpoints = $row['vtotal'];
            }

            // $vpoints = mysql_result($vpointsresult, 0, 'vtotal');

            $apointssql = "select count(*) as atotal from CROWD where uun = '".$uun."' and status = 'A' and game = '" . $_SESSION["game"] . "' and date(date_created) = CURDATE() ;";
            $apointsresult = mysqli_query($link,$apointssql) ;#or die("A MySQL error has occurred.<br />Your Query: " . $apointssql . "<br /> Error: (" . mysqli_errno() . ") " . mysqli_error());
            while ($row = $apointsresult->fetch_assoc()) {
                $apoints = $row['atotal'];
            }

            // $apoints = mysql_result($apointsresult, 0, 'atotal');

            $ppointssql = "select count(*) as ptotal from CROWD where uun = '".$uun."' and status = 'P' and game = '" . $_SESSION["game"] . "' and date(date_created) = CURDATE() ;";
            $ppointsresult = mysqli_query($link,$ppointssql) ;#or die("A MySQL error has occurred.<br />Your Query: " . $ppointssql . "<br /> Error: (" . mysqli_errno() . ") " . mysqli_error());
            while ($row = $ppointsresult->fetch_assoc()) {
                $ppoints = $row['ptotal'];
            }

            $upointssql = "select sum(quality) as utotal from VOTES where submitter = '".$uun."' and game = '" . $_SESSION["game"] . "' and date(date_created) = CURDATE() ;";
            $upointsresult = mysqli_query($link,$upointssql) ;#or die("A MySQL error has occurred.<br />Your Query: " . $upointssql . "<br /> Error: (" . mysqli_errno() . ") " . mysqli_error());
            while ($row = $upointsresult->fetch_assoc()) {
                $upoints = $row['utotal'];
            }

            // $upoints = mysql_result($upointsresult, 0, 'utotal');
            // $mpointssql = "select count(*) as mtotal from CROWD where uun = '".$uun."' and status = 'M' and game = '" . $_SESSION["game"] . "' and date(date_created) = CURDATE() ;";
            // $mpointsresult=mysql_query($mpointssql) or die( "A MySQL error has occurred.<br />Your Query: " . $mpointssql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

            // $mpoints = mysql_result($mpointsresult, 0, 'mtotal');

            // $vpointssql = "select count(*) as vtotal from VOTES where voter = '".$uun."' and game = '" . $_SESSION["game"] . "' and date(date_created) = CURDATE() ;";
            // $vpointsresult=mysql_query($vpointssql) or die( "A MySQL error has occurred.<br />Your Query: " . $vpointssql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

            // $vpoints = mysql_result($vpointsresult, 0, 'vtotal');

            // $apointssql = "select count(*) as atotal from CROWD where uun = '".$uun."' and status = 'A' and game = '" . $_SESSION["game"] . "' and date(date_created) = CURDATE() ;";
            // $apointsresult=mysql_query($apointssql) or die( "A MySQL error has occurred.<br />Your Query: " . $apointssql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

            // $apoints = mysql_result($apointsresult, 0, 'atotal');

            // $ppointssql = "select count(*) as ptotal from CROWD where uun = '".$uun."' and status = 'P' and game = '" . $_SESSION["game"] . "' and date(date_created) = CURDATE() ;";
            // $ppointsresult=mysql_query($ppointssql) or die( "A MySQL error has occurred.<br />Your Query: " . $ppointssql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

            // $ppoints = mysql_result($ppointsresult, 0, 'ptotal');

            // $upointssql = "select sum(quality) as utotal from VOTES where submitter = '".$uun."' and game = '" . $_SESSION["game"] . "' and date(date_created) = CURDATE() ;";
            // $upointsresult=mysql_query($upointssql) or die( "A MySQL error has occurred.<br />Your Query: " . $upointssql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

            // $upoints = mysql_result($upointsresult, 0, 'utotal');

            $pointstotal = $mpoints + $mpoints + $ppoints + $vpoints + $upoints + $apoints;

            $userarray[$i]['name'] = $firstname.' '.$surname;
            $userarray[$i]['points'] = $pointstotal;

            $i++;
        }

        // }


        function array_sort_by_column(&$arr, $col, $dir = SORT_DESC) {
            $sort_col = array();
            foreach ($arr as $key=> $row) {
                $sort_col[$key] = $row[$col];
            }

            array_multisort($sort_col, $dir, $arr);
        }


        array_sort_by_column($userarray, 'points');
        $pos = 1;
        foreach ($userarray as $user)
        {
            $pos = $pos;
            $name = $user['name'];
            $pointstotal = $user['points'];
            if ($pointstotal > 0)
            {
                echo '<tr><td>'.$pos.'</td><td>'.$name.'</td><td>'.$pointstotal.'</td></tr>';
                $pos++;
            }

        }


        echo'</table>
            </div>
            </div>';

        ?>



    <?php
    // close mysqli connection
    mysqli_close($link);

    ?>


    <?php
    include 'footer.php';?>
    <style type="text/css">
        #footer {
            height: auto;
        }
        @media (max-width: 800px) {
            #footer img{
                display: none;
            }
            div.footer-links {
                float: none;
                width: 85%;
                margin: auto;
            }
        }
    </style>
    <?php
    if($_SESSION['theme'] == 'roslin') {
        ?>
        <script type="text/javascript">
            // document.getElementsByClassName("container")[0].style.width = "90%";
            document.getElementsByClassName("container")[0].style.maxWidth = "800px";
            document.getElementsByClassName("container")[0].style.margin = "auto";
            document.getElementsByClassName("container")[0].style.marginTop = "110px";
            // document.getElementById("footer").style.width = "100%";
            // document.getElementById("footer").style.margin = "auto";
        </script>
    <?php } ?>
</div>

<!-- div central -->
</body>
</html>
