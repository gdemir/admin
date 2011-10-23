<?php
// TABLES
//
// nerede bizim istediğimiz tablolar ?
F3::set('SESSION.TABLES', array(
				'ondokuz' => 'username',
			));

// TABLE INIT
//
// login olursa, default olarak admin tablosu seçilsin
F3::set('SESSION.TABLE_INIT', 'ondokuz');

// FIELDS
//
// tablo incele kısmında buna benzer şeyleri görürsen bizimde görmemize izin ver :-)
// Ör :
// talep : _id => true
// cevap : bilmem_id, bilmem2_id, bilmem3_id
//
// talep : name => true
// cevap : name, surname, first_name, last_name
F3::set('SESSION.FIELDS', array(
				'_id' => true,
				'id' => false,
				'name' => true,
				'tc' =>false,
				'photo' => false,
			));
?>
