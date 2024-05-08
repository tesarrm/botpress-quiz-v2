<?php
include "../utils/header.php"; // $conn, $method, $result

switch ($method) {
    case 'POST':

        // ambil id siswa dari emal
        $email = $_POST['email'];
        $query1 = "SELECT * FROM siswa WHERE email= '$email'";
        $result1 = mysqli_query($conn, $query1);
        $siswa = mysqli_fetch_assoc($result1);
        $id = $siswa['id'];


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

        echo json_encode(array('message' => 'Data berhasil ditambahkan'));

        break;

    default:
        http_response_code(405);
        echo json_encode(array('error' => 'Method not allowed'));
        break;
}
