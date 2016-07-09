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
                if (!isset($_SESSION['pseudo']) and !isset($_SESSION['role'])){
                    header("Location: ../login.php");
                } else if (in_array($_SESSION['role'], array('ADMINISTRATEUR', 'MODERATEUR', 'AUTEUR'))) {
                   // connexion réussie
                   echo "<br />";
                   if ($_SESSION['role'] == 'ADMINISTRATEUR' or $_SESSION['role'] == 'MODERATEUR')
                        echo "<div class=\"breadcrumb-container\">
                                  <ol class=\"breadcrumb\">
                                      <li><a href=\"writing.php\">Ecrire un article</a></li>
                                      <li><a href=\"utilisateurs/\">Gérer les utilisateurs</a></li>
                                      <li><a href=\"edit_config.php\">Editer la configuration du blog</a></li>
                                      <li><a href=\"block_ip.php\">Bloquer une IP</a></li>
                                      <li><a href=\"remarquable_articles.php\">Gérer les articles mis en avant</a></li>
                                      <li><a href=\"../private/cible_envoi.php\">Héberger une image</a></li>
                                  </ol>
                              </div>";
                    else if ($_SESSION['role'] == 'AUTEUR')
                        echo "<div class=\"breadcrumb-container\">
                                  <ol class=\"breadcrumb\">
                                      <li><a href=\"writing.php\">Ecrire un article</a></li>
                                      <li><a href=\"../private/cible_envoi.php\">Héberger une image</a></li>
                                  </ol>
                              </div>";
                    
                    // les news
                    echo "Liste des news : <br />";
                    echo "<ul>";
                    $pm = new PostManager();
                    foreach ($pm->findAll() as $post) {
                        echo "<li>";
                        echo "{$post->getId()} - Catégorie : <b>{$post->getCategorie()}</b> - Titre : <b><a href=\"../post.php?id={$post->getId()}\" target=blank>{$post->getTitre()}</a></b> ; {$post->getDisplayableDate()}";
                        if ($post->getEdited()){
                            echo " - A été édité";
                        }
                        echo " - <a href='writing.php?action=post_edit&post={$post->getId()}' target='blank'>Editer</a> - <a href='supr_edit_news.php?edit=0&post={$post->getId()}' target='blank'>Supprimer</a>";
                        echo "</li>";
                    }
                    echo "</ul><hr><a class=\"btn btn-primary\" href=\"comments.php\">Liste des commentaires</a>";
                } else {
                    header('Location: ../error.php?error=403');
                }
            ?>
            
            <?php
                include(__DIR__ . '/../footer.php');
            ?>
        </div>
    </body>
</HTML>