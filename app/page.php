<?php

class Page extends F3instance {

	function _page($template, $title, $notice = NULL) {
		F3::set('title', $title);
		F3::set('template', $template);
	}
	function _clear($notices) {
		foreach ($notices as $notice )
			F3::clear('SESSION.' . $notice);
	}
	function login() {
		$this->_page('login', 'Yönetici Paneli');
	}
	function home() {
		$process = new Axon(F3::get('TABLEPROCESS'));
		DB::sql('SELECT * FROM ' . F3::get('TABLEPROCESS') .' ORDER BY time DESC LIMIT 10');
		F3::set('SESSION.PROCESS', F3::get('DB->result')); // last 10 process

		$table = new Axon(F3::get('TABLE'));
		DB::sql('SELECT * FROM ' . F3::get('TABLE') .' WHERE login < logout');
		F3::set('SESSION.ADMINUNCONNECT', F3::get('DB->result')); // last login admin

		$table = new Axon(F3::get('TABLE'));
		DB::sql('SELECT * FROM ' . F3::get('TABLE') .' WHERE login > logout');
		F3::set('SESSION.ADMINCONNECT', F3::get('DB->result')); // login admin
		$this->_clear(array('error'));
		$this->_page('home', 'Yönetici Paneli');
	}
	function info() {
		$this->_clear(array('success', 'error'));
		$this->_page('info', 'Bilgilendirme Sayfası');
	}
	function create() {
		if (!F3::get('SESSION.adminsuper')) return F3::reroute('/');
		$this->_clear(array('success'));
		$this->_page('create', 'Kaydet');
	}
	function review() {
		$this->_clear(array('success', 'error'));
		$table = new Axon(F3::get('SESSION.TABLE'));
		F3::set('ROWS', $table->afind());
		$this->_page('review', 'Listelendi');
	}
	function find() {
		$this->_page('find', 'Bul');
	}
	function show() {
		$this->_page('show', 'İnceleme Sonuçları');
	}
	function edit() {
		$this->_page('edit', 'düzenle');
	}
	function upload() {
		if (!F3::get('SESSION.adminsuper')) return F3::reroute('/');
		$this->_page('upload', 'Tablo Yükleme');
	}
	function download() {
		$this->_page('download', 'Tablo Indirme');
	}
	function beforeroute() {
		if (! F3::get('SESSION.admin')) {
			F3::set('SESSION.error', 'Hesabınıza giriş yapın!');
			return F3::reroute('/');
		}
	}
	function afterroute() {
		echo Template::serve('layout.htm');
	}
}
