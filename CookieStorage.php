<?php

	class CookieStorage {
		public function saveCookie($name, $values) {
			setcookie($name, $value, -1);
		}
	}