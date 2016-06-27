<?php
    session_start();
    
    include('../private/postmanager.php');
    
    $pm = new PostManager();
    if (isset($_GET['post']) and $_SESSION['role'] == 'ADMINISTRATEUR') {
        $posts = $pm->findAll();
        $final_post = null;
        foreach ($posts as $post) {
            if ($post->getId() == $_GET['post']) {
                $final_post = $post;
                break;
            }
        }
        if ($final_post) {
            if (intval($_GET['edit']) == 1) {
                echo 'La fonctionnalité d\'édition de post n\'est pas encore prise en compte.';
            } else {
                $pm->deletePost($final_post);
                echo 'Supprimé';
            }
        } else {
            echo 'Impossible de trouver le post';
        }
    } else {
        echo 'Pas les droits';
    }
?>