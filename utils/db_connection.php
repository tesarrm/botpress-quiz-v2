<?php
$conn = mysqli_connect('localhost', 'root', '', 'botpress-quiz-v2');
if (!$conn) {
    http_response_code(500);
    echo json_encode(array('error' => 'Failed to connect to database'));
    exit;
}
mysqli_set_charset($conn, 'utf8');
