<?php

	require_once("UserCredentialsModel.php");


	class LoginModel {

		private $loginCredentials;

		public function __construct() {
			$this->loginCredentials = new UserCredentialsModel();
		}

		public function getDateTime() {
			setlocale(LC_ALL,"sv");
			return ucfirst(utf8_encode(strftime("%A, den %d %B år %Y. Klockan är [%X]")));
		}

/*
		public function didUserCheckBox() {
			if (isset($_POST['KeepSignedIn']) && $_POST['KeepSignedIn'] == true) {
				$_SESSION["isUserPersistantLoggedIn"] = true;
				return true;
			}
			return false;
		}
*/
		public function isUserPersistantLoggedIn() {
			if(isset($_POST['KeepSignedIn'])){
				$_SESSION['isUserPersistantLoggedIn'] = true;
				return true;
			} else {
				if($_SESSION['isUserPersistantLoggedIn'] == true) {
					return true;
				}
				return false;
			}			
		}

		public function isUserLoggedOn() {
			if((isset($_SESSION['userLoggedOn']) && $_SESSION['userLoggedOn'] == true)) {
				return true;
			}
		}

		public function setSessionUsername($username) {
			$_SESSION['Username'] = $username;
		}

		public function getLoginCredentials() {
			return $this->loginCredentials->credentials;
		}
	}