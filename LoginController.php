<?php

require_once("LoginView.php");

	class LoginController {

		private $model;
		private $view;

		public function __construct() {
			$this->model = new LoginModel();
			$this->view = new LoginView($this->model);
		}

		public function showSite() {

			$loginMessage = "";

			if($this->view->didUserPostForm()){
				if($this->view->didUSerSucessfullyLogOn($this->model->getLoginCredentials())) {
					$_SESSION['userLoggedOn'] = true;
					$loginMessage = "";
				} else {
					//sök upp vad felet är. fattas användarnamn eller/och lösenord. sätt variabel till detta.
					$loginMessage = $this->view->howDidUserFailLogin();
				}
			}

			if($this->view->didUserPressLogoff()) {
				$_SESSION['userLoggedOn'] = false;
			}


			if($this->model->isUserLoggedOn()) {
				return $this->view->showLoggedInPage("");
			} else {
				return $this->view->showLoginForm($loginMessage);
			}
		}
	}