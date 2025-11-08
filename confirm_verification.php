<?php
require_once "database.php";

$title = "Conferma verifica";

if(!isset($_GET['token'])){
    header("Location: register.php");
    exit;
}

$pdo = Database::getInstance()->getConnection();
$stmt = $pdo->prepare("SELECT * FROM utenti WHERE verification_token = :token AND verification_expires > NOW() AND email_verified = false");
$stmt->execute(['token' => $_GET['token']]);
$utente = $stmt->fetch();

if($utente){
    $stmt = $pdo->prepare("UPDATE utenti SET email_verified = true, verification_token = NULL, verification_expires = NULL WHERE id = :id");
    $stmt->execute(['id' => $utente['id']]);
    $message = "Email verificata con successo";
} else {
    $message = "Token non valido o scaduto";
}



?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $title ?></title>
</head>
<body>
    <h1><?= $title ?></h1>

    <?php if(!empty($message)) echo "<p>$message</p>"; ?>

</body>
</html>
