<?php
// TABLES
//
// nerede bizim istediğimiz tablolar ?
F3::set('SESSION.TABLES', array(
				'admin' => 'username',
				'people' => 'tc'
			)
		);
// INIT
//
// login olursa, default olarak admin tablosu seçilsin
F3::set('SESSION.TABLE_INIT', 'admin');

// FIELDS
//
// tablo incele kısmında buna benzer şeyleri görürsen bizimde görmemize izin ver
// Ör :
// talep : _id => true
// cevap : bilmem_id, bilmem2_id, bilmem3_id
//
// talep : name => true
// cevap : name, surname, first_name, last_name, username
F3::set('SESSION.FIELDS', array(
				'_id' =>  true,
				'id' =>  true,
				'name' => true,
				'title' => true,
				'content' => true,
				'tc' => false,
			));
?>
