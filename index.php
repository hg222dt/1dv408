<?php

	require_once("HTMLView.php");
	require_once("LoginController.php");
	require_once("LoginView.php");
	require_once("LoginModel.php");

	session_start();



	$controller = new LoginController();

	$htmlBody = $controller->showSite();

	$view = new HTMLView();
	$view->echoHTML($htmlBody);

