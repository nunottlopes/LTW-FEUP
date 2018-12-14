<?php
    require_once("../../api/api.php");

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];

    if($password != $password2) echo "passes diferentes";
    else {
        $id = User::create($username, $email, $password);
        if($id) {
            Auth::login($username, $password, $error);
            header("Location:" . $_SERVER['HTTP_REFERER']);
        } else {
            echo "erro (pw min size 6 ou username ja existe)";
        }
    }

?>