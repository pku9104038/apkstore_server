<?php
session_start();
//unset($_SESSION['role_id']);
//unset($_SESSION['role']);
//unset($_SESSION['account']);
session_destroy();
setcookie('account', "", time()-(60*60*24*7));


header("Location: ../admin/log_in.php");
?>