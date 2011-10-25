<?php

require_once  'lib/base.php';    // for fat-free
require_once  'asset/lib.php';   // for plug-in
require_once  'config/init.php'; // for db connect

function page($template, $title, $layout='layout') {
	F3::set('title', $title);
	F3::set('template', $template);
	echo Template::serve($layout . '.htm');
}
function rl() { // require_login, before_filter
	if (! F3::get('SESSION.admin'))  return F3::reroute('/');
}
function download() {
	page('download', 'Tablo Indirme');
}
function upload() {
	page('upload', 'Tablo Yükleme');
}
function home() {
	page('home', 'Yönetici Paneli');
}
function info() {
	page('info', 'Bilgilendirme Sayfası');
}
function add() {
	page('new', 'Kaydet');
}
function edit() {
	page('edit', 'Düzenle');
}
function find() {
	page('find', 'Bul');
}
function show() {
	page('show', 'İnceleme Sonuçları');
}
function review() {
	page('review', 'Listelendi');
}
function login() {
	require_once 'config/db.php'; // for create table
	if (F3::get('SESSION.admin'))  return F3::call('home'); // f3.php'den fonksiyon çağırımı
	page('login', 'Yönetici Paneli');
}

F3::route("GET  /*",        'login');          // login page
F3::route("POST /login",    'login.php');      // login action
F3::route('GET  /logout',   'logout.php');     // logout action
F3::route('POST /table',    'table.php');      // table action

F3::route("GET  /new",      'rl; new.php');    // new page
F3::route("GET  /find",     'rl; find');       // find page
F3::route("GET  /show",     'rl; show');       // show page
F3::route("GET  /review",   'rl; review.php'); // review action
F3::route("GET  /download", 'rl; download');   // csv download page
F3::route("GET  /upload",   'rl; upload');     // csv upload page
F3::route("GET  /info",     'rl; info');       // info page

F3::route("POST /download", 'download.php');   // csv download action
F3::route("POST /upload",   'upload.php');     // csv upload action
F3::route("POST /add",      'add.php');        // new action
F3::route("POST /find",     'find.php');       // find action
F3::route("POST /del",      'del.php');        // del action
F3::route("POST /edit",     'edit.php');       // edit action
F3::route("POST /update",   'update.php');     // update action

F3::run();

?>
