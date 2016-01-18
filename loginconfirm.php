<?php
    session_start();
    
    include('private/usermanager.php');
    $um = new UserManager();
    
    if (isset($_POST['user']) and isset($_POST['pwd'])){
        $user = $um->findUserByPseudo($_POST['user']);
        if ($user){
            if ($user->checkLogin($_POST['pwd'])) {
                // connexion réussie
                $_SESSION["pseudo"] = $_POST["user"];
                $_SESSION["role"] = $user->getRole();
                header("Location: index.php");
            } else {
                header("Location: error.php?error=connexion");
            }
        } else {
            header("Location: error.php?error=connexion");
        }
    } else {
        header("Location: error.php?error=connexion");
    }
?>