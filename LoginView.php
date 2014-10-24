<?php

	require_once("CookieStorage.php");

	class LoginView {

		private $model;
		private $loginCredentials;
		private $passwordInput;
		private $usernameInput;
		private $cookieStorage;
		private $usernamePlaceholder = "";
		private $usrAction;
		public $feedbackMsg;
		private $timestamp;

		public function __construct(LoginModel $model) {
			$this->model = $model;
			$this->loginCredentials = $this->model->getLoginCredentials();
			$this->cookieStorage = new CookieStorage(); 
		}

		public function didUserSucessfullyLogOn() {

			$this->usernameInput = $_POST['Username'];
			$this->passwordInput = $_POST['Password'];

			foreach($this->loginCredentials as $username => $password) {

				if($username == $_POST['Username']) {
					if($password == $_POST['Password']) {
						//Användaren har angivit allt korrekt
						$this->model->setSessionUsername($username);
						$_SESSION['userLoggedOn'] = true;
						$_SESSION['userAgent'] = $_SERVER["HTTP_USER_AGENT"];
						return true;
					}
				}
			}

			return false;
		}

		public function howDidUserFailLogin() {
			if((isset($_POST['Username']) && $_POST['Username'] == "") && (isset($_POST['Password']) && $_POST['Password'] == "")) {
				return "Username is missing.";
			} else if((isset($_POST['Username']) && $_POST['Username'] == "") && (isset($_POST['Password']) && $_POST['Password'] != "")) {
				return "Username is missing";
			} else if((isset($_POST['Username']) && $_POST['Username'] != "") && (isset($_POST['Password']) && $_POST['Password'] == "")) {
				$this->usernamePlaceholder = $_POST['Username'];
				return "Password is missing";
			} else {
				$this->usernamePlaceholder = $_POST['Username'];
				return "Wrong username or/and password.";
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

		public function getFeedback() {
			return $this->feedbackMsg;
		}

		public function showLogInForm() {

			$_usernamePlaceholder = $this->usernamePlaceholder;

			$dateTimeStr = $this->model->getDateTime();

			$ret ="
				<h1>Laborationskod hg222dt</h1>
<h2>Ej inloggad</h2>
<form action='' method='post'>
	<fieldset>
		<legend>Logga in med användarnamn och lösenord</legend>
		<p>".$this->getFeedback()."</p>
		<label for='usrnameId'>Username</label>
		<input type='text' id='usrnameId' size='20' name='Username' value='$_usernamePlaceholder'>
		<label for='passwordId'>Password</label>
		<input type='password' id='passwordId' size='20' name='Password' placeholder='********'>
		<label for='keepLoggedInId'>Keep me logged on</label>		
		<input type='checkbox' id='keepLoggedInId' name='KeepSignedIn'>
		<input type='submit' name='loginFormPosted' value='Log in'>
	</fieldset>
</form>
<p>$dateTimeStr</p>
			";

/*			if($this->didUserPressLogOff()) {
				header('Location: ' . $_SERVER['PHP_SELF']);
				setcookie("loggedInUsername", "", -1);
				setcookie("loggedInPassword", "", -1);
			}
*/
			return $ret;
		}

		public function showLoggedInPage(){

			$username = $this->model->getSessionUsername();
			$dateTimeStr = $this->model->getDateTime();

			$ret ="
				<h1>$username är inloggad!</h1>
				<h2>".$this->getFeedback()."</h2>
				<form action='' method='post'>
				<input type='submit' value='Logga ut!' name='userPressedLogOff'>
				</form>
				<p>$dateTimeStr</p>
			";

			//if($this->didUserPostForm()) {
			//	header('Location: ' . $_SERVER['PHP_SELF']);
			//}

			return $ret;

		}

		public function didUserPressKeepSignedIn() {
			if(isset($_POST['KeepSignedIn'])) {
				return true;
			}
			return false;
		}

		public function getPostedUsername() {
			if(isset($_POST['Username'])){
				return $_POST['Username'];
			} 
		}

		public function getUserAction() {

			//Om anändaren är inloggad
			if($this->model->isUserLoggedOn()) {
				//Om användaren valt att logga ut
				if($this->didUserPressLogoff()) {
					$this->usrAction = 1;
				} 

				//Om användaren loggar in
				else {
					$this->usrAction = 2;
				}
			}

			else {
				//Om användaren har sparat sin inloggning
				if($this->isUserLoggedOnByCookie($this->getCookieUsername(), $this->getCookiePassword()) && $this->didUserPressLogoff() !== true) {
					$this->usrAction = 3;
				}
				//Om användaren har postat login-formulär
				else if($this->didUserPostForm()) {

					//Om användaren lyckas med inloggning
					if($this->didUserSucessfullyLogOn($this->model->getLoginCredentials())) {
						if($this->didUserPressKeepSignedIn()){
							$this->usrAction = 4;
						} else {
							$this->usrAction = 5;
						}
					}

					//Om användaren misslyckas med inloggning
					else {
						$this->usrAction = 6;
					}
				}
				//Om användaren bara laddar sidan och ska se registreringsformulär
				else {
					$this->usrAction = 7;
				}
			}

			return $this->usrAction;
		}


		//cookie stuff

		

		public function getCookieUsername() {
			if(isset($_COOKIE['loggedInUsername']))
				return $_COOKIE['loggedInUsername'];
		}

		public function getCookiePassword() {
			if(isset($_COOKIE['loggedInPassword']))
				return $_COOKIE['loggedInPassword'];
		}

		public function isUserLoggedOnByCookie($username, $password) {
			if($this->doesCookieUsernameAndPasswordExist($username, $password)) {

				$verificationToken =  $this->createSecureIdentifierForVerif($password, $username);
				$this->temp1 = $verificationToken;

				if($this->model->verifyCookieCredentials($verificationToken)) {
					return true;
				}
			}
		}

		public function doesCookieUsernameAndPasswordExist($username, $password) {
			if($username != null && $password != null) {
				return true;
			}
			return false;
		}

		public function bakeNewCookies() {

			$hashedPassword = $this->getHashedPassword();
			$this->createCookies($this->getPostedUsername(), $hashedPassword);

			$secureIdentifier = $this->createSecureIdentifier($hashedPassword);
			$this->model->saveSecureIdentifier($secureIdentifier);
		}

		public function getHashedPassword() {
			return md5($_POST['Password']);
		}

		public function createSecureIdentifier($hashedPassword) {
			return $_POST['Username'] . "," . $hashedPassword . "," . $this->timestamp . "," . $_SERVER['REMOTE_ADDR'];
		}

		public function createSecureIdentifierForVerif($hashedPassword, $username) {
			return $_COOKIE['loggedInUsername'] . "," . $hashedPassword . "," . $this->timestamp . "," . $_SERVER['REMOTE_ADDR'];
		}

		public function createCookies($username, $hashedPassword) {
			$this->timestamp = time() + 1200;
			setcookie("timestamp", $this->timestamp, $this->timestamp);
			setcookie("loggedInUsername", $username, $this->timestamp);
			setcookie("loggedInPassword", $hashedPassword, $this->timestamp);
		}
	}