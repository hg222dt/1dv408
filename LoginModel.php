<?php

	class LoginModel {

		//Ska tillhandhålpla login information för respktive användaref
		//Ska tillhanda hålla okm respetktiveanvändare har sparat sin inkoggningsssession.

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
		<input type='submit' name='tryLogin' value='Log in'>
	</fieldset>
</form>
<p>$dateTimeStr</p>
			";

			return $ret;
		}

		public function getLoggedInPage($msgStr){

			$_SESSION['userIsLoggedOf'] = false;

			$ret ="
				<h1>Inloggad!</h1>
				<h2>$msgStr</h2>
				<form action='' method='post'>
				<input type='submit' value='Logga ut!' name='logUserOff'>
				</form>
			";

			return $ret;

		}

		public function getFailedLogin() {
			return "Failed Login";
		}

		public function getDateTime() {
			setlocale(LC_ALL,"sv");
			return ucfirst(utf8_encode(strftime("%A, den %d %B år %Y. Klockan är [%X]")));
		}

		public function didUserTryLogin() {
			if (isset($_POST["tryLogin"])) {
				return true;
			}
			return false;
		}

		public function didUserCheckBox() {
			if (isset($_POST['KeepSignedIn']) && $_POST['KeepSignedIn'] == true) {
				$_SESSION["isUserPersistantLoggedIn"] = true;
				return true;
			}
			return false;
		}

		public function authenticateUser() {

			if ($_POST['Username'] != 'hej') {
				return false;
			}

			if($_POST['Password'] != "hej") {
				return false;
			}  

			if($_POST['Username'] == 'hej' && $_POST['Password'] == "hej") {
				return true;
			}

			return false;
		}

		public function isUserPersistantLoggedIn() {
				if (isset($_SESSION['isUserPersistantLoggedIn']) && $_SESSION['isUserPersistantLoggedIn'] == true) {
				return true;
			}			
			return false;
		}

		public function isUserLoggedIn() {
				if ($this->isUserLoggedOf() == false) {
					return true;
				} 		
			return false;
		}

		public function isUserLoggedOf() {
			if(isset($_POST['logUserOff']) || $_SESSION['userIsLoggedOf'] == true) {
				$_SESSION['isUserPersistantLoggedIn'] = false;
				$_SESSION['userIsLoggedOf'] = true;
				return true;
			} 
			return false;
		}
	}