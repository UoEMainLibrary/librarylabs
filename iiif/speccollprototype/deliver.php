<?php
//session_start();
//var_dump($_SESSION);
include '../../games/config/vars.php';
// connect to db
$error = '';
$link = new mysqli($dbserver, $username, $password, $database);
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
            background-color: #ffffff;
            border-color: #ffffff;
        }
        h2 {
            margin-left: 20px;
            margin-right: 20px;
        }
        h3 {
            margin-left: 20px;
            margin-right: 20px;
        }
        .menutext {
            color: white;
        }
        p{
            margin-left: 20px;
        }
        a{
            margin-left: 20px;
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
        .uvlogo
        { margin-left:25px; display: inline-block; width: 50px; height: 50px;  background: url(../../images/uv.png) no-repeat 0px 0px;}
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
            background-color: #ffffff;
        }
        div.container-fluid div.all {
            font-family: Arial;
        }
        div.central {
            background-color: #ffffff;
        }
        h1, h2, textarea, input, h3, span{
            background-color:#ffffff;
        }
        textarea, input
        {border-width: 1px;
         border-color: #333329;
         margin-left : 25px;}

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
</head>

<body>
<?php include_once("./../analyticstracking.php")?>

<?php
    $image_block = '';
    if ($_POST['imageblock'] !== '')
    {
        $image_block = $_POST['imageblock'];
    }

    if ($_POST['shelfmark_for_check'] !== '')
    {
        $shelfmark_for_check = $_POST['shelfmark_for_check'];
    }

    if ($_POST['man_name'] !== '')
    {
        $man_name = $_POST['man_name'];
    }
    else
    {
        $man_name = 'User generated manifest';
    }


?>

    <div class="all container-fluid">
    <h1>IIIF Manifest Builder BETA</h1>


<?php
    if (isset($image_block)) {
        $detail= 'user selection';
        $image_array = preg_split('/\r\n|[\r\n]/', $image_block);
        $manifestlist = array();
        $imagecount = 0;
    }

    if (isset($shelfmark_for_check)) {
        $detail = $shelfmark_for_check;
        $imagesql = "select image_id from IMAGE where shelfmark ='" . $shelfmark_for_check . "';";
        $imageresult = mysqli_query($link, $imagesql);
        $options="";
        $i = 0;
        while ($row = $imageresult->fetch_assoc()) {
            $image_array[$i] = $row['image_id'];
            $i++;

        }
    }

    if (isset($image_array))
    {

        foreach ($image_array as $image)
        {
            $get_ind_man_result = '';
            $row = '';
            $outpath ='';
            $get_man_sql = "select jpeg_path from orders.IMAGE where image_id = '" . $image. "' order by rand() limit 1;";

            if ($get_man_result = $link->query($get_man_sql))
            {

                while ( $row = $get_man_result->fetch_assoc())
                {
                    $outpath = $row['jpeg_path'];
                }
                $get_man_result->free;
            }
            if ($outpath != '') {
                $manifest = str_replace('detail', 'iiif/m', $outpath) . '/manifest';
                $manifestlist[] = $manifest;
                $imagecount++;
            }

        }

        if ($imagecount > 1){
            $suffix = 's';
        }
        else{
            $suffix = '';
        }
        echo '<h2>Here comes your manifest ('.$detail. ') - '. $imagecount. ' digitised image'.$suffix.'  </h2>
        <h3>This manifest is not in a persistent location- it is currently only intended for experimentation/teaching.</h3>';



        $j = 0;
        $manifestarray = array();
        $hasalma = false;
        while ($j < $imagecount)
        {
            $jobj = '';
            $json = file_get_contents($manifestlist[$j]);
            $jobj = json_decode($json, true);
            $error = json_last_error();
            if ($jobj !== '')
            {
                if ($j == 0) {
                    $attribution = $jobj['attribution'];
                    $context = $jobj['@context'];
                    $related = str_replace('iiif/m', 'detail', $manifestlist[$j]);
                    $related = str_replace('/manifest', '', $related);
                    $rand_no = bin2hex(openssl_random_pseudo_bytes(12));
                    foreach ($jobj['sequences'][0]['canvases'][0]['metadata'] as $metadatapair) {
                        $label = $metadatapair['label'];
                        $value = $metadatapair['value'];

                        if (strpos($value, "discovered") !== false) {
                            $UOE = strpos($value, "44UOE_");
                            $hasalma = true;
                            $almaurl = "https://discovered.ed.ac.uk/primo-explore/sourceRecord?vid=44UOE_VU2&docId=" . substr($value, $UOE, 27);

                        }

                    }
                }

                $manifestarray[] = $jobj['sequences'][0]['canvases'][0];

            }
            $j++;
        }


        $manifest_file =array
                 ('label' => $man_name,
                'attribution'=> $attribution,
                'logo' =>  "https://www.eemec.med.ed.ac.uk/img/logo-white.png" ,
                '@id'=> "https://librarylabs.ed.ac.uk/iiif/speccollprototype/manifests/user/" .$rand_no. ".json",
                'related' => $related,
                'sequences' => array(array("@type"=>"sc:Sequence", "viewingHint"=>"individual", "canvases"=>$manifestarray)),
                "@type"=>"sc:Manifest",
                'seeAlso'=>$almaurl,
                "@context"=>$context
                );
        $json_out = json_encode($manifest_file);
        $mandir = 'manifests/user/';
        $out_file = $mandir.$rand_no.'.json';
        $file_handle_out = fopen($out_file, "w")or die("<p>Sorry. I can't open the manifest file.</p>");

        fwrite($file_handle_out, $json_out);

        unset($_POST["imageblock"]);
        $manifestpath = "https://librarylabs.ed.ac.uk/iiif/speccollprototype/".$mandir.$rand_no.".json";

?>
            <div>
                <input type="text" size = "100" value = "<?php echo $manifestpath;?>"/>
                <br><br>
                <span class ="json-link-item"><a href="https://librarylabs.ed.ac.uk/iiif/uv/?manifest=<?php echo $manifestpath;?>" target="_blank" class="uvlogo" title="View in UV"></a>The viewer below is Mirador. Click here to see it in UV.</span>
                <br><br>
                <a href="manifestbuild.php">Create another manifest</a>
                <?php
                if ($hasalma == true)
                {
                    echo '<p>This item has Alma metadata. See below the viewer!</p>';
                }
                ?>
                <br><br>
                <table width = "1300">
                    <tr>
                        <?php
                        if ($hasalma == true)
                        {
                            $viewlink = '
                             <tr>
                                <td>
                                    <iframe src="https://librarylabs.ed.ac.uk/iiif/mirador/?manifest='.$manifestpath.'" width = "1300" height = "800" allowfullscren = "true"></iframe>
                                </td>
                             </tr>
                             <tr>
                                <td>
                                    <iframe src="'.$almaurl.'"width = "1300" height = "300" ></iframe>
                                </td>
                             </tr>';
                        }
                        else
                        {
                            $viewlink = '
                                <td>
                                    <iframe src="https://librarylabs.ed.ac.uk/iiif/mirador/?manifest='.$manifestpath.'" width = "1300" height = "800" allowfullscren = "true"></iframe>
                                </td>';

                        }

                        echo $viewlink;
                        ?>
                    </tr>
                </table>
            </div>

            <?php
            }

            ?>
</body>
</html>