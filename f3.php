<?php

require_once  'lib/base.php';
require_once  'asset/lib.php';
require_once  'config/init.php';

function page($title, $template, $layout='render') {
	F3::set('title', $title);
	F3::set('template', $template);
	F3::call($layout);
}
function render() {
	echo Template::serve('layout.htm');
}
function download() {
	page('Tablo Indirme', 'download');
}
function upload() {
	page('Tablo Yükleme', 'upload');
}
function home() {
	page('Yönetici Paneli', 'home');
}
function info() {
	page('Bilgilendirme Sayfası', 'info');
}
function add() {
	page('Kaydet', 'new');
}
function edit() {
	page('Düzenle', 'edit');
}
function find() {
	page('Bul', 'find');
}
function show() {
	page('İnceleme Sonuçları', 'show');
}
function review() {
	page('Listelendi', 'review');
}
function login() {
	// tablo ve alanlarımız
	include 'config/session_table_field.php';

	if (F3::get('SESSION.admin'))  return F3::call('home'); // f3.php'den fonksiyon çağırımı
	page('Yönetici Paneli', 'login'); // adminlayout sadece login sayfası için
}

F3::route("GET  /*",        'login');        // login page
F3::route("POST /login",    'login.php');    // login action
F3::route('GET  /logout',   'logout.php');   // logout action
F3::route('POST /table',    'table.php');    // table action

F3::route("GET  /info",     'info');         // info page
F3::route("GET  /review",   'review.php');   // review action
F3::route("GET  /download", 'download');     // csv download page
F3::route("POST /download", 'download.php'); // csv download action
F3::route("GET  /upload",   'upload');       // csv upload page
F3::route("POST /upload",   'upload.php');   // csv upload action
F3::route("GET  /new",      'new.php');      // new page
F3::route("POST /add",      'add.php');      // new action
F3::route("GET  /find",     'find');         // find page
F3::route("POST /find",     'find.php');     // find action
F3::route("GET  /show",     'show');         // show page
F3::route("POST /del",      'del.php');      // del action
F3::route("POST /edit",     'edit.php');     // edit action
F3::route("POST /update",   'update.php');   // update action

F3::run();

?>
