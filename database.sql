CREATE TABLE siswa(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nik VARCHAR(50) UNIQUE, -- tambahkan UNIQUE untuk mencegah duplikasi
    nik_kepala_keluarga VARCHAR(50),
    ttl DATE,
    nama_peserta VARCHAR(100),
    jenis_peserta VARCHAR(50),
    status_peserta VARCHAR(20) -- typo di sini: seharusnya "status_peserta" bukan "status_perserta"
);

CREATE TABLE pembayaran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nik_kepala_keluarga VARCHAR(50),
    FOREIGN KEY (nik_kepala_keluarga) REFERENCES peserta(nik),
    tagihan DECIMAL(10, 2),
    status_pembayaran VARCHAR(20)
);

CREATE TABLE virtual_account( -- ubah nama tabel dari "virtual_acount" menjadi "virtual_account"
    id INT AUTO_INCREMENT PRIMARY KEY,
    bank VARCHAR(50),
    virtual_account VARCHAR(50), -- perbaiki penulisan "virtual_account"
    nik_kepala_keluarga VARCHAR(50),
    FOREIGN KEY (nik_kepala_keluarga) REFERENCES peserta(nik)
);

CREATE TABLE info_jkn_panduan_layanan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipe_info_jkn VARCHAR(50),
    file_url VARCHAR(255)
);

========

CREATE TABLE siswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nis VARCHAR(50),
    email VARCHAR(100),
    password VARCHAR(100),
    nama VARCHAR(100),
    no_orang_tua VARCHAR(20)
);

CREATE TABLE ujian_jadwal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100),
    tgl DATE,
    jml_soal INT,
    quiz_id INT,
    status VARCHAR(50),
    catatan TEXT
);

CREATE TABLE ujian_hasil (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tgl_jam DATETIME,
    siswa_id INT,
    ujian_jadwal_id INT,
    jml_benar INT,
    jml_salah INT,
    nilai DECIMAL(5,2),
    catatan TEXT,
    FOREIGN KEY (siswa_id) REFERENCES siswa(id),
    FOREIGN KEY (ujian_jadwal_id) REFERENCES ujian_jadwal(id)
);

CREATE TABLE `otp_verification` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `otp` varchar(6) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;