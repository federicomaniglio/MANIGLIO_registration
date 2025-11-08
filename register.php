<?php

require_once "database.php";

if(!empty($_POST)){
    if(($_POST['password'] ?? '') !== $_POST['password2'] ){
        $errors["password"] = "Le password non coincidono";
    }

    $pdo = Database::getInstance()->getConnection();
    $stmt = $pdo->prepare("SELECT * FROM utenti WHERE username = :username");
    $stmt->execute(['username' => $_POST['username']]);
    $utente = $stmt->fetch();
    if($utente){
        $errors["username"] = "Username esistente";
    }
    $stmt = $pdo->prepare("SELECT * FROM utenti WHERE email = :email");
    $stmt->execute(['email' => $_POST['email']]);
    $email = $stmt->fetch();
    if($email){
        $errors["email"] = "Email esistente";
    }

    if(empty($errors)){
        require "email_verification_service.php";
        $token_info = generateEmailVerificationToken();
        $stmt = $pdo->prepare("INSERT INTO utenti (username, password, nome, cognome, email, verification_token, verification_expires) VALUES (:username, :password, :nome, :cognome, :email, :verification_token, :verification_expires)");

        $stmt->execute([
                "username" => $_POST['username'],
            "password" => password_hash($_POST['password'], PASSWORD_DEFAULT),
            "email" => $_POST['email'],
            "nome" => $_POST['nome'],
            "cognome" => $_POST['cognome'],
            "verification_token" => $token_info[0],
            "verification_expires" => ($token_info[1] instanceof DateTimeInterface) ? $token_info[1]->format("Y-m-d H:i:s") : $token_info[1]
        ]);

        $url = urlencode("http://localhost/MANIGLIO_registration/confirm_verification.php?token=$token_info[0]");

        sendVerificationEmail($_POST['email'], $_POST["username"], $url);








    }



}


$title = "Registrazione";
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title><?= $title ?></title>
</head>
<body>
<h1><?= $title ?></h1>


<p> <?php if(!empty($errors)) print_r($errors) ?></p>

<form method="post" class="login">
    <div class="form-element">
        <div class="label">
            <label for="username">Username</label>
                 </div>
        <input type="text" name="username" id="username" required
               value="">
    </div>
    <div class="form-element">
        <div class="label">
            <label for="nome">Nome</label>
        </div>
        <input type="text" name="nome" id="nome" required
               value=""">
    </div>
    <div class="form-element">
        <div class="label">
            <label for="cognome">Cognome</label>
        </div>
        <input type="text" name="cognome" id="cognome" required
               value="">
    </div>
    <div class="form-element">
        <div class="label">
            <label for="email">Email</label>
        </div>
        <input type="email" name="email" id="email" required
               value="">
    </div>
    <div class="form-element">
        <div class="label">
            <label for="password">Password</label>

        </div>
        <input type="password" name="password" id="password"
               required>
    </div>
    <div class="form-element">
        <div class="label">
            <label for="password2">Conferma Password</label>

        </div>

        <input type="password" name="password2" id="password2"
               required>

    </div>
    <div class="form-element">
        <input type="submit" value="Registrati">
    </div>
</form>


</body>
</html>
