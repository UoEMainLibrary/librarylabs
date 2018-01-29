<?php
$filename = $_GET['file'];

//$logfile = $directory."logfile.txt";
//$file_handle_log_out = fopen($logfile, "a+")or die("<p>Sorry. I can't open the logfile.</p>");
//fwrite($file_handle_log_out, 'File: '.$filename."\n");
//header('Content-Type: application/octet-stream');
header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename='.basename($filename));
//header('Content-Disposition: attachment; filename='.$filename);
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filename));
readfile($filename);
exit;
?>