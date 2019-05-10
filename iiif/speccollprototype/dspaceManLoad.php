<?php
/**
 * Created by PhpStorm.
 * User: srenton1
 * Date: 12/02/2018
 * Time: 09:20
 */

function manifest_check_insert ($manifestshelf, $link)
{
    /**
     * Created by PhpStorm.
     * User: srenton1
     * Date: 12/02/2018
     * Time: 09:20
     * @param manifesthelf
     * @return manifest_id
     */
    $get_manifest_id_sql = "select manifest_id from MANIFEST_SHELFMARK where shelfmark = '".$manifestshelf."';";
    $get_manifest_id_result = mysqli_query($link, $get_manifest_id_sql);
    $man_count= mysqli_num_rows($get_manifest_id_result);

    if ($man_count == 0)
    {
        $manifest_id =bin2hex(openssl_random_pseudo_bytes(4));
        $manifest_id_insert = "insert into MANIFEST_SHELFMARK (manifest_id, shelfmark) VALUES ('".$manifest_id."','".$manifestshelf."');";
        $insertresult = mysqli_query($link, $manifest_id_insert);
    }
    else
    {
        while ($row = $get_manifest_id_result->fetch_assoc())
        {
            $manifest_id = $row['manifest_id'];
        }

    }
    return $manifest_id;
}


function create_folders($foldername, $directory, $update_directory, $update_file)
{
    $file_handle_upd_in = fopen($update_file, "r")or die("<p>Sorry. I can't open upfile.</p>");
    fwrite($file_handle_upd_in, 'Upload file '.$update_file."\n");
    $update = false;
    while (!feof($file_handle_upd_in))
    {
        $line = fgets($file_handle_upd_in);
        $found = strpos($line,$foldername);
        if ($found !== false)
        {
            $update = true;
        }
    }

    if ($update == true)
    {
        $subfolder = $update_directory.$foldername;
    }
    else
    {
        $subfolder = $directory.$foldername;
    }
    if (!(file_exists($subfolder))) {
        mkdir($subfolder);
        chmod($subfolder, 0777);
    }
    return $subfolder;
}

function write_dc_headers($subfolder, $dublincorefile, $identifier, $foldername)
{
    $file_handle_dc_out = fopen($subfolder . '/' . $dublincorefile, "w") or die("<p>Sorry. I can't open dc outfile.</p>");
    //$file_handle_contents_out = fopen($subfolder . '/' . $contentsfile, "w") or die("<p>Sorry. I can't open contents outfile.</p>");
    fwrite($file_handle_dc_out, "<?xml version=\"1.0\" encoding=\"UTF-8\"?> \n");
    fwrite($file_handle_dc_out, "<dublin_core>\n");
    $outline = '<dcvalue element = "identifier" qualifier = "">' . $identifier . "</dcvalue>\n";
    fwrite($file_handle_dc_out, $outline);
    $dotpos = '';
    $dotpos = strpos($foldername, "-");
    $dc_type = substr($identifier, 0, $dotpos);
    $outline = '<dcvalue element = "type" qualifier = "">' . $dc_type . "</dcvalue>\n";
    fwrite($file_handle_dc_out, $outline);
    return $file_handle_dc_out;
}

function get_image_result($image_select_sql, $link)
{
    $imageresult = mysqli_query($link, $image_select_sql);
    return $imageresult;
}


