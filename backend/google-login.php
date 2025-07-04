<?php


require_once '../vendor/autoload.php';


$client = new Google_Client();
$client->setClientId('1025249111506-f45fhikj07ft79q2efr1vhs4avgdngve.apps.googleusercontent.com'); // Reemplaza con tu ID de Cliente
$client->setClientSecret('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'); // Reemplaza con tu Secreto de Cliente
$client->setRedirectUri('http://localhost/mi_proyecto/backend/google-callback.php');
$client->addScope("email");
$client->addScope("profile");


$auth_url = $client->createAuthUrl();
header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
exit();
?>