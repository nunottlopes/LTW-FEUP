<?php
    require_once("../../api/api.php");

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password2 = $_POST['repeat_password'];
    $linkFile = $_POST['fileToUpload'];

    if($password != $password2) echo "passes diferentes";
    else {
        //TODO: UPDATE USER
        
        // $id = User::create($username, $email, $password);
        // if($id) {
        //     Auth::login($username, $password, $error);
        //     header("Location:" . $_SERVER['HTTP_REFERER']);
        // } else {
        //     echo "erro (pw min size 6 ou username ja existe)";
        // }
    }

?>