<?php
    session_start();
    
    include('private/usermanager.php');
    include('private/blockedusersmanager.php');
    
    $um = new UserManager();
    $bum = new BlockedUsersManager();
    
    if (isset($_POST['user']) and isset($_POST['pwd'])){
        $user = $um->findUserByPseudo($_POST['user']);
        if (!$bum->isBlocked()) {
            if ($user){
                if ($user->checkLogin($_POST['pwd'])) {
                    // connexion réussie
                    $user->setLastLogin(time());
                    $um->editUser($user);
                    $um->updateUsers();
                    $_SESSION["pseudo"] = $_POST["user"];
                    $_SESSION["role"] = $user->getRole();
                    header("Location: index.php");
                } else
                    header("Location: error.php?error=connexion");
            } else
                header("Location: error.php?error=connexion");
        } else
            header("Location: error.php?error=ip_blocked");
    } else
        header("Location: error.php?error=connexion");
?>