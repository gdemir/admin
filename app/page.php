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
		$this->_clear(array('warning', 'info'));
		$this->_page('login', 'Yönetici Paneli');
	}
	function home() {
		$this->_clear(array('error', 'warning', 'info'));
		$this->_page('home', 'Yönetici Paneli');
	}
	function info() {
		$this->_clear(array('success', 'error', 'warning', 'info'));
		$this->_page('info', 'Bilgilendirme Sayfası');
	}
	function create() {
		$this->_clear(array('success', 'warning', 'info'));
		$this->_page('create', 'Kaydet');
	}
	function review() {
		$this->_clear(array('success', 'error', 'warning', 'info'));
		$table = new Axon(F3::get('SESSION.TABLE'));
		$table->afind();
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
