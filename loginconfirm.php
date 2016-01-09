<?php
    if (isset($_POST['user']) and isset($_POST['pwd'])){
        if (true) {
            // connexion réussie
            session_start();
            $_SESSION[$_POST['user']] = array(
                "pseudo" => $_POST['user'],
                "pwd" => $_POST['pwd']
            );
            header("Location: index.php");
        }
    } else {
        header("Location: error.php?error=connexion");
    }
?>