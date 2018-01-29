<?php
    session_start();
    //var_dump($_SESSION);
    include '../../games/config/vars.php';


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
</head>

<body>
<?php include_once("./../analyticstracking.php") ?>
<div class="all container-fluid">
    <h1>Special Collections IIIF Prototype</h1>
    <?php

        $shelfsql = "select distinct(shelfmark) from orders.IMAGE where not shelfmark like '%Corson%' and not shelfmark like '%Coll%' and not shelfmark like 'EU%' and jpeg_path like 'http%' order by rand();";
        $shelfresult = mysqli_query($link, $shelfsql);
        $rec_count = mysqli_num_rows($shelfresult);
        $i = 0;
        while ($row = $shelfresult->fetch_assoc()) {
            $shelf[$i] = $row['shelfmark'];
            $i++;
        }

        echo "<h3>We're working with ".$rec_count." Shelfmarks</h3>";



        echo '<p><a href= "download.php?file=files/shelfreport.csv"><h4>Download Summary File</h4></a></p>';


        echo    '<div class = "box" >
               <table class ="lightbox">
                  <tr>';

                    $rec_limit = 50;
                    $i = 0;
					$j = 0;
					if ($rec_limit > $rec_count)
					{
						$hits = $rec_count;
					}
					else
					{
						$hits = $rec_limit;
					}
                    while ($i < $hits)
                    {
                        $manifestshelf = str_replace(" ","-",$shelf[$i]);
                        $manifestshelf = str_replace("*","-",$manifestshelf);
                        $manifestshelf = str_replace("/","-",$manifestshelf);
                        $manifestshelf = str_replace(".","-",$manifestshelf);
                        $imagesql = "select jpeg_path from orders.IMAGE where shelfmark = '" . $shelf[$i] . "' order by rand() limit 1;";
                        $imageresult = mysqli_query($link, $imagesql);
                        while ($row = $imageresult->fetch_assoc()) {
                            $image = $row['jpeg_path'];
                        }
                        $pagesql ="select count(*) as pagecount from orders.IMAGE where shelfmark ='". $shelf[$i] ."';";
                        $pageresult = mysqli_query($link, $pagesql);
                        $s = '';
                        while ($row = $pageresult->fetch_assoc()) {
                            $pagecount= $row['pagecount'];
                            if($pagecount > 1)
                            {
                                $s = 's';
                            }
                        }


                        $iiifurl = str_replace('detail', 'iiif', $image) . '/1000,1000,200,200/200,200/0/default.jpg';

                        echo '<td>
								<div class = "itembox">

									<div class = "lightheading">
									     <a href="https://librarylabs.ed.ac.uk//iiif/uv?manifest=https://test.librarylabs.ed.ac.uk/speccollprototype/manifests/'.$manifestshelf.'.json" target=""_blank"><img src ="' . $iiifurl . '" </a>
                                    </div>
                                    <div class= "mdbox">
										<table>
											<tr>
												<td class="searchResults"><span class ="md">' . $shelf[$i] . ' ('.$pagecount.' image'.$s.') </span></td>
											</tr>
										</table>
									</div>
								</div>
								</td>
                                ';
                        $i++;
                        $j++;
                        if ($j == 5)
                        {
                            echo '</tr><tr>';
                            $j = 0;
                        }
                    }

                    echo '</form>

                </table>
            </div>';
/*

            <div class = "box">
                <table>';
                $i = 0;
                $remaining = $rec_count;
                if ($page > 0)
                {
                    if ($left_rec < $rec_limit)
                    {
                        $last = $page - 2;

                        echo "<tr><td><a href=\"$_PHP_SELF?page=$last&search_term=$search_term&orderby=$order_field&dir=$order_dir\">Prev</a></td>";
                        echo "<td class = \"tiny\"><a href=\"$_PHP_SELF?search_term=$search_term&order_field='$order_field'&order_dir=$order_dir&rec_limit=$rec_limit\">1</a> |</td>";
                        while ($remaining > $rec_limit)
						{
							$remaining = $rec_count - ($rec_limit * ($i + 1));
							$page_disp = $i + 2;
							if ($page_disp <= 10)
							{
								echo "<td><a href=\"$_PHP_SELF?page=$i&search_term=$search_term&order_field='$order_field'&order_dir=$order_dir&rec_limit=$rec_limit\">$page_disp</a> | </td>";
							}
							else
							{
								echo "<td>.</td>";
							}
							$i++;
                        }
                        echo '<td> Next</td>';

                    }
                    else
                    {
                        $last = $page - 2;
                        echo "<tr><td><a href=\"$_PHP_SELF?page=$last&search_term=$search_term&orderby=$order_field&dir=$order_dir\">Prev</a> |</td>";
                        echo "<td class = \"tiny\"><a href=\"$_PHP_SELF?search_term=$search_term&order_field='$order_field'&order_dir=$order_dir&rec_limit=$rec_limit\">1</a> | </td>";
						while ($remaining > $rec_limit)
						{

							$remaining = $rec_count - ($rec_limit * ($i + 1));
							$page_disp = $i + 2;
							if ($page_disp <= 10)
							{
								echo "<td><a href=\"$_PHP_SELF?page=$i&search_term=$search_term&order_field='$order_field'&order_dir=$order_dir&rec_limit=$rec_limit\">$page_disp</a> | </td>";
							}
							else
							{
								echo "<td>.</td>";
							}
							$i++;
                        }
                        echo"<td ><a href=\"$_PHP_SELF?page=$page&search_term=$search_term&order_field='$order_field'&order_dir=$order_dir&rec_limit=$rec_limit\">Next</a></td>";
                    }
                }
                else if ($page == 0)
                {
                    if ($left_rec > $rec_limit)
                    {
                         echo '<tr><td>Prev </td>';
                        echo "<td  class = \"tiny\"><a href=\"$_PHP_SELF?search_term=$search_term&order_field='$order_field'&order_dir=$order_dir&rec_limit=$rec_limit\">1</a> | </td>";
                        while ($remaining > $rec_limit)
						{

							$remaining = $rec_count - ($rec_limit * ($i + 1));
							$page_disp = $i + 2;
							if ($page_disp <= 10)
							{
								echo "<td><a href=\"$_PHP_SELF?page=$i&search_term=$search_term&order_field='$order_field'&order_dir=$order_dir&rec_limit=$rec_limit\">$page_disp</a> | </td>";
							}
							else
							{
								echo "<td>.</td>";
							}
							$i++;
                        }
                        echo "<td><a href=\"$_PHP_SELF?page=$page&search_term=$search_term&order_field='$order_field'&order_dir=$order_dir&rec_limit=$rec_limit\">Next</a></td>";


                    }
                }
                echo '
</tr>
                </table>
                <table>
                <tr>
                <td>
						<form method = "post" action = "searchLightBox.php?search_term=' . $search_term . '&order_dir=' . $order_dir . '&order_field=\'' . $order_field . '\'">
    Show
								<select name="rec_limit" size="1">
								<OPTION value=8>8</option>
								<OPTION value=16>16</option>
								<OPTION value=32>32</option>
								<OPTION value=64>64</option>
								</select>
results per page
<td><input type = "hidden" name="search_term" value = "' . $search_term . '"/></td>
										<td><input type = "submit" value = "Go"/></td>
									</form>
								</td>
							</tr>
					</table>
                </div>';
*/
            ?>

</div>
</body>

</html>
