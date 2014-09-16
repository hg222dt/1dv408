<?php

	class formView {

		public function getDateTime() {
			setlocale(LC_ALL,"sv");
			return ucfirst(utf8_encode(strftime("%A, den %d %B år %Y. Klockan är [%X]")));
		}

		public function showFormView($formItems) {

			$ret = "";

			foreach($formItems as $key => $value) {
				$ret .= "<a href='?portfolio=$key'>$value</a> ";
			}

			$dateTimeString = $this->getDateTime();

			return "
<h1>Inloggning</h1>
<h2>Ej inloggad</h2>

<form>
	<fieldset>

		<legend>Logga in med användarnamn och lösenord</legend>

		<label for='usrnameId'>Username</label>
		<input type='text' id='usrnameId' size='20' name='LoginView::Usrname' value>
		<label for='passwordId'>Password</label>
		<input type='text' id='passwordId' size='20' name='LoginView::Password' value>
		<label for='keepLoggedInId'>Keep me logged on</label>		
		<input type='checkbox' id='keepLoggedInId' name='LoginView::Checked'>
		<input type='submit' name value='Log on'>
	</fieldset>
</form>

$dateTimeString


$ret
";
		}
	}