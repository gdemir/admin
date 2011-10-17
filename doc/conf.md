- veritabanı yapılandırması :

    $ mysql -u table user -p  < db/db.sql

.f3.ini içinde gerekli yerlerin düzenlenmesi

- tablo yapılandırması :

`config/session_table_field.php` içinde

		F3::set('SESSION.TABLES', array(
					'admin' => 'username',
		));

gerekli `tablo + key` bilgilerinin eklenmesi/düzenlenmesi.

- tablo ilklendirmesinin yapılandırması


		F3::set('SESSION.TABLE_INIT', 'admin');

sisteme login olunca ilk seçilecek tablo.

- tablo içerik görüntüleme yapılandırması

		F3::set('SESSION.FIELDS', array(
					'id' => true,
					'name' => true,
					'tc' => false,
		));

		// Ör :
		// talep : _id => true
		// cevap : bilmem_id, bilmem2_id, bilmem3_id
		//
		// talep : name => true
		// cevap : name, surname, first_name, last_name, username

- public/upload dizini izin ayarı

	`sudo chmod -R 777 upload` diyerek izinlerinin değiştirilmesi

diyerek izinlerinin değiştirilmesi, `public/upload/«tablo_adı»/primary_key.jpg` şeklinde
kayıt alıyor.

- kurulum sonrası özel ayarlar için :[ayarlar](http://github.com/gdemir/admin/blob/master/config/session_table_field.php)


not : sistemde tabloda photo, content isimleri için ayrı input gösterimleri var.
