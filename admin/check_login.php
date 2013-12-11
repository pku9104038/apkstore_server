<?php
session_start();
if(!isset($_SESSION['role_id']) || !isset($_SESSION['account'])){
    header("Location: ./log_in.php");
    exit;
}
?>