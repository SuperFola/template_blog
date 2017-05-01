<?php
    session_start();
    
    include('private/usermanager.php');
    include('private/blockedusersmanager.php');
    
    $um = new UserManager();
    $bum = new BlockedUsersManager();
    $r = false;
    
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
                    $r = true;
                    header("Location: index.php");
                }
            }
        }
    }
    if (!$r) {
        // if we are here it is because we we'ren't redirected
        http_response_code(403);
        exit();
    }
?>