<?php
    session_start();
?>

<!DOCTYPE html>
<HTML>
    <?php include('head.php'); ?>
    <body>
    <?php
        if (isset($_GET['id'])){
            $postid = intval($_GET['id']);

            $postManager = new PostManager();
            $post = $postManager->findPost($postid);

            if ($post->getId() == 0) {
                http_response_code(404);
                exit();
            }

            if (isset($_POST['cmd']) and $_POST['cmd'] == 'post_comment_add') {
                $commentaire = new Commentaire();
                if (!isset($_SESSION) or !isset($_SESSION['pseudo']))
                    $pseudo = htmlentities($_POST['post_comment_pseudo']);
                else
                    $pseudo = $_SESSION['pseudo'];
                $message = htmlentities($_POST['post_comment_message']);
                $commentaire->handlePostRequest($pseudo, $message);
                $validation = $commentaire->validate();
                if ($validation['valid']) {
                    $post->addCommentaire($commentaire);
                    $postManager->updatePost($post);
                }
                header("Location: post.php?id=" . $_GET['id'] . "#comments");
            }
        } else {
            header("Location: index.php");
        }
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
                <h4><?php echo $post->getDisplayableDate(); ?> par <?php echo $post->getAuthor(); ?></h4>
                <div class="col-md-4 col-md-offset-4">
                    <hr />
                </div>
            </div>
            <div class="post-content">
                <?php echo nl2br($post->getContent()); ?>
            </div>
        </div>
        <hr>
        <div>
            <div class="row">
                <div class="commentaire-form-container well col-md-8 col-md-offset-2">
                    <form class="commentaire-form form-horizontal" method="post">
                        <div class="container-fluid">
                            <?php if (!isset($_SESSION) or !isset($_SESSION['pseudo'])) { ?>
                            <h4>Donnez votre opinion !</h4>
                            <div class="form-group">
                                <input class="form-control" placeholder="Pseudo" name="post_comment_pseudo" />
                            </div>
                            <?php } else {
                                echo '<h4 style="display: inline;">Donnez votre opinion !</h4>';
                                echo '<div class="avatar" style="display: inline; float: right; line-height: 20px;">';
                                echo '<b>' . $_SESSION['pseudo'] . '</b>';
                                echo '<img src="http://identicon.org?t=' . $_SESSION['pseudo'] . '&s=50" class="img-responsive">';
                                echo '<br />';
                                echo '</div>';
                            } ?>
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
                            <h3 id="comments"><?php echo count($post->getCommentaires()) ?> Commentaire<?php if (count($post->getCommentaires()) > 1): ?>s<?php endif ?> : </h3>
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
        <?php
            include('footer.php');
        ?>
    </div>
    </body>
</HTML>