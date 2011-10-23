<?php

if (F3::get('SESSION.admin')) {
	// for logout time
	$username = F3::get('SESSION.adminusername');
	$admin = new Axon('ondokuz');
	$admin->load("username='$username'");
	$admin->logout = date('Y-m-d h:i:s');
	$admin->save();

	F3::clear('SESSION.adminusername'); // admin özelliği sil
	F3::clear('SESSION.adminpassword'); // ek admin özellikleri sil
	F3::clear('SESSION.admin');         // admin özelliği sil
	F3::clear('SESSION.adminsuper');    // ek admin özellikleri sil
	F3::clear("SESSION.TABLE_INIT");    // ilk_tablo bilgisini sil
	F3::clear("SESSION.TABLE");         // tablo bilgisini sil
	F3::clear("SESSION.KEY");           // tablo uniq key'i sil
}
F3::reroute('/');

?>
