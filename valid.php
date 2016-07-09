<?php
    include("private/usermanager.php");
    
    $um = new UserManager();
    
    if (isset($_GET['key'])) {
        foreach ($um->findAll() as $user) {
            if ($user->activate($_GET['key'])) {
                $um->updateUsers();
                header('Location: index.php?action=activated');
            }
        }
        header('Location: index.php?action=failed_activation');
    }
    header('Location: index.php');
?>