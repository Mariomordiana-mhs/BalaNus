# 📚 BalaNus (Books are windows to the world)

BalaNus adalah aplikasi Sistem Informasi Perpustakaan berbasis web yang dibangun menggunakan **CodeIgniter 4**. Aplikasi ini memudahkan pengelolaan katalog buku, sirkulasi peminjaman, pelaporan, hingga pembayaran denda secara *online* terintegrasi dengan *Payment Gateway* **Midtrans**.

---

## 📸 Screenshot Fitur Utama

1. **Dashboard Member & Statistik Denda**
   ![Dashboard Member](imagereadme\Dashboard_Member.png)

2. **Katalog buku & Ketersediaan**
   ![Katalog Buku](imagereadme\Katalog_Buku.png)

3. **Pembayaran Denda Online (Midtrans)**
   ![Pembayaran Midtrans](imagereadme\Denda_Member.png)

---

## 🛠️ Persyaratan Sistem

Pastikan sistem Anda memenuhi persyaratan berikut sebelum melakukan instalasi:
* PHP 8.2 atau lebih baru
* MySQL / MariaDB
* Composer
* Ekstensi PHP: `intl`, `mbstring`, `mysqli` / `mysqlnd`

---

## 🚀 Cara Instalasi

Ikuti langkah-langkah berikut untuk menjalankan aplikasi BalaNus di server lokal (Localhost):

1. **Kloning Repositori**
   ```bash
   git clone git clone [https://github.com/Mariomordiana-mhs/BalaNus.git](https://github.com/Mariomordiana-mhs/BalaNus.git)
   cd balanus

2. **Konfigurasi .env**
    * Salin file .env.example dan ubah namanya menjadi .env.
    * Buka file .env tersebut menggunakan VS Code, lalu sesuaikan konfigurasinya seperti di bawah ini:
    ```bash
        Mode Pengembangan
        CI_ENVIRONMENT = development

        #URL Aplikasi
        app.baseURL = 'http://localhost:8080/'

        #Konfigurasi Database
        database.default.hostname = localhost
        database.default.database = balanus
        database.default.username = root
        database.default.password = 
        database.default.DBDriver = MySQLi

        #Konfigurasi Midtrans Payment Gateway (Sandbox)
        MIDTRANS_SERVER_KEY="Masukkan Server Key Anda"
        MIDTRANS_CLIENT_KEY="Masukkan Client Key Anda"
        MIDTRANS_IS_PRODUCTION=false

3. Setup Database (Migration & Seeder)
    - Buat database kosong baru bernama balanus melalui phpMyAdmin atau Laragon Anda.
    - Jalankan perintah migrasi tabel sekaligus pengisian data awal (seeding) melalui terminal:
    ```bash
        php spark migrate --seed Myseed

4. Jalankan Aplikasi
    Setelah konfigurasi selesai, jalankan local development server bawaan CodeIgniter:
    ```bash
        php spark serve

🔐 Akun Demo (Testing)
Silakan gunakan akun demo yang sudah disediakan oleh sistem seeder untuk masuk ke dalam aplikasi:
```bash
👨‍💼 Akses Admin (Pustakawan)
    - Email / Username: admin
    - Password: 123

🧑‍💻 Akses User (Anggota Perpustakaan)
    - Email / Username: yunus
    - Password: 123