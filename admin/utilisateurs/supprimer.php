<?php
    include('../../private/usermanager.php');

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