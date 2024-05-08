<?php
include "../utils/header.php"; // $conn, $method, #result

switch ($method) {
    case 'POST':

        $id = $_POST['id'];

        $query1 = "SELECT * FROM ujian_jadwal WHERE id= $id";
        $result1 = mysqli_query($conn, $query1);

        if ($result1 && mysqli_num_rows($result1) > 0) {
            $quiz = mysqli_fetch_assoc($result1);
            $quiz_id = $quiz['quiz_id'];

            echo json_encode($quiz_id);
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
