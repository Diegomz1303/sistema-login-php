<?php


require_once '../vendor/autoload.php';
require_once 'db_connect.php';
session_start();

$client = new Google_Client();
$client->setClientId('1025249111506-f45fhikj07ft79q2efr1vhs4avgdngve.apps.googleusercontent.com');
$client->setClientSecret('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'); 
$client->setRedirectUri('http://localhost/mi_proyecto/backend/google-callback.php');


if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

   
    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();
    
    $google_id = $google_account_info->id;
    $email = $google_account_info->email;
    $nombre = $google_account_info->name;

    
    $sql = "SELECT * FROM usuarios WHERE google_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $google_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nombre'];
    } else {
        
        
        $sql = "INSERT INTO usuarios (google_id, nombre, email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $google_id, $nombre, $email);
        $stmt->execute();
        
        
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['user_name'] = $nombre;
    }

    $stmt->close();
    $conn->close();

    
    header('Location: ../frontend/dashboard.php');
    exit();

} else {
    
    header('Location: ../frontend/index.html');
    exit();
}
?>