<?php

	class LoginModel {

		public function __construct() {

		}

		public function getLogInForm($logInMsg) {

			$dateTimeStr = $this->getDateTime();

			if($logInMsg !== "") {
				$logInMsgStr = "<p>$logInMsg<p>";
			} else {
				$logInMsgStr = "";
			}

			$ret ="
				<h1>Inloggning</h1>
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

			return $ret;
		}

		public function getLoggedInPage($msgStr){

			$ret ="
				<h1>Inloggad!</h1>
				<h2>$msgStr</h2>
				<form action='' method='post'>
				<input type='submit' value='Logga ut!' name='userPressedLogOff'>
				</form>
			";

			return $ret;

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

		

	}