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

		public function setUserLoggedOff() {
			$_SESSION['userLoggedOn'] = false;
		}

		public function setUserLoggedOn() {
			$_SESSION['userLoggedOn'] = true;
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
			if((isset($_SESSION['userLoggedOn']) && $_SESSION['userLoggedOn'] == true) && $this->doUsrAgentControll()) {
				return true;
			}
		}

		public function doUsrAgentControll() {
			if ($_SESSION['userAgent'] === $_SERVER["HTTP_USER_AGENT"]) {
				return true;
			}

			return false;
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

					if (!(preg_match("/\b".preg_quote($username)."\b/i", $trimmedLine))) {
    					$content .= $line . "\n";
					}
				}

				$file = fopen('secureIdentifiers.txt', 'w+');
				fwrite($file, $content);
			}
		}


		public function verifyCookieCredentials($verificationToken) {

			$incomingToken = explode(",", $verificationToken);

			$incomingPass = $incomingToken[1];
			$incomingExpiration = $incomingToken[2];
			$incomingRemoteAddr = $incomingToken[3];

			$lines = @file("secureIdentifiers.txt");
			
			if($lines === false) {
				return null;
			} else {
				foreach ($lines as $line) {
					$line = trim($line);

					$lineParts = explode(",", $line);

					$existingPass = $lineParts[1];
					$existingExpiration = $lineParts[2];
					$existingRemoteAddr = $lineParts[3];

					if (strcmp($existingPass, $incomingPass) === 0 && 
						strcmp($existingRemoteAddr, $incomingRemoteAddr) === 0) {
						return true;
					}
				}
				
				return false;
			}
		}

		public function deleteUserCookies() {
			setcookie("loggedInUsername", "", -1);
			setcookie("loggedInPassword", "", -1);
		}
	}