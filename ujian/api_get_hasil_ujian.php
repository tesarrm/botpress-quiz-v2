<?php
include "../utils/header.php"; // $conn, $method, #result

switch ($method) {
    case 'POST':

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {

            // get ujian
            $jadwals = array();
            $query9 = "SELECT * FROM ujian_jadwal";
            $result9 = mysqli_query($conn, $query9);

            if ($result9 && mysqli_num_rows($result9) > 0) {
                while ($row = mysqli_fetch_assoc($result9)) {
                    $jadwals[] = $row;
                }

                // echo json_encode($jadwals);
            } else {
                echo json_encode(array('message' => 'Data tidak ditemukan'));
            }


            // get hasil ujian
            $email = $_POST['email'];
            $query2 = "SELECT * FROM siswa WHERE email= '$email'";
            $result2 = mysqli_query($conn, $query2);

            if ($result2 && mysqli_num_rows($result2) > 0) {

                $siswa = mysqli_fetch_assoc($result2);
                $siswa_id = $siswa['id'];

                $query1 = "SELECT * FROM ujian_hasil";
                $result1 = mysqli_query($conn, $query1);

                if ($result1 && mysqli_num_rows($result1) > 0) {
                    $hasils = array();
                    while ($row = mysqli_fetch_assoc($result1)) {
                        $hasils[] = $row;
                    }

                    foreach ($hasils as $hasil) {
                        foreach ($jadwals as $ujian_item) {
                            if ($hasil['ujian_jadwal_id'] === $ujian_item['id']) {
                                // Menggabungkan data dari $hasil_ujian dan $ujian
                                $hasil_gabungan[] = array_merge($hasil, ['ujian' => $ujian_item]);
                                break;
                            }
                        }
                    }

                    echo json_encode($hasil_gabungan);
                } else {
                    echo json_encode(array('message' => 'Data tidak ditemukan'));
                }
            } else {
                echo json_encode(array('message' => 'Data tidak ditemukan'));
            }
        } else {
            // Autentikasi gagal, kirim respons JSON dengan status 401
            echo json_encode(array("message" => "Email ga ada"));
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(array('error' => 'Method not allowed'));
        break;
}

// Close the connection
mysqli_close($conn);
