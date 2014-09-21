<?php

	require_once("UserCredentialsModel.php");


	class LoginModel {

		public  $linesAmount;
		private $loginCredentials;

		public function __construct() {
			$this->loginCredentials = new UserCredentialsModel();

		}

		public function getDateTime() {
			setlocale (LC_ALL, "sv");
			return utf8_encode(ucfirst(strftime("%A"))) . ', den ' . date('d F') . ' år ' . date('Y') . '. Klockan är [' . date('H:i:s') . ']';
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

		public function doesUsernameCookieExist() {
			if(isset($_COOKIE['loggedInUsername'])) {
				$cookieUsername = $_COOKIE['loggedInUsername'];
				return true;
			}
		}

		public function setSessionUsername($username) {
			$_SESSION['Username'] = $username;
		}

		public function getSessionUsername() {
			return $_SESSION['Username'];
		}

		public function getLoginCredentials() {
			return $this->loginCredentials->credentials;
		}

		public function getUsernameCookie() {
			if(isset($_COOKIE['loggedInUsername'])) {
				return $cookieUsername = $_COOKIE['loggedInUsername'];
			}
		}

		public function saveSecureIdentifier($secureIdentifier) {
			$file = fopen('secureIdentifiers.txt', 'a');
			fwrite($file, ($secureIdentifier . "\n"));
		}

		public function deleteSecureIdentifier($username) {
			$lines = @file("secureIdentifiers.txt");
			if($lines === false) {
				return null;
			} else {
				$content = "";

				foreach ($lines as $line) {
					$line = trim($line);

					// TRIMMA ALLT FRAM TILL KOMMAT
					$trimmedLine = substr($line, 0, strpos($line, ","));
					var_dump($trimmedLine);

					if (!(preg_match("/\b".preg_quote($username)."\b/i", $trimmedLine))) {
    					$content .= $line . "\n";
					}
				}

				$file = fopen('secureIdentifiers.txt', 'w+');
				fwrite($file, $content);
			}
		}


		public function verifyCookieCredentials($verificationToken) {

			$verificationToken = $verificationToken;

			$lines = @file("secureIdentifiers.txt");
			
			if($lines === false) {
				return null;
			} else {
				foreach ($lines as $line) {
					$line = trim($line);

					if (strcmp($verificationToken, $line)) {
						return true;
					}
				}
				
				return false;
			}
		}
	}