<?php

require_once("FormView.php");

	class Form {

		private $formView;

		public function __construct() {
			$this->formView = new FormView();

		}

		public function doControll() {
			return $this->formView->showFormView();
		}

	}