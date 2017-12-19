<?php
session_start();
//var_dump($_SESSION);
include '../games/config/vars.php';


// connect to db
$error = '';
$link = mysqli_connect($dbserver, $username, $password, $database);
//@mysqli_select_db($database) ;#or die( "Unable to select database".$database);

?>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
    <meta name="viewport" content="user-scalable=no" />
    <title>Special Collections UV Manifest Work</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <link href='http://fonts.googleapis.com/css?family="Comic Sans MS"' rel='stylesheet' type='text/css'>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <?php

    echo $_SESSION['stylesheet'];

    ?>
    <meta name="author" content="Library Digital Development" />
    <meta name="description" content=
    "Edinburgh University Library Crowd Sourcing" />
    <meta name="distribution" content="global" />
    <meta name="resource-type" content="document" />
    <meta http-equiv="Content-Type" content="text/html; charset=us-ascii" />
    <style>
        html {
            font-size: 20px;
        }
        * {
            font-family: Arial;
            background-color: #333333;
            border-color: #333333;
        }
        h2 {
            margin-left: 20px;
            margin-right: 20px;
        }
        .menutext {
            color: white;
        }
        h1 {
            font-size: 100px;
            font-weight: bold;
            letter-spacing: normal;
            display: block;
        }
        span {
            letter-spacing: normal;

        }
        a:hover {
            text-decoration: none;
        }
        .heading {
            margin-bottom: 30px;
            max-height: 700px;
        }
        .heading-first {
            margin-bottom: 50px;
        }
        body {
            background-color: #333333;
        }
        div.container-fluid div.all {
            font-family: Arial;
        }
        div.central {
            background-color: #333333;
        }
        h1, h3, span{
            background-color:#33cccc;
        }

        td{
            padding:3px;
            vertical-align: middle;
        }
        .lightheading{
            width:220px;
            height:220px;
        }
        img{
            vertical-align:middle;
            text-align:center;
        }
        /* iPhone 6 in portrait  */
        @media only screen
        and (min-device-width : 375px)
        and (max-device-width : 667px)
        and (orientation : portrait) {
            html {
                padding-right: 1em;
                padding-left: 1em;
            }
            h2 {
                font-weight: bold;
                font-size: 2rem;
            }
            .menutext {
                font-size: 1.5rem;
            }
            input.btn {
                width: 30rem;
                height: 5rem;
                font-size: 3em;
            }
            input.form-control.form-inline {
                margin-bottom: 2rem;
                height: 4rem;
                font-size: 3em;
            }
            td.menu {
                box-sizing: border-box;
                padding-left: 2rem;
                padding-right: 2rem;
            }
            a {
                font-size: 1.3rem;
            }
            #footer a{
                color: ;
                font-size: 1.3rem;
                padding-bottom: 2rem;
            }
            #footer div.uoe-logo img{
                float: left;
                width: 5rem;
            }
            #footer div.luc-logo img{
                float: right;
                width: 4rem;
            }
        }

    </style>
    <script>
     //   $("#loading").ajaxStart(function () {
       //     $(this).show();
     //   });

    //    $("#loading").ajaxStop(function () {
      //      $(this).hide();
     //   });
    </script>
</head>

<body>
<?php include_once("./../analyticstracking.php") ?>
<div class="all container-fluid">
    <h1>Special Collections IIIF Prototype</h1>
    <!--<div id="loading" style="display:none;">
        Generating your file- please wait....
        <img src="ajax-loader.gif" alt="Loading" />
    </div>-->
<?php
echo '<p>HELOO</p>';
/**
 * Created by PhpStorm.
 * User: srenton1
 * Date: 13/11/2017
 * Time: 15:06
 */
$outfile = 'files/shelfreport.csv';
$file_handle_out = fopen('files/shelfreport.csv', "w") or die ("can't open loaded papers file");
$shelf_list_count_sql = "select distinct shelfmark, count(shelfmark) as imagecount from orders.IMAGE group by shelfmark order by shelfmark ; ";
$shelf_list_count_result = mysqli_query($link, $shelf_list_count_sql);
$shelf_list_count_count = mysqli_num_rows($shelf_list_count_result);
$j = 0;
fwrite($file_handle_out, "Shelfmark, No Images, Title, Date, Creator, Catalogue No\n");

while ($row = $shelf_list_count_result->fetch_assoc()) {

    $shelf_list[$j] = $row['shelfmark'];
    $shelf_list_image_count[$j] = $row['imagecount'];
    $specific_image_sql = "select jpeg_path from orders.IMAGE where shelfmark = '".$shelf_list[$j]."' limit 1;";
    $specific_image_result = mysqli_query($link, $specific_image_sql);
    $manifest_url = '';
    while ($imagerow = $specific_image_result->fetch_assoc()) {
        $image_url = $imagerow['jpeg_path'];
        $manifest_url = str_replace('detail/', 'iiif/m/', $image_url).'/manifest';
    }
    echo $manifest_url;
    $json = file_get_contents($manifest_url);
    $jobj = json_decode($json, true);
    $error = json_last_error();
    $jsonMD = $jobj['sequences'][0]['canvases'][0]['metadata'];
    $rights = '';
    $photographer = '';
    $photoline = '';
    foreach ($jsonMD as $jsonMDPair)
    {

        if ($jsonMDPair['label'] == 'Title')
        {
            $title = str_replace("<span>", "", $jsonMDPair['value']);
            $title = str_replace("</span>", "", $title);
        }
        if ($jsonMDPair['label'] == 'Date')
        {
            $date = str_replace("<span>", "", $jsonMDPair['value']);
            $date = str_replace("</span>", "", $date);
        }
        if ($jsonMDPair['label'] == 'Creator')
        {
            $creator = str_replace("<span>", "", $jsonMDPair['value']);
            $creator = str_replace("</span>", "", $creator);
        }
        if ($jsonMDPair['label'] == 'Catalogue Number')
        {
            $catalogue_no = str_replace("<span>", "", $jsonMDPair['value']);
            $catalogue_no = str_replace("</span>", "", $catalogue_no);
        }

    }

    fwrite($file_handle_out, '"'.$shelf_list[$j].'",'.$shelf_list_image_count[$j].',"'.$title.'",'.$date.',"'.$creator.'",'.$catalogue_no.','.substr($manifest_url, 47,8)."\n");
    $j++;
}
fclose($file_handle_out);
?>
<p><a href= "download.php?file=<?php echo $outfile;?>"><h4>Download File</h4></a></p>
    </div>
</body>
</html>

