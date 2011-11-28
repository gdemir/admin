<?php

class Datas extends F3instance {

	function save() {
		$TABLE = F3::get('SESSION.TABLE');
		$KEY   = F3::get('SESSION.KEY');
		$key   = F3::get('REQUEST.' . $KEY);

		$table = new Axon($TABLE);
		if ($table->found("$KEY='$key'")) {
			F3::set('SESSION.error', "$TABLE tablosunda $key isminde bir kayıt zaten var.");
			return F3::call('Page->create');
		}
		$table->copyFrom('POST');
		$table->photo = ""; // default resim
		$table->save();

		$table = new Axon($TABLE);
		$table->load("$KEY='$key'");

		$up = new Upload();
		if ($response = ($up->load($TABLE, $table->$KEY, F3::get("FILES.photo"), false)))
			if ($response[0]) // istek başarı mı / hata mı ?
				$table->photo = $response[1];
			else
				F3::set('SESSION.error', $response[1]);

		if (F3::exists('SESSION.error')) return F3::call('Datas->create'); // yüklemede hata var mı?
		$table->save();

		process($key, $_POST, 'ekledi'); // process takip

		// yeni kayıt, kayıt sayısı
		F3::set('SESSION.SAVE', count($table->find()));

		F3::set('SESSION.success', $table->$KEY . " bilgisine sahip kişi $TABLE tablosuna başarıyla eklendi.");
		return F3::reroute('/show/' . $key);
	}
	function find() {
		F3::clear('SESSION.success');F3::clear('SESSION.error');

		$this->_checkkey();

		$table = new Axon(F3::get('SESSION.TABLE'));
		if ($table->found(F3::get('SESSION.KEY') . "='" . F3::get('REQUEST.key') ."'")) {
			return F3::reroute('/show/' . F3::get('REQUEST.key'));
		} else
			F3::set('SESSION.error', "Böyle bir kayıt bulunmamaktadır");
		F3::call('Page->find');
	}
	function erase() {
		if (!F3::get('SESSION.adminsuper')) return F3::reroute('/');

		$table = new Axon(F3::get('SESSION.TABLE'));
		$datas = $table->afind("$KEY='$request_key'"); // process takip için
		$table->load(F3::get('SESSION.KEY') . "='" . F3::get('PARAMS.key') . "'");

		$up = new Upload();
		$up->erase(F3::get('SESSION.TABLE'), $table->photo);

		$table->erase();
		F3::set('SESSION.SAVE', F3::get('SESSION.SAVE') - 1);

		process(F3::get('PARAMS.key'), http_build_query($datas[0]), 'sildi'); // process takip

		F3::set('SESSION.error', F3::get('PARAMS.key') . " ye ait bilgiler başarıyla silindi");
		F3::reroute('/find');
	}
	function show() {
		$table = new Axon(F3::get('SESSION.TABLE'));
		$datas = $table->afind(F3::get('SESSION.KEY') . "='" . F3::get('PARAMS.key') . "'");
		F3::set('SESSION.DATA', $datas[0]);
		F3::call('Page->show');
	}
	function edit() {
		$table = new Axon(F3::get('SESSION.TABLE'));
		$datas = $table->afind(F3::get('SESSION.KEY') . "='" . F3::get('PARAMS.key') . "'");
		F3::set('SESSION.DATA', $datas[0]);
		F3::call('Page->edit');
	}
	function update() {
		$TABLE = F3::get('SESSION.TABLE');
		$KEY   = F3::get('SESSION.KEY');
		$request_key   = F3::get('REQUEST.' . $KEY);
		$key   = F3::get('REQUEST.key'); // orjinal key

		$table = new Axon($TABLE);
		if ($key != $request_key && $table->found("$KEY='$request_key'")) {
			F3::set('SESSION.error', "$KEY = $request_key olan bir kayıt var, güncelleme gerçekleşmedi");
			return F3::reroute('/show/' . $request_key);
		}
		$datas = $table->afind("$KEY='$request_key'"); // process takip için
		$table->load("$KEY='$key'"); // oturumdan veriyi yükleyelim

		foreach ($_POST as $field => $value)
				$table->$field = $value;

		$table->$KEY = $request_key;
		$table->save();

		$table = new Axon($TABLE);
		$table->load("$KEY='$request_key'");

		$up = new Upload();
		if ($response = ($up->load($TABLE, $table->$KEY, F3::get("FILES.photo"), true)))
			if ($response[0])
				$table->photo = $response[1];
			else
				F3::set('SESSION.error', $response[1]);

		if (F3::exists('SESSION.error')) return F3::call('Page->edit'); // yüklemede hata var mı?

		$table->save();

		process($request_key, http_build_query($datas[0]), 'güncelledi'); // process takip

		return F3::reroute('/show/' . $request_key);
	}
	function upload() {

		$TABLE    = F3::get('SESSION.TABLE');
		$KEY      = F3::get('SESSION.KEY');
		$csv_key  = F3::get('REQUEST.csv_key');

		$up = new Upload(array('csv'));
		if ($response = ($up->load($TABLE, $TABLE, F3::get('FILES.csv_file'), true))) {
			if (! $response[0])
				F3::set('SESSION.error', $response[1]);
		} else
			F3::set('SESSION.error', "Yüklemede bir sorun ile karşılaşıldı");

		// yükleme sırasında hata var mı?
		if (F3::exists('SESSION.error')) return F3::call('Page->upload');

		// tablo kayıt alanları
		F3::get('DB')->schema($TABLE, 0);
		$fields = F3::get('DB->result'); // gerçek alanlar

		$csv_path = 'public/upload/' . $TABLE . '/' . $TABLE . '.csv';

		$_rows = Csv::read($csv_path, $csv_key);
		$_fields = array_shift($_rows);

		foreach ($_rows as $row) {
			$table = new Axon($TABLE);

			foreach ($fields as $field_info)
				$table->$field_info['Field'] = "";

			$table->photo = ""; // default resim
			foreach ($_fields as $field)
				$table->$field = array_shift($row);
			$table->save();
		}

		process($key, "csv yükleme", "csv yüklendi"); // process takip
		F3::reroute('/review');
	}
	function download() {
		$TABLE = F3::get('SESSION.TABLE');

		if (!$csv_key = F3::get('REQUEST.csv_key')) {
			F3::set('SESSION.error', "Csv ayırt edici karakter boş bırakılamaz");
			return F3::call('Page->download');
		}
		$table = new Axon($TABLE);
		$table->afind();

		if ($table->dry())
			Csv::download($TABLE, F3::get('DB->result'), $csv_key);
		else
			F3::set('SESSION.error', "$TABLE tablosunda kayıt yok");
		F3::call('Page->download');
	}
	function table() {

		$TABLE = F3::exists('REQUEST.table') ? F3::get('REQUEST.table') : F3::get('TABLEINIT');
		$TABLES = F3::get('TABLES');

		// tablo ve uniq key
		F3::set('SESSION.TABLE', $TABLE);
		F3::set('SESSION.KEY', $TABLES[$TABLE]);

		$table = new Axon($TABLE);

		// tablo kayıt sayısı
		$save = $table->find();
		F3::set('SESSION.SAVE', ($save) ? count($save) : "0");

		// tablo kayıt alanları
		F3::get('DB')->schema($TABLE, 0);
		$fields = array();
		foreach (F3::get('DB->result') as $index => $field)
			array_push($fields, $field['Field']);
		F3::set('SESSION.FIELDS', $fields);

		F3::set('SESSION.success', "$TABLE tablosu başarıyla seçildi.");
		F3::reroute('/home');
	}
	function _checkkey() {
		foreach (array('key') as $alan) {
			F3::input($alan,
				function($value) use($alan) {
					$ne = F3::get('SESSION.KEY');
					if ($hata = denetle($value, array(
						'dolu'    => array(true, "$ne boş bırakılamaz"),
						'enaz'    => array(1,    "$ne çok kısa"),
						'enfazla' => array(127,  "$ne çok uzun"),
					))) { F3::set('SESSION.error', $hata); return; }
					F3::set("REQUEST.$alan", $value);
				}
			);
		}
	}
	function beforeroute() {
		if (! F3::get('SESSION.admin'))  return F3::reroute('/');
	}
	function afterroute() {
		echo Template::serve('layout.htm');
	}
}
