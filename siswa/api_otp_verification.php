<?php
include "../utils/header.php"; // $conn, $method, $result
include '../nobox/nobox.php';



switch ($method) {
    case 'POST':

        $email = $_POST['email'];
        $password = $_POST['password'];
        $otp = $_POST['otp'];

        // login nobox
        $token = generateToken($email, $password);

        $sql = "INSERT INTO token_nobox (email, token) VALUES ('$email','$token')";

        global $conn; // Sesuaikan dengan koneksi database Anda

        if (authenticateUser($email, $otp)) {
            // Autentikasi berhasil, kirim respons JSON
            if ($conn->query($sql) === TRUE) {
                echo json_encode(array("message" => "Autentikasi berhasil", "token_nobox" => $token));
            } else {
                echo json_encode(array('message' => 'Error: ' . $conn->error));
            }
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


function generateToken($email, $password)
{
    // Your PHP function code here
    $nobox = new Nobox(null);
    $tokenResponse = $nobox->generateToken($email, $password);

    // echo json_encode($tokenResponse->Data);
    return $tokenResponse->Data;
}




// generateToken();
