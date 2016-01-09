<?php
    include('private/post_storage.php');
    
    if (isset($_GET['id'])){
        $postid = intval($_GET['id']);

        $postManager = new PostManager();
        $post = $postManager->findPost($postid);

        if ($post->getId() == 0) {
            http_response_code(404);
            exit();
        }

        if ($_POST['cmd'] == 'post_comment_add') {
            $commentaire = new Commentaire();
            $commentaire->handlePostRequest();
            $validation = $commentaire->validate();
            if ($validation['valid']) {
                $post->addCommentaire($commentaire);
                $postManager->updatePost($post);
            }
        }
    }
    
?>

<!DOCTYPE html>
<HTML>
    <head>
        <title>Le blog d'un codeur</title>
        <meta charset="utf-8" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="Content-Language" content="fr-FR" />
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no" />

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <!-- Website Style -->
        <link rel="stylesheet" href="css/style.css">

    </head>
    <body>
    <?php
        include("header.php");
    ?>

    <div class="container">
        <div class="breadcrumb-container">
            <ol class="breadcrumb">
                <li><a href="index.php">Accueil</a></li>
                <li><a class="nolink"><?php echo $post->getTitre() ?></a></li>
            </ol>
        </div>
        <hr>
        <div class="post">
            <div class="post-header">
                <h1><?php echo $post->getTitre() ?></h1>
                <h4><?php echo $post->getDisplayableDate() ?></h4>
                <div class="col-md-4 col-md-offset-4">
                    <hr />
                </div>
            </div>
            <div class="post-content">
                <?php echo nl2br($post->getContent()) ?>
            </div>
        </div>
        <hr>
        <div>
            <div class="row">
                <div class="commentaire-form-container well col-md-8 col-md-offset-2">
                    <form class="commentaire-form form-horizontal" method="post">
                        <div class="container-fluid">
                            <h4>Donnez votre opinion !</h4>
                            <div class="form-group">
                                <input class="form-control" placeholder="Pseudo" name="post_comment_pseudo" />
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" row="5" placeholder="Votre message..." name="post_comment_message"></textarea>
                            </div>
                            <div class="form-footer">
                                <input type="hidden" name="cmd" value="post_comment_add" />
                                <input type="submit" class="btn btn-primary" value="Poster" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="commentaires-container col-md-10 col-md-offset-1">
                    <?php if (count($post->getCommentaires()) < 1): ?>
                            <h3 style="text-align: center">Aucun commentaire</h3>
                    <?php else: ?>
                            <h3><?php echo count($post->getCommentaires()) ?> Commentaire<?php if (count($post->getCommentaires()) > 1): ?>s<?php endif ?> : </h3>
                    <?php endif ?>
                    <?php
                    foreach($post->getCommentairesSorted() as $commentaire){
                        $validation = $commentaire->validate();
                        if (!$validation['valid']) {
                            continue;
                        }
                        $date = $commentaire->getDisplayableDate();
                        $pseudo = $commentaire->getPseudo();
                        $message = $commentaire->getMessage();
                    ?>
                    <div class="commentaire">
                        <div>
                            <div class="avatar">
                                <img src="http://identicon.org?t=<?php echo $pseudo ?>&s=50" class="img-responsive">
                            </div>
                            <div class="body">
                                <div class="header">
                                    <p><b><?php echo $pseudo ?></b>, <?php echo $date ?></p>
                                </div>
                                <div class="message">
                                    <?php echo nl2br($message) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <br />
        <br />

    </div>
    </body>
</HTML>