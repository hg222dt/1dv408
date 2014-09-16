<?php

namespace controller;

require_once("FormView.php");

	class Form {

		private $formView;

		public function __construct() {
			$this->formView = new \FormView();

		}

		public function doControll() {

			$formItems = array("item1", "item2");

			return $this->formView->showFormView($formItems);
		}

	}