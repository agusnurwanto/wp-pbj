# PBJ Pra LPSE

Aplikasi untuk optimasi kinerja Pengadaan Barang dan Jasa pemerintah daerah
Semoga bermanfaat

### Cara pakai plugin:
- Install wordpress
- Install plugin ini dan aktifkan
- Import SQL file tabel.sql untuk membuat tabel database yang diperlukan oleh plugin
- Install plugin **ultimate member** dan aktifkan
- Create pages sesuai settingan default ultimate member
- Page Register tidak dipakai sehingga perlu diupdate visibility-nya menjadi private
- Install plugin **Ultimate Member – reCAPTCHA** dan aktifkan untuk menambahkan captcha pada halaman login
- Buka halaman **Ultimate Member > Settings > Extensions** untuk mendaftarkan api_key recaptcha
- Untuk mendapatkan api key recaptcha bisa didapat dari http://www.google.com/recaptcha/admin

### Catatan
- Untuk debug koneksi database. Rubah mode debug menjadi 1 pada file **admin/class-wp-pbj-admin.php line: 60**

### Permintaan fitur:
- User umum bisa request penambahan fitur dengan membuat issue

### Video Tutorial 
- ...