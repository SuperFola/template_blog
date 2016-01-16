<?php
    include('private/usermanager.php');
    $um = new UserManager();
    
    if (isset($_POST['user']) and isset($_POST['pwd'])){
        $user = $um->findUserByPseudo($_POST['user']);
        if ($user){
            if ($user->is('MEMBRE') and $user->checkLogin($_POST['pwd'])) {
                // connexion réussie
                session_start();
                $_SESSION["pseudo"] = $_POST["user"];
                $_SESSION["role"] = "MEMBRE";
                header("Location: index.php");
            }
        } else {
            header("Location: error.php?error=connexion");
        }
    } else {
        header("Location: error.php?error=connexion");
    }
?>