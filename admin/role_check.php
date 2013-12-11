<?php
$checked = FALSE;
if ($_SESSION['role_id'] != 1){
    foreach ($roles_required as $role_id){
        if ($role_id == $_SESSION['role_id']){
            $checked = TRUE;
            break;
        }
    }
}
else{
    $checked = TRUE;
}

if (!$checked){
    header("Location: role_required.php");
}
?>