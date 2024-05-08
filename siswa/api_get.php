<?php
include "../utils/header.php"; // $conn, $method, $result



switch ($method) {
    case 'POST':

        $email = $_POST['email'];

        $query1 = "SELECT * FROM siswa WHERE email= '$email'";
        $result1 = mysqli_query($conn, $query1);

        if ($result1 && mysqli_num_rows($result1) > 0) {
            $siswa = mysqli_fetch_assoc($result1);
            $nama = $siswa['nama'];

            echo json_encode($nama);
        } else {
            echo json_encode(array('message' => 'Data tidak ditemukan'));
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(array('error' => 'Method not allowed'));
        break;
}

// Close the connection
mysqli_close($conn);
