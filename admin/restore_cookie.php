<?php
session_start();
// If the session vars aren't set, try to set them with a cookie
if(isset($_POST['account'])){
    $_SESSION['account'] = $_POST['account'];
}
else{
    if (!isset($_SESSION['account'])) {
        if (isset($_COOKIE['account'])) {
            $_SESSION['account'] = $_COOKIE['account'];
        }
        else{
            $_SESSION['account'] = "";
        }
        
    }
}
?>