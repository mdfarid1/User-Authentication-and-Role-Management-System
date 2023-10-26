<?php
session_start();
if(isset($_SESSION['user'])){
    $user = $_SESSION['user'];

    if($user['role'] === 'user'){
        header("Location: ./user-page.php");
    }
    else if($user['role'] === 'manager'){
        header("Location: ./manager-page.php");
    }
    else if($user['role'] === 'admin'){
        header("Location: ./manage-role.php");
    }
}
else{
    header("Location: ./login.php");
}
?>



