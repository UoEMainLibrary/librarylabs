<?php session_start(); ?>
<?php
	$token = $_POST['idtoken'];
	$cool = $_SESSION['idtoken'];
	echo "cool" . $token;
	var_dump($token);
	echo "123" . $cool;
?>