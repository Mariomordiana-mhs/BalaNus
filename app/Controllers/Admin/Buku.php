<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ModelBuku;

class Buku extends BaseController
{
    protected $modelBuku;

    public function __construct()
    {
        $this->modelBuku = new ModelBuku();
    }

    // 1. Menampilkan Daftar Buku (Read)
    public function index()
    {
        $data = [
            'title' => 'Kelola Data Buku',
            'buku'  => $this->modelBuku->findAll()
        ];
        return view('admin/buku/index', $data);
    }

    // 2. Menampilkan Form Tambah Buku
    public function create()
    {
        $data = [
            'title' => 'Tambah Buku Baru',
            'validation' => \Config\Services::validation()
        ];
        return view('admin/buku/create', $data);
    }

    // 3. Memproses Data Tambah Buku (Create)
    public function store()
    {
        // Validasi Input (Diperketat dengan Pesan Kustom)
        $rules = [
            'isbn' => [
                'rules'  => 'required|is_unique[buku.isbn]',
                'errors' => [
                    'required'  => 'ISBN wajib diisi.',
                    'is_unique' => 'Gagal menyimpan! Buku dengan ISBN ini sudah ada di dalam database.'
                ]
            ],
            'judul_buku' => [
                'rules'  => 'required|min_length[3]',
                'errors' => [
                    'required'   => 'Judul buku wajib diisi.',
                    'min_length' => 'Judul buku minimal 3 karakter.'
                ]
            ],
            'penulis' => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Penulis wajib diisi.'
                ]
            ],
            'cover_buku' => [
                'rules'  => 'max_size[cover_buku,2048]|is_image[cover_buku]|mime_in[cover_buku,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'Ukuran gambar cover maksimal 2MB.',
                    'is_image' => 'File yang dipilih bukan gambar.',
                    'mime_in'  => 'Format gambar harus JPG, JPEG, atau PNG.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            // Flash Message Gagal
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Proses Upload Gambar Cover
        $fileCover = $this->request->getFile('cover_buku');
        $namaCover = 'default.png'; 

        // Jika ada file yang diunggah manual
        if ($fileCover && $fileCover->isValid() && !$fileCover->hasMoved()) {
            $namaCover = $fileCover->getRandomName();
            $fileCover->move('uploads/covers', $namaCover);
        } else {
            // Cek apakah ada URL cover dari Open Library API
            $coverUrl = $this->request->getPost('cover_url');
            if (!empty($coverUrl)) {
                $namaCover = 'api_' . time() . '.jpg';
                // Simpan gambar dari URL API ke folder lokal
                file_put_contents('uploads/covers/' . $namaCover, file_get_contents($coverUrl));
            }
        }

        // Simpan ke Database
        $this->modelBuku->save([
            'isbn'       => $this->request->getPost('isbn'),
            'judul_buku' => $this->request->getPost('judul_buku'),
            'penulis'    => $this->request->getPost('penulis'),
            'penerbit'   => $this->request->getPost('penerbit'),
            'kategori'   => $this->request->getPost('kategori'),
            'stok'       => $this->request->getPost('stok'),
            'cover'      => $namaCover
        ]);

        // Flash Message Sukses
        return redirect()->to('/admin/buku')->with('success', 'Data buku berhasil ditambahkan!');
    }

    // ====================================================================
    // FUNGSI BARU UNTUK EDIT, UPDATE, DAN DELETE
    // ====================================================================

    // 4. Menampilkan Form Edit Buku (Read Spesifik)
    public function edit($id)
    {
        $data = [
            'title' => 'Edit Data Buku',
            'buku'  => $this->modelBuku->find($id),
            'validation' => \Config\Services::validation()
        ];
        
        // Pastikan buku ditemukan
        if (empty($data['buku'])) {
            return redirect()->to('/admin/buku')->with('error', 'Buku tidak ditemukan.');
        }

        return view('admin/buku/edit', $data);
    }