function create_manifests($manifest_id, $imageresult,  $file_handle_dc_out, $foldername, $viewinghint, $docbase)
{
    $j =0;
    $manifestarray = [];
    while ($row = $imageresult->fetch_assoc())
    {
        $outpath = $row['jpeg_path'];
        $manifest = str_replace('detail', 'iiif/m', $outpath).'/manifest';
        $manifestlist[$j] = $manifest;

        $json = file_get_contents($manifestlist[$j]);
        $jobj = json_decode($json, true);
        $error = json_last_error();

        if ($j == 0)
        {

            $attribution = $jobj['attribution'];
            $outline = '<dcvalue element = "relation" qualifier = "ispartof">'.$attribution."</dcvalue>\n";
            fwrite($file_handle_dc_out,$outline);
            $dc_image_uri = str_replace( "detail", "iiif", $jobj['related'])."/full/full/0/default.jpg";
            $outline = '<dcvalue element = "identifier" qualifier = "imageUri">'.$dc_image_uri."</dcvalue>\n";
            fwrite($file_handle_dc_out,$outline);
            $context = $jobj['@context'];
            $related = str_replace('iiif/m', 'detail', $manifestlist[$j]);
            $related = str_replace('/manifest', '', $related);
            $rand_no = bin2hex(openssl_random_pseudo_bytes(12));
            foreach ($jobj['sequences'][0]['canvases'][0]['metadata'] as $metadatapair) {
                $label = $metadatapair['label'];
                $value = $metadatapair['value'];
                if ($label === "Title") {
                    $dc_title = $metadatapair['value'];
                    $dc_title = str_replace("<span>","",$dc_title);
                    $dc_title = str_replace("</span>","",$dc_title);
                    $dc_title = str_replace("&", "and", $dc_title);
                    $outline='<dcvalue element = "title" qualifier = "">'.$dc_title."</dcvalue>\n";
                    fwrite($file_handle_dc_out,$outline);
                }
                if ($label === "Creator") {
                    $dc_creator = $metadatapair['value'];
                    $dc_creator = str_replace("<span>","",$dc_creator);
                    $dc_creator = str_replace("</span>","",$dc_creator);
                    $dc_title = str_replace("&", "and", $dc_title);
                    $outline = '<dcvalue element = "contributor" qualifier = "author">'.$dc_creator."</dcvalue>\n";
                    fwrite($file_handle_dc_out,$outline);
                }
                if ($label === "Date") {
                    $dc_date = $metadatapair['value'];
                    $dc_date = str_replace("<span>","",$dc_date);
                    $dc_date = str_replace("</span>","",$dc_date);
                    $outline = '<dcvalue element = "date" qualifier = "created">'.$dc_date."</dcvalue>\n";
                    fwrite($file_handle_dc_out,$outline);
                }
            }
            $outline = '<dcvalue element = "identifier" qualifier = "manifest">'.$manifest_id."</dcvalue>\n";
            fwrite($file_handle_dc_out,$outline);
        }
        $manifestarray[] = $jobj['sequences'][0]['canvases'][0];
        $j++;
    }
    $manifest_file =array
    ('label' => 'Manifest: '.$dc_title,
        'attribution'=> $attribution,
        'logo' =>  "https://www.eemec.med.ed.ac.uk/img/logo-white.png" ,
        '@id'=> "https://librarylabs.ed.ac.uk/iiif/speccollprototype/manifest/" .$foldername. ".json",
        'related' => $related,
        'sequences' => array(array("@type"=>"sc:Sequence", "viewingHint"=>"individual", "canvases"=>$manifestarray)),
        "@type"=>"sc:Manifest",
        // 'seeAlso'=>$almaurl,
        "@context"=>$context,
        'viewingHint' => $viewinghint
    );

    $dc_format_extent = $j;
    $outline = '<dcvalue element = "format" qualifier = "extent">'.$dc_format_extent."</dcvalue>\n";
    fwrite($file_handle_dc_out,$outline);
    fwrite($file_handle_dc_out, '</dublin_core>');
    fclose($file_handle_dc_out);

    $json_out = json_encode($manifest_file);
    $out_file = $docbase."iiif/speccollprototype/manifest/".$manifest_id.".json";
    $file_handle_out = fopen($out_file, "w")or die("<p>Sorry. I can't open the manifest file.</p>");

    fwrite($file_handle_out, $json_out);

    //$file_handle_contents_out = fopen($subfolder.'/'.$contentsfile, "w")or die("<p>Sorry. I can't open contents outfile.</p>");
    //fwrite($file_handle_contents_out, "manifest.json");
    //fclose($file_handle_contents_out);
}

session_start();
$docbase = '/Users/srenton1/Projects/librarylabs/';
//var_dump($_SESSION);
include $docbase.'games/config/vars.php';
// connect to db
$error = '';
echo $dbserver. $username. $password. $database;
$link = new mysqli($dbserver, $username, $password, $database);
@mysqli_set_charset('utf8', $link);
@mysqli_select_db($database) ;#or die( "Unable to select database".$database);

