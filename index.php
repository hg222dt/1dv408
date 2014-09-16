<?php

	require_once("HTMLView.php");
	require_once("Form.php");
	require_once("formView.php");

	$view = new HTMLView();

	$vfc = new \controller\Form();

	$htmlBody = $vfc->doControll();

	$view->echoHTML($htmlBody);