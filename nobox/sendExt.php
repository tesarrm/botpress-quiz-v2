<?php
include 'nobox.php';


header("Content-Type:application/json");
$conn = mysqli_connect('localhost', 'id21978988_root', 'asjfhsjd##$%lkjsfas2332RRR', 'id21978988_ps123');
mysqli_set_charset($conn, 'utf8');
$method = $_SERVER['REQUEST_METHOD'];
$results = array();


$query = mysqli_query($conn, 'SELECT * FROM token');
$firstRow = mysqli_fetch_assoc($query);
$token = $firstRow['token'];

// $query1 = mysqli_query($conn, 'SELECT * FROM siswa');



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idsiswa = $_POST['idsiswa'];

    $query1 = "SELECT * FROM siswa WHERE id = $idsiswa";
    $result1 = mysqli_query($conn, $query1);
    $data = mysqli_fetch_assoc($result1);

    $hportu = $data['hportu'];

    echo json_encode($hportu);


    // Your PHP function code here
    $pelanggaran = $_POST['pelanggaran'];
    $idsiswa = $_POST['idsiswa'];
    $token = $token;
    $extId = '628993673900';
    $channelId = '1';
    $accountIds = '540030223855621';
    $bodyType = '1';
    $body = "Anak anda melakuakan pelanggaran ini: $pelanggaran";
    $attachment = '[]';




    // $token = $_COOKIE['token'];
    // $extId = $data['ExtId'];
    // $channelId = $data['ChannelId'];
    // $accountIds = $data['AccountIds'];
    // $bodyType = $data['BodyType'];
    // $body = $data['Body'];
    // $attachment = $data['Attachment'];
    $nobox = new Nobox($token);
    $tokenResponse = $nobox->sendInboxMessageExt($extId, $channelId, $accountIds, $bodyType, $body, $attachment);
    echo json_encode($tokenResponse);
}