ini_set('max_execution_time', 10000);

$dublincorefile = 'dublin_core.xml';
$contentsfile = 'contents';
$collection = 'speccoll/';
$logfile = $docbase.'iiif/speccollprototype/files/image_output.txt';

$update_file =  $docbase.'iiif/speccollprototype/files/mapfile.txt';
echo "<p> Using update file: ".$update_file.'</p>';

$file_handle_log_out = fopen($logfile, "a+")or die("<p>Sorry. I can't open the logfile.</p>");
$directory = $docbase.'iiif/speccollprototype/files/dspaceNew/';
echo "<p> Using output directory (new items): ".$directory.'<br>';
//mkdir($directory);
$update_directory = $docbase.'iiif/speccollprototype/files/dspaceExisting/';
echo "<p> Using output directory (existing items): ". $update_directory.'<br>';
//mkdir($update_directory);

$shelfsql = "select distinct(shelfmark) from orders.IMAGE;"; //
/*where not shelfmark like '%Corson%' and not shelfmark like 'EU%' and jpeg_path like 'http%';";*/
$shelfresult = mysqli_query($link, $shelfsql);

$rec_count = mysqli_num_rows($shelfresult);
$i = 0;
print_r('COUNT'.$rec_count);
while ($row = $shelfresult->fetch_assoc())
{
    if ($row['shelfmark'] == 'N/A')
    {
        fwrite($file_handle_log_out, "Skipping N/As\n");
    }
    else
    {
        $shelf[$i] = $row['shelfmark'];
        $options = "";
        $manifest_file = '';
        $manifestshelf = str_replace(" ", "-", $shelf[$i]);
        $manifestshelf = str_replace("*", "-", $manifestshelf);
        $manifestshelf = str_replace("/", "-", $manifestshelf);
        $manifestshelf = str_replace(".", "-", $manifestshelf);

        if ($row['shelfmark'] == 'Archive') {
            echo "Working on an Archives \n";
            $dor_sql = "select distinct(dor_id) from orders.IMAGE_DOR;";
            $dor_sql_result = mysqli_query($link, $dor_sql);
            $dor_count = 0;
            while ($row = $dor_sql_result->fetch_assoc())
            {
                $dor_id[$dor_count] = $row['dor_id'];
                echo "Working on an archive- ID is: " . $dor_id[$dor_count] . "\n";
                $manifestshelf = $dor_id[$dor_count];
                $manifest_id = manifest_check_insert ($manifestshelf, $link);
                $subfolder = create_folders($manifest_id, $directory, $update_directory, $update_file);
                $file_handle_dc_out = write_dc_headers($subfolder, $dublincorefile, $manifestshelf, $manifest_id );
                $image_select_sql = "select i.jpeg_path, i.sequence from IMAGE i, IMAGE_DOR id where id.dor_id ='" . $dor_id[$dor_count] . "' and id.image_id = i.image_id order by i.sequence, i.jpeg_path;";
                $imageresult = get_image_result($image_select_sql, $link);
                $viewing_hint = 'individuals';
                create_manifests($manifest_id, $imageresult, $file_handle_dc_out, $manifest_id, $viewing_hint, $docbase);

                $dor_count++;
            }
        }
        else
        {
            echo "Working on a rare book- ID is: " . $shelf[$i] . "\n";
            $manifest_id = manifest_check_insert ($manifestshelf, $link);
            $subfolder = create_folders($manifest_id, $directory, $update_directory, $update_file);
            $file_handle_dc_out = write_dc_headers($subfolder, $dublincorefile, $manifestshelf, $manifest_id );
            $image_select_sql = "select jpeg_path, sequence from IMAGE where shelfmark ='" . $shelf[$i] . "' order by sequence, jpeg_path;";
            $imageresult =  get_image_result($image_select_sql, $link);
            $viewing_hint = 'paged';
            create_manifests($manifest_id, $imageresult,  $file_handle_dc_out, $manifest_id, $viewing_hint, $docbase);
        }
    }

    $i++;
}

?>