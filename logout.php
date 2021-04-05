<?php
    session_start(); //initialise la session
    session_unset(); //desactive la session
    session_destroy(); // détruit la session

    header('location: index.php');
    exit();
?>