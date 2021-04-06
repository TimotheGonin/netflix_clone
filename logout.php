<?php
    session_start(); //initialise la session
    session_unset(); //desactive la session
    session_destroy(); // détruit la session

    //SUPPRESSION COOKIE
    setcookie('auth', '', time()-1, '/', null, false, true);

    header('location: index.php');
    exit();
?>