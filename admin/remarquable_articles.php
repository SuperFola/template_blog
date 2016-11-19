<?php
    session_start();
?>

<!DOCTYPE html>

<HTML>
    <?php include('head.php'); ?>
    <body>
        <?php include(__DIR__ . '/header.php'); ?>
        <div class="container">
            <?php
                if (!isset($_SESSION['pseudo']) and (!isset($_SESSION['role']) or $_SESSION['role'] != 'ADMINISTRATEUR')){
                    header("Location: ../login.php");
                } else if ($_SESSION['role'] == 'ADMINISTRATEUR'){
                    // connexion réussie
                    echo "Interface d'administration, bonjour {$_SESSION['pseudo']}";
                    echo "<br /><br />";
                    
                    $fpp_mgr = new FirstPagePostsManager();
                    $pm = new PostManager();
                    
                    if (isset($_POST['article_id']) && isset($_POST['image_path'])) {
                        if ($_POST['article_id'] >= 1 && $_POST['article_id'] <= count($pm->findAll())) {
                            $fpp_mgr->hydrate(array("post_id" => intval($_POST['article_id']), "post_image" => $_POST['image_path']));
                            $fpp_mgr->persistPostsList();
                            echo '<div class="alert alert-success" role="alert">Article ajouté avec succès !</div>';
                        } else {
                            echo '<div class="alert alert-danger" role="alert">Une erreur est survenue ! L\'id de l\'article ne doit pas être correct</div>';
                        }
                    }
                    
                    if (isset($_POST['article_id_to_delete'])) {
                        if ($fpp_mgr->deletePostNumber($_POST['article_id_to_delete'])) {
                            $fpp_mgr->persistPostsList();
                            echo '<div class="alert alert-success" role="alert">Article enlevé avec succès !</div>';
                        } else {
                            echo '<div class="alert alert-danger" role="alert">Une erreur est survenue ! L\'id de l\'article ne doit pas être correct</div>';
                        }
                    }
                    
                    echo "Liste des articles mis en avant : <br />";
                    if ($fpp_mgr->getSize() != 0) {
                        echo '<ul>';
                        $nb = 0;
                        foreach ($fpp_mgr->findAll() as $article) {
                            if ($article['id']) {
                                $post = $pm->findPost($article['id']);
                                echo '<li>';
                                echo $article['id'] . " - " . $post->getTitre() . " - Par " . $post->getAuthor() . " - Numéro " . $nb;
                                echo '</li>';
                                $nb++;
                            }
                        }
                        echo '</ul>';
                    } else {
                        echo '<div class="alert alert-info" role="alert">Aucun article n\'est actuellement mis en avant</div>';
                    }
                }
            ?>
            
            <br />
            <hr />
            <br />
            
            <form action="remarquable_articles.php" method="post">
                Ajouter un article :
                <br /><br />
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1">ID</span>
                    <input type="text" class="form-control" placeholder="post ID" name="article_id" aria-describedby="basic-addon1">
                </div>
                <br />
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon2">Chemin vers l'image</span>
                    <input type="text" class="form-control" placeholder="chemin/vers/l/image.png" name="image_path" aria-describedby="basic-addon2">
                </div>
                <br />
                <a class="btn btn-default" href="index.php" target="blank">Liste des posts</a>&nbsp;&nbsp;
                <a class="btn btn-default" href="../private/cible_envoi.php" target="blank">Héberger une image</a>&nbsp;&nbsp;
                <button type="submit" class="btn btn-primary">Valider</button>
            </form>
            
            <br />
            <hr />
            <br />
            
            <form action="remarquable_articles.php" method="post">
                Supprimer un article de la mise en avant :
                <br /><br />
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1">N° (0, 1, 2, ou 3)</span>
                    <input type="text" class="form-control" placeholder="numéro du post à supprimer" name="article_id_to_delete" aria-describedby="basic-addon1">
                </div>
                <br />
                <button type="submit" class="btn btn-danger">Valider</button>
            </form>
            
            <?php
                include(__DIR__ . '/footer.php');
            ?>
        </div>
    </body>
</HTML>