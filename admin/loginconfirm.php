<?php
    if (isset($_POST['user']) and isset($_POST['pwd'])){
        if (true) {
            // connexion réussie
            session_start();
            $_SESSION["pseudo"] = $_POST["user"];
            $_SESSION["role"] = "ADMINISTRATEUR";
            header("Location: index.php");
        }
    } else {
        header("Location: ../error.php?error=connexion");
    }
?>