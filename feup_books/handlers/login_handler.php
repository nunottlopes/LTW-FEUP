<?php
    require_once("../../api/api.php");

    $username = $_POST['username'];
    $password = $_POST['password'];
    $error;

    $ret = Auth::login($username, $password, $error);

    if ($ret) {
        header("Location:" . $_SERVER['HTTP_REFERER']);
    } else {
        echo $error;
    }
?>
