<?php
include "../utils/header.php"; // $conn, $method, #result

switch ($method) {
    case 'GET':

        // ambil jadwal
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




        $query1 = "SELECT * FROM ujian_jadwal";
        $result1 = mysqli_query($conn, $query1);

        if ($result1 && mysqli_num_rows($result1) > 0) {
            $ujian = array();
            while ($row = mysqli_fetch_assoc($result1)) {
                $ujian[] = $row;
            }



            // get hasil ujian
            $query100 = "SELECT * FROM ujian_hasil";
            $result100 = mysqli_query($conn, $query100);

            $ujians = array();
            while ($row = mysqli_fetch_assoc($result100)) {
                $ujians[] = $row;
            }

            foreach ($ujians as $hasil) {
                foreach ($jadwals as $ujian_item) {
                    if ($hasil['ujian_jadwal_id'] === $ujian_item['id']) {
                        // Menggabungkan data dari $hasil_ujian dan $ujian
                        $hasil_ujian[] = array_merge($hasil, ['ujian' => $ujian_item]);
                        break;
                    }
                }
            }


            $ujian_belum_dikerjakan = [];

            // Loop melalui setiap ujian
            foreach ($ujian as $ujian_item) {
                $ujian_id = $ujian_item['id'];
                $ujian_dikerjakan = false;

                // Periksa apakah ujian telah dikerjakan
                foreach ($hasil_ujian as $hasil_ujian_item) {
                    if ($hasil_ujian_item['ujian']['id'] === $ujian_id) {
                        $ujian_dikerjakan = true;
                        break;
                    }
                }

                // Jika ujian belum dikerjakan, tambahkan ke array $ujian_belum_dikerjakan
                if (!$ujian_dikerjakan) {
                    $ujian_belum_dikerjakan[] = $ujian_item;
                }
            }


            echo json_encode($ujian_belum_dikerjakan);
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
