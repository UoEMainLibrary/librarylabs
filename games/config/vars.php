<?php
	$username = '';
	$password = '';
	$database = 'orders';
	$dbserver = 'localhost';
	$rec_limit = '';
  	$default_sort = '';
	$default_dir = 'asc';
	$lunausername = '';
	$lunapassword = '';
	$lunadbserver = '';
    $publickey = '';
    $privatekey = '';

if (!function_exists('mysql_result')) {
    function mysql_result($result, $number, $field=0) {
        mysqli_data_seek($result, $number);
        $row = mysqli_fetch_array($result);
        return $row[$field];
    }
}
?>
