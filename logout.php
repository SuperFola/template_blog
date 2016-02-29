<?php
    session_start();
    
    unset($_SESSION['pseudo']);
    unset($_SESSION['role']);
    header("Location: index.php");
?>