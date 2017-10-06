<?php
session_start();

//Include Google client library 
include_once 'src/Google_Client.php';
include_once 'src/contrib/Google_Oauth2Service.php';

/*
 * Configuration and setup Google API
 */
$clientId = '682814395284-uankisvij90jc6b7g78udvot1170v39s.apps.googleusercontent.com';
$clientSecret = 'vJiBxnxacNq-nT2gOjGMcfCS';
$redirectURL = 'https://test.librarylabs.ed.ac.uk/games/';

//Call Google API
$gClient = new Google_Client();
$gClient->setApplicationName('TestG');
$gClient->setClientId($clientId);
$gClient->setClientSecret($clientSecret);
$gClient->setRedirectUri($redirectURL);

$google_oauthV2 = new Google_Oauth2Service($gClient);
?>