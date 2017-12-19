<?php

session_start();
include 'config/vars.php';

// Connect to db
$error = '';
$link = mysql_connect($dbserver, $username, $password);
@mysql_select_db($database) or die( "Unable to select database");

if (isset ($_POST['save']))
{
    $check_box = $_POST['moderated'];
    $value = $_POST['value'];
    $uun = $_REQUEST['uun'];
    $voted = false;

    for($i=0; $i<sizeof($check_box); $i++)
    {
        $line = explode("|",$check_box[$i]);

        $action = $line[0];
        $crowd_id=$line[1];
        $uun=$line[2];

        if ($action == '1' or $action == '-1')
        {
            $get_image_sql = "select image_id from orders.CROWD where id = ".$crowd_id.";";
            $get_image_result =mysql_query($get_image_sql) or die( "A MySQL error has occurred.<br />Your Query: " . $get_image_result . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
            $image_id = mysql_result($get_image_result, 0, 'image_id');

            $vote_insert_sql = "insert into orders.VOTES (crowd_id, submitter, voter, image_id, quality, game ) values (".$crowd_id.", '".$uun."', '".$_SESSION['uun']."','".$image_id."',".$action.",'". $_SESSION["game"]. "');";
            $vote_insert_result=mysql_query($vote_insert_sql) or die( "A MySQL error has occurred.<br />Your Query: " . $vote_insert_result . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
            //echo 'SQL'.$vote_insert_sql;

            $vote_sql = "select sum(quality) as votes from orders.VOTES where id = ".$crowd_id.";";
            $vote_result=mysql_query($vote_sql) or die( "A MySQL error has occurred.<br />Your Query: " . $vote_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
            $votes = mysql_result($vote_result,0, 'votes');


            if ($votes >= 2)
            {
                $status = 'A';

            }
            else if ($votes <= -2)
            {
                $status = 'R';
            }
            else
            {
                 $status = 'M';
            }

            //update the user to value of 'C' (complete) based on the chosen uun
            $sql = "UPDATE orders.CROWD set status = '".$status."'  where id= ".$crowd_id.";";
            $result = mysql_query($sql) or die( "A MySQL error has occurred.<br />Your Query: " . $sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

            $voted = true;

        }

    }

    if($voted == true)
    {
        $_SESSION['vimages'] = $_SESSION['vimages'] + 1;
    }
}
?>


<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
    <title>Metadata Games</title>
    <?php echo $_SESSION['stylesheet']; ?>
    <meta name="author" content="Library Digital Development" />
    <meta name="description" content=
    "Edinburgh University DIU Crowd Sourcing" />
    <meta name="distribution" content="global" />
    <meta name="resource-type" content="document" />
    <meta http-equiv="Content-Type" content="text/html; charset=us-ascii" />
    <link href="../assets/font-awesome-4.4.0/css/font-awesome.css" rel="stylesheet">
</head>
<body>
<?php include_once("./../analyticstracking.php") ?>
<div class = "centralDolAp">
    <div class = "heading">
        <a href="gameMenu.php" title="Metadata Games">
            <img src="<?php echo $_SESSION['banner']; ?>" alt="The University of Edinburgh Image Collections" width="800" height="80" border="0" />
        </a>
    </div>
	<?php

            $mpointssql = "select count(*) as mtotal from CROWD where uun = '".$_SESSION['uun']."' and status = 'M' and game = '" . $_SESSION["game"] . "' and date(date_created) = CURDATE() ;";
            $mpointsresult=mysql_query($mpointssql) or die( "A MySQL error has occurred.<br />Your Query: " . $mpointssql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

            $mpoints = mysql_result($mpointsresult, 0, 'mtotal');

            $vpointssql = "select count(*) as vtotal from VOTES where voter = '".$_SESSION['uun']."' and game = '" . $_SESSION["game"] . "' and date(date_created) = CURDATE() ;";
            $vpointsresult=mysql_query($vpointssql) or die( "A MySQL error has occurred.<br />Your Query: " . $vpointssql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

            $vpoints = mysql_result($vpointsresult, 0, 'vtotal');

            $apointssql = "select count(*) as atotal from CROWD where uun = '".$_SESSION['uun']."' and status = 'A' and game = '" . $_SESSION["game"] . "' and date(date_created) = CURDATE() ;";
            $apointsresult=mysql_query($apointssql) or die( "A MySQL error has occurred.<br />Your Query: " . $apointssql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

            $apoints = mysql_result($apointsresult, 0, 'atotal');

            $ppointssql = "select count(*) as ptotal from CROWD where uun = '".$_SESSION['uun']."' and status = 'P' and game = '" . $_SESSION["game"] . "' and date(date_created) = CURDATE() ;";
            $ppointsresult=mysql_query($ppointssql) or die( "A MySQL error has occurred.<br />Your Query: " . $ppointssql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

            $ppoints = mysql_result($ppointsresult, 0, 'ptotal');

            $upointssql = "select sum(quality) as utotal from VOTES where submitter = '".$_SESSION['uun']."' and game = '" . $_SESSION["game"] . "' and date(date_created) = CURDATE() ;";
            $upointsresult=mysql_query($upointssql) or die( "A MySQL error has occurred.<br />Your Query: " . $upointssql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

            $upoints = mysql_result($upointsresult, 0, 'utotal');

            $pointstotal = $mpoints + $mpoints + $vpoints + $upoints + $apoints + $ppoints;


            $_SESSION['points'] = $pointstotal;



            if(!isset($_SESSION['vimages']))
            {
                $_SESSION['vimages'] = 0;
            }

            if (($_SESSION['theme'] == 'art'|| $_SESSION['theme'] == 'roslin') and $_SESSION['vimages'] >= 10)
            {
                echo '<br/>
<br/><table style = "text-align: center;">
                                    <tr>
                                        <td class="gameover">
                                            <h1 class="giant">GAME</h1><br />
                                            <h1 class="giant">OVER!</h1>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="menutext" colspan="2">Thanks for doing all that voting. Keep an eye on your scores - there could be a prize for you!<br />Your score will grow as other people vote on your tags!</td>
                                    </tr>
                                    <tr>
                                      <td class = "menu">
                                        <form action="https://www.ease.ed.ac.uk/logout.cgi" method="post" name="logout">
                                            <input type="submit" value="Logout" name="verify" />
                                            <input type="hidden" value="https://www.ease.ed.ac.uk/logout.html" name="url">
                                        </form>
                                      </td>
                                    </tr>
                                    </table>';
            }
            else
            {
                if ($_SESSION['theme'] == 'roslin')
                {
                    $rand_sql = "
                                select
                                i.image_id,
                                i.collection,
                                i.shelfmark,
                                i.title,
                                i.author,
                                i.page_no,
                                i.jpeg_path
                                from
                                orders.IMAGE i
                                join (select x.image_id from orders.IMAGE x, orders.CROWD y where x.image_id = y.image_id and x.collection = 17 and y.status = 'M'  order by rand() limit 1)
                                as a on i.image_id = a.image_id
                                ;
                                ";
                }

                    else
                    {
                        $rand_sql = "
                                select
                                i.image_id,
                                i.collection,
                                i.shelfmark,
                                i.title,
                                i.author,
                                i.page_no,
                                i.jpeg_path
                                from
                                orders.IMAGE i
                                join (select x.image_id from orders.IMAGE x, orders.CROWD y where x.image_id = y.image_id and y.status = 'M' and y.uun <> '".$_SESSION['uun']."' order by rand() limit 1)
                                as a on i.image_id = a.image_id
                                ;
                                ";
                    }

                    $result=mysql_query($rand_sql) or die( "A MySQL error has occurred.<br />Your Query: " . $rand_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

                    $count = mysql_numrows($result);

                    if ($count == 0)
                    {
                        echo '<div class = "sourcebox">
                        <p>No items to show!</p>
                        </div>';
                    }
                    else
                    {


                        $i = 0;

                        $image_id = mysql_result($result, $i, 'image_id');
                        $collection = mysql_result($result, $i, 'collection');
                        $shelfmark = mysql_result($result, $i, 'shelfmark');
                        $title = mysql_result($result, $i, 'title');
                        $author = mysql_result($result, $i, 'author');
                        $page_no = mysql_result($result, $i, 'page_no');
                        $jpeg_path = mysql_result($result, $i, 'jpeg_path');
                        $publication_status = mysql_result($result, $i, 'publication_status');
                        $size = getimagesize('../'.$jpeg_path);
                        $fullwidth = $size[0];
                        $fullheight = $size[1];

                        if ($fullheight > $fullwidth)
                        {
                            $aspect = $fullheight/ $fullwidth;
                            $short_side = 320 / $aspect;
                            $dimstyle = "height: 95%";
                            $divstyle= "height: 320; width: " . $short_side . " px; vertical-align: middle;";
                        }
                        else
                        {
                            $aspect = $fullwidth / $fullheight;
                            $short_side = 320 / $aspect;
                            $dimstyle = "width: 95%";
                            $divstyle = "height: " . $short_side . " px; width: 320px; vertical-align: middle;";
                        }


                        echo '<br><br>
                    <div class="sourcebox">
                        <div class = "image">
                        ';

                        $urlrecordid = ltrim($image_id, '0');
                        $urlsql = "select
                                    recordid, objectid, imageid, institutionid, collectionid
                                   from
                                        OBJECTIMAGE
                                   where
                                    recordid = ".$urlrecordid.";";
                        $urlresult=mysql_query($urlsql) or die( "A MySQL error has occurred.<br />Your Query: " . $urlsql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
                        $count = mysql_numrows($urlresult);

                        $urlobjectid = mysql_result($urlresult, 0, 'objectid');
                        $urlimageid = mysql_result($urlresult, 0, 'imageid');
                        $urlinstid = mysql_result($urlresult, 0, 'institutionid');
                        $urlcollid = mysql_result($urlresult, 0, 'collectionid');

                        echo '<p><a href= "http://images.is.ed.ac.uk/luna/servlet/detail/'.$urlinstid.'~'.$urlcollid.'~'.$urlcollid.'~'.$urlobjectid.'~'.$urlimageid.'" target = "_blank"><img src = "../'.$jpeg_path.'" style = "'.$divstyle.'"/></a></p>
                        </div>
                    </div>
                    <!--<div class = "info">-->


                       <!-- <div class="box">
                        <p class = "menutext">Select the relevant radio button.</p><!--
</div>
</div>
--></div><!--
--><div class ="approve">
<div class = "heading">
                            <h3>Pending Information: Image '.$image_id.'</h3>
                            </div><!--
--><form action = "gameCrowdSourcingApprovalDolly.php" method = "post"><table class ="radio">
                         <tr>
                         <td class="typelabel">Type</td>
                         <td class="label">Value</td>
                         <td class = "radiotd"><i class="fa fa-check"></i></td>
                         <td class = "radiotd"><i class="fa fa-question"></i></td>
                         <td class = "radiotd"><i class="fa fa-times"></i></td>
                         </tr>
                         <tr>
                         <td class="typelabel">----</td>
                         <td class="label">-----</td>
                         <td class = "radiotd">---</td>
                         <td class = "radiotd">---</td>
                         <td class = "radiotd">---</td>
                         </tr>';

                $data_sql = "select c.id as crowd_id, u.first_name, u.surname, value_text, c.status, c.type, c.uun from orders.CROWD c, orders.USER u where c.uun = u.uun and c.status = 'M' and image_id = '".$image_id."';";
                $data_result = mysql_query($data_sql) or die( "A MySQL error has occurred.<br />Your Query: " . $data_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
                $data_count = mysql_numrows($data_result);

                $k = 0;
                $crowds = array();
                while ($k <  $data_count)
                {
                    $crowd_id = mysql_result ($data_result, $k, 'crowd_id');
                    $crowds[$k] = $crowd_id;

                    $value_text = mysql_result($data_result, $k, 'value_text');
                    $type = mysql_result($data_result, $k, 'type');
                    $uun = mysql_result($data_result, $k, 'uun');
                    //<input type="hidden" name = "crowd_id" value = '.$crowd_id[$k].'/>

                    // echo '<tr><td>From '.$first_name .' '.$surname.'</td><td> Value for '.$type.': </td><td><input type = "text" name = "value['.$k.']" value = "'.$value_text.'"</td><td><input type="checkbox" name="moderated['.$k.']" value = "'.$crowd_id.'|'.$uun.'"/></td></tr>';

                    echo '<tr">
                    <td class="typelabel">'.$type.'</td>
                    <td class="label">'.strtoupper($value_text).'</td>
                    <td class="radiotd"><input type="radio" name="moderated['.$k.']" value = "1|'.$crowd_id.'|'.$uun.'"/></td>
                    <td class="radiotd"><input type="radio" name="moderated['.$k.']" value = "O|'.$crowd_id.'|'.$uun.'"/></td>
                    <td class="radiotd"><input type="radio" name="moderated['.$k.']" value = "-1|'.$crowd_id.'|'.$uun.'"/></td>
                    </tr>';
                    $k++;

                }

                //$crowd_serialized = serialize($crowds);

                echo '
                </table>
                <br><div class="approve"><input type="submit" name = "save" style = "width:520px;" value="Submit votes and get new image" /></div>
                </form>
                        <div class = "heading">
                            <h4>'.$title.'</h4>
                        </div>';
                        echo "<h4>For information, " . $_SESSION['first_name'] . ", you currently have <span class='blink'>" . $_SESSION['points'] ."</span> point";

                        if ($_SESSION['points'] != 1)
                        {
                            echo 's';
                        }

                        echo "!&nbsp;";

                        if ($_SESSION['points'] >= 200)
                        {
                            echo '<span class="goldstars">*****</span>';
                        }
                        else if ($_SESSION['points'] >=150)
                        {
                            echo '<span class="silverstars">***</span>';
                        }
                        else if ($_SESSION['points'] >= 100)
                        {
                            echo '<span class="bronzestars">*</span>';
                        }

                        echo "</h4>";

             }
        }

    // close mysql connection
    mysql_close($link);

			?>

<?php include 'footer.php';?>
    </div>
		<div class="bottom">
<br/>
            <br/>
            <br/>
            <br/><br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/><br/>
            <br/><br/>
            <br/><br/>
            <br/><br/>
            <br/><br/>
            <br/>

        </div>
</div>
	</body>
</html>
