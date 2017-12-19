<!DOCTYPE html>

<html lang="en">
	<head>
		<TITLE>DIU Photography Ordering System</TITLE>
		<link rel="stylesheet" type ="text/css" href="diustyles.css">
		<meta name="author" content="Library Online Editor">
		<meta name="description" content="Edinburgh University Library Online: Book purchase request forms for staff: Medicine and Veterinary">
		<meta name="distribution" content="global">
		<meta name="resource-type" content="document">
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	</HEAD>
	<BODY BGCOLOR="#FFFFFF">
		<div align="center">
			<table>
				<tr>
					<td bgcolor="#025193" align="left">
						<a href="http://www.is.ed.ac.uk/" title="Link to Information Services home page">
							<img src="images/header4.jpg" alt="The University of Edinburgh Image Collections" width="754" height="65" border="0">
						</a>
					</td>
				</tr>
			</table>
			<table cellpadding="5" cellspacing="0" border="0" width="754"  bgcolor="#f0f0f0">
			<tr>
				<td>
					<H2>MEMBERS AREA</H2>
				</td>
			</tr>
			<?php

				#DIU Numbers
				#Scott Renton, 02/08/2010
				include 'vars.php';
				$error = '';
				// //get_and_fill('lac_galli');
				// //get_and_fill('lac_wmman');
				// //get_and_fill('lac_carwatson');
				// //get_and_fill('lac_shake');
				// //get_and_fill('lac_walter');
				
				// function get_and_fill($databaselive)
				// {
				// $dbserverlive = 'lac-live1.is.ed.ac.uk:3306';
				// $usernamelive = 'insight';
				// $passwordlive = 'lepwom8';
				// $linklive = mysql_connect($dbserverlive, $usernamelive, $passwordlive);
				// @mysql_select_db($databaselive, $linklive) or die ( "Unable to select database");
				
				// $tag_sql="select o.objectid as tag_objectid, 
				// v.valuetext as tag_valuetext, i.displayname as displayname
				// from DTVALUETOOBJECT o, 
				// DTVALUES v , IRFIELDS i
				// where v.valueid = o.valueid
				// and v.fieldid = i.fieldid
				// and i.displayname like 'Subject%';";

				// $tag_result=mysql_query($tag_sql,$linklive) or die( "A MySQL error has occurred.<br />Your Query: " . $tag_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
				// $tag_numrows = mysql_num_rows($tag_result);
				// $tag_row=mysql_fetch_array($tag_result);
				
				// $i = 0;				
				// while ($i < $tag_numrows)
					// {
						// $tag_objectid[$i] = mysql_result($tag_result, $i, "tag_objectid");
						// $tag_valuetext[$i] = mysql_result($tag_result, $i, "tag_valuetext");
						// $i++;
					// }
					
				// $title_sql = "select distinct o.objectid as title_objectid, v.valuetext as title_valuetext
					// from DTVALUES v, DTVALUETOOBJECT o, IRFIELDS i
					// where i.fieldid = v.fieldid
					// and o.valueid = v.valueid
					// and i.displayname = 'Repro Title';";
				
				// $title_result=mysql_query($title_sql,$linklive) or die( "A MySQL error has occurred.<br />Your Query: " . $title_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
				// $title_numrows = mysql_num_rows($title_result);
				// $title_row=mysql_fetch_array($title_result);
				
				// $i = 0;				
				// while ($i < $title_numrows)
					// {
						// $title_objectid[$i] = mysql_result($title_result, $i, "title_objectid");
						// $title_valuetext[$i] = mysql_result($title_result, $i, "title_valuetext");
						// $i++;
					// }
					
				// $id_sql = "select o.objectid as id_objectid, v.valuetext as id_valuetext
					// from DTVALUES v, DTVALUETOOBJECT o, IRFIELDS i
					// where i.fieldid = v.fieldid
					// and o.valueid = v.valueid
					// and i.displayname = 'Work Record ID';";
				
				// $id_result=mysql_query($id_sql,$linklive) or die( "A MySQL error has occurred.<br />Your Query: " . $id_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
				// $id_numrows = mysql_num_rows($id_result);
				// $id_row=mysql_fetch_array($id_result);
				
				// $i = 0;				
				// while ($i < $id_numrows)
				// {
						// $id_objectid[$i] = mysql_result($id_result, $i, "id_objectid");
						// $id_valuetext[$i] = mysql_result($id_result, $i, "id_valuetext");
						// $i++;
				// }
				// include 'vars.php';
				// $link = mysql_connect($dbserver, $username, $password);
				// @mysql_select_db($database, $link) or die ( "Unable to select database");
				// $j = 0;
				// while ($j < $tag_numrows)
				// { 
					// $tag_valuetext[$j] = str_replace("'", "&#39;", $tag_valuetext[$j]);
					// $tag_in_sql = "insert into TAG values (".$tag_objectid[$j].", '".$tag_valuetext[$j]."','".$databaselive."');";
					// $tag_result=mysql_query($tag_in_sql,$link) or die( "A MySQL error has occurred.<br />Your Query: " . $tag_in_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
					// $j++;
				// }
				
				// $j = 0;				
				// while ($j < $title_numrows)
				// { 
					// $title_valuetext[$j] = str_replace("'", "&#39;", $title_valuetext[$j]);
					// $title_in_sql = "insert into TITLE (objectid, title, db_name) VALUES (".$title_objectid[$j].", '".$title_valuetext[$j]."','".$databaselive."');";
					// $title_result=mysql_query($title_in_sql,$link) or die( "A MySQL error has occurred.<br />Your Query: " . $title_in_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
					// $j++;
				// }
				
				// $j = 0;				
				// while ($j < $id_numrows)
				// { 
					// $id_in_sql = "insert into OBJECTIMAGE VALUES (".$id_objectid[$j].", '".$id_valuetext[$j]."','".$databaselive."');";
					// $id_result=mysql_query($id_in_sql,$link) or die( "A MySQL error has occurred.<br />Your Query: " . $id_in_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
					// $j++;
				// }				
				// }
				
				$outfile = "flickr_uploader/files.xml";
				$infile = "flickrids.txt";
				$file_handle_in = fopen($infile, "r")or die("can't open infile");
				$file_handle_out = fopen($outfile, "w")or die("can't open this outfile");
				fwrite($file_handle_out,"<?xml version=\"1.0\" encoding=\"utf-8\"?>\n");
				fwrite($file_handle_out,"<contentdm>\n");
				
				$link = mysql_connect($dbserver, $username, $password);
				@mysql_select_db($database, $link) or die ( "Unable to select database");
				
				while (!feof($file_handle_in))
				{
					$line = fgets($file_handle_in);
					$linecompare = ltrim($line,'0');					
					$linecompare = substr_replace($linecompare,"",-6);
					$xml_title_sql = "select distinct t.title as title from  TITLE t, OBJECTIMAGE o where t.objectid = o.objectid and o.imageid = ".$linecompare.";";
					$xml_title_result=mysql_query($xml_title_sql,$link) or die( "A MySQL error has occurred.<br />Your Query: " . $xml_title_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
					$xml_title_numrows = mysql_num_rows($xml_title_result);
					$xml_title_row=mysql_fetch_array($xml_title_result);
					$xml_title[0] = mysql_result($xml_title_result, 0, "title");
					fwrite($file_handle_out,"<item>\n");
					fwrite($file_handle_out,"<title>");
					fwrite($file_handle_out,$xml_title[0]);
					fwrite($file_handle_out,"</title>\n");
					fwrite($file_handle_out,"<tags>");
					$xml_tag_sql = "select distinct t.tag as tag from  TAG t, OBJECTIMAGE o where t.objectid = o.objectid and o.imageid = ".$linecompare.";";
					$xml_tag_result=mysql_query($xml_tag_sql,$link) or die( "A MySQL error has occurred.<br />Your Query: " . $xml_tag_sql . "<br /> Error: (" . mysql_errno() . ") " . mysql_error());
					$xml_tag_numrows = mysql_num_rows($xml_tag_result);
					$xml_tag_row=mysql_fetch_array($xml_tag_result);
					$i = 0;
					while ($i < $xml_tag_numrows)
					{
						$xml_tag[$i] = mysql_result($xml_tag_result, $i, "tag");
						fwrite($file_handle_out,$xml_tag[$i]);
						fwrite($file_handle_out,",");
						$i++;
					}
					fwrite($file_handle_out,"</tags>\n");
					fwrite($file_handle_out,"<file>");
					$line = str_replace("\n","",$line);
					fwrite($file_handle_out,$line);
					fwrite($file_handle_out,"</file>\n");
					fwrite($file_handle_out,"</item>\n");
					
					$folder = substr($line, 1, 4).'000-'. substr($line, 1, 4).'999';
					$file = 'images/'.$folder.'/'.$line;
					echo $file;
					$destination = 'flickr_uploader/contentdm/';
					if (!copy($file, $destination)
					{
						echo "failed to copy $file...\n";
					}
				}
				fclose($file_handle_in);
				fwrite($file_handle_out,"</contentdm>");
				fclose($file_handle_out);
				
				
print('
					</TABLE>
				</div>
			<!-- FOOTER CODE -->
			</body>
		</html>
		');

?>