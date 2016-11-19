<?php
    session_start();
    
    include(__DIR__ . "/../private/projectmanager.php");
    include(__DIR__ . "/../private/postmanager.php");
    
    if (isset($_SESSION)) {
        $postid = intval($_GET['postid']);
        $aid = intval($_GET['aid']);
        $pm = new ProjectManager();
        $post = $pm->findProject($postid);
        $article = $post->findArticle($aid);
        $done = false;
        
        if ($_SESSION['role'] == 'ADMINISTRATEUR' or $_SESSION['role'] == 'MODERATEUR' or in_array($_SESSION['pseudo'], $post->getMembers())) {
            // on est bien admin
            if (isset($_GET['action'])){
                $timestamp = intval($_GET['comts']);
                
                if ($_GET['action'] == 'delete') {
                    if (isset($_GET['postid']) and isset($_GET['comts'])) {
                        foreach($article->getCommentairesSorted() as $commentaire) {
                            if ($commentaire->getTimestamp() == $timestamp) {
                                $article->removeCommentaire($commentaire);
                                $post->updateArticle($article);
                                $pm->updateProject($post);
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
                    foreach($article->getCommentairesSorted() as $commentaire) {
                        if ($commentaire->getTimestamp() == $timestamp) {
                            $commentaire->setMessage($_GET['message']);
                            $post->updateArticle($article);
                            $pm->updateProject($post);
                            $done = true;
                            break;
                        }
                    }
                    echo 'Edité';
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