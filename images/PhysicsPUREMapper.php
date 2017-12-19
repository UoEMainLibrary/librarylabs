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
    <img src="images/lddutilities.jpg">
    <h1>INSPIRE HEP to PURE mapping process </h1>
    <p>Convert your INSPIRE-HEP search to PURE XML here.</p>
    <p>Enter your collaboration (e.g. LHCb, Atlas) and year, or run for a single paper.</p>
    <p>Upload your "internal ID" file with three colon-separated parms (INSPIRE ID:PURE ID:Name). Name format is (e.g.) Clarke, P.E.L.</p>
    <p>Upload your "internal Org" file with two colon-separated parms (Name:PURE ID:). Name format is as INSPIRE-HEP.</p>
    <p>This code is on github (https://github/UoEMainLibrary/lddutilities/PhysicsPUREMapper.php . You will need download.php too.</p>
    <p>Sample files on github (https://github/UoEMainLibrary/lddutilities/input/[atlasUserMap.txt, LHCbUserMap.txt, orgs.txt]. There is only one organisations file, which can be used for both processes.</p>
</div>
<div class = "box">

    <form action="PhysicsPUREMapper.php" method="post" enctype="multipart/form-data">
        <table>
            <tr class ="inputtypeindent"><td class ="inputtypeindent">Enter collaboration:<input type="text" name="collab" id="collab"  class ="inputtypeindent"></td></tr>
            <tr class ="inputtypeindent"><td class ="inputtypeindent">Enter journal year (* for all years):<input type="text" name="year" id="year"  class ="inputtypeindent"></td></tr>
            <tr class ="inputtypeindent"><td class ="inputtypeindent">OR enter specific paper (this overrides above fields):<input type="text" name="paperid" id="paperid"  class ="inputtypeindent"></td></tr>
            <tr class ="inputtypeindent"><td class ="inputtypeindent">Upload internal ID file:<input type="file" name="internalIDfile" id="internalIDfile"  class ="inputtypeindent"></td></tr>
            <tr class ="inputtypeindent"><td class ="inputtypeindent">Include organisations? <input type="checkbox" name="orgs" id="orgs"></td></tr>
            <tr class ="inputtypeindent"><td class ="inputtypeindent">Upload org mapping file:<input type="file" name="orgfile" id="orgfile"  class ="inputtypeindent"></td></tr>
            <tr class ="inputtypeindent"><td class ="inputtypeindent">No. papers per file (Default 5)<input type="text" name="loop" id="loop"  class ="inputtypeindent"></td></tr>
        </table>
        <br>
        <input type="submit" value="Go" name="upload" class ="inputtypeindent">
    </form>

</div>
<?php

//Scott Renton, March 2016
//Load Inspire-HEP papers into PURE
//Two files required to make this work- an ID file for persons and an org file.
//User can do their own, but syntax is available in sample files.
//Examples are on git- lddutilities/input/[atlasUserMap.txt, LHCbUserMap.txt, atlasOrgs.txt, LHCbOrgs.txt]

ini_set('max_execution_time', 400);
if (isset($_POST['upload']))
{

    $target_dir = "/home/lib/lacddt/librarylabs/files/";

    //get form data
    $collab = $_POST['collab'];
    $year = $_POST['year'];
    $orgs = $_POST['orgs'];
    $paperid = $_POST['paperid'];

    //default papers per file if nothing specified
    if ($_POST['loop'] !== '') {
        $loop = $_POST['loop'];
    }
    else{
        $loop = 5;
    }

    //if specific paper specified, that overrides anything else entered
    if ($paperid !== '') {
        $parms = $paperid;
    }
    else
    if ($year == "*")
    {
        $parms = "cn+".$collab.'+and+collection:published';
    }
    else
    {
        $parms = "cn+".$collab."+and+jy+".$year.'+and+collection:published';
    }

    //This URL is the inspirehep search- can run this in a browser to check
    $url = 'https://inspirehep.net/search?p='.$parms.'&of=xm&em=B&sf=year&so=d&rg=500';
    echo '<h5>Your INSPIRE-HEP search URL is: '.$url.'</h5>';

    //generate space to save Ouput. Timestamp so multiple users can run at once
    $directory = $target_dir.'PUREOutput'.time().'/';
    mkdir($directory);

    //use curl to get marcXML for Inspire search- save to file for reference
    $curl = curl_init();
    $fp = fopen($directory."curl.xml", "w");
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_FILE, $fp);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl,  CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ( $httpCode == 404 ) {
        touch($directory."cache/404_err.txt");
    }
    else
    {
        fwrite($fp, $response);
    }

    curl_close($curl);
    fclose($fp);

    //Process upload of internal ID mapping file
    $target_id_file = $target_dir . basename($_FILES["internalIDfile"]["name"]);
    echo '<h5>ID file is called '.$target_id_file.'</h5>';


    $uploadOkID = 1;
    $imageFileTypeID = pathinfo($target_id_file,PATHINFO_EXTENSION);

    if (file_exists($target_id_file)) {
        echo "Sorry, file already exists.";
        $uploadOkID = 0;
    }

    if ($_FILES["internalIDfile"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOkID = 0;
    }

    if($imageFileTypeID != "txt"  ) {
        echo "Sorry, only TXT files are allowed at this stage.";
        $uploadOkID = 0;
    }

    if ($uploadOkID == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["internalIDfile"]["tmp_name"], $target_id_file)) {
            echo "<h5> The file ". basename( $_FILES["internalIDfile"]["name"]). " has been uploaded.</h5>";
        } else {
            echo "Sorry, there was an error uploading your ID file.";
        }
    }

    chmod ($target_id_file, 0777);

    if ($orgs == true) {
        //Process Organisations mapping file
        $target_org_file = $target_dir . basename($_FILES["orgfile"]["name"]);
        echo '<h5>Org file is called ' . $target_org_file . '</h5>';

        $uploadOkOrg = 1;
        $imageFileTypeOrg = pathinfo($target_org_file, PATHINFO_EXTENSION);

        if (file_exists($target_org_file)) {
            echo "Sorry, file already exists.";
            $uploadOkOrg = 0;
        }

        if ($_FILES["internalOrgfile"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOkOrg = 0;
        }

        if ($imageFileTypeOrg != "txt") {
            echo "Sorry, only TXT files are allowed at this stage.";
            $uploadOkOrg = 0;
        }

        if ($uploadOkOrg == 0) {
            echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["orgfile"]["tmp_name"], $target_org_file)) {
                echo "<h5> The file " . basename($_FILES["orgfile"]["name"]) . " has been uploaded.</h5>";
            } else {
                echo "Sorry, there was an error uploading your Org file.";
            }
        }

        chmod($target_org_file, 0777);
        //Process Org file into an array
        //o counts through the array from the input mapping file
        $k = 0;
        $file_handle_org_in = fopen($target_org_file, "r") or die ("can't open org mapping file");

        while (!feof($file_handle_org_in)) {
            $line = fgets($file_handle_org_in);
            $map = explode(":", $line);
            $pureIntOrg[$o][0] = $map[0];
            $pureIntOrg[$o][1] = $map[1];
            $o++;
        }
    }

    chmod ($directory, 0777);

    //Load the captured curl response into XML
    $xml_file = $directory."curl.xml";

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
    //Process ID file into an array
    //k counts through the array from the input mapping file
    $k = 0;
    $file_handle_id_in = fopen($target_id_file, "r") or die ("can't open mapping file");
    echo '<h5>Authors To Match</h5>';
    while (!feof($file_handle_id_in)) {
        $line = fgets($file_handle_id_in);
        $map = explode(":", $line);
        $pureIntAr[$k][0] = $map[0];
        $pureIntAr[$k][1] = $map[1];
        $pureIntAr[$k][2] = $map[2];
        echo '<p>'.$k.' '.$pureIntAr[$k][0].' '.$pureIntAr[$k][1].' '.$pureIntAr[$k][2]."</p>";
        $k++;
    }



    //Use XMLWriter for output files
    $writer = new XMLWriter();
    $writer->openURI($directory."PURELoad0.xml");
    $writer->startDocument('1.0', 'UTF-8');
    $writer->startElement('v1:publications');
    $writer->writeAttribute('xmlns:v1', 'v1.publication-import.base-uk.pure.atira.dk');
    $writer->writeAttribute('xmlns:commons', 'v3.commons.pure.atira.dk');

    //pureCount actually the same as k
    $pureCount = count($pureIntAr);
    //n counts the lines in the document
    $n = 0;
    //z counts the papers (without stopping at twenty)
    $z = 1;
    //filecounter counts the xml files for output
    $filecounter = 0;
    //papercounter counts the papers up to loop number specified
    $papercounter = 0;
    $author=array();
    $initarray =array();
    $newfile = false;
    $idarray = array();

    //Table to report output for researchers/schol comms to track loaded tables
    echo '<table class ="inputypeindent">';
    echo '<tr class ="inputypeindent"><td class ="inputypeindent">Paper</td><td>Id</td><td>DOI</td><td>Title</td><td>Total authors</td><td>Matched authors</td><td>Journal Year</td><td>Acceptance Date</td><td>Published Date</td><td>Full Text</td></tr>';

    foreach ($xml->children() as $object)
    {
        if ($newfile == true)
        {
            $writer = new XMLWriter();
            $writer->openURI($directory."PURELoad".$filecounter.".xml");
            $writer->startDocument('1.0', 'UTF-8');
            $writer->startElement('v1:publications');
            $writer->writeAttribute('xmlns:v1', 'v1.publication-import.base-uk.pure.atira.dk');
            $writer->writeAttribute('xmlns:commons', 'v3.commons.pure.atira.dk');
        }
        //j counts every the authors within a paper
        $j = 0;
        $author = '';
        $id = '';
        $doi_block ='';
        $journal_block ='';
        $volume_block ='';
        $number_block  ='';
        $pages_block ='';
        $title_block ='';
        $abstract_block = '';
        $month_block ='';
        $day_block ='';
        $year_block ='';
        $idarray = '';
        $initarray = '';
        //idcount counts through the different ids within a paper
        $idcount = 0;
        $org = '';
        $name = '';
        $authorid = '';
        //matchedcount counts the number of matched internal authors within a paper
        $matchedcount = 0;
        $fulltext = '';
        $rawurl = '';
        $accday = '';
        $accmonth = '';
        $accyear = '';
        $accdate = '';
        $rawjournal = '';
        $journal_issn = '';
        $licence = '/dk/atira/pure/core/document/licenses/creative_commons_attribution_cc_by_';
        $accstatus = '';
        $textstatus = '';
        $date = '';
        $pubstatus = '';

        foreach ($object->children() as $item)
        {
            $update = false;
            $subfolder = '';
            $inspirecode = false;
            $valid = false;
            $inspireid = false;
            $doi_done = false;
            //tag 024 = doi
            if ($item[$n]['tag'] == '024') {
                foreach($item[$n]->subfield as $subfield) {
                    if ($subfield['code'] == 'a') {
                        $doi_block = $subfield;
                        //make sure we only do this once
                        if ($doi_done == false) {
                            //use DOI to interrogate SCOAP record. This gets us the PDF of the paper
                            $scoapurl = 'https://repo.scoap3.org/search?ln=en&p=' . $doi_block . '&f=doi&action_search=Search&c=SCOAP3+Repository&sf=&so=d&rm=&rg=10&sc=1&of=xm';
                            $scoapcurl = curl_init();
                            $scoapfp = fopen($directory . "scoapcurl.xml", "w");
                            curl_setopt($scoapcurl, CURLOPT_URL, $scoapurl);
                            curl_setopt($scoapcurl, CURLOPT_SSL_VERIFYPEER, false);
                            curl_setopt($scoapcurl, CURLOPT_FILE, $scoapfp);
                            curl_setopt($scoapcurl, CURLOPT_HEADER, 0);
                            curl_setopt($scoapcurl, CURLOPT_RETURNTRANSFER, TRUE);
                            $scoapresponse = curl_exec($scoapcurl);
                            $scoaphttpCode = curl_getinfo($scoapcurl, CURLINFO_HTTP_CODE);

                            if ($scoaphttpCode == 404) {
                                touch($directory . "cache/404_err.txt");
                            } else {
                                fwrite($scoapfp, $scoapresponse);
                            }
                            curl_close($scoapcurl);
                            fclose($scoapfp);

                            $scoapxml_file = $directory . "scoapcurl.xml";
                            $scoapxml = simplexml_load_file($scoapxml_file);

                            if ($scoapxml == FALSE) {
                                echo "Failed loading SCOAP XML\n";

                                foreach (libxml_get_errors() as $error) {
                                    echo "\t", $error->message;
                                }
                            }
                            //s counts the lines in the scoap xml document
                            $s = 0;

                            foreach ($scoapxml->children() as $scoapobject) {
                                foreach ($scoapobject->children() as $scoapitem) {
                                    //marc field 856 is the electronic pdf
                                    if ($scoapitem[$s]['tag'] == '856') {
                                        foreach ($scoapitem[$s]->subfield as $scoapsubfield) {
                                            if ($scoapsubfield['code'] == 'u') {
                                                if (strpos($scoapsubfield, "=pdfa") > 0) {
                                                    $fulltext = $scoapsubfield;
                                                }
                                                //interrogate the pdf's raw xml to get the acceptance date
                                                if (strpos($scoapsubfield, ".xml") > 0) {
                                                    $rawurl = $scoapsubfield;
                                                    $rawcurl = curl_init();
                                                    $rawfp = fopen($directory . "rawcurl.xml", "w");
                                                    curl_setopt($rawcurl, CURLOPT_URL, $rawurl);
                                                    curl_setopt($rawcurl, CURLOPT_FILE, $rawfp);
                                                    curl_setopt($rawcurl, CURLOPT_HEADER, 0);
                                                    curl_setopt($rawcurl, CURLOPT_RETURNTRANSFER, TRUE);
                                                    $rawresponse = curl_exec($rawcurl);
                                                    $rawhttpCode = curl_getinfo($rawcurl, CURLINFO_HTTP_CODE);

                                                    if ($rawhttpCode == 404) {
                                                        touch($directory . "cache/404_err.txt");
                                                    } else {
                                                        fwrite($rawfp, $rawresponse);
                                                    }

                                                    curl_close($rawcurl);
                                                    fclose($rawfp);

                                                    $rawxml_file = $directory . "rawcurl.xml";
                                                    $rawxml = simplexml_load_file($rawxml_file);
                                                    if ($rawxml == FALSE) {

                                                        foreach (libxml_get_errors() as $error) {
                                                            echo "\t", $error->message;
                                                        }
                                                    }

                                                    foreach ($rawxml->children() as $rawobject) {
                                                        foreach ($rawobject->{'article-meta'}->history->date as $rawitem) {
                                                            if ($rawitem['date-type'] == 'accepted') {
                                                                $accday = $rawitem->day;
                                                                $accmonth = $rawitem->month;
                                                                $accyear = $rawitem->year;
                                                                $accdate = $accyear . '-' . $accmonth . '-' . $accday;
                                                            }

                                                        }

                                                        foreach($rawobject->{'journal-meta'}->issn as $rawjournal)
                                                        {
                                                            $journal_issn = $rawjournal;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    $s++;
                                }
                            }
                            $doi_done = true;
                        }
                    }
                }
            }

            //035 is ID
            if ($item[$n]['tag'] == '035') {
                foreach($item[$n]->subfield as $subfield) {
                    if ($subfield['code'] == '9') {
                        $idarray[$idcount][0] = $subfield;
                    }
                    if ($subfield['code'] == 'a') {
                        $idarray[$idcount][1] = $subfield;
                    }
                }
                $idcount ++;
            }

            //773 is Journal info
            if ($item[$n]['tag'] == '773') {
                foreach($item[$n]->subfield as $subfield) {
                    if ($subfield['code'] == 'p') {
                        $journal_block = $subfield;
                    }
                    if ($subfield['code'] == 'v') {
                        $volume_block = $subfield;
                    }
                    if ($subfield['code'] == 'n') {
                        $number_block = $subfield;
                    }
                    if ($subfield['code'] == 'c') {
                        $pages_block = $subfield;
                    }
                }
            }

            //245 is title
            if ($item[$n]['tag'] == '245') {
                foreach($item[$n]->subfield as $subfield) {
                    if ($subfield['code'] == 'a') {
                        $title_block = $subfield;
                    }
                }
            }

            //260 is published date
            if ($item[$n]['tag'] == '260') {
                foreach($item[$n]->subfield as $subfield) {
                    if ($subfield['code'] == 'c') {
                        $date = $subfield;
                        $year_block = substr($date, 0, 4);
                        $month_block = substr($date, 5, 2);
                        if ($month_block == null) {
                            $month_block = '01';
                        }
                        $day_block = substr($date, 8, 2);
                        if ($day_block == null) {
                            $day_block = '01';
                        }
                    }
                }
            }

            //520 is abstract
            if ($item[$n]['tag'] == '520') {
                foreach($item[$n]->subfield as $subfield) {
                    if ($subfield['code'] == 'a') {
                        $abstract_block = $subfield;
                    }
                }
            }

            //540 is licence- requires special format
            if ($item[$n]['tag'] == '540') {
                foreach($item[$n]->subfield as $subfield) {
                    if ($subfield['code'] == 'a') {
                        switch ($subfield)
                        {
                            case "CC-BY-4.0":
                            case "CC-BY-3.0";
                            case "http://creativecommons.org/licenses/by/4.0/":
                            case "http://creativecommons.org/licenses/by/3.0/":
                                $licence = "/dk/atira/pure/core/document/licenses/creative_commons_attribution_cc_by_";
                                break;
                            case "CC-BY-ND-4.0":
                            case "CC-BY-ND-3.0";
                            case "http://creativecommons.org/licenses/by-nd/4.0/":
                            case "http://creativecommons.org/licenses/by-nd/3.0/":
                        	    $licence = "/dk/atira/pure/core/document/licenses/cc_by_nd";
                                break;
                            case "CC-BY-NC-4.0":
                            case "CC-BY-NC-3.0";
                            case "http://creativecommons.org/licenses/by-nc/4.0/":
                            case "http://creativecommons.org/licenses/by-nc/3.0/":
                                $licence = "/dk/atira/pure/core/document/licenses/cc_by_nc";
                                break;
                            case "CC-BY-NC-ND-4.0":
                            case "CC-BY-NC-ND-3.0";
                            case "http://creativecommons.org/licenses/by-nc-nd/4.0/":
                            case "http://creativecommons.org/licenses/by-nc-nd/3.0/":
                                $licence = "/dk/atira/pure/core/document/licenses/cc_by_nc_nd";
                                break;
                            case "CC-BY-NC-SA-4.0":
                            case "CC-BY-NC-SA-3.0";
                            case "http://creativecommons.org/licenses/by-nc-sa/4.0/":
                            case "http://creativecommons.org/licenses/by-nc-sa/3.0/":
                                $licence = "/dk/atira/pure/core/document/licenses/cc_by_nc_sa";
                                break;
                            case "CC-BY-SA-4.0":
                            case "CC-BY-SA-3.0";
                            case "http://creativecommons.org/licenses/by-sa/4.0/":
                            case "http://creativecommons.org/licenses/by-sa/3.0/":
                                $licence = "/dk/atira/pure/core/document/licenses/cc_by_sa";
                                break;
                            default:
                                $licence = '/dk/atira/pure/core/document/licenses/creative_commons_attribution_cc_by_';
                        }

                    }
                }
            }

            //700 is author (in all its glory)
            if ($item[$n]['tag'] == '700') {
                foreach($item[$n]->subfield as $subfield)
                {
                    if ($subfield['code'] == 'a')
                    {
                        $name = $subfield;
                        //dirty hack- these two authors had special chars that, no matter what UTF-8 mapping I did, resulted in dodgy characters, prevented loathing.
                        //this is horrible hard-coding, but anyone uploading would have to deal with these manually EVERY time they go near the importer for LHCb otherwise.
                        if ((strstr($name, "Marchand")) and (strstr($name, "Jean")))
                        {
                            $name = "Marchand, Jean F.";
                        }
                        if ((strstr($name, "Girard")) and (strstr($name, "Olivier")))
                        {
                            $name = "Girard, Olivier G.";
                        }
                    }
                    //get INSPIRE-ID
                    if ($subfield['code'] == 'i')
                    {
                        $authorid = $subfield;
                        $inspirecode = true;
                    }
                    //get orgs
                    if ($subfield['code'] == 'u')
                    {
                        $org = $subfield;
                    }
                }

                if (!$inspirecode == true)
                {
                    $author[$j][0] = '';
                }
                else{
                    $author[$j][0] = $authorid;
                }
                $commapos = strpos($name, ",");
                $family = substr($name, 0, $commapos);
                $given = substr($name, $commapos+2, 10);
                $given = str_replace(".", " ", $given);
                $given = str_replace("  ", " ", $given);
                $given = trim($given);
                $inits = substr($given,0,1).substr($family,0,1);
                $author [$j][1] = $family;
                $author[$j][2] = $given;
                $author[$j][3] = $given." ".$family;
                $author[$j][4] = $org;
                $author[$j][5] = $name;
                $author[$j][6] = $inits;
                $j++;
            }


            $n++;
        }
        $matchedcount = 0;
        //get matchcount to see if we process the paper by checking if the author is on the internal id array
        if ($j > 0) {
            for ($x = 0; $x <= $j; $x++) {
                for ($q = 0; $q <= $pureCount; $q++) {
                    if ($pureIntAr[$q][2] !== null) {
                        if ($author[$x][0] == '') {
                            if ($pureIntAr[$q][2] == $author[$x][5]) {
                                $matchedcount++;
                            }
                        } else {
                            if ($pureIntAr[$q][0] == $author[$x][0]) {
                                $matchedcount++;
                            }
                        }
                    }
                }
            }
        }
        //ID cascade- INSPIRETeX preferred
        for ($g = 0; $g<=$idcount; $g++)
        {
            if ($idarray[$g][0] == 'INSPIRETeX')
            {
                $id = $idarray[$g][1];
                $inspireid = true;
            }
        }
        //failing that, use SPIRESTeX
        if (!$inspireid) {
            for ($g = 0; $g <= $idcount; $g++) {
                if ($idarray[$g][0] == 'SPIRESTeX') {
                    $id = $idarray[$g][1];
                    $inspireid = true;
                }
            }
        }
        //failing that, use arXiv
        if (!$inspireid)
        {
            for ($g = 0; $g<=$idcount; $g++)
            {
                if ($idarray[$g][0] == 'arXiv')
                {
                    $id = $idarray[$g][1];
                }
            }
        }
        //write the record if there are matches- of no interest to us otherwise
        //If there is no published date, it won't load, so skip in that case too
        if($matchedcount > 0 and $date !== '') {
            $writer->startElement("v1:contributionToJournal");
            $writer->writeAttribute('id', $id);
            $writer->writeAttribute('subType', 'article');
            $writer->writeElement('v1:peerReviewed', 'true');
            $writer->writeElement('v1:publicationCategory', 'research');
            $writer->setIndent(4);
            $writer->startElement('v1:publicationStatuses');
            $writer->startElement('v1:publicationStatus');
            $writer->writeElement('v1:statusType', 'published');
            $writer->startElement('v1:date');
            $writer->writeElement('commons:year', $year_block);
            $writer->writeElement('commons:month', $month_block);
            $writer->writeElement('commons:day', $day_block);
            $writer->endElement();
            $writer->endElement();
            //Report no Acc Date if we did not get one earlier
            if ($accdate == '') {
                $accstatus = 'ACC DATE NOT FOUND';
            }
            else{
                //write the Acc Date (optional due to failures)
                    $writer->startElement('v1:publicationStatus');
                    $writer->writeElement('v1:statusType', 'inpress');
                    $writer->startElement('v1:date');
                    $writer->writeElement('commons:year', $accyear);
                    $writer->writeElement('commons:month', $accmonth);
                    $writer->writeElement('commons:day', $accday);
                    $writer->endElement();
                    $writer->endElement();
                $accstatus = $accdate;
            }

            $writer->endElement();
            $writer->writeElement('v1:language', 'en_GB');
            $writer->startElement('v1:title');
            $writer->writeElement('commons:text', $title_block);
            $writer->writeAttribute('lang', 'en');
            $writer->writeAttribute('country', 'GB');
            $writer->endElement();
            $writer->startElement('v1:abstract');
            $writer->writeElement('commons:text', $abstract_block);
            $writer->writeAttribute('lang', 'en');
            $writer->writeAttribute('country', 'GB');
            $writer->endElement();
            $writer->setIndent(4);
            $writer->startElement('v1:persons');
            $matchedcount = 0;
            //process authors
            for ($x = 0; $x <= $j; $x++) {
                if (($author[$x][0] == '')) {
                    $internal = false;
                }
                if ($author[$x][1] !== null) {
                    $writer->startElement('v1:author');
                    $writer->writeElement('v1:role', 'author');
                    $writer->startElement('v1:person');
                    $internal = false;
                    //look for matches, when found report as internal, with ID
                    for ($q = 0; $q <= $pureCount; $q++) {
                        if ($author[$x][0] == '') {
                            if ($pureIntAr[$q][2] == $author[$x][5]) {
                                $writer->writeAttribute('id', trim($pureIntAr[$q][1]));
                                $internal = true;
                                $writer->writeAttribute('external', 'false');
                                $initarray[$matchedcount] = $author[$x][6].'/';
                                $matchedcount++;
                            }
                        } else {
                            if ($pureIntAr[$q][0] == $author[$x][0]) {
                                $writer->writeAttribute('id', trim($pureIntAr[$q][1]));
                                $internal = true;
                                $writer->writeAttribute('external', 'false');
                                $initarray[$matchedcount] = $author[$x][6].'/';
                                $matchedcount++;
                            }
                        }
                    }

                    if (!$internal) {
                        $writer->writeAttribute('external', 'true');
                    }
                    $writer->writeElement('v1:fullName', $author[$x][3]);
                    $writer->writeElement('v1:firstName', $author[$x][2]);
                    $writer->writeElement('v1:lastName', $author[$x][1]);
                    $writer->endElement();
                    //If organisations checkbox ticked, run the relevant orgs into the record
                    if ($orgs == true) {
                        $writer->startElement('v1:organisations');
                        $orgfound = false;
                        $ordID = '';
                        //get matches using input org array
                        for ($d = 0; $d <= $o; $d++) {
                            if (trim($pureIntOrg[$d][0]) == trim($author[$x][4])) {
                                $orgId = trim($pureIntOrg[$d][1]);
                                $orgfound = true;
                            }
                        }

                        if ($orgfound)
                        {
                            $writer->startElement('v1:organisation');
                            $writer->writeAttribute('id',$orgId);
                        }
                        else
                        {
                            $writer->startElement('v1:organisation');
                        }
                        $writer->startElement('v1:name');
                        $writer->writeElement('commons:text', $author[$x][4]);
                        $writer->endElement();
                        $writer->endElement();
                        $writer->endElement();
                    }
                    $writer->endElement();
                }
            }

            $urlbit = 'http://dx.doi.org/';

            $qpoint = strpos ($fulltext, '?');
            $fulltext = substr($fulltext, 0, $qpoint);
            $writer->endElement();
            $writer->startElement('v1:owner');
            $writer->writeAttribute('id', 'S44');
            $writer->endElement();
            $writer->startElement('v1:electronicVersions');
            //if full text was not found earlier, do not write this bit
            if ($fulltext == '')
            {
                $textstatus = 'FULL TEXT NOT FOUND';
            }
            else
            {
                $writer->startElement('v1:electronicVersionFile');
                $writer->writeElement('v1:version','/dk/atira/pure/publication/electronicversion/versiontype/publishersversion');
                $writer->writeElement('v1:licence',$licence);
                $writer->writeElement('v1:publicAccess', 'open');
                $writer->writeElement('v1:title', $fulltext);
                $writer->startElement('v1:file');
                $writer->writeElement('v1:filename', $fulltext);
                $writer->writeElement('v1:mimetype', 'application/pdf');
                $writer->endElement();
                $writer->endElement();
                $textstatus = $fulltext;

            }
            $writer->startElement('v1:electronicVersionDOI');
            $writer->writeElement('v1:doi', $doi_block);
            $writer->endElement();
            $writer->endElement();
            $writer->writeElement('v1:pages', $pages_block);
            $writer->writeElement('v1:articleNumber', $id);
            $writer->writeElement('v1:journalNumber', $number_block);
            $writer->writeElement('v1:journalVolume', $volume_block);
            $writer->startElement('v1:journal');
            $writer->writeElement('v1:title', $journal_block);
            $writer->startElement('v1:printIssns');
            $writer->writeElement('v1:issn', $journal_issn);
            $writer->endElement();
            $writer->endElement();
            $writer->endElement();
            $papercounter++;
            $newfile = false;
            //if you have hit the end of the loop, start a new file once this one is closed
            if($papercounter == $loop)
            {
                $filecounter++;
                $papercounter = 0;
                $newfile = true;
            }

            if ($newfile == true) {
                $writer->endDocument();
            }
            $trstyle = "trnormal";
        }
        else
        {
            //unloadable papers appear red
            $trstyle = "trred";
        }


        if ($date == '')
        {
            $pubstatus = 'PUBLISHED DATE NOT FOUND';
        }
        else
        {
            $pubstatus =  $date;
        }
        //write status of paper to screen
        echo '<tr class="'.$trstyle.'"><td class ="inputypeindent">' . $z . '</td><td>' . $id . '</td><td><a href="' . $urlbit . $doi_block . ' target = "_blank">' . $urlbit . $doi_block . '</a></td><td>' . substr($title_block, 0, 30) . '...</td><td>' . $j . '</td><td>' . $matchedcount . ' (';


        for ($ic = 0; $ic <= $matchedcount; $ic++)
        {
            echo $initarray[$ic];
        }
        echo ')</td><td>'.$year.'</td><td>'.$accstatus.'</td><td>'.$pubstatus.'</td><td>'.$textstatus.'</td></tr>';

        $z++;

    }
    echo '</table>';
    $writer->endDocument();
    //unlink files so new ones can be used
    unlink($target_id_file);
    unlink($target_org_file);
    unlink($processed_file);
    //write papers to a zip file for download
    $zipname = $directory.'PURELoad.zip';
    $zip = new ZipArchive();
    $opened = $zip->open($zipname, ZipArchive::CREATE);

    if ($opened !== true) {
            switch($opened){
                case ZipArchive::ER_EXISTS:
                    $ErrMsg = "File already exists.";
                    break;

                case ZipArchive::ER_INCONS:
                    $ErrMsg = "Zip archive inconsistent.";
                    break;

                case ZipArchive::ER_MEMORY:
                    $ErrMsg = "Malloc failure.";
                    break;

                case ZipArchive::ER_NOENT:
                    $ErrMsg = "No such file.";
                    break;

                case ZipArchive::ER_NOZIP:
                    $ErrMsg = "Not a zip archive.";
                    break;

                case ZipArchive::ER_OPEN:
                    $ErrMsg = "Can't open file.";
                    break;

                case ZipArchive::ER_READ:
                    $ErrMsg = "Read error.";
                    break;

                case ZipArchive::ER_SEEK:
                    $ErrMsg = "Seek error.";
                    break;

                default:
                    $ErrMsg = "Unknown (Code $rOpen)";
                    break;
            }
            die( 'ZipArchive Error: ' . $ErrMsg);
        }

    else {

        if (is_dir($directory)) {
            if ($dh = opendir($directory)) {
                while (($file = readdir($dh)) !== false) {
                    if ((strpos($file, "URE") > 0) and (strpos($file, ".xml")> 0)) {
                        $zip->addFile($directory.$file, basename($file));
                    }
                }
            }
        }

    }

    $zip->close();

    echo '<h5>That seems to have done everything it is supposed to. Get your file by clicking the link below.</h5>';
    echo '<a href= "download.php?directory='.$directory.'"><h4>Download Output</h4></a>';
}

?>
</body>
</html>
