<?php

	class LoginView {

		private $model;

		public function __construct(LoginModel $model) {
			$this->model = $model;
		}

/*
		public function ShowLoggedInPage() {
			if($this->model->isUserPersistantLoggedIn()) {
				//Write a message
				return $this->model->getLoggedInPage("UserIsPersistantLoggedIn");
			} else {
				return $this->model->getLoggedInPage("");
			}
		}

		public function showLogInForm() {
			if($this->whatWentWrongOnLogIn) {
				$this->model->getLoggedInPage();
			}
		}
*/

		public function didUserSucessfullyLogOn() {
			if(isset($_POST['loginFormPosted'])){
				if(($_POST['Username'] == "hej") && ($_POST['Password'] == "hej")) {
					$this->model->setSessionUsername("hej");
					return true;
				} else {
					$this->model->setSessionUsername("hej");
					return false;
				}
			}
		}

		public function howDidUserFailLogin() {
			if((isset($_POST['Username']) && $_POST['Username'] == "") && (isset($_POST['Password']) && $_POST['Password'] == "")) {
				return "Both username and password are missing";
			} else if((isset($_POST['Username']) && $_POST['Username'] == "") && (isset($_POST['Password']) && $_POST['Password'] != "")) {
				return "Username is missing";
			} else if((isset($_POST['Username']) && $_POST['Username'] != "") && (isset($_POST['Password']) && $_POST['Password'] == "")) {
				return "Password is missing";
			} else if ((isset($_POST['Username']) && $_POST['Username'] != "hej") && (isset($_POST['Password']) && $_POST['Password'] == "hej")) {
				//fel användarnamn
				return "Wrong username.";
			} else if ((isset($_POST['Username']) && $_POST['Username'] == "hej") && (isset($_POST['Password']) && $_POST['Password'] != "hej")) {
				//Fel lösenord
				return "Wrong password.";
			} else {
				//Fel båda
				return "Both username and password is incorrect.";
			}
		}

		public function didUserPostForm() {
			if(isset($_POST['loginFormPosted'])) {
				return true;
			}
			return false;
		}

		public function didUserPressLogoff() {
			if(isset($_POST['userPressedLogOff'])) {
				return true;
			}
			return false;
		}
	}