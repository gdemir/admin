<?php
// table ana csv çıktı şeması
function csv($TABLE, $table) {
	$title = false;
	$fields = "";
	$rows = "";
	foreach (F3::get('DB->result') as $index => $kisi) {
		$row = "";
		foreach ($kisi as $alan => $deger) {
			if (!$title)
			       	$fields .= ( $fields ? ',' : '') . $alan;
			$row .= ($row ? ',' : '') . $deger;
		}
		if (!$title)
			$rows .= $fields . "\n";
		$rows .= $row . "\n";
		$title = true;
	}
	echo $rows;

	header("Content-type: text/csv");
	header("Content-Disposition: attachment; filename=$TABLE-". date("Y.m.d") . ".csv");
	exit;
}

?>
