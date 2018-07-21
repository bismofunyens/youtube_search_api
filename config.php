<?php
	session_start();
	require_once "GoogleAPI/vendor/autoload.php";
	require_once "google_client.php";
	$gClient = new Google_Client();
	$gClient->setClientId($google_client_id);
	$gClient->setClientSecret($google_client_secret);
	$gClient->setApplicationName("CPI Login Tutorial");
	$gClient->setRedirectUri($google_redirect_url);
	$gClient->addScope("https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email");
?>
