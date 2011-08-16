<?php

require_once  'lib/base.php';

function page($title, $template, $layout='render') {
	F3::set('title', $title);
	F3::set('template', $template);
	F3::call($layout);
}
function adminsuper() {
	return F3::get('SESSION.adminsuper');
}
function render() {
	echo Template::serve('layout.htm');
}
function home() {
	if (!adminsuper()) return F3::call('home');
	page('Yönetici Paneli', 'home');
}
function info() {
	if (! F3::get('SESSION.admin'))
		return F3::call('giris');
	page('Bilgilendirme Sayfası', 'info');
}
function add() {
	if (!adminsuper()) return F3::call('home');
	if (F3::get('SESSION.key')) // bu bir direkt erişim mi?
		return F3::call('giris');
	page('Kaydet', 'new');
}
function edit() {
	if (!adminsuper()) return F3::call('home');
	if (! F3::exists('data')) // bu bir direkt erişim mi?
		return F3::call('giris');
	page('Düzenle', 'edit');
}
function find() {
	if (!adminsuper()) return F3::call('home');
	page('Bul', 'find');
}
function show() {
	if (!adminsuper()) return F3::call('home');
	page('İnceleme Sonuçları', 'show');
}
function review() {
	if (!adminsuper()) return F3::call('home');
	page('Listelendi', 'review');
}
function giris() {
	// nerede bizim istediğimiz tablolar ?
	F3::set('SESSION.TABLES', array(
				      'admin' => 'username',
			      ));
	F3::set('SESSION.TABLE_INIT', 'admin'); // login olursa, default olarak admin tablosu seçilsin

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

//F3::route("GET /pdf",    'pdf.php'); TODO halen sorunlu yapılmadı.
F3::route("GET /info",   'info');
F3::route("GET /review", 'review.php');
F3::route("GET /csv",    'csv.php');
F3::route("GET /new",    'new.php');
F3::route("GET /find",   'find');
F3::route("GET /add",    'add');
F3::route("GET /show",   'show');
F3::route("POST /show",   'show');
F3::route("POST /add",   'add.php');
F3::route("POST /find",  'find.php');
F3::route("POST /del",   'del.php');
/* F3::route("GET  /edit",  'edit'); */
F3::route("POST /edit",  'edit.php');
F3::route("POST /update",'update.php');

F3::run();

?>

