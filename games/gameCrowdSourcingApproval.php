<?php

include 'config/vars.php';
session_start();
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
            $get_image_result =mysqli_query($link, $get_image_sql); #or die( "A MySQL error has occurred.<br />Your Query: " . $get_image_result . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
            while ($row = $get_image_result->fetch_assoc()) {
                $image_id = $row['image_id'];
            }
            // $image_id = mysql_result($get_image_result, 0, 'image_id');

            $vote_insert_sql = "insert into orders.VOTES (crowd_id, submitter, voter, image_id, quality, game ) values (".$crowd_id.", '".$uun."', '".$_SESSION['uun']."','".$image_id."',".$action.",'". $_SESSION["game"]. "');";
            $vote_insert_result=mysqli_query($link, $vote_insert_sql); #or die( "A MySQL error has occurred.<br />Your Query: " . $vote_insert_result . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
            //echo 'SQL'.$vote_insert_sql;

            $vote_sql = "select sum(quality) as votes from orders.VOTES where crowd_id = ".$crowd_id.";";
            $vote_result=mysqli_query($link, $vote_sql); #or die( "A MySQL error has occurred.<br />Your Query: " . $vote_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
            while ($row = $vote_result->fetch_assoc()) {
                $votes = $row['votes'];
            }
            // $votes = mysql_result($vote_result,0, 'votes');

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
            $result = mysqli_query($link, $sql); #or die( "A MySQL error has occurred.<br />Your Query: " . $sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());

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
    <meta name="viewport" content="user-scalable=no" />
    <title>Metadata Games</title>
    <?php echo $_SESSION['stylesheet']; ?>
    <meta name="author" content="Library Digital Development" />
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
        table{
            vertical-align:middle;
        }
        tbody{
            vertical-align:middle;
            text-align: center;
        }
        tr{
            padding:10px;
        }
	td {
	    padding: 4px 20px !important;
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
            td, tr {
                color: #1aa5c7;
                font-size: 14px;
                padding:15px !important ;
            }
            table {
                margin: auto;
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
    <?php 
        if($_SESSION['theme'] != 'roslin') {
    ?>
    <hr/>
    <?php } ?>
</div>
<!--heading-->

<?php
$mpointssql = "select count(*) as mtotal from CROWD where uun = '" . $_SESSION['uun'] . "' and status = 'M' and game = '" . $_SESSION["game"] . "' and date(date_created) = CURDATE() ;";
$mpointsresult = mysqli_query($link,$mpointssql) ;
while ($row = $mpointsresult->fetch_assoc()) {
    $mpoints = $row['mtotal'];
}

$vpointssql = "select count(*) as vtotal from VOTES where voter = '" . $_SESSION['uun'] . "' and game = '" . $_SESSION["game"] . "' and date(date_created) = CURDATE() ;";
$vpointsresult = mysqli_query($link,$vpointssql) ;
while ($row = $vpointsresult->fetch_assoc()) {
    $vpoints = $row['vtotal'];
}

$apointssql = "select count(*) as atotal from CROWD where uun = '" . $_SESSION['uun'] . "' and status = 'A' and game = '" . $_SESSION["game"] . "' and date(date_created) = CURDATE() ;";
$apointsresult = mysqli_query($link,$apointssql) ;
while ($row = $apointsresult->fetch_assoc()) {
    $apoints = $row['atotal'];
}

$ppointssql = "select count(*) as ptotal from CROWD where uun = '" . $_SESSION['uun'] . "' and status = 'P' and game = '" . $_SESSION["game"] . "' and date(date_created) = CURDATE() ;";
$ppointsresult = mysqli_query($link,$ppointssql) ;
while ($row = $ppointsresult->fetch_assoc()) {
    $ppoints = $row['ptotal'];
}

$upointssql = "select sum(quality) as utotal from VOTES where submitter = '" . $_SESSION['uun'] . "' and game = '" . $_SESSION["game"] . "' and date(date_created) = CURDATE() ;";
$upointsresult = mysqli_query($link,$upointssql) ;
while ($row = $upointsresult->fetch_assoc()) {
    $upoints = $row['utotal'];
}


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

if(!isset($_SESSION['vimages']))
{
    $_SESSION['vimages'] = 0;
}

if (($_SESSION['theme'] == 'art'|| $_SESSION['theme'] == 'roslin') and $_SESSION['vimages'] >= 10)
{
    echo '<table style = "text-align: center;">
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
    echo '<hr />';

    if ($_SESSION['theme'] == 'art')
    {
        $rand_sql = "
                                select
                                i.image_id,
                                i.collection,
                                i.jpeg_path
                                from
                                orders.IMAGE i
                                join (select x.image_id from orders.IMAGE x, orders.CROWD y where x.image_id = y.image_id and x.collection = 'UoEart~1~1' and y.status = 'M' and y.uun <> '".$_SESSION['uun']."' and x.image_id not in (select v.image_id from orders.VOTES v where v.voter = '".$_SESSION['uun']."') order by rand() limit 1)
                                as a on i.image_id = a.image_id
                                ;
                                ";

    }
    if ($_SESSION['theme'] == 'roslin')
    {
        $rand_sql = "
                                select
                                i.image_id,
                                i.collection,
                                i.jpeg_path
                                from
                                orders.IMAGE i
                                join (select x.image_id from orders.IMAGE x, orders.CROWD y where x.image_id = y.image_id and x.collection = 'UoEgal~6~6' and y.status = 'M' and y.uun <> '".$_SESSION['uun']."' and x.image_id not in (select v.image_id from orders.VOTES v where v.voter = '".$_SESSION['uun']."') order by rand() limit 1)
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
                                i.jpeg_path
                                from
                                orders.IMAGE i
                                join (select x.image_id from orders.IMAGE x, orders.CROWD y where x.image_id = y.image_id and y.status = 'M' and y.uun <> '".$_SESSION['uun']."' order by rand() limit 1)
                                as a on i.image_id = a.image_id
                                ;
                                ";
    }

    $result=mysqli_query($link, $rand_sql); #or die( "A MySQL error has occurred.<br />Your Query: " . $rand_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
    $count = mysqli_num_rows($result);
    // $count = mysql_numrows($result);


    if ($count == 0)
    {
        echo '<div class = "sourcebox">
                        <p>No items to show!</p>
                        </div>';
    }
    else
    {
        while ($row = $result->fetch_assoc()) {
            $image_id = $row['image_id'];
            $collection  = $row['collection'];
            $jpeg_path = $row['jpeg_path'];
        }
        $iiifstatic = str_replace('detail','iiif',$jpeg_path);
        $iiifstatic = $iiifstatic.'/full/full/0/default.jpg';
        $size = getimagesize( $iiifstatic);

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
                            </div>

                            <div class = "image">
                            ';
/*
        if (strpos($image_id, '-') == false) {
            $urlrecordid = ltrim($image_id, '0');
        } else {
            $urlrecordid = $image_id;
        }


        $urlsql = "select recordid, objectid, imageid, institutionid, collectionid
                   from OBJECTIMAGE
                   where recordid = $urlrecordid; ";

        $urlresult = mysqli_query($link,$urlsql) ;
        $count = mysqli_num_rows($urlresult);

        while ($row = $urlresult->fetch_assoc()) {
            $urlobjectid = $row['objectid'];
            $urlimageid = $row['imageid'];
            $urlinstid = $row['institutionid'];
            $urlcollid = $row['collectionid'];
        }
*/
?>
<!--iiif test-->
<div>
         <?php
echo '<!-- '.$iiifstatic.'-->';
            $imageCounter = 0;
            $tileSource = str_replace('full/full/0/default.jpg', 'info.json', $iiifstatic);
            $json =  file_get_contents($tileSource);
            $jobj = json_decode($json, true);
            $error = json_last_error();
            $jsoncontext[$imageCounter] = $jobj['@context'];
            $jsonid[$imageCounter] = $jobj['@id'];
            $jsonheight[$imageCounter] = $jobj['height'];
            $jsonwidth[$imageCounter] = $jobj['width'];
            $jsonprotocol[$imageCounter] = $jobj['protocol'];
            $jsontiles[$imageCounter]= $jobj['tiles'];
            $jsonprofile[$imageCounter] = $jobj['profile'];
            list($width, $height) = getimagesize($iiifstatic);
            $portrait = true;
            if ($width > $height)
            {
                $jsontilesize[$imageCounter] = $jsontiles[$imageCounter][0]['width'];
                $portrait = false;
            }
            else
            {
                $jsontilesize[$imageCounter] = $jsontiles[$imageCounter][0]['height'];
            }

       // $tileSource = 'http://images.is.ed.ac.uk/luna/servlet/iiif/'.$urlinstid.'~'.$urlcollid.'~'.$urlcollid.'~'.$urlobjectid.'~'.$urlimageid.'/info.json';
    echo '</div> ';
        $json = file_get_contents($tileSource);
    $jobj = json_decode($json, true);
        $error = json_last_error();
        $jsoncontext = $jobj['@context'];
        $jsonid = $jobj['@id'];
        $jsonheight = $jobj['height'];
        $jsonwidth = $jobj['width'];
        $jsonprotocol = $jobj['protocol'];
        $jsontiles = $jobj['tiles'];
        $jsonprofile = $jobj['profile'];

    $sql = "select count(*) from IMAGE;";
    $totalImage = mysqli_query($link,$sql);
    $sql = "select count(distinct image_id) from CROWD;";
    $taggedNumber = mysqli_query($link,$sql);
        while ($row = $totalImage->fetch_assoc()) {
            foreach ($row as $value) {
                $imageNum = $value;
            }
        }
        while ($row = $taggedNumber->fetch_assoc()) {
            foreach ($row as $value) {
                $taggedNum = $value;
            }
        }
        $percentage = ($taggedNum/$imageNum) * 100;
?>
    <div class="progressTitle">
        <?php
            echo "Leevi has learned " . $taggedNum . " words, but there are " . $imageNum . " in total. Help him make more progress and be smarter!"
        ?>
    </div>

    <div class="progress">
      <div id="progressBar" class="progress-bar progress-bar-success progress-bar-striped" role="progressbar"
      aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:40%">
        40% Complete (success)
      </div>
    <script type="text/javascript">
        var x = "<?php echo $percentage; ?>" + "%";
        var y = "<?php echo $taggedNum; ?>" + "/" + "<?php echo $imageNum; ?>";
        // document.getElementById("progressBar").style.width = Number("<?php echo ($taggedNum/$imageNum); ?>");
        document.getElementById("progressBar").style.width = x;
        document.getElementById("progressBar").innerHTML = y;
    </script>
    </div>


    <div class="col-lg-12 main-image">

<!--         <?php echo $tileSource; ?> -->
        <div id='toolbarDiv'>
            <div class='toolbarItem' id='zoom-in'></div><div class='toolbarItem' id='zoom-out'></div><div class='toolbarItem' id='home'></div><div class='toolbarItem' id='full-page'></div>
        </div>

<!--         <?php  $divCounter = 0;
        $freshIn = true;
        //            while ($divCounter < $imageCounter)
        //            {
        ?> -->
        <div id="openseadragon1" class="image-toggle" >
        <script src="../assets/openseadragon/openseadragon.min.js"></script>
        <script type="text/javascript">
            // if(<?php //echo $_SESSION['theme'] ?> == 'roslin') {
            //     var x = document.getElementById('openseadragon1');
            //     x.style.height = "320px";
            // }
            <?php
                if($_SESSION['theme']  == 'roslin') { 
            ?>
            var x = document.getElementById('openseadragon1');
                x.style.height = "320px";
                x.style.width = "320px";
                x.style.margin = "auto";
            for (var i = 0; i < 4 ; i++) {
                var y = document.getElementsByClassName('toolbarItem')[i];
                y.style.float = "left";
            }

            <?php } ?>
        </script>
        <script type="text/javascript">
        OpenSeadragon({
        id:                 "openseadragon1",
        prefixUrl:          "./images/buttons/",
        toolbar:            toolbarDiv,
        showNavigator:      true,
        zoomPerScroll:      1.5,
        preserveViewport:   true,
        autoHideControls:   false,
        visibilityRatio:    1,
        minZoomLevel:       1,
        defaultZoomLevel:   1,
        sequenceMode:       true,
        zoomInButton:   "zoom-in",
        zoomOutButton:  "zoom-out",
        homeButton:     "home",
        fullPageButton: "full-page",
        tileSources:  [{
                             "@context": "<?php echo $jsoncontext ?>",
                             "@id": "<?php echo $jsonid ?>",
                             "height": <?php echo $jsonheight ?>,
                             "width": <?php echo $jsonwidth ?>,
                             "profile": ["http://iiif.io/api/image/2/level2.json",
                              {
                                    "formats": ["gif", "pdf"]
                              }
                               ],
                             "protocol": "<?php echo $jsonprotocol ?>",
                             "tiles": [{
                             "scaleFactors": [1, 2, 8, 16, 32],
                             "width": 512
                        }]
    }]
          });
         </script>
</div>

        <?php
        $freshIn = false;
        //            }
        ?>
    <?php 
        if($_SESSION['theme'] != 'roslin') {
    ?>
    <!--<hr/>-->
    <?php } ?>
    </div>
<!--</div>-->

    <?php

           echo '
                        </div>

                        <div class = "info">

                            <div class = "heading">
                                <h3>Pending Information: Image '.$image_id.'</h3>
                            </div>

                        <form action = "gameCrowdSourcingApproval.php" method = "post">
                        <div class="box">
                        <p class = "menutext">Select the relevant radio button.</p>
                        <table>
                         <tr>
                         <td class="tdlabel">Cataloguer</td>
                         <!--<td class="typelabel">Type</td>-->
                         <td class="tdlabel">Value</td>
                         <td class = "radiotd">Good</td>
                        <!-- <td class = "radiotd">?</td>-->
                         <td class = "radiotd">Bad</td>
                         </tr>
                         <tr>
                         <td class="tdlabel">----------</td>
                         <!--<td class="typelabel">----</td>-->
                         <td class="tdlabel">-----</td>
                         <td class = "radiotd">---</td>
                        <!-- <td class = "radiotd">---</td>-->
                         <td class = "radiotd">---</td>
                         </tr>';

                $data_sql = "select c.id as crowd_id, u.first_name, u.surname, value_text, c.status, c.type, c.uun from orders.CROWD c, orders.USER u where c.uun = u.uun and c.status = 'M' and image_id = '".$image_id."';";
                $data_result = mysqli_query($link, $data_sql); #or die( "A MySQL error has occurred.<br />Your Query: " . $data_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
                $data_count = mysqli_num_rows($data_result);
                // $data_count = mysql_numrows($data_result);

                $k = 0;
                $crowds = array();
                while ($k <  $data_count)
                {
                        while ($row = $data_result->fetch_assoc()) {
                            // $image_id = $row['image_id'];
                            // $collection  = $row['collection'];
                            // $shelfmark = $row['shelfmark'];
                            // $title = $row['title'];
                            // $author = $row['author'];
                            // $page_no = $row['page_no'];
                            // $jpeg_path = $row['jpeg_path'];
                            // $publication_status = $row['publication_status'];
                            //var_dump($row);
                            //var_dump($row['objectid']) ;
                            $crowd_id = $row['crowd_id'];
                            $crowds[$k] = $crowd_id;
                            $first_name = $row['first_name'];
                            $surname = $row['surname'];
                            $value_text = $row['value_text'];
                            $type = $row['type'];
                            $uun = $row['uun'];

                            // $crowd_id = mysql_result ($data_result, $k, 'crowd_id');
                            // $crowds[$k] = $crowd_id;

                            // $first_name = mysql_result($data_result, $k, 'first_name');
                            // $surname = mysql_result($data_result, $k, 'surname');
                            // $value_text = mysql_result($data_result, $k, 'value_text');
                            // $type = mysql_result($data_result, $k, 'type');
                            // $uun = mysql_result($data_result, $k, 'uun');
                            //<input type="hidden" name = "crowd_id" value = '.$crowd_id[$k].'/>

                            // echo '<tr><td>From '.$first_name .' '.$surname.'</td><td> Value for '.$type.': </td><td><input type = "text" name = "value['.$k.']" value = "'.$value_text.'"</td><td><input type="checkbox" name="moderated['.$k.']" value = "'.$crowd_id.'|'.$uun.'"/></td></tr>';

                            echo '<tr>
                    <td class="tdlabel">' . $first_name . ' ' . $surname . '</td>
                    <!--<td class="typelabel"></td>-->
                    <td class="tdlabel">' . strtoupper($value_text) . '</td>
                    <td class="radiotd"><input type="radio" name="moderated[' . $k . ']" value = "1|' . $crowd_id . '|' . $uun . '"/></td>
                    <!--<td class="radiotd"><input type="radio" name="moderated[' . $k . ']" value = "O|' . $crowd_id . '|' . $uun . '"/></td>-->
                    <td class="radiotd"><input type="radio" name="moderated[' . $k . ']" value = "-1|' . $crowd_id . '|' . $uun . '"/></td>
                    </tr>';
                            $k++;

                        }

                }

                //$crowd_serialized = serialize($crowds);

                echo '
                </table>

                </div><br><input type="submit" name = "save" style = "width:520px;" value="Submit votes and get new image" />
                </form>';

             }
        }

    // close mysql connection
    mysqli_close($link);

			?>



    <?php 
        if($_SESSION['theme'] != 'roslin') {
    ?>
    <!--<hr/>-->
    <?php } ?>
        </div>

    </div>


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
