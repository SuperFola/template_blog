<?php
    if (isset($_SESSION)) {
        if ($_SESSION['role'] == 'ADMINISTRATEUR') {
            // on est bien admin
            if (isset($_GET['action'])){
                if ($_GET['action'] == 'delete') {
                    if (isset($_GET['postid']) and isset($_GET['comts'])) {
                        $postid = intval($_GET['postid']);
                        $timestamp = floatval($_GET['comts']);
                        $done = false;
                        $pm = new PostManager();
                        foreach ($pm->findAll() as $post) {
                            foreach($post->getCommentairesSorted() as $commentaire) {
                                if ($post->getId() == $postId and $commentaire->getTimestamp()) {
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
                    }
                } else if ($_GET['action'] == 'edit') {
                    
                }
            }
        }
    }
?>