<?php
    require_once("../../api/api.php");
    Auth::logout();
    header("Location:" . $_SERVER['HTTP_REFERER']);
?>