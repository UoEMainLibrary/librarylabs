<?php
if (isset ($_POST['save']))
{
    $_POST['button'] = false;
    include 'config/vars.php';
    session_start();
    $uun = $_SESSION['uun'];

    //variables passed in from order form
    mysql_connect($dbserver, $username, $password);
    @mysql_select_db($database) or die( "Unable to select database");

    $image_id = $_POST['image_id'];
    $subject = trim(mysql_real_escape_string($_POST['subject']));

    $subject_len = strlen($subject);

    $subject_len = $subject_len - 1;
    if (substr($subject, $subject_len, 1) == ";")
    {
        $subject = substr($subject, 0, $subject_len);
    }

    if ($subject != '')
    {

            if (strpos($subject, ';') !== false)
            {
                $subject_array =explode(";", $subject);
                foreach($subject_array as $subject_unit)
                {
                    $subject_unit = ucwords(trim($subject_unit));
                    $insert_sql = "insert into orders.CROWD (image_id, value_text, uun, status ) values ('$image_id', '$subject_unit', '$uun', 'P');";
                    $insert_result=mysql_query($insert_sql) or die( "A MySQL error has occurred.<br />Your Query: " . $insert_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
                }
            }
            else
            {
                //echo '<div class = box><h4>Person : '.$person.'</h4></div>';
                $insert_sql = "insert into orders.CROWD (image_id, value_text,uun, status ) values ('$image_id', '$subject', '$uun', 'P');";
                $insert_result=mysql_query($insert_sql) or die( "A MySQL error has occurred.<br />Your Query: " . $insert_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
            }

    }
    $_REQUEST['image_id'] = null;
}
?>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
    <meta name="generator" content="HTML Tidy for Linux/x86 (vers 11 February 2007), see www.w3.org" />
    <title>Crowd Sourcing Game</title>
    <link rel="stylesheet" type ="text/css" href="css/crowd.css">
    <meta name="author" content="Library Online Editor" />
    <meta name="description" content=
    "Edinburgh University DIU Crowd Sourcing" />
    <meta name="distribution" content="global" />
    <meta name="resource-type" content="document" />
    <meta http-equiv="Content-Type" content="text/html; charset=us-ascii" />
</head>

<body>
<div class = "central">
<div class = "heading">
    <a href="gameMenu.php" title="DIU Games Home Link">
        <img src="images/crowdbanner.gif" alt="The University of Edinburgh Image Collections" width="800" height="80" border="0" />
    </a>
    <hr/>
        <h2>HELP US DESCRIBE OUR IMAGES!</h2>
    <hr/>
</div>
			<?php
            session_start();

            #DIU Numbers
            #Scott Renton, 02/08/2010
            include 'config/vars.php';
            $error = '';

            $link = mysql_connect($dbserver, $username, $password);
            @mysql_select_db($database) or die( "Unable to select database".$database);


            $mpointssql = "select count(*) as mtotal from CROWD where uun = '".$_SESSION['uun']."' and status = 'M';";
            $mpointsresult=mysql_query($mpointssql) or die( "A MySQL error has occurred.<br />Your Query: " . $mpointssql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

            $mpoints = mysql_result($mpointsresult, 0, 'mtotal');

            $vpointssql = "select count(*) as vtotal from VOTES where voter = '".$_SESSION['uun']."';";
            $vpointsresult=mysql_query($vpointssql) or die( "A MySQL error has occurred.<br />Your Query: " . $vpointssql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

            $vpoints = mysql_result($vpointsresult, 0, 'vtotal');

            $apointssql = "select count(*) as atotal from CROWD where uun = '".$_SESSION['uun']."' and status = 'A';";
            $apointsresult=mysql_query($apointssql) or die( "A MySQL error has occurred.<br />Your Query: " . $apointssql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

            $apoints = mysql_result($apointsresult, 0, 'atotal');

            $ppointssql = "select count(*) as ptotal from CROWD where uun = '".$_SESSION['uun']."' and status = 'P';";
            $ppointsresult=mysql_query($ppointssql) or die( "A MySQL error has occurred.<br />Your Query: " . $ppointssql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

            $ppoints = mysql_result($ppointsresult, 0, 'ptotal');

            $upointssql = "select sum(quality) as utotal from VOTES where submitter = '".$_SESSION['uun']."';";
            $upointsresult=mysql_query($upointssql) or die( "A MySQL error has occurred.<br />Your Query: " . $upointssql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

            $upoints = mysql_result($upointsresult, 0, 'utotal');

            $pointstotal = $mpoints + $mpoints + $vpoints + $upoints + $apoints + $ppoints;


            $_SESSION['points'] = $pointstotal;


            echo "<h4>Hello " . $_SESSION['first_name'] . ", you currently have <span class='blink'>" . $_SESSION['points'] ."</span> point";

            if ($_SESSION['points'] != 1)
            {
                echo 's';
            }


            echo "!</h4>";
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


            if (isset ($_REQUEST['image_id']))
            {
                $image_id= $_REQUEST['image_id'];
                $sql = "
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
						where image_id = $image_id;
						";

                $result=mysql_query($sql) or die( "A MySQL error has occurred.<br />Your Query: " . $rand_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
                $count = mysql_numrows($result);
            }
            else
            {
                echo '<hr />';

              /*  echo '
					<div class = "heading">
					<form action = "gameCrowdSourcing.php" method = "post">
						<h3>Choose a random image

							<input type = "submit" value = "Go" name = "button">

						</h3>
			        </form>
			        <form action = "gameCrowdSourcing.php" method = "post">
						<h3>OR enter an image ID
                            <input type = "text" value = "" size = "7" name = "image_id">
							<input type = "submit" value = "Go" name = "buttonwithimage">

						</h3>
						</form>
					</div>';*/

               // if (isset ($_POST['button']))
               // {

                   /* $rand_sql = "
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
							join (select image_id from orders.IMAGE where publication_status = 'Y' and collection in(2,3,5,6,8,9,10,15,16,17)
                  and image_id not in (select image_id from orders.CROWD)
                            order by rand() limit 1)
							as a on i.image_id = a.image_id
							;
							";*/

                    $rand_sql = "
							select
							i.image_id,
							i.collection,
							i.shelfmark,
							i.title,
							i.author,
							i.page_no,
							i.jpeg_path
							from orders.CROWDSOURCE_IMAGE_TMP i
							join (select image_id from orders.CROWDSOURCE_IMAGE_TMP
							order by rand() limit 1)
							as a on i.image_id = a.image_id
							;
							";


                    $result=mysql_query($rand_sql) or die( "A MySQL error has occurred.<br />Your Query: " . $rand_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
                    $count = mysql_numrows($result);
                }

                if (isset ($_POST['buttonwithimage']))
                {

                    $withimagesql = "
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
                            where i.image_id = ".$_POST['image_id']."
							;
							";

                    $result=mysql_query($withimagesql) or die( "A MySQL error has occurred.<br />Your Query: " . $withimagesql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
                    $withimagecount = mysql_numrows($result);
                }

            //}
            $i = 0;
            //if ((isset ($_POST['button'])) or	(isset($_POST['image_id'])))
           // {
                    $image_id = mysql_result($result, $i, 'image_id');
                    $collection = mysql_result($result, $i, 'collection');
                    $shelfmark = mysql_result($result, $i, 'shelfmark');
                    $title = mysql_result($result, $i, 'title');
                    $author = mysql_result($result, $i, 'author');
                    $page_no = mysql_result($result, $i, 'page_no');
                    $jpeg_path = mysql_result($result, $i, 'jpeg_path');
                    $publication_status = mysql_result($result, $i, 'publication_status');
                    $size = getimagesize($jpeg_path);
                    $fullwidth = $size[0];
                    $fullheight = $size[1];

                    if ($fullheight > $fullwidth)
                    {
                        $aspect = $fullheight/ $fullwidth;
                        $short_side = 350 / $aspect;
                        $dimstyle = "height: 95%";
                        $divstyle= "height: 490; width: " . $short_side . " px; vertical-align: middle;";
                    }
                    else
                    {
                        $aspect = $fullwidth / $fullheight;
                        $short_side = 350 / $aspect;
                        $dimstyle = "width: 95%";
                        $divstyle = "height: " . $short_side . " px; width: 550px; vertical-align: middle;";
                    }


                    echo '
                <div class = "sourcebox">
                	<div class = "plusheading">
						<h3>+++++++++++++++++++++++++++++++++++++</h3>
						<h3>+++++++ What Can You Tell Us? +++++++</h3>
						<h3>+++++++++++++++++++++++++++++++++++++</h3>
					</div>
					<div class = "box">
                    </div>
                </div>
                <div class="sourcebox">
					<div class = "heading">
						<h4>'.$title.'</h4>
					</div>

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


                 <div class="sourcebox">
					<form action = "gameCrowdSourcing.php" method = "post">
                            <table style = "text-align: center;">
                                <tr>
									<td class="menutext" colspan="2">Enter tags here- e.g. flag; tiger; hat</td>
								</tr>
								<tr>
									<td><input type = "text" name = "subject" style = "width:500px;"></input></td>
								</tr>
								<tr>
									<input type = "hidden" name = "image_id" value = "'.$image_id.'">
								</tr>
								<tr> <td colspan="2"><input type="submit" name = "save" style = "width:500px;" value="Submit tags and get new image"/></td></tr>
								<tr><td class="menutext" colspan="2">If you enter more than one person, object or place, please separate <br />with a semi-colon. Points only awarded for correctly spelled words.</td></tr>
							</table>
					</form>

                </div>
				<div class= "sourcebox">
					    <div class = "plusheading">';

			       //echo'<h3>++++++++++++++++++++++++++++++++++++++</h3><h3>++++++++ What We Already Know  +++++++</h3><h3>++++++++++++++++++++++++++++++++++++++</h3></div><div = "box"><table>';

                    //variables passed in from order form
                   /* mysql_connect($lunadbserver, $lunausername, $lunapassword);


                    switch ($collection)
                    {
                        case "1":
                        case "12":
                        case "9":
                        case "13":
                            $lunadbase = 'lac_carwatson';
                            break;
                        case "2":
                        case "6":
                        case "8":
                        case "15":
                            $lunadbase = 'lac_galli';
                            break;
                        case "3":
                        case "7":
                        case "10":
                        case "14":
                            $lunadbase = 'lac_shake';
                            break;
                        case "4":
                        case "5":
                            $lunadbase = 'lac_wmman';
                            break;
                        case "11":
                            $lunadbase = 'lac_walter';
                            break;
                        case "16":
                        case "17":
                        case "18":
                            $lunadbase = 'no_db';
                            break;
                    }

                    if ($lunadbase != 'no_db');
                    {
                        @mysql_select_db($lunadbase) or die( "Unable to select database....Error: (" . mysql_errno() . ") " . mysql_error());

                        $luna_sql =
                            "select
							v.valuetext as valuetext,
							i.displayname as displayname
							from
							$lunadbase.DTVALUES v,
							$lunadbase.DTVALUETOOBJECT o,
							$lunadbase.IRFIELDS i
							where o.valueid = v.valueid
							and v.fieldid = i.fieldid
							and o.objectid =
							(select o2.objectid
							from
							$lunadbase.DTVALUES v2,
							$lunadbase.DTVALUETOOBJECT o2,
							$lunadbase.IRFIELDS i2
							where o2.valueid = v2.valueid
							and v2.fieldid = i2.fieldid
							and i2.displayname = 'Work Record Id'
							and v2.valuetext = $image_id
							);";
                        $luna_result=mysql_query($luna_sql) or die( "A MySQL error has occurred.<br />Your Query: " . $luna_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
                        $luna_count = mysql_numrows($luna_result);

                        $j = 0;
                        while ($j < $luna_count)
                        {
                            $spos = false;
                            $cpos = false;
                            $wpos = false;
                            $pos = false;

                            $value = mysql_result($luna_result, $j, 'valuetext');
                            $field = mysql_result($luna_result, $j, 'displayname');


                            $spos = strpos($field, "Subject");
                            $cpos =strpos($field,"Creator Name");
                            $wpos = strpos($field,"Work Record ID");

                            if (($spos !== false) or ($cpos !== false) or ($wpos !== false))
                            {
                                $pos = true;
                            }

                            if ($pos !== false)
                            {
                                echo'<tr>
								<td>'.$field.':</td><td>'.$value.'</td>
								</tr>
								';
                            }
                            $j++;
                        }

                        echo'
							</table>
						</div>
                   </div>*/

                        //$i++;
                    //}

           // }
?>
<div class = "footer">
    <p>


    <hr/>
    <p><a href="gameMenu.php">Back To Menu</a></p>
</div>
		</div> <!-- div central -->
	</body>
</html>
