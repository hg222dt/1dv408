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

			if($this->model->isUserLoggedIn()){
				//Visa inloggad sida
				$_SESSION['isUserLoggedIn'] = true;

				if($this->model->isUserPersistantLoggedIn()){
					//Visa meddelande "user persistant Logged in"
					return $this->model->getLoggedInPage("User is persistant logged in.");
				} else {
					//Visa inget meddelande.
					return $this->model->getLoggedInPage("");
				}

			} else {
				if($this->model->didUserPostLoginForm()) {
					//visa felmeddelande
					return $this->model->getLogInForm("Wrong password or username");
				} else {
					//visa formulär
					return $this->model->getLogInForm("");
				}
			}
		}
	}