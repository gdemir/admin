### Admin Paneli

- veritabanı yapılandırması :

    $ mysql -u table user -p  < db/db.sql

.f3.ini içinde gerekli yerlerin düzenlenmesi

- tablo yapılandırması :

index.php içindeki `giris()` fonksiyonunda

	F3::set('SESSION.TABLES', array(
			'admin' => 'username',
			));

gerekli `tablo + key` bilgilerinin eklenmesi.

- img dizini yapılandırması



	sudo chmod 777 img`

diyerek izinlerinin değiştirilmesi, `img/«tablo_adı»/primary_key.jpg` şeklinde
kayıt alıyor

not : tabloda photo, content isimlerinin özellikleri var

f3-version: 2.0
--

