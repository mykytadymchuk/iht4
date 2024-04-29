<?php
    setcookie('username', '', time() - 3600);
    //setcookie('sort', '', time() - 3600);
    header('Location: loginPage.php');
?>