<?php

	require_once("HTMLView.php");

	session_start();

	class LikeModel {

		private $sessionLocation = "LikeModel::NumLikes";

		public function __construct() {
			if(isset($_SESSION[$this->sessionLocation]) == false) {
				$_SESSION[$this->sessionLocation] = 0;
				$this->numLikes = 0;	
			}
			
		}

		public function getNumLikes() {
			return $_SESSION[$this->sessionLocation];
		}

		public function addLike() {
			$_SESSION[$this->sessionLocation]++;
		}
	}

	class LikeView {
		private $model;

		public function __construct(LikeModel $model) {
			$this->model = $model;
		}

		public function showLikes() {

			$likes = $this->model->getNumLikes();
			$ret = "Antalet likes är $likes";

			$ret .= "
				<form action='' method='post'>
				<input type='submit' value'Gilla!' name='iLike'>
				</form>";

			if($this->didUserPressLike()) {
				$ret .= " You like!";
			}

			return $ret;
		}

		public function didUserPressLike() {
			if(isset($_POST["iLike"])) {
				return true;
			}
			 return false;
		}
	}

	class LikeController {
		private $view;
		private $model;

		public function __construct() {
			$this->model = new LikeModel();
			$this->view = new LikeView($this->model);
		}

		public function doControll() {

			if($this->view->didUserPressLike()) {
				$this->model->addLike();
			}

			return $this->view->showLikes();
		
		}
	}

	$c = new LikeController();

	$htmlBody = $c->doControll();

	$view = new HTMLView();
	$view->echoHTML($htmlBody);