    // 5. Memproses Data Update Buku (Update)
    public function update($id)
    {
        // Validasi Input (Pastikan is_unique mengecualikan ID buku yang sedang diedit)
        $rules = [
            'isbn' => [
                'rules'  => "required|is_unique[buku.isbn,id_buku,{$id}]",
                'errors' => [
                    'required'  => 'ISBN wajib diisi.',
                    'is_unique' => 'Gagal! ISBN ini sudah dipakai oleh buku lain.'
                ]
            ],
            'judul_buku' => [
                'rules'  => 'required|min_length[3]',
                'errors' => [
                    'required'   => 'Judul buku wajib diisi.',
                    'min_length' => 'Judul buku minimal 3 karakter.'
                ]
            ],
            'penulis' => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Penulis wajib diisi.'
                ]
            ],
            'cover_buku' => [
                'rules'  => 'max_size[cover_buku,2048]|is_image[cover_buku]|mime_in[cover_buku,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'Ukuran gambar cover maksimal 2MB.',
                    'is_image' => 'File yang dipilih bukan gambar.',
                    'mime_in'  => 'Format gambar harus JPG, JPEG, atau PNG.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $bukuLama = $this->modelBuku->find($id);
        $fileCover = $this->request->getFile('cover_buku');
        $namaCover = $bukuLama['cover']; // Defaultnya tetap gunakan cover lama

        // Jika user memilih file cover baru untuk diupload
        if ($fileCover && $fileCover->isValid() && !$fileCover->hasMoved()) {
            $namaCover = $fileCover->getRandomName();
            $fileCover->move('uploads/covers', $namaCover);
            
            // Hapus file cover fisik yang lama (agar penyimpanan tidak penuh), kecuali jika itu default.png
            if ($bukuLama['cover'] != 'default.png' && file_exists('uploads/covers/' . $bukuLama['cover'])) {
                unlink('uploads/covers/' . $bukuLama['cover']);
            }
        }

        // Simpan pembaruan ke Database
        $this->modelBuku->update($id, [
            'isbn'       => $this->request->getPost('isbn'),
            'judul_buku' => $this->request->getPost('judul_buku'),
            'penulis'    => $this->request->getPost('penulis'),
            'penerbit'   => $this->request->getPost('penerbit'),
            'kategori'   => $this->request->getPost('kategori'),
            'stok'       => $this->request->getPost('stok'),
            'cover'      => $namaCover
        ]);

        return redirect()->to('/admin/buku')->with('success', 'Data buku berhasil diupdate!');
    }

    // 6. Menghapus Data Buku (Delete)
    public function delete($id)
    {
        $buku = $this->modelBuku->find($id);
        
        if ($buku) {
            // Hapus file gambar secara fisik jika bukan default.png
            if ($buku['cover'] != 'default.png' && file_exists('uploads/covers/' . $buku['cover'])) {
                unlink('uploads/covers/' . $buku['cover']);
            }
            
            // Hapus record dari database
            $this->modelBuku->delete($id);
            return redirect()->to('/admin/buku')->with('success', 'Data buku berhasil dihapus!');
        }

        return redirect()->to('/admin/buku')->with('error', 'Data buku gagal dihapus.');
    }

    // --- FUNGSI UNTUK ACC PEMINJAMAN ---
    public function acc_peminjaman($id_peminjaman)
    {
        $modelPeminjaman = new \App\Models\ModelPeminjaman();
        
        // Update status menjadi Dipinjam dan tentukan tenggat waktu (misal: 7 hari dari sekarang)
        $modelPeminjaman->update($id_peminjaman, [
            'status'        => 'Dipinjam',
            'tenggat_waktu' => date('Y-m-d', strtotime('+7 days'))
        ]);

        return redirect()->to('/admin')->with('success', 'Peminjaman berhasil disetujui (ACC)!');
    }

