<?php
    session_start();
    
    include(__DIR__ . "/../private/postmanager.php");
    
    if (isset($_SESSION)) {
        if ($_SESSION['role'] == 'ADMINISTRATEUR') {
            // on est bien admin
            if (isset($_GET['action'])){
                if ($_GET['action'] == 'delete') {
                    if (isset($_GET['postid']) and isset($_GET['comts'])) {
                        $postid = intval($_GET['postid']);
                        $timestamp = intval($_GET['comts']);
                        $done = false;
                        $pm = new PostManager();
                        foreach ($pm->findAll() as $post) {
                            foreach($post->getCommentairesSorted() as $commentaire) {
                                if ($post->getId() == $postid and $commentaire->getTimestamp()) {
                                    $post->removeCommentaire($commentaire);
                                    $done = true;
                                }
                                
                                if ($done){
                                    break;
                                }
                            }
                            
                            if ($done) {
                                break;
                            }
                        }
                        
                        if ($done) {
                            echo 'Supprimé';
                        } else {
                            echo 'Il s\'est passé quoi ?';
                        }
                    }
                } else if ($_GET['action'] == 'edit') {
                    
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