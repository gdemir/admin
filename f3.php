<?php
<<<<<<< HEAD
require_once 'lib/base.php';
require_once 'asset/lib.php';
=======

require_once  'lib/base.php';
require_once  'inc/init.php';
>>>>>>> de65aee3a127560e2d80eaef2d986171cd2fe5fa

function page($title, $template, $layout='render') {
	F3::set('title', $title);
	F3::set('template', $template);
	F3::call($layout);
}
<<<<<<< HEAD

=======
function adminsuper() {
	return F3::get('SESSION.adminsuper');
}
>>>>>>> de65aee3a127560e2d80eaef2d986171cd2fe5fa
function render() {
	echo Template::serve('layout.htm');
}
function home() {
	page('Yönetici Paneli', 'home');
}
function info() {
	if (! F3::get('SESSION.admin'))
		return F3::call('giris');
	page('Bilgilendirme Sayfası', 'info');
}
function add() {
<<<<<<< HEAD
=======
	if (!adminsuper()) return F3::call('home');
>>>>>>> de65aee3a127560e2d80eaef2d986171cd2fe5fa
	if (F3::get('SESSION.key')) // bu bir direkt erişim mi?
		return F3::call('giris');
	page('Kaydet', 'new');
}
function edit() {
<<<<<<< HEAD
=======
	if (!adminsuper()) return F3::call('home');
>>>>>>> de65aee3a127560e2d80eaef2d986171cd2fe5fa
	if (! F3::exists('data')) // bu bir direkt erişim mi?
		return F3::call('giris');
	page('Düzenle', 'edit');
}
function find() {
<<<<<<< HEAD
	page('Bul', 'find');
}
function show() {
	page('İnceleme Sonuçları', 'show');
}
function review() {
=======
	if (!adminsuper()) return F3::call('home');
	page('Bul', 'find');
}
function show() {
	if (!adminsuper()) return F3::call('home');
	page('İnceleme Sonuçları', 'show');
}
function review() {
	if (!adminsuper()) return F3::call('home');
>>>>>>> de65aee3a127560e2d80eaef2d986171cd2fe5fa
	page('Listelendi', 'review');
}
function giris() {
	// nerede bizim istediğimiz tablolar ?
	F3::set('SESSION.TABLES', array(
<<<<<<< HEAD
				      'admin' => 'username',
			      ));
	F3::set('SESSION.TABLE_INIT', 'admin'); // login olursa, default olarak admin tablosu seçilsin

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
					'tc' => false,
					'photo' => false,
=======
				      'kul' => 'tc',
				      'staj' => 'tc',
			      ));

       	// login olursa, default olarak admin tablosu seçilsin
	F3::set('SESSION.TABLE_INIT', 'kul');

	// tablo incele kısmında buna benzer şeyleri görürsen bizimde görmemize izin ver :-)
	// Ör :
	// talep : id
	// cevap : bilmem_id, bilmem2_id, bilmem3_id
	//
	// talep : name
	// cevap : name, surname, first_name, last_name
	F3::set('SESSION.FIELDS', array(
					'id' => false,
					'ad' => false,
					'tc' =>false,
					'name' => true,
					'kizliksoyad' => false,
					'tarih' =>false,
					'saat' => false,
>>>>>>> de65aee3a127560e2d80eaef2d986171cd2fe5fa
				));

	if (F3::get('SESSION.admin'))
		return F3::call('home');
	page('Yönetici Paneli', 'login'); // adminlayout sadece login sayfası için
}

function logout() {
	if (F3::get('SESSION.admin')) {
		F3::clear('SESSION.adminusername'); // admin özelliği sil
		F3::clear('SESSION.adminpassword'); // ek admin özellikleri sil
		F3::clear('SESSION.admin');         // admin özelliği sil
		F3::clear('SESSION.adminsuper');    // ek admin özellikleri sil
		F3::clear("SESSION.TABLE_INIT");    // ilk_tablo bilgisini sil
		F3::clear("SESSION.TABLE");         // tablo bilgisini sil
		F3::clear("SESSION.KEY");           // tablo uniq key'i sil
	}
	F3::reroute('/');
}

F3::config(".f3.ini");
F3::set('DB', new DB('mysql:host=localhost;port=3306;dbname=' . F3::get('dbname'), F3::get('dbuser'), F3::get('dbpass')));
F3::set('SERVICEROOT', '/' . strtok($_SERVER["SCRIPT_NAME"], '/'));

F3::route("GET  /*",      'giris');
F3::route("POST /login",  'login.php');
F3::route('GET  /logout', 'logout');
F3::route('POST /table',  'table.php');

<<<<<<< HEAD
=======
//F3::route("GET /pdf",    'pdf.php'); TODO halen sorunlu yapılmadı.
>>>>>>> de65aee3a127560e2d80eaef2d986171cd2fe5fa
F3::route("GET /info",   'info');
F3::route("GET /review", 'review.php');
F3::route("GET /csv",    'csv.php');
F3::route("GET /new",    'new.php');
F3::route("GET /find",   'find');
F3::route("GET /add",    'add');
F3::route("GET /show",   'show');
<<<<<<< HEAD
F3::route("POST /show",  'show');
F3::route("POST /add",   'add.php');
F3::route("POST /find",  'find.php');
F3::route("POST /del",   'del.php');
=======
F3::route("POST /show",   'show');
F3::route("POST /add",   'add.php');
F3::route("POST /find",  'find.php');
F3::route("POST /del",   'del.php');
/* F3::route("GET  /edit",  'edit'); */
>>>>>>> de65aee3a127560e2d80eaef2d986171cd2fe5fa
F3::route("POST /edit",  'edit.php');
F3::route("POST /update",'update.php');

F3::run();

?>