    // --- FUNGSI UNTUK TOLAK PEMINJAMAN ---
    public function tolak_peminjaman($id_peminjaman)
    {
        $modelPeminjaman = new \App\Models\ModelPeminjaman();
        $modelBuku = new \App\Models\ModelBuku();

        // Cari data peminjaman untuk mengembalikan stok fisik buku
        $peminjaman = $modelPeminjaman->find($id_peminjaman);
        if ($peminjaman) {
            $buku = $modelBuku->find($peminjaman['id_buku']);
            if ($buku) {
                // Kembalikan stok karena pinjaman ditolak
                $modelBuku->update($peminjaman['id_buku'], ['stok' => $buku['stok'] + 1]);
            }
            // Hapus data pengajuan
            $modelPeminjaman->delete($id_peminjaman);
        }

        return redirect()->to('/admin')->with('error', 'Pengajuan peminjaman telah ditolak.');
    }

    // ====================================================================
    // FUNGSI WEBSERVICE CLIENT (KONSUMSI OPEN LIBRARY API)
    // ====================================================================
    public function cariBukuViaApi($isbn)
    {
        // 0. CEK DATABASE LOKAL: Cegah duplikasi sebelum mencari ke API
        $db = \Config\Database::connect();
        $cekBukuLokal = $db->table('buku')->where('isbn', $isbn)->get()->getRowArray();
        
        if ($cekBukuLokal) {
            // Jika ISBN sudah ada di database, langsung tolak dan beri pesan error
            return $this->response->setJSON([
                'status' => 'error',
                'pesan'  => 'Buku dengan ISBN ini sudah terdaftar di perpustakaan Anda! Tidak perlu ditambahkan lagi.'
            ]);
        }

        // 1. CEK CACHE: Inisialisasi layanan Cache CodeIgniter
        $cache = \Config\Services::cache();
        $cacheKey = 'openlibrary_isbn_' . $isbn;

        // Jika data sudah ada di cache, langsung gunakan untuk performa cepat
        if ($dataBuku = $cache->get($cacheKey)) {
            return $this->response->setJSON([
                'status' => 'success',
                'sumber' => 'cache',
                'data'   => $dataBuku
            ]);
        }

        // 2. FETCH API & ERROR HANDLING: Ambil dari API jika tidak ada di cache
        try {
            $client = \Config\Services::curlrequest();
            // Panggil Open Library API
            $url = "https://openlibrary.org/api/books?bibkeys=ISBN:{$isbn}&format=json&jscmd=data"; 
            
            $response = $client->request('GET', $url, ['http_errors' => false]);
            $body = json_decode($response->getBody(), true);
            $bookKey = 'ISBN:' . $isbn;

            // Jika respons API kosong (buku tidak ditemukan)
            if (empty($body) || !isset($body[$bookKey])) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'pesan'  => 'Data buku dengan ISBN tersebut tidak ditemukan di Open Library.'
                ]);
            }

            $bookData = $body[$bookKey];

            // 3. SUSUN DATA
            $hasil = [
                'judul'    => $bookData['title'] ?? '',
                'penulis'  => isset($bookData['authors']) ? $bookData['authors'][0]['name'] : '',
                'penerbit' => isset($bookData['publishers']) ? $bookData['publishers'][0]['name'] : '',
                'tahun'    => $bookData['publish_date'] ?? '',
                'cover'    => $bookData['cover']['large'] ?? ($bookData['cover']['medium'] ?? '')
            ];

            // 4. SIMPAN KE CACHE: Simpan data selama 1 hari (86400 detik) untuk efisiensi server
            $cache->save($cacheKey, $hasil, 86400);

            return $this->response->setJSON([
                'status' => 'success',
                'sumber' => 'api_live',
                'data'   => $hasil
            ]);

        } catch (\Exception $e) {
            // ERROR HANDLING: Tangkap jika koneksi API terputus
            return $this->response->setJSON([
                'status' => 'error',
                'pesan'  => 'Gagal terhubung ke API. Pastikan koneksi internet stabil.'
            ]);
        }
    }
}