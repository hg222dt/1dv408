<?php

require_once("LoginView.php");

	class LoginController {

		private $model;
		private $view;

		public function __construct() {
			$this->model = new LoginModel();
			$this->view = new LoginView($this->model);
		}

		public function doControll() {

			if($this->model->didUserTryLogin()) {

				$keepSignedIn = $this->model->didUserCheckBox();

				if($keepSignedIn == true) {
					$keepSignedInStr = "Vi kommer att hålla dig inloggad.";
				} else {
					$keepSignedInStr = "";
				}

				$didUserPassLogin = $this->model->authenticateUser();

				if($didUserPassLogin) {
					//Show logged in page
					return $this->model->getLoggedInPage($keepSignedInStr);
				} else {
					//Show failed login
					return $this->model->getLogInForm("Wrong username or password");
				}

				$didUserPassLogin = false;

				$_POST["tryLogin"] = false;
			} else {
				if($this->model->isUserPersistantLoggedIn()){
					return $this->model->getLoggedInPage("Vi kommer att hålla dig inloggad...");
				} else {
					return $this->model->getLogInForm("");
				}
			}
		}
	}