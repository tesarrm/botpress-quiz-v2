<?php
include "../utils/header.php"; // $conn, $method, $result



switch ($method) {
    case 'POST':

        $email = $_POST['email'];
        $otp = $_POST['otp'];
        if (authenticateUser($email, $otp)) {
            // Autentikasi berhasil, kirim respons JSON
            echo json_encode(array("message" => "Autentikasi berhasil"));
        } else {
            // Autentikasi gagal, kirim respons JSON dengan status 401
            echo json_encode(array("message" => "Autentikasi gagal. OTP tidak valid"));
        }

        break;

    default:
        http_response_code(405);
        echo json_encode(array('error' => 'Method not allowed'));
        break;
}


// Fungsi untuk mengautentikasi pengguna berdasarkan OTP
function authenticateUser($email, $otp)
{
    global $conn; // Sesuaikan dengan koneksi database Anda
    $sql = "SELECT * FROM otp_verification WHERE email = '$email' AND otp = '$otp'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        // Hapus OTP dari database setelah digunakan
        $sql_delete = "DELETE FROM otp_verification WHERE email = '$email'";
        mysqli_query($conn, $sql_delete);

        return true;
    } else {
        return false;
    }
}
