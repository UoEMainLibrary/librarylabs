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

if (isset ($_POST['save'])) {
    $_POST['button'] = false;
    $image_id = $_POST['image_id'];
    $subject = trim(mysqli_real_escape_string($link, $_POST['subject']));

    $subject_len = strlen($subject);

    $subject_len = $subject_len - 1;
    if (substr($subject, $subject_len, 1) == ";") {
        $subject = substr($subject, 0, $subject_len);
    }

    if ($subject != '') {
        if (strpos($subject, ';') !== false) {
            $subject_array = explode(";", $subject);
            foreach ($subject_array as $subject_unit) {
                    $lines = file('naughtyWords.txt');
                    $found = false;
                    foreach ($lines as $line){
                        if (trim($subject_unit) == trim($line)) {
                            $found = true;
                            break;
                        }
                    }   
                if($found == true) { ?>
                <script type="text/javascript">
                    alert("Leevi doesn't speak naughty words :(");
                </script>
<?php
                    continue;
                }
                $subject_unit = ucwords(trim($subject_unit));
                $insert_sql = "insert into orders.CROWD (image_id, value_text, uun, status, game ) values ('$image_id', '$subject_unit', '$uun', 'M', '" . $_SESSION['game'] . "');";
                $insert_result = mysqli_query($link,$insert_sql) ;
            }
        } else {
            $lines = file('naughtyWords.txt');
            $found = false;
            foreach ($lines as $line){
                if (trim($subject) == trim($line)) {
                    $found = true;
                    break;
                }
            }
            if($found == true) { ?>
            <script type="text/javascript">
                alert("Leevi doesn't speak naughty words :(");
            </script>

<?php
            }
            else {
                $subject = ucwords(trim($subject));
                $insert_sql = "insert into orders.CROWD (image_id, value_text,uun, status, game ) values ('$image_id', '$subject', '$uun', 'M', '" . $_SESSION['game'] . "');";
                $insert_result = mysqli_query($link,$insert_sql) ;
            }
        }
        $_SESSION['images'] = $_SESSION['images'] + 1;
    }

    $_REQUEST['image_id'] = null;
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
            height: 800px;
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
        tr{
            padding:10px;
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

if ((($_SESSION['theme'] == 'art') or ($_SESSION['theme'] == 'roslin')) and $_SESSION['images'] >= 10)
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
             <hr>
          </div>';
}
else
{
    $imageprovcount = 1;
    if ($_REQUEST['image_id'] == null) {
        unset($_REQUEST['image_id']);
    }

    if (isset ($_REQUEST['image_id'])) {

        $image_id = $_REQUEST['image_id'];
        $sql = "
                            select
                            i.image_id,
                            i.collection,
                            i.jpeg_path
                            from
                            orders.IMAGE i
                            where image_id = '".$image_id."';
                            ";

        $result = mysqli_query($link,$sql) ;
        $imageprovcount = mysqli_num_rows($result);

    } else {



        if(!isset($_SESSION['images']))
        {
            $_SESSION['images'] = 0;
        }

        if ($_SESSION['theme'] == 'art' )
        {
                $rand_sql = "
                                    select
                                    i.image_id,
                                    i.collection,
                                    i.jpeg_path
				    from orders.IMAGE i
                                    where collection = 'UoEart~1~1'
                                    order by rand() limit 1
                                    ;
                                    ";

        }
        else if ($_SESSION['theme'] == 'roslin') {
            $rand_sql ="            
                                    select
                                    i.image_id,
                                    i.collection,
                                    i.jpeg_path
                                    from orders.IMAGE i
                                    where collection = 'UoEgal~6~6'
                                    order by rand() limit 1
                                    ;
                                    ";           
        }
        else
        {
            /*  $rand_sql = "select
                          i.image_id,
                          i.collection,
	                  i.jpeg_path
                          from orders.IMAGE i
                          where collection in
	                     ('UoEcar~3~3', 'UoEcar~4~4', 'UoEsha~4~4')
                          order by rand() limit 1
                          ;
                          ";*/
/*            $rand_sql = "
                                    select
                                    i.image_id,
                                    i.collection,
                                    i.jpeg_path
                                    from orders.CROWDSOURCE_IMAGE_TMP i
                                    join (select image_id from orders.CROWDSOURCE_IMAGE_TMP
                                    order by rand() limit 1)
                                    as a on i.image_id = a.image_id
                                    ;
                                    ";*/
            $rand_sql = "
                                    select
                                    i.image_id,
                                    i.collection,
                                    i.jpeg_path
                                    from orders.CLASSIC_CROWD_IMAGES i
                                    join (select image_id from orders.CLASSIC_CROWD_IMAGES
                                    order by rand() limit 1)
                                    as a on i.image_id = a.image_id
                                    ;
                                    ";
        }

        $result = mysqli_query($link,$rand_sql) ;
        $count = mysqli_num_rows($result);

        $images = $_SESSION['images'];
        $_SESSION['images'] = $images++;
    }


    if (isset ($_POST['buttonwithimage'])) {

        $withimagesql = "
                                select
                                i.image_id,
                                i.collection,
                                i.jpeg_path
                                from
                                orders.IMAGE i
                                where i.image_id = " . $_POST['image_id'] . "
                                ;
                                ";

        $result = mysqli_query($link,$withimagesql) ;
        $withimagecount = mysqli_num_rows($result);
    }

    if ($imageprovcount == 1)
    {
        $i = 0;

        while ($row = $result->fetch_assoc()) {
            $image_id = $row['image_id'];
            $collection  = $row['collection'];
            $jpeg_path = $row['jpeg_path'];
        }
        $iiifstatic = str_replace('detail','iiif', $jpeg_path);
        $iiifstatic = $iiifstatic.'/full/full/0/default.jpg';
        $size = getimagesize($iifstatic);

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

    $sql = "select count(*) from CLASSIC_CROWD_IMAGES;";
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
    <hr/>
    <?php } ?>
    </div>
</div>

        <?php
        $imageCounter = 0;
        $tileSource = str_replace('full/full/0/default.jpg', 'info.json', $iiifstatic);
        $json =  file_get_contents($tileSource);
        $jobj = json_decode($json, true);
        $error = json_last_error();
        $jsonmetadata[$imageCounter] = $jobj['metadata'];
        // metadata to be displayed on the page

        foreach ($jsonmetadata[$imageCounter] as $item) {
            if($item['label'] == 'Title') {
                $jsontitle[$imageCounter] = $item['value'];
                break;
            }
        }
        $jsoncreator[$imageCounter] = "";
        foreach ($jsonmetadata[$imageCounter] as $item) {
            if($item['label'] == 'Creator') {
                $jsoncreator[$imageCounter] = $jsoncreator[$imageCounter] . "\n \n" . $item['value'] . ". ";
            }
        }
        foreach ($jsonmetadata[$imageCounter] as $item) {
            if($item['label'] == 'Production Place') {
                $jsonproductionplace[$imageCounter] = $item['value'];
                break;
            }
        }
        foreach ($jsonmetadata[$imageCounter] as $item) {
            if($item['label'] == 'Subject Place') {
                $jsonsubjectplace[$imageCounter] = $item['value'];
                break;
            }
        }
        foreach ($jsonmetadata[$imageCounter] as $item) {
            if ($item['label'] == 'Subject Category') {
                $jsonsubjectcategory[$imageCounter] = $item['value'];
                break;
            }
        }
        $none = 1;
        foreach ($jsonmetadata[$imageCounter] as $item) {
            if ($item['label'] == 'Description') {
                $jsondescription[$imageCounter] = $item['value'];
                $none = 0;
                break;
            }
        }
        if ($none == 1) {
            $jsondescription[$imageCounter] = "None.";
        }


        ?>
        
    <?php
            echo '<div class="sourcebox">
                            <form action = "gameCrowdSourcing.php" method = "post">
                                    <table style = "text-align: center;">
                                        <tr>
                                            <td class="menutext" colspan="2">Enter tags here- e.g. flag; tiger; hat</td>
                                        </tr>
                                        <tr>
                                            <td><input class="form-control form-inline" type = "text" name = "subject" style = "width:500px; margin: auto;"></input></td>
                                        </tr>
                                        <tr>
                                            <input type = "hidden" name = "image_id" value = "' . $image_id . '">
                                        </tr>
                                        <tr> <td colspan="2"><input class="btn btn-primary active" type="submit" name = "save" style = "width:500px; margin-bottom: 20px;" value="Submit tags and get new image"/></td></tr>
                                        <tr><td class="menutext" colspan="2">If you enter more than one person, object or place, please separate <br />with a semi-colon. Points only awarded for correctly spelled words.</td></tr>
                                    </table>
                    </form>
                </div>';
        }
   // }
    else
    {
        echo '
        <div class="sourcebox">
        <p>Sorry, that image is not in the pool. You cannot add metadata to it!</p>
        <p> <a href="gameCrowdSourcing.php">Tag some others?</a></p>
        </div>';
    }
}
?>
<!-- background information -->
        <div class="information">
        <div>
            <span style="margin: auto; margin-top: 10px;">Background Information</span><br>
            <?php
                if(isset($jsontitle[$imageCounter])) {
                    echo "Title:  " . $jsontitle[$imageCounter];
                }
            ?>
        </div>
            <div>
            <?php
                if(isset($jsoncreator[$imageCounter])) {
                    echo "Creator:  " . $jsoncreator[$imageCounter];
                }
            ?>
        </div>
        <div>
            <?php
            if(isset($jsonsubjectplace[$imageCounter])) {
                echo "Subject Place:  " . $jsonsubjectplace[$imageCounter];
            }
            ?>
        </div>
        <div>
            <?php
            if(isset($jsonsubjectcategory[$imageCounter])) {
                echo "Subject Category:  " . $jsonsubjectcategory[$imageCounter];
            }
            ?>

        </div>
            <div class="detaileddescription">
            <span style="color: darkgrey">Click the arrow for detailed description</span>
            <a id="arrow" class="caret" role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
            </a>
            <div class="collapse" id="collapseExample">
                <div class="description">
                    <?php
                        if(isset($jsonsubjectcategory[$imageCounter])) {
                            echo "Description:  " . $jsondescription[$imageCounter];
                        }
                    ?>
                </div>
            </div>
            </div>
    <?php 
        if($_SESSION['theme'] != 'roslin') {
    ?>
    <hr/>
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
