<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>LDD Utilities Menu</title>

        <!-- Bootstrap -->
        <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/pure.css">
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
<body>
    <div = "header">
    <br>
    <img src="/home/lib/lacddt/images/lddutilities.jpg">
    <h1>LUNA OAI Loader</h1>

    <?php

        //Scott Renton, September 2017
        //Harvest LUNA OAI and insert new rows into the metadata games database.

        include '/home/lib/lacddt/librarylabs/games/config/vars.php';
        ini_set('max_execution_time', 5000);
        $error = '';

        //This URL is the inspirehep search- can run this in a browser to check
        $baseurl = 'https://images.is.ed.ac.uk/luna/servlet/oai?verb=ListRecords&metadataPrefix=oai_dc';
        $directory = '/home/lib/lacddt/librarylabs/games/files/';
        $logfile = $directory."lunaoai.log";
        $file_handle_out = fopen($logfile, "a+")or die("<p>Sorry. I can't open the log file.</p>");
        $link = mysqli_connect($dbserver, $username, $password, $database);
        @mysqli_select_db($database) ;

        $collselectsql = "select id from orders.COLLECTION;";
        $collselectresult = mysqli_query($link,$collselectsql) ;#or die("A MySQL error has occurred.<br />Your Query: " . $upointssql . "<br /> Error: (" . mysqli_errno() . ") " . mysqli_error());
        while ($row = $collselectresult->fetch_assoc())
        {
            $collection = $row['id'];
            echo $collection."<br>";
            $resToken = '';
            $tokenExists = 'true';
            $seturl = $baseurl."&set=".$collection;
            $urlarray = [];
            $reproarray = [];
            $shelfarray = [];
            $j = 0;

            while ($tokenExists !== 'false')
            {
                if (isset($resToken) and $resToken !== "")
                {
                    $url = $seturl . "&resumptionToken=" . $resToken;
                }
                else
                {
                    $url = $seturl;
                }
                echo 'URL'.$url;
                $curl = curl_init();
                $fp = fopen($directory . "curl.xml", "w");
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_FILE, $fp);
                curl_setopt($curl, CURLOPT_HEADER, 0);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                $response = curl_exec($curl);
                //var_dump($response);
                $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

                if ($httpCode == 404)
                {
                    touch($directory . "cache/404_err.txt");
                }
                else
                {
                    fwrite($fp, $response);
                }

                curl_close($curl);
                fclose($fp);

                chmod($directory."curl.xml", 0777);

                //Load the captured curl response into XML
                $xml_file = $directory . "curl.xml";
                $xml = simplexml_load_file($xml_file);

                if ($xml == FALSE)
                {
                    echo "Failed loading XML\n";

                    foreach (libxml_get_errors() as $error)
                    {
                        echo "\t", $error->message;
                    }
                }

                $error = '';

                echo $j . "<br>";
                $n = 0;

                foreach ($xml->children() as $object)
                {
                    foreach ($object->resumptionToken as $resToken)
                    {
                        if (empty ($resToken) or $resToken =='' or $resToken == null)
                        {
                            $tokenExists = 'false';
                        }
                        else
                        {
                            echo "<br>TOKEN" . $resToken . "<br>";
                        }
                    }

                    foreach ($object->record as $rNode)
                    {
                        foreach ($rNode->metadata->children('oai_dc', 1)->dc->children('dc', 1) as $dc)
                        {
                            $lunapoint = strpos($dc, "luna/servlet");
                            $mediapoint = strpos($dc, "MediaManager");

                            if ($lunapoint > 0)
                            {
                                $urlarray[$j] = $dc;
                            } else
                                if ($mediapoint > 0)
                                {
                                    $slashpoint = strrpos($dc, "/");
                                    $dc = substr($dc, $slashpoint + 1, 20);
                                    $reproarray[$j] = $dc;
                                }
                                else
                                {
                                    $shelfarray[$j] = $dc;
                                }
                        }
                        $j++;
                    }
                }
            }


            for ($i =0;$i<$j; $i++)
            {
                fwrite($file_handle_out, $reproarray[$i]."\n");
                $image_id = str_replace('.jpg','', $reproarray[$i]);
                $checksql = "select jpeg_path from orders.IMAGE where image_id= '".$image_id."';";
                $checkresult = mysqli_query($link,$checksql) ;#or die("A MySQL error has occurred.<br />Your Query: " . $upointssql . "<br /> Error: (" . mysqli_errno() . ") " . mysqli_error());
                $rows = mysqli_num_rows($checkresult);
                if ($rows == 0)
                {
                    $insertsql = "insert into orders.IMAGE (image_id, shelfmark, date_created, date_modified, jpeg_path, collection) VALUES ('".$image_id."','".$shelfarray[$i]."',CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, '".$urlarray[$i]."', '".$collection."');";
                    $insertresult = mysqli_query($link, $insertsql);
                    fwrite($file_handle_out, 'inserted '. $image_id . "','" . $shelfarray[$i] . "',''" . $urlarray[$i]."\n");
                }
                else
                {
                    while ($row = $checkresult->fetch_assoc())
                    {
                        $jpeg_path = $row['jpeg_path'];
                        if (strpos($jpeg_path, ".jpg") > 0)
                        {
                            $updatesql = "update orders.IMAGE set jpeg_path = '" . $urlarray[$i] . "', shelfmark = '" . $shelfarray[$i] . "', collection = '" . $collection . "' where image_id = '" . $image_id . "';";
                            $updateresult = mysqli_query($link, $updatesql);
                            fwrite($file_handle_out, 'updated '. $image_id . "','" . $shelfarray[$i] . "',''" . $urlarray[$i]."\n");

                        }
                        else
                        {
                            fwrite($file_handle_out, 'No update required for ' . $image_id . "\n");
                        }
                    }
                }

            }
            $i++;
        }

    $outfile = '/home/lib/lacddt/librarylabs/games/files/shelfreport.csv';
    $file_handle_out = fopen('/home/lib/lacddt/librarylabs/games/files/shelfreport.csv', "w") or die ("can't open loaded papers file");
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
        $json = @file_get_contents($manifest_url);
        if ($json === false)
        {
            fwrite($file_handle_out, "No manifest any more- should be deleted: ".$manifest_url."\n");
        }
        else {
            $jobj = json_decode($json, true);
            $error = json_last_error();
            $jsonMD = $jobj['sequences'][0]['canvases'][0]['metadata'];
            $rights = '';
            $photographer = '';
            $photoline = '';
            $catalogue_no = '';
            foreach ($jsonMD as $jsonMDPair) {

                if ($jsonMDPair['label'] == 'Title') {
                    $title = str_replace("<span>", "", $jsonMDPair['value']);
                    $title = str_replace("</span>", "", $title);
                }
                if ($jsonMDPair['label'] == 'Date') {
                    $date = str_replace("<span>", "", $jsonMDPair['value']);
                    $date = str_replace("</span>", "", $date);
                }
                if ($jsonMDPair['label'] == 'Creator') {
                    $creator = str_replace("<span>", "", $jsonMDPair['value']);
                    $creator = str_replace("</span>", "", $creator);
                }
                if ($jsonMDPair['label'] == 'Catalogue Number') {
                    $catalogue_no = str_replace("<span>", "", $jsonMDPair['value']);
                    $catalogue_no = str_replace("</span>", "", $catalogue_no);
                }

            }
            fwrite($file_handle_out, '"' . $shelf_list[$j] . '",' . $shelf_list_image_count[$j] . ',"' . $title . '",' . $date . ',"' . $creator . '",' . $catalogue_no . ',' . substr($manifest_url, 47, 8) . "\n");
            $j++;
        }
    }
    fclose($file_handle_out);
    echo 'I HAVE FINISHED';
    ?>
    </body>
</html>
