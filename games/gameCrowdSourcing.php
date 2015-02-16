<?php

include 'config/vars.php';
session_start();
$uun = $_SESSION['uun'];

if (isset ($_POST['save'])) {
    $_POST['button'] = false;

    //variables passed in from order form
    mysql_connect($dbserver, $username, $password);
    @mysql_select_db($database) or die("Unable to select database");

    $image_id = $_POST['image_id'];
    $subject = trim(mysql_real_escape_string($_POST['subject']));
    $creator = trim(mysql_real_escape_string($_POST['creator']));
    $date = trim(mysql_real_escape_string($_POST['date']));
    $location = trim(mysql_real_escape_string($_POST['location']));
    $transcription = trim(mysql_real_escape_string($_POST['transcription']));
    $translation = trim(mysql_real_escape_string($_POST['translation']));
    $production = trim(mysql_real_escape_string($_POST['production']));

    $subject_len = strlen($subject);

    $subject_len = $subject_len - 1;
    if (substr($subject, $subject_len, 1) == ";") {
        $subject = substr($subject, 0, $subject_len);
    }

    if ($subject != '') {
        if (strpos($subject, ';') !== false) {
            $subject_array = explode(";", $subject);
            foreach ($subject_array as $subject_unit) {
                $subject_unit = ucwords(trim($subject_unit));
                $insert_sql = "insert into orders.CROWD (image_id, value_text, uun, status, game ) values ('$image_id', '$subject_unit', '$uun', 'P', '" . $_SESSION['game'] . "');";
                $insert_result = mysql_query($insert_sql) or die("A MySQL error has occurred.<br />Your Query: " . $insert_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
            }
        } else {
            //echo '<div class = box><h4>Person : '.$person.'</h4></div>';
            $insert_sql = "insert into orders.CROWD (image_id, value_text,uun, status, game ) values ('$image_id', '$subject', '$uun', 'P', '" . $_SESSION['game'] . "');";
            $insert_result = mysql_query($insert_sql) or die("A MySQL error has occurred.<br />Your Query: " . $insert_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
        }
    }

    if ($creator != '') {

        $creator = ucwords(trim($creator));
        $insert_sql = "insert into orders.CROWD (image_id, value_text, type, uun, status, game ) values ('$image_id', '$creator', 'creator_research', '$uun', 'P', '" . $_SESSION['game'] . "');";
        $insert_result = mysql_query($insert_sql) or die("A MySQL error has occurred.<br />Your Query: " . $insert_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
    }

    if ($date != '') {

        $date = ucwords(trim($date));
        $insert_sql = "insert into orders.CROWD (image_id, value_text, type, uun, status, game ) values ('$image_id', '$date', 'date_research', '$uun', 'P', '" . $_SESSION['game'] . "');";
        $insert_result = mysql_query($insert_sql) or die("A MySQL error has occurred.<br />Your Query: " . $insert_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
    }

    if ($location != '') {

        $location = ucwords(trim($location));
        $insert_sql = "insert into orders.CROWD (image_id, value_text, type, uun, status, game ) values ('$image_id', '$location', 'location_research', '$uun', 'P', '" . $_SESSION['game'] . "');";
        $insert_result = mysql_query($insert_sql) or die("A MySQL error has occurred.<br />Your Query: " . $insert_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
    }

    if ($production != '') {

        $production = ucwords(trim($production));
        $insert_sql = "insert into orders.CROWD (image_id, value_text, type, uun, status, game ) values ('$image_id', '$production', 'production_research', '$uun', 'P', '" . $_SESSION['game'] . "');";
        $insert_result = mysql_query($insert_sql) or die("A MySQL error has occurred.<br />Your Query: " . $insert_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
    }

    if ($transcription != '') {

        $transcription = ucwords(trim($transcription));
        $insert_sql = "insert into orders.CROWD (image_id, value_text, type, uun, status, game ) values ('$image_id', '$transcription', 'transcription', '$uun', 'P', '" . $_SESSION['game'] . "');";
        $insert_result = mysql_query($insert_sql) or die("A MySQL error has occurred.<br />Your Query: " . $insert_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
    }

    if ($translation != '') {

        $translation = ucwords(trim($translation));
        $insert_sql = "insert into orders.CROWD (image_id, value_text, type, uun, status, game ) values ('$image_id', '$translation', 'translation_research', '$uun', 'P', '" . $_SESSION['game'] . "');";
        $insert_result = mysql_query($insert_sql) or die("A MySQL error has occurred.<br />Your Query: " . $insert_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
    }

    $_SESSION['images'] = $_SESSION['images'] + 1;
    $_REQUEST['image_id'] = null;
}
?>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <title>Metadata Games</title>
    <?php echo $_SESSION['stylesheet']; ?>
    <meta name="author" content="Library Digital Development" />
    <meta name="description" content="Edinburgh University Library Crowd Sourcing"/>
    <meta name="distribution" content="global"/>
    <meta name="resource-type" content="document"/>
    <meta http-equiv="Content-Type" content="text/html; charset=us-ascii"/>
</head>

<body>
<?php include_once("./../analyticstracking.php") ?>
<div class="central">
<div class="heading">
    <a href="gameMenu.php" title="Metadata Games Menu">
        <img src="<?php echo $_SESSION['banner']; ?>" alt="The University of Edinburgh Image Collections"
             width="800" height="80" border="0"/>
    </a>
    <hr/>
    <h2>HELP US DESCRIBE OUR IMAGES</h2>
    <hr/>
</div>
<!--heading-->

<?php
$error = '';

$link = mysql_connect($dbserver, $username, $password);
@mysql_select_db($database) or die("Unable to select database" . $database);

$mpointssql = "select count(*) as mtotal from CROWD where uun = '" . $_SESSION['uun'] . "' and status = 'M' and game = '" . $_SESSION["game"] . "' and date(date_created) = CURDATE() ;";
$mpointsresult = mysql_query($mpointssql) or die("A MySQL error has occurred.<br />Your Query: " . $mpointssql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

$mpoints = mysql_result($mpointsresult, 0, 'mtotal');

$vpointssql = "select count(*) as vtotal from VOTES where voter = '" . $_SESSION['uun'] . "' and game = '" . $_SESSION["game"] . "' and date(date_created) = CURDATE() ;";
$vpointsresult = mysql_query($vpointssql) or die("A MySQL error has occurred.<br />Your Query: " . $vpointssql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

$vpoints = mysql_result($vpointsresult, 0, 'vtotal');

$apointssql = "select count(*) as atotal from CROWD where uun = '" . $_SESSION['uun'] . "' and status = 'A' and game = '" . $_SESSION["game"] . "' and date(date_created) = CURDATE() ;";
$apointsresult = mysql_query($apointssql) or die("A MySQL error has occurred.<br />Your Query: " . $apointssql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

$apoints = mysql_result($apointsresult, 0, 'atotal');

$ppointssql = "select count(*) as ptotal from CROWD where uun = '" . $_SESSION['uun'] . "' and status = 'P' and game = '" . $_SESSION["game"] . "' and date(date_created) = CURDATE() ;";
$ppointsresult = mysql_query($ppointssql) or die("A MySQL error has occurred.<br />Your Query: " . $ppointssql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

$ppoints = mysql_result($ppointsresult, 0, 'ptotal');

$upointssql = "select sum(quality) as utotal from VOTES where submitter = '" . $_SESSION['uun'] . "' and game = '" . $_SESSION["game"] . "' and date(date_created) = CURDATE() ;";
$upointsresult = mysql_query($upointssql) or die("A MySQL error has occurred.<br />Your Query: " . $upointssql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

$upoints = mysql_result($upointsresult, 0, 'utotal');

$pointstotal = $mpoints + $mpoints + $vpoints + $upoints + $apoints + $ppoints;

$_SESSION['points'] = $pointstotal;


echo "<h4>Hello " . $_SESSION['first_name'] . ", you currently have <span class='blink'>" . $_SESSION['points'] . "</span> point";

if ($_SESSION['points'] != 1) {
    echo 's';
}

echo "!&nbsp;";

if ($_SESSION['points'] >= 200) {
    echo '<span class="goldstars">*****</span>';
} else if ($_SESSION['points'] >= 150) {
    echo '<span class="silverstars">***</span>';
} else if ($_SESSION['points'] >= 100) {
    echo '<span class="bronzestars">*</span>';
}
echo "</h4>";

if ($_SESSION['theme'] == 'art' and $_SESSION['images'] >= 10)
{
    echo '<div class="sourcebox">
              <form action = "gameCrowdSourcingApproval.php" method = "post">
                     <table style = "text-align: center;">
                                    <tr>
                                        <td class="menutext" colspan="2">Thanks for doing all that tagging. The time has come to vote on other people\'s tags</td>
                                    </tr>
                                    <tr> <td colspan="2"><input type="submit" name = "save" style = "width:500px;" value="Go to voting"/></td></tr>
                      </table>
                </form>

          </div>';
}
else
{
    if ($_REQUEST['image_id'] == null) {
        unset($_REQUEST['image_id']);
    }

    if (isset ($_REQUEST['image_id'])) {
        $image_id = $_REQUEST['image_id'];
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

        $result = mysql_query($sql) or die("A MySQL error has occurred.<br />Your Query: " . $rand_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
        $count = mysql_numrows($result);
    } else {
        echo '<hr />';

        if(!isset($_SESSION['images']))
        {
            $_SESSION['images'] = 0;
        }

        if ($_SESSION['theme'] == 'art' || $_SESSION['theme'] == 'artAccessible')
        {
            if ($_SESSION['images'] == 0)
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
                                    from orders.IMAGE i
                                    where collection = 20
                                    and highlight = 'Y'
                                    order by rand() limit 1
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
                                    from orders.IMAGE i
                                    where collection = 20
                                    order by rand() limit 1
                                    ;
                                    ";
            }
        }
        else if ($_SESSION['theme'] == 'photo')
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
                                    from orders.IMAGE i
                                    where photography = 'Y'
                                    order by rand() limit 1
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
                                    from orders.CROWDSOURCE_IMAGE_TMP i
                                    join (select image_id from orders.CROWDSOURCE_IMAGE_TMP
                                    order by rand() limit 1)
                                    as a on i.image_id = a.image_id
                                    ;
                                    ";
        }

        $result = mysql_query($rand_sql) or die("A MySQL error has occurred.<br />Your Query: " . $rand_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
        $count = mysql_numrows($result);
        $images = $_SESSION['images'];
        $_SESSION['images'] = $images++;
    }

    if (isset ($_POST['buttonwithimage'])) {

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
                                where i.image_id = " . $_POST['image_id'] . "
                                ;
                                ";

        $result = mysql_query($withimagesql) or die("A MySQL error has occurred.<br />Your Query: " . $withimagesql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
        $withimagecount = mysql_numrows($result);
    }

    $i = 0;
    $image_id = mysql_result($result, $i, 'image_id');
    $collection = mysql_result($result, $i, 'collection');
    $shelfmark = mysql_result($result, $i, 'shelfmark');
    $title = mysql_result($result, $i, 'title');
    $author = mysql_result($result, $i, 'author');
    $page_no = mysql_result($result, $i, 'page_no');
    $jpeg_path = mysql_result($result, $i, 'jpeg_path');
    $publication_status = mysql_result($result, $i, 'publication_status');
    $size = getimagesize('../' . $jpeg_path);

    $fullwidth = $size[0];
    $fullheight = $size[1];

    if ($fullheight > $fullwidth) {
        $aspect = $fullheight / $fullwidth;
        $short_side = 350 / $aspect;
        $dimstyle = "height: 95%";
        $divstyle = "height: 350; width: " . $short_side . " px; vertical-align: middle;";
    } else {
        $aspect = $fullwidth / $fullheight;
        $short_side = 350 / $aspect;
        $dimstyle = "width: 95%";
        $divstyle = "height: " . $short_side . " px; width: 350px; vertical-align: middle;";
    }

    echo '
                    <div class="sourcebox">
                        <div class = "heading">
                            <h5>' . $title . ": " . $author . '</h5>
                        </div>

                        <div class = "image">
                        ';

    if (strpos($image_id, '-') == false) {
        $urlrecordid = ltrim($image_id, '0');
    } else {
        $urlrecordid = $image_id;
    }
    $urlsql = "select recordid, objectid, imageid, institutionid, collectionid
               from OBJECTIMAGE
               where recordid = '" . $urlrecordid . "';";

    $urlresult = mysql_query($urlsql) or die("A MySQL error has occurred.<br />Your Query: " . $urlsql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
    $count = mysql_numrows($urlresult);

    $urlobjectid = mysql_result($urlresult, 0, 'objectid');
    $urlimageid = mysql_result($urlresult, 0, 'imageid');
    $urlinstid = mysql_result($urlresult, 0, 'institutionid');
    $urlcollid = mysql_result($urlresult, 0, 'collectionid');


    echo '<p><a href= "http://images.is.ed.ac.uk/luna/servlet/detail/' . $urlinstid . '~' . $urlcollid . '~' . $urlcollid . '~' . $urlobjectid . '~' . $urlimageid . '" target = "_blank"><img src = "../' . $jpeg_path . '" style = "' . $divstyle . '"/></a></p>
                        </div>
                    </div>
                        ';

    if ($_SESSION['theme'] == 'photo') {
        echo '<div class="sourcebox">
                        <form action = "gameCrowdSourcing.php" method = "post">
                                <table style = "text-align: center;">
                                    <h4>What can you tell us about this image?</h4>
                                    <tr>
                                        <td>Creator Information: </td>
                                        <td>Date Information: </td>
                                    </tr>
                                        <td><textarea name = "creator"></textarea></td>
                                        <td><textarea name = "date"></textarea></td>
                                    </tr>
                                    <tr>
                                        <td>Location Information: </td>
                                        <td>Production Information: </td>
                                    </tr>
                                    <tr>
                                        <td><textarea name = "location"></textarea></td>
                                        <td><textarea name = "production"></textarea></td>
                                    </tr>

                                    <tr>
                                        <td>Translation: </td>
                                        <td>Transcription: </td>
                                    </tr>
                                    <tr>
                                        <td><textarea name = "translation"></textarea></td>
                                        <td><textarea name = "transcription"></textarea></td>
                                    </tr>
                                    <tr>
                                        <input type = "hidden" name = "image_id" value = "' . $image_id . '">
                                    </tr>
                                    <tr> <td colspan="2"><input type="submit" name = "save" style = "width:500px;" value="Submit info and get new image"/></td></tr>
                                </table>
                        </form>

                        </div>';
    } else {
        echo '<div class="sourcebox">
                        <form action = "gameCrowdSourcing.php" method = "post">
                                <table style = "text-align: center;">
                                    <tr>
                                        <td class="menutext" colspan="2">Enter tags here- e.g. flag; tiger; hat</td>
                                    </tr>
                                    <tr>
                                        <td><input type = "text" name = "subject" style = "width:500px;"></input></td>
                                    </tr>
                                    <tr>
                                        <input type = "hidden" name = "image_id" value = "' . $image_id . '">
                                    </tr>
                                    <tr> <td colspan="2"><input type="submit" name = "save" style = "width:500px;" value="Submit tags and get new image"/></td></tr>
                                    <tr><td class="menutext" colspan="2">If you enter more than one person, object or place, please separate <br />with a semi-colon. Points only awarded for correctly spelled words.</td></tr>
                                </table>
                </form>

            </div>';
    }
}
?>
<?php include 'footer.php';?>
</div>
<!-- div central -->
</body>
</html>
