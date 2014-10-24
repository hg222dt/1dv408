<?php

require_once("LoginView.php");

	class LoginController {

		private $model;
		private $view;
		public $loggedInMessage;
		public $loginFormMessage;
		private $userLoggesOnByCookie;
		private $firstLoad;
		private $loggedInByCookie;

		public function __construct() {
			$this->model = new LoginModel();
			$this->view = new LoginView($this->model);
			$this->loggedInMessage = "";
			$this->firstLoad = true;
			$this->loggedInByCookie = false;
		}

		public function showSite() {

			//Actions
			$userLogsOut = 1;
			$userLogsInDefault = 2;
			$userLoggedOnByCookie = 3;

			$userLogsOnKeepSignedIn = 4;
			$userLogsOnNotKeep = 5;
			$userFailedLogin = 6;
			$userDefaultLoadForm = 7;


			$action = $this->view->getUserAction();

			switch ($action) {

				case $userLogsOut:
					$this->view->feedbackMsg = "User logged out.";
					$this->model->deleteSecureIdentifier($this->view->getCookieUsername());
					$this->model->deleteUserCookies();
					$this->model->setUserLoggedOff();
					$this->model->setSessionUsername("");
					return $this->view->showLoginForm();
					break;

				case $userLogsInDefault:
					$this->view->feedbackMsg = "User logged in default";
					return $this->view->showLoggedInPage();
					break;

				case $userLoggedOnByCookie:
					$this->view->feedbackMsg = "User logged in by cookie";
					$this->model->setSessionUsername($_COOKIE['loggedInUsername']);
					$this->userLoggesOnByCookie = true;
					$this->model->setUserLoggedOn();
					return $this->view->showLoggedInPage();
					break;

				case $userLogsOnKeepSignedIn:
					$this->view->feedbackMsg = "User logged in and will be kept logged on.";
					$this->model->setUserLoggedOn();
					$this->view->bakeNewCookies();
					$this->loggedInByCookie = true;
					return $this->view->showLoggedInPage();
					break;

				case $userLogsOnNotKeep:
					$this->view->feedbackMsg = "User logged on without persistant logIn";
					$this->model->setUserLoggedOn();
					return $this->view->showLoggedInPage();
					break;

				case $userFailedLogin:
					$this->view->feedbackMsg = $this->view->howDidUserFailLogin();
					return $this->view->showLoginForm();
					break;

				case $userDefaultLoadForm:
					$this->view->feedbackMsg = "Go ahead fill in your credentials!";
					return $this->view->showLoginForm();					
					break;

				default:
					break;
			}
			



/*

			//$this->loggedInMessage = "";

			//Har användaren postat login-formulär?
			if($this->view->didUserPostForm()) {
				if($this->view->didUSerSucessfullyLogOn($this->model->getLoginCredentials())) {
					$_SESSION['userLoggedOn'] = true;
					
					if($this->view->didUserPressKeepSignedIn()) {
						$this->view->bakeNewCookies();
						$this->loggedInByCookie = true;
						$this->loggedInMessage = "Inloggning lyckades och vi kommer ihåg dig nästa gång.";
					}
				} else {
					$this->loginFormMessage = $this->view->howDidUserFailLogin();
				}
			}

			//Om användaren tryckte på logoff-knappen
			if($this->view->didUserPressLogoff()) {
				$_SESSION['userLoggedOn'] = false;

				$this->model->deleteSecureIdentifier($this->view->getCookieUsername());
			}

			//Har användaren loggat på med cookie?
			if($this->model->isUserLoggedOn() == false){
				if(($this->view->isUserLoggedOnByCookie($this->view->getCookieUsername(), $this->view->getCookiePassword()) && !$this->view->didUserPressLogoff())) {
					$this->userLoggesOnByCookie = true;
					$_SESSION['userLoggedOn'] = true;
					$this->loggedInMessage = "Användaren loggade in med hjälp av cookie";
				
				//Om inte cookien är korrekt	
				} else if (!($this->view->isUserLoggedOnByCookie($this->view->getCookieUsername(), $this->view->getCookiePassword()))) {
					if(!($this->view->didUserPressLogoff()) && !($this->view->didUserPostForm())) {
						//$this->loggedInMessage = "Felaktig information i cookie.";
						setcookie("loggedInUsername", "", -1);
						setcookie("loggedInPassword", "", -1);
					}
				}
			}

			if($this->model->isUserLoggedOn()) {

				if(isset($_COOKIE['loggedInUsername'])){
					$this->model->setSessionUsername($_COOKIE['loggedInUsername']);
				}

//				$msgStr = $this->loggedInMessage;
//				$this->loggedInMessage = "";

				return $this->view->showLoggedInPage($this->loggedInMessage);
			} else {
				$msgStr = $this->loginFormMessage;
				$this->loginFormMessage = "";

				return $this->view->showLoginForm($msgStr);
			}

			*/
		}

	}