<?php


include 'config/vars.php';
session_start();
$uun = $_SESSION['uun'];

$_SESSION['stylesheet'] = '<link rel="stylesheet" type ="text/css" href="css/roslin.css">';
$_SESSION['banner'] = "./images/rosbanner.jpg";
$_SESSION['game'] = 'R';

$page = $_SERVER['PHP_SELF'];
$sec = "5";
// connect to db
$error = '';
$link = mysql_connect($dbserver, $username, $password);
@mysql_select_db($database) or die( "Unable to select database");
?>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
    <title>Metadata Games</title>
    <?php echo $_SESSION['stylesheet']; ?>
    <meta http-equiv="refresh" content="<?php echo $sec?>;URL='<?php echo $page?>'">
    <meta name="author" content="Library Digital Development" />
    <meta name="description" content=
    "Edinburgh University Library Crowd Sourcing" />
    <meta name="distribution" content="global" />
    <meta name="resource-type" content="document" />
    <meta http-equiv="Content-Type" content="text/html; charset=us-ascii" />
</head>

<body>
<?php include_once("./../analyticstracking.php") ?>
<div class = "centralDolAp">
    <div class = "heading">
        <a href="gameMenu.php" title="Metadata Games">
            <img src="<?php echo $_SESSION['banner']; ?>" alt="The University of Edinburgh Image Collections" width="800" height="80" border="0" />
        </a>
        <br/><br/><br/>
                <h2>Thanks for helping describe our images!</h2>
            </div>
    </div>
<div class = "approve">
			<?php

					$sql = "
						select
						 u.first_name,
						 u.surname,
						 u.uun
						from 
						orders.USER u
						where type = '" . $_SESSION["type"]. "'
						;
						";
						
					$result=mysql_query($sql) or die( "A MySQL error has occurred.<br />Your Query: " . $rand_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());	
					$count = mysql_numrows($result);

				    $i = 0;

                   echo '<div class = "sourcebox">
                	<div class = "heading">
						<h3> Our Leaders...</h3>
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
                    while ($i < $count)
                    {

                        $firstname = strtoupper(mysql_result($result, $i, 'first_name'));
                        $surname = strtoupper(mysql_result($result, $i, 'surname'));

                        $uun = mysql_result($result, $i, 'uun');
                        $pos = $i + 1;

                        $mpointssql = "select count(*) as mtotal from CROWD where uun = '".$uun."' and status = 'M' and game = '" . $_SESSION["game"] . "' and date(date_created) = CURDATE() ;";
                        //$mpointssql = "select count(*) as mtotal from CROWD where uun = '".$uun."' and status = 'M' and game = '" . $_SESSION["game"] . "' and (date(date_created) BETWEEN '2015-02-15 00:00:01' AND '2015-02-20 23:59:59') ;";
                        $mpointsresult=mysql_query($mpointssql) or die( "A MySQL error has occurred.<br />Your Query: " . $mpointssql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

                        $mpoints = mysql_result($mpointsresult, 0, 'mtotal');

                        $vpointssql = "select count(*) as vtotal from VOTES where voter = '".$uun."' and game = '" . $_SESSION["game"] . "' and date(date_created) = CURDATE() ;";
                        //$vpointssql = "select count(*) as vtotal from VOTES where voter = '".$uun."' and game = '" . $_SESSION["game"] . "' and (date(date_created) BETWEEN '2015-02-15 00:00:01' AND '2015-02-20 23:59:59') ;";

                        $vpointsresult=mysql_query($vpointssql) or die( "A MySQL error has occurred.<br />Your Query: " . $vpointssql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

                        $vpoints = mysql_result($vpointsresult, 0, 'vtotal');

                        $apointssql = "select count(*) as atotal from CROWD where uun = '".$uun."' and status = 'A' and game = '" . $_SESSION["game"] . "' and date(date_created) = CURDATE() ;";
                        //$apointssql = "select count(*) as atotal from CROWD where uun = '".$uun."' and status = 'A' and game = '" . $_SESSION["game"] . "' and (date(date_created) BETWEEN '2015-02-15 00:00:01' AND '2015-02-20 23:59:59') ;";
                        $apointsresult=mysql_query($apointssql) or die( "A MySQL error has occurred.<br />Your Query: " . $apointssql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

                        $apoints = mysql_result($apointsresult, 0, 'atotal');

                        $ppointssql = "select count(*) as ptotal from CROWD where uun = '".$uun."' and status = 'P' and game = '" . $_SESSION["game"] . "' and date(date_created) = CURDATE() ;";
                        //$ppointssql = "select count(*) as ptotal from CROWD where uun = '".$uun."' and status = 'P' and game = '" . $_SESSION["game"] . "' and (date(date_created) BETWEEN '2015-02-15 00:00:01' AND '2015-02-20 23:59:59') ;";
                        $ppointsresult=mysql_query($ppointssql) or die( "A MySQL error has occurred.<br />Your Query: " . $ppointssql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

                        $ppoints = mysql_result($ppointsresult, 0, 'ptotal');

                        $upointssql = "select sum(quality) as utotal from VOTES where submitter = '".$uun."' and game = '" . $_SESSION["game"] . "' and date(date_created) = CURDATE() ;";
                        //$upointssql = "select sum(quality) as utotal from VOTES where submitter = '".$uun."' and game = '" . $_SESSION["game"] . "' and (date(date_created) BETWEEN '2015-02-15 00:00:01' AND '2015-02-20 23:59:59') ;";
                        $upointsresult=mysql_query($upointssql) or die( "A MySQL error has occurred.<br />Your Query: " . $upointssql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

                        $upoints = mysql_result($upointsresult, 0, 'utotal');

                        $pointstotal = $mpoints + $mpoints + $ppoints + $vpoints + $upoints + $apoints;

                        $userarray[$i]['name'] = $firstname.' '.$surname;
                        $userarray[$i]['points'] = $pointstotal;

                        $i++;

                    }


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

            // close mysql connection
            mysql_close($link);

            ?>
            <?php include 'footer.php';?>
            <!--<embed src ="pacman_beginning.mp3"></embed>-->
		</div>
        <div class = "bottom">
             <br/> <br/> <br/> <br/> <br/> <br/> <br/> <br/> <br/> <br/> <br/> <br/> <br/>
</div>
	</body>
</html>
