<?php

	require_once("CookieStorage.php");

	class LoginView {

		private $model;
		private $loginCredentials;

		private $passwordInput;
		private $usernameInput;

		private $cookieStorage;

		private $usernamePlaceholder = "";

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

		public function showLogInForm($logInMsg) {

			$_usernamePlaceholder = $this->usernamePlaceholder;

			$dateTimeStr = $this->model->getDateTime();

			if($logInMsg !== "") {
				$logInMsgStr = "<p>$logInMsg<p>";
			} else {
				$logInMsgStr = "";
			}

			$ret ="
				<h1>Laborationskod hg222dt</h1>
<h2>Ej inloggad</h2>
<form action='' method='post'>
	<fieldset>
		<legend>Logga in med användarnamn och lösenord</legend>
		$logInMsgStr
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

			if($this->didUserPressLogOff()) {
				header('Location: ' . $_SERVER['PHP_SELF']);
				setcookie("loggedInUsername", "", -1);
				setcookie("loggedInPassword", "", -1);
			}

			return $ret;
		}

		public function showLoggedInPage($msgStr){

			$username = $this->model->getSessionUsername();
			$dateTimeStr = $this->model->getDateTime();

			$ret ="
				<h1>$username är inloggad!</h1>
				<h2>$msgStr</h2>
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

		//cookie stuff

		public function getHashedPassword() {
			return crypt($_POST['Password']);
		}

		public function createSecureIdentifier($hashedPassword) {
			return $_POST['Username'] . "," . md5($hashedPassword . $_COOKIE['timestamp'] . $_SERVER['REMOTE_ADDR']);
		}

		public function createSecureIdentifierForVerif($hashedPassword, $username) {
			return $username . "," . md5($hashedPassword . $_COOKIE['timestamp'] . $_SERVER['REMOTE_ADDR']);
		}

		public function createCookies($username, $hashedPassword) {
			$timestamp = time() + 1200;
			setcookie("timestamp", $timestamp, $timestamp);
			setcookie("loggedInUsername", $username, $timestamp);
			setcookie("loggedInPassword", $hashedPassword, $timestamp);
		}

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
	}