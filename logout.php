<?php
    session_start();
    require_once("config.php");
    unset($_SESSION['user_login']);
    unset($_SESSION['admin_login']);
    header('location:index.html');
?>