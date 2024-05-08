<?php
include "../utils/header.php"; // $conn, $method, $result
include '../nobox/nobox.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Sesuaikan dengan lokasi file autoload PHPMailer

switch ($method) {
    case 'POST':


        // ambil id siswa dari email
        $email = $_POST['email'];
        $query1 = "SELECT * FROM siswa WHERE email= '$email'";
        $result1 = mysqli_query($conn, $query1);
        $siswa = mysqli_fetch_assoc($result1);
        $id = $siswa['id'];
        $nama = $siswa['nama'];

        $tgl_jam = $_POST['tgl_jam'];
        $ujian_jadwal_id = $_POST['ujian_jadwal_id'];
        $jml_benar = $_POST['jml_benar'];
        $jml_salah = $_POST['jml_salah'];
        $nilai = $_POST['nilai'];

        $sql = "INSERT INTO ujian_hasil (
                siswa_id,
                tgl_jam,
                ujian_jadwal_id,
                jml_benar,
                jml_salah,
                nilai
            ) VALUES (
                '$id',
                '$tgl_jam',
                '$ujian_jadwal_id',
                '$jml_benar',
                '$jml_salah',
                '$nilai'
            )";
        $result = mysqli_query($conn, $sql);


        // get nama ujian
        $jadwals = array();
        $query9 = "SELECT * FROM ujian_jadwal WHERE id = $ujian_jadwal_id";
        $result9 = mysqli_query($conn, $query9);

        $ujian = mysqli_fetch_assoc($result9);
        $ujian_nama = $ujian['nama'];


        // Buat isi email
        $ujian = "
            <h2>Hasil Ujian</h2>
            <h3>Selamat $nama anda telah melakukan ujian berikut:</h3>
            <p>Nama Ujian: $ujian_nama</p>
            <p>Tanggal & Jam Ujian: $tgl_jam</p>
            <p>Tanggal & Jam Ujian: $tgl_jam</p>
            <p>Jumlah Benar: $jml_benar</p>
            <p>Jumlah Salah: $jml_salah</p>
            <p>Nilai: $nilai</p>
        ";



        // kirim nobox wa
        $query88 = mysqli_query($conn, 'SELECT * FROM token_nobox');
        $firstRow = mysqli_fetch_assoc($query88);
        $token = $firstRow['token'];

        $no_orang_tua = $siswa['no_orang_tua'];
        echo json_encode($no_orang_tua);

        $ujian_wa =
            "
*Hasil Ujian*

_Anak anda $nama, telah melakukan ujian berikut_

Nama Ujian: $ujian_nama
Tanggal & Jam Ujian: $tgl_jam
Jumlah Benar: $jml_benar
Jumblah Salah: $jml_salah
Nilai: $nilai
        ";


        $nobox = new Nobox($token);
        $listAccount = $nobox->getAccountList();
        $accountData = $listAccount->Data;


        $accountIdYa = '';

        // Melakukan pengelompokan berdasarkan ID
        foreach ($accountData as $item) {
            $nama = $item->Name;
            // Memeriksa apakah string 'WhatsApp' ditemukan dalam nama
            if (strpos($nama, 'WhatsApp') !== false) {
                $accountIdYa = $item->Id;
                break; // Keluar dari loop setelah menemukan ID pertama yang cocok
            }
        }
        // echo json_encode($listAccount);
        // }




        // Your PHP function code here
        $extId = $no_orang_tua;
        $channelId = '1';
        $accountIds = $accountIdYa;
        $bodyType = '1';
        $body = $ujian_wa;
        $attachment = '[]';

        $nobox = new Nobox($token);
        $tokenResponse = $nobox->sendInboxMessageExt($extId, $channelId, $accountIds, $bodyType, $body, $attachment);
        // echo json_encode($tokenResponse);


        // Kirim email
        if (kirimUjian($email, $ujian)) {
            echo json_encode(array('message' => 'Data berhasil ditambahkan dan email terkirim', 'response_token' => $tokenResponse));
        } else {
            echo json_encode(array('message' => 'Data berhasil ditambahkan, tetapi email gagal terkirim'));
        }


        break;

    default:
        http_response_code(405);
        echo json_encode(array('error' => 'Method not allowed'));
        break;
}

function kirimUjian($email, $ujian)
{
    $mail = new PHPMailer(true);

    try {
        // Konfigurasi SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Host SMTP Gmail
        $mail->SMTPAuth = true;
        $mail->Username = 'tesarrm58@gmail.com'; // Alamat email Anda
        $mail->Password = 'cslulirpurnvnnpw'; // Kata sandi email Anda
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587; // Port SMTP untuk TLS

        // Set pengirim dan penerima email
        $mail->setFrom('tesarrm58@gmail.com', 'Support'); // Alamat email pengirim
        $mail->addAddress($email); // Alamat email penerima

        // Konten email
        $mail->isHTML(true);
        $mail->Subject = 'Hasil Ujian';
        $mail->Body = $ujian;

        // Kirim email
        $mail->send();

        return true;
    } catch (Exception $e) {
        // Jika ada kesalahan, tangani di sini
        return false;
    }
}
