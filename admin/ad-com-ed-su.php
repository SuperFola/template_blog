<?php
    session_start();
    
    include(__DIR__ . "/../private/postmanager.php");
    
    if (isset($_SESSION)) {
        if ($_SESSION['role'] == 'ADMINISTRATEUR') {
            // on est bien admin
            if (isset($_GET['action'])){
                $postid = intval($_GET['postid']);
                $timestamp = intval($_GET['comts']);
                $done = false;
                $pm = new PostManager();
                $post = $pm->findPost($postid);
                
                if ($_GET['action'] == 'delete') {
                    if (isset($_GET['postid']) and isset($_GET['comts'])) {
                        foreach($post->getCommentairesSorted() as $commentaire) {
                            if ($commentaire->getTimestamp() == $timestamp) {
                                $post->removeCommentaire($commentaire);
                                $pm->updatePost($post);
                                $done = true;
                                break;
                            }
                        }
                        
                        if ($done)
                            echo 'Supprimé';
                        else
                            echo 'Il s\'est passé quoi ?';
                    } else {
                        echo 'Arguments manquants';
                    }
                } else if ($_GET['action'] == 'edit') {
                    $pm->updatePost($post);
                }
            } else {
                echo 'pas d\'action';
            }
        } else {
            echo 'pas admin';
        }
    } else {
        echo 'pas de session';
    }
?>