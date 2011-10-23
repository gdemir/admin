<?php

// table
$TABLE = F3::get('SESSION.TABLE');

F3::get('DB')->schema($TABLE, 0);// 0 nolu kayıt gibi Field alanlarını al.

$fields = array();
foreach (F3::get('DB->result') as $gnl => $blg)
	array_push($fields, $blg['Field']);

F3::set('fields', $fields);

F3::clear('SESSION.key'); // yeni veri(kullanıcı) öncesi eskiyi veriyi sil

return F3::call('add'); // f3.php'den fonksiyon çağırımı

?>
