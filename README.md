<<<<<<< HEAD
### Simülasyon Merkezi Admin Paneli

- veritabanı yapılandırması :

    $ mysql -u sim -p sim < db/db.sql
=======
### Admin Paneli

- veritabanı yapılandırması :

    $ mysql -u table user -p  < db/db.sql
>>>>>>> de65aee3a127560e2d80eaef2d986171cd2fe5fa

.f3.ini içinde gerekli yerlerin düzenlenmesi

- tablo yapılandırması :

index.php içindeki `giris()` fonksiyonunda

	F3::set('SESSION.TABLES', array(
			'admin' => 'username',
			));

gerekli `tablo + key` bilgilerinin eklenmesi.

<<<<<<< HEAD
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

- img dizini `sudo chmod 777 img` diyerek izinlerinin değiştirilmesi
=======
- img dizini yapılandırması



	sudo chmod 777 img`

diyerek izinlerinin değiştirilmesi, `img/«tablo_adı»/primary_key.jpg` şeklinde
kayıt alıyor
>>>>>>> de65aee3a127560e2d80eaef2d986171cd2fe5fa

not : tabloda photo, content isimlerinin özellikleri var

f3-version: 2.0
--

