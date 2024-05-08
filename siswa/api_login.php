<?php
include "../utils/header.php"; // $conn, $method, $result

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Sesuaikan dengan lokasi file autoload PHPMailer




switch ($method) {
    case 'POST':

        // cek apakah ada nis dan password di database
        $nis = $_POST['nis'];
        $password = $_POST['password'];

        $query1 = "SELECT * FROM siswa WHERE nis= $nis AND password= $password";
        $result1 = mysqli_query($conn, $query1);

        // jika ada maka kirim otp
        if ($result1 && mysqli_num_rows($result1) > 0) {
            // $peserta = mysqli_fetch_assoc($result1);
            // $nik_kepala_keluarga = $peserta['nik_kepala_keluarga'];

            // $query2 = "SELECT * FROM peserta WHERE nik_kepala_keluarga = $nik_kepala_keluarga";
            // $result2 = mysqli_query($conn, $query2);

            // if ($result2 && mysqli_num_rows($result1) > 0) {
            //     $anggota_keluarga = array();
            //     while ($row = mysqli_fetch_assoc($result2)) {
            //         $anggota_keluarga[] = $row;
            //     }

            //     echo json_encode($anggota_keluarga);
            // } else {
            //     http_response_code(500);
            //     echo json_encode(array('error' => 'Data tidak ditemukan'));
            // }

            $siswa = mysqli_fetch_assoc($result1);
            $email = $siswa['email'];

            $otp = generateOTP();
            if (sendOTP($email, $otp)) {
                // OTP berhasil dikirim, kirim respons JSON
                echo json_encode(array("message" => "OTP telah dikirim ke email Anda", "email"=> $email));
            } else {
                // Gagal mengirim OTP, kirim respons JSON dengan status 500
                echo json_encode(array("message" => "Gagal mengirim OTP. Silakan coba lagi."));
            }
        } else {
            echo json_encode(array('message' => 'Email atau password salah'));
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(array('error' => 'Method not allowed'));
        break;
}


// Fungsi untuk menghasilkan OTP
function generateOTP()
{
    $otp = rand(100000, 999999);
    return $otp;
}

// Fungsi untuk mengirimkan OTP ke email
function sendOTP($email, $otp)
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
        $mail->Subject = 'Kode OTP';
        $mail->Body = 'Kode OTP Anda: ' . $otp;

        // Kirim email
        $mail->send();

        // Simpan OTP di database
        global $conn; // Sesuaikan dengan koneksi database Anda
        $sql = "INSERT INTO otp_verification (email, otp) VALUES ('$email', '$otp')";
        $result = mysqli_query($conn, $sql);

        return true;
    } catch (Exception $e) {
        return false;
    }
}
