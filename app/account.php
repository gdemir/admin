<?php

class Account extends F3instance {

	// Display our home page
	function login() {
		if (F3::get('SESSION.admin'))
			return F3::call('Page->home');
		F3::call('Page->login');
	}

	// User authentication
	function auth() {

		$this->_checkinput();
		if (! F3::exists('SESSION.error')) {
			$username = F3::get('REQUEST.username');
			$password = F3::get('REQUEST.password');
			$admin = new Axon(F3::get('TABLE'));
			$admin->load(F3::get('KEY') ."='$username'");

			if (!$admin->dry() && streq_turkish($admin->password, $password)) {

				// admini oturuma gömelim ve oradan alalım
				F3::set('SESSION.adminusername', $username);
				F3::set('SESSION.adminpassword', $password);
				F3::set('SESSION.admin', true);  // admin özelliği ekle

				if ($admin->status)               // ek admin özellikleri ekle
					F3::set('SESSION.adminsuper', true);

				$admin->login = date("Y-m-d h:i:s");
				$admin->save();

				return F3::call('Datas->table');
			} else
				F3::set('SESSION.error', "Lütfen girdiğiniz bilgileri kontrol edin.");
		}

		F3::call('Page->login');
	}

	// End the session
	function logout() {

		if (F3::get('SESSION.admin')) {
			$username = F3::get('SESSION.adminusername');
			$admin = new Axon(F3::get('TABLE'));
			$admin->load("username='$username'");
			$admin->logout = date("Y-m-d h:i:s");
			$admin->save();

			F3::clear('SESSION');
		}
		F3::reroute('/');
	}

	// Validate username, password
	function _checkinput() {

		foreach (array('username', 'password') as $alan) {
			F3::input($alan,
				function($value) use($alan) {
					$ne = $alan;
					if ($hata = denetle($value, array(
						'dolu'    => array(true, "$ne boş bırakılamaz"),
						'enaz'    => array(2,    "$ne çok kısa"),
						'enfazla' => array(127,  "$ne çok uzun"),
					))) { F3::set('SESSION.error', $hata); return; }
					F3::set("REQUEST.$alan", $value);
				}
			);
		}
	}

	function beforeroute() {
// 
// 		DB::sql(
// 			array(
// 			'CREATE TABLE IF NOT EXISTS ondokuz ('.
// 			'username VARCHAR(45) CHARACTER SET "utf8" COLLATE "utf8_general_ci" NOT NULL ,'.
// 			'firstname VARCHAR(45) CHARACTER SET "utf8" COLLATE "utf8_general_ci" ,'.
// 			'lastname VARCHAR(45) CHARACTER SET "utf8" COLLATE "utf8_general_ci" ,'.
// 			'password VARCHAR(45) CHARACTER SET "utf8" COLLATE "utf8_general_ci" NOT NULL ,'.
// 			'status INT(1) NOT NULL ,'.
// 			'login DATETIME NOT NULL ,'.
// 			'logout DATETIME NOT NULL ,'.
// 			'photo VARCHAR(45) CHARACTER SET "utf8" COLLATE "utf8_general_ci" NOT NULL ,'.
// 			'PRIMARY KEY (username) )'.
// 			'ENGINE = MyISAM'.
// 			'DEFAULT CHARACTER SET = utf8'.
// 			'COLLATE = utf8_general_ci;',
// 
// 			'CREATE TABLE IF NOT EXISTS process ('.
// 			'id INT NOT NULL AUTO_INCREMENT ,'.
// 			'username VARCHAR(45) CHARACTER SET "utf8" COLLATE "utf8_general_ci" NOT NULL ,'.
// 			'tablename VARCHAR(128) CHARACTER SET "utf8" COLLATE "utf8_general_ci" NOT NULL ,'.
// 			'keyname VARCHAR(128) CHARACTER SET "utf8" COLLATE "utf8_general_ci" NOT NULL ,'.
// 			'content VARCHAR(128) CHARACTER SET "utf8" COLLATE "utf8_general_ci" NOT NULL ,'.
// 			'processtime DATETIME NOT NULL ,'.
// 			'PRIMARY KEY (id) )'.
// 			'ENGINE = MyISAM'.
// 			'DEFAULT CHARACTER SET = utf8'.
// 			'COLLATE = utf8_general_ci;'
// 		));
// 
// 		$table = new Axon(F3::get('TABLE'));
// 		if (count($table->find()) == 0)
// 			DB::sql("insert into " . F3::get('TABLE') . 
// 				" (username, password, status, photo) " .
// 				" values ('19', 'ondokuz', 1, '" . F3::get('default_image') . "');");
	require_once 'cfg/db.php'; // FIXME
	}

	function afterroute() {
		echo Template::serve('layout.htm');
	}
}
