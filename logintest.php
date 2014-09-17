<?php

	require_once("HTMLView.php");

	session_start();

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
			$ret ="
				<h1>Inloggad!</h1>
				<h2>$msgStr</h2>
				<a href=''>Logga ut knapp. säger vi.</a>
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
				if (isset($_SESSION['UserPersistantLoggedIn']) && $_SESSION['UserPersistantLoggedIn'] == true) {
				return true;
			}			
			return false;
		}
	}


	class LoginView {
		//Ska skapa login vyn
		//Skickar logindata till controllern.

		private $model;

		public function __construct(LoginModel $model) {
			$this->model = $model;
		}

	}

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
				if(isUserPersistantLoggedIn()){
					return $this->model->getLoggedInPage("Vi kommer att hålla dig inloggad");
				} else {
					return $this->model->getLogInForm("");
				}
			}
		}
	}


	$c = new LoginController();

	$htmlBody = $c->doControll();

	$view = new HTMLView();
	$view->echoHTML($htmlBody);