<?php
    include('../../private/usermanager.php');
    if (isset($_SESSION) and $_SESSION['role'] == 'ADMINISTRATEUR') {
        $roles = array('ADMINISTRATEUR', 'AUTEUR', 'MODERATEUR', 'MEMBRE');

        $userManager = new UserManager();
        $user = $userManager->findUser($_GET['id']);

        if (!$user) {
            header('Location: index.php');
            http_response_code(404);
            exit();
        }

        $userManager->removeUser($user);
        $userManager->updateUsers();
        header('Location: index.php');
        exit();
    } else {
        header('Location: ../../error.php?error=403');
    }
?>