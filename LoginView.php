<?php

	class LoginView {

		private $model;
		private $loginCredentials;

		private $passwordInput;
		private $usernameInput;

		public function __construct(LoginModel $model) {
			$this->model = $model;
			$this->loginCredentials = $this->model->getLoginCredentials();
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

			//$loginCredentials = $this->model->getLoginCredentials();

			//var_dump($this->model->getLoginCredentials());
			//var_dump($_POST['Username']);
			//var_dump($_POST['Password']);

			$usernameInput = $_POST['Username'];
			$passwordInput = $_POST['Password'];

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
				return "Password is missing";
			} else if ((isset($_POST['Username']) && $_POST['Username'] != $usernameInput) && (isset($_POST['Password']) && $_POST['Password'] == $passwordInput)) {
				//fel användarnamn
				return "Wrong username or/and password.";
			} else if ((isset($_POST['Username']) && $_POST['Username'] == $usernameInput) && (isset($_POST['Password']) && $_POST['Password'] != $passwordInput)) {
				//Fel lösenord
				return "Wrong username or/and password.";
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



		public function showLogInForm($logInMsg) {

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
		<input type='text' id='usrnameId' size='20' name='Username' value>
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
			}

			return $ret;
		}

		public function showLoggedInPage($msgStr){

			$username = $this->model->getSessionUsername();
			

			$ret ="
				<h1>$username är inloggad!</h1>
				<h2>$msgStr</h2>
				<form action='' method='post'>
				<input type='submit' value='Logga ut!' name='userPressedLogOff'>
				</form>
			";

			if($this->didUserPostForm()) {
				header('Location: ' . $_SERVER['PHP_SELF']);
			}

			return $ret;

		}


	}