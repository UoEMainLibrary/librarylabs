<?php
include 'config/vars.php';
session_start();
$uun = $_SESSION['uun'];
// Connect To Database
$error = '';
$link = mysql_connect($dbserver, $username, $password);
@mysql_select_db($database) or die( "Unable to select database");
if (isset ($_POST['save']))
{    $_POST['button'] = false;
    $check_box = $_POST['moderated'];
    $value = $_POST['value'];
    $uun = $_SESSION['uun'];
    $subjecttype = $_POST['subjecttype'];
    for($i=0; $i<sizeof($check_box); $i++)
    {
        $line = explode("|",$check_box[$i]);
        $action = $line[0];
        switch ($action)
        {
            case 'C':
                $subjecttype_text = 'Category';
                $action = 'M';
                break;
            case 'O':
                $subjecttype_text = 'Object';
                $action = 'M';
                break;
            case 'S':
                $subjecttype_text = 'Person';
                $action = 'M';
                break;
            case 'G':
                $subjecttype_text = 'Place';
                $action = 'M';
                break;
            case 'R':
                $subjecttype_text = '';
                $action = 'R';
                break;
            case 'D':
                $subjecttype_text = '';
                $action = 'D';
                break;
            case 'I':
                $subjecttype_text = '';
                $action = 'P';
                break;
        }
        $crowd_id = $line[1];
        $uun = $line[2];
        $value_text =  mysql_real_escape_string($value[$i]);
        //update the user to value of 'C' (complete) based on the chosen uun
        $sql = "UPDATE orders.CROWD set status = '$action', type = '".$subjecttype_text."', value_text ='".$value_text."'  where id= '".$crowd_id."';";
        $result=mysql_query($sql) or die( "A MySQL error has occurred.<br />Your Query: " . $sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
    }
}
?>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
    <title>Metadata Games</title>
    <?php echo $_SESSION['stylesheet'];?>
    <meta name="author" content="Library Digital Development" />
    <meta name="description" content= "Edinburgh University Library Metadata Games" />
    <meta name="distribution" content="global" />
    <meta name="resource-type" content="document" />
    <meta http-equiv="Content-Type" content="text/html; charset=us-ascii" />
</head>

<body>
<?php include_once("./../analyticstracking.php") ?>
<div class = "central">
<div class = "heading">
    <a href="gameMenu.php" title="Metadata Games Menu">
        <img src="<?php echo $_SESSION['banner']; ?>" alt="The University of Edinburgh Image Collections" width="754" height="65" border="0" />
    </a>
    <h2>MODERATE IMAGE DATA</h2>
    <hr/>
</div>
<?php
if($_SESSION['theme'] == 'art' || $_SESSION['theme'] == 'artAccessible') {
    $unmod_sql =          "select count(distinct (r.image_id)) as unmod_total
                    from
                    orders.CROWD r, orders.IMAGE i
                    where
                    r.status = 'P'
                    and i.image_id = r.image_id
                    and i.game = 'A'
                    ;";
}
else
{
    $unmod_sql =          "select count(distinct (r.image_id)) as unmod_total
                    from
                    orders.CROWD r, orders.IMAGE i
                    where
                    r.status = 'P'
                    and i.image_id = r.image_id
                    and r.game = 'D'
                    ;";}
$unmod_result=mysql_query($unmod_sql) or die( "A MySQL error has occurred.<br />Your Query: " . $unmod_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
$unmod_count = mysql_numrows($unmod_result);
$unmod_total = mysql_result($unmod_result, 0, 'unmod_total');

if($unmod_total > 0)
{
echo '
    <div class = "heading">
        <h3>THERE ARE '.$unmod_total.' IMAGES STILL TO MODERATE</h2>
        <hr/>
    </div>';
if ($_REQUEST['image_id'] == null)
{
    unset($_REQUEST['image_id']);
}
if (isset ($_REQUEST['image_id']))
{
    $image_id= $_GET['image_id'];
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
    if ($_SESSION['theme'] == 'art' || $_SESSION['theme'] == 'artAccessible') {
        //this is temporarily set for art moderation only.
        $sql = "
              select distinct (r.image_id) as image_id, c.name as collection_name, i.title as title,
                        i.collection as collection,
                        i.author as author,
                        i.image_id as image_id, i.shelfmark as shelfmark,
                        i.page_no as page_no,
                        i.jpeg_path as jpeg_path,
                        i.publication_status as publication_status
                        from orders.IMAGE i,
                        orders.COLLECTION c,
                        orders.CROWD r
                        where
                        r.status = 'P'
                        and r.image_id = i.image_id
                        and i.collection = c.id
                        and i.collection = 20
                                                    order by rand() limit 1;
                                                    ";
    }
    else
    {
        //this is temporarily set for art moderation only.
        $sql = "
              select distinct (r.image_id) as image_id, c.name as collection_name, i.title as title,
                        i.collection as collection,
                        i.author as author,
                        i.image_id as image_id, i.shelfmark as shelfmark,
                        i.page_no as page_no,
                        i.jpeg_path as jpeg_path,
                        i.publication_status as publication_status
                        from orders.IMAGE i,
                        orders.COLLECTION c,
                        orders.CROWD r
                        where
                        r.status = 'P'
                        and r.image_id = i.image_id
                        and i.collection = c.id
                        and not(i.collection = 20)
                                                    order by rand() limit 1;
                                                    ";
    }
    $result=mysql_query($sql) or die( "A MySQL error has occurred.<br />Your Query: " . $rand_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
    $count = mysql_numrows($result);
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
$size = getimagesize('../'.$jpeg_path);
$fullwidth = $size[0];
$fullheight = $size[1];
if ($fullheight > $fullwidth)
{
    $aspect = $fullheight/ $fullwidth;
    $short_side = 350 / $aspect;
    $dimstyle = "height: 95%";
    $divstyle= "height: 350; width: " . $short_side . " px; vertical-align: middle;";
}
else
{
    $aspect = $fullwidth / $fullheight;
    $short_side = 350 / $aspect;
    $dimstyle = "width: 95%";
    $divstyle = "height: " . $short_side . " px; width: 350px; vertical-align: middle;";
}
echo '
                            <div class= "sourcebox">
                                            <div class = "heading">
                                                    <h2>'.$title.'</h2>
                                                     <h3>by '.$author.' ('.$image_id.')</h3>
                                            </div>
                                            <div class = "box">
                            <div class = "half">';
if (strpos($image_id, '-') == false)
{
    $urlrecordid = ltrim($image_id, '0');
}
else
{
    $urlrecordid = $image_id;
}
$urlsql = "select
                                    recordid, objectid, imageid, institutionid, collectionid
                                   from
                                        OBJECTIMAGE
                                   where
                                    recordid = '".$urlrecordid."';";
$urlresult=mysql_query($urlsql) or die( "A MySQL error has occurred.<br />Your Query: " . $urlsql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
$count = mysql_numrows($urlresult);
$urlobjectid = mysql_result($urlresult, 0, 'objectid');
$urlimageid = mysql_result($urlresult, 0, 'imageid');
$urlinstid = mysql_result($urlresult, 0, 'institutionid');
$urlcollid = mysql_result($urlresult, 0, 'collectionid');
echo '<p><a href= "http://images.is.ed.ac.uk/luna/servlet/detail/'.$urlinstid.'~'.$urlcollid.'~'.$urlcollid.'~'.$urlobjectid.'~'.$urlimageid.'" target = "_blank"><img src = "../'.$jpeg_path.'" style = "'.$divstyle.'"/></a></p>
                                            </div>
                                    </div>
                                    <div class= "box">
                                    <div class="half">
                                            <div class = "heading">
                                                    <h3>What We Know</h3>
                                            </div>
                                                    <table>';
// close mysql connection
mysql_close($link);
// connect to luna db
$link2 = mysql_connect($lunadbserver, $lunausername, $lunapassword);
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
    case "20":
        $lunadbase ='lac_art';
        break;
}
$checkfield = 'Work Record Id';
if (strlen($image_id)< 7 )
{
    $link_id = str_pad($image_id, 7, "0", STR_PAD_LEFT);
    $link_id = $link_id."c";
    $checkfield = 'Repro Link Id';
}
else
{
    $link_id = $image_id;
}
if (strpos($image_id, '-') > 0)
{
    $image_id_array = explode("-",$image_id);
    $link_id = $image_id_array[0]."c-".$image_id_array[2];
    $checkfield = 'Repro Link Id';
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
                                                            and i.displayname like 'Subject%'
                                                            and o.objectid =
                                                            (select o2.objectid
                                                            from
                                                            $lunadbase.DTVALUES v2,
                                                            $lunadbase.DTVALUETOOBJECT o2,
                                                            $lunadbase.IRFIELDS i2
                                                            where o2.valueid = v2.valueid
                                                            and v2.fieldid = i2.fieldid
                                                            and i2.displayname = '".$checkfield."'
                                                            and v2.valuetext = '".$link_id."'
                                                            );";
    $luna_result = mysql_query($luna_sql) or die( "A MySQL error has occurred.<br />Your Query: " . $luna_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
    $luna_count = mysql_numrows($luna_result);
    $j = 0;
    while ($j < $luna_count)
    {
        $value = mysql_result($luna_result, $j, 'valuetext');
        $field = mysql_result($luna_result, $j, 'displayname');
        $pos = strpos($field, "Repro");
        if ($pos !== FALSE)
        {
            $pos = 0;
        }
        else
        {
            echo'<tr>
                                                                    <td>'.$field.':</td><td>'.$value.'</td>
                                                                    </tr>';
        }
        $j++;
    }
    echo'
                                                            </table>
                                    <div class = "info">
                                                    <div class = "heading">
                                                    <br>
                                                    <br>
                                                            <h3>Crowdsourced, but not yet in system</h3>
                            </div>';
    // close mysql connection
    mysql_close($link2);
    $link = mysql_connect($dbserver, $username, $password);
    @mysql_select_db($database) or die( "Unable to select database");
    $moderated_sql = "select value_text,type from orders.CROWD c where image_id = '".$image_id."' and status in ('A', 'M');";
    $moderated_result = mysql_query($moderated_sql) or die( "A MySQL error has occurred.<br />Your Query: " . $moderated_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
    $moderated_count = mysql_numrows($moderated_result);
    $l = 0;
    while ($l < $moderated_count)
    {
        $value_text = mysql_result($moderated_result, $l, 'value_text');
        $subject_type = mysql_result($moderated_result, $l, 'type');
        $value_text_array[$l] = $value_text;
        echo '<table>
                <tr>
                    <td>'.$subject_type.':</td><td>'.$value_text.'</td>
                </tr>
              </table>';
        $l++;
    }
    echo '
                                                    </div>
                                        </div>
                                        </div>
                                        </div>
                                            <form action = "assessData.php" method = "post">
                                            <div class = "box"><table><tr><td colspan="8"><h2>Pending Information</h2></td></tr><tr><td colspan="8"><i>Select the radiobox if this information is ok.</i></td></tr>';
    $nbr_line = 0;
    // Loop to prepare the display of 100 product lines
    //echo '<br />Image id: ' . $image_id;
    $data_sql = "select c.id as crowd_id, u.first_name, u.surname, value_text, c.status, c.type, c.uun from orders.CROWD c, orders.USER u where c.uun = u.uun and c.status = 'P' and image_id = '".$image_id."';";
    //echo '<br />SQL: ' . $data_sql;
    $data_result = mysql_query($data_sql) or die( "A MySQL error has occurred.<br />Your Query: " . $data_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
    $data_count = mysql_numrows($data_result);
    $k = 0;
    $crowds = array();
    while ($k <  $data_count)
    {
        $l++;
        $crowd_id = mysql_result ($data_result, $k, 'crowd_id');
        $crowds[$k] = $crowd_id;
        $first_name = mysql_result($data_result, $k, 'first_name');
        $surname = mysql_result($data_result, $k, 'surname');
        $value_text = mysql_result($data_result, $k, 'value_text');
        $type = mysql_result($data_result, $k, 'type');
        $uun = mysql_result($data_result, $k, 'uun');
        $dupcheck = '';
        foreach ($value_text_array as $value)
        {
            if (strcasecmp($value, $value_text) == 0)
            {
                $dupcheck = 'checked = "checked"';
            }
        }
        $value_text_array[$l] = $value_text;
        //<input type="hidden" name = "crowd_id" value = '.$crowd_id[$k].'/>
        // echo '<tr><td>From '.$first_name .' '.$surname.'</td><td> Value for '.$type.': </td><td><input type = "text" name = "value['.$k.']" value = "'.$value_text.'"</td><td><input type="checkbox" name="moderated['.$k.']" value = "'.$crowd_id.'|'.$uun.'"/></td></tr>';
        echo '<tr><td></td><td>Value</td><td>Category</td><td>Object</td><td>Person</td><td>Place</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td></td><td>Reject</td><td>Ignore</td><td>Duplicate</td></tr>';
        echo '<tr><td>From '.$first_name .' '.$surname.'</td><td><input type = "text" name = "value['.$k.']" value = "'.$value_text.'"</td>
                    <td><input type="radio" name="moderated['.$k.']" value = "C|'.$crowd_id.'|'.$uun.'"/></td>
                    <td><input type="radio" name="moderated['.$k.']" value = "O|'.$crowd_id.'|'.$uun.'"/></td>
                    <td><input type="radio" name="moderated['.$k.']" value = "S|'.$crowd_id.'|'.$uun.'"/></td>
                    <td><input type="radio" name="moderated['.$k.']" value = "G|'.$crowd_id.'|'.$uun.'"/></td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td><input type="radio" name="moderated['.$k.']" value = "R|'.$crowd_id.'|'.$uun.'"/></td>
                    <td><input type="radio" name="moderated['.$k.']" value = "P|'.$crowd_id.'|'.$uun.'"/></td>
                    <td><input type="radio" name="moderated['.$k.']" value = "D|'.$crowd_id.'|'.$uun.'" '.$dupcheck.'/></td>
                    </tr>';
        $k++;
    }
    //$crowd_serialized = serialize($crowds);
    echo '
                </table></div>';
}
// close mysql connection
mysql_close($link);
?>

<div class = "footer">
    <table>
        <tr> <td colspan="2"><input type="submit" name = "save" style = "width:500px;" value="Submit tags and get new image"/></td></tr>
        <tr><td class="menutext" colspan="2">If you enter more than one person, object or place, please separate <br />with a semi-colon. Points only awarded for correctly spelled words.</td></tr>
    </table>
    </form>
    <?php }
    else {
        echo '

    <div class = "heading">
        <h3>THERE ARE '.$unmod_total.' IMAGES STILL TO MODERATE</h3>
        <hr/>
        <h3>All images have been moderated</h3>
        <h4>Either you are a very good moderator or it is a slow day!</h4>
    </div>
    <div class = "footer">';

        }
        ?>

        <hr/>
        <p><a href="gameMenu.php">Back To Menu</a></p>
    </div>
    <?php include 'footer.php';?>
</div>
</body>
</html>