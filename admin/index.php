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
                } else if ($_SESSION['role'] == 'ADMINISTRATEUR' or $_SESSION['role'] == 'MODERATEUR' or $_SESSION['role'] == 'AUTEUR'){
                    // connexion réussie
                    echo "Interface d'administration, bonjour {$_SESSION['pseudo']}";
                    
                    if ($_SESSION['role'] == 'ADMINISTRATEUR' or $_SESSION['role'] == 'MODERATEUR')
                        echo "<div class=\"breadcrumb-container\">
                                  <ol class=\"breadcrumb\">
                                      <li><a href=\"../index.php\">Accueil</a></li>
                                      <li><a href=\"writing.php\">Ecrire un article</a></li>
                                      <li><a href=\"utilisateurs/\">Gérer les utilisateurs</a></li>
                                      <li><a href=\"remarquable_articles.php\">Gérer les articles mis en avant</a></li>
                                  </ol>
                              </div>";
                    else if ($_SESSION['role'] == 'AUTEUR')
                        echo "<div class=\"breadcrumb-container\">
                                  <ol class=\"breadcrumb\">
                                      <li><a href=\"../index.php\">Accueil</a></li>
                                      <li><a href=\"writing.php\">Ecrire un article</a></li>
                                  </ol>
                              </div>";
                    
                    // les news
                    echo "Liste des news : <br />";
                    echo "<ul>";
                    $pm = new PostManager();
                    foreach ($pm->findAll() as $post) {
                        echo "<li>";
                        echo "{$post->getId()} - {$post->getCategorie()} {$post->getTitre()}  {$post->getDisplayableDate()}";
                        if ($post->getEdited()){
                            echo " - A été édité";
                        }
                        echo " - <a href='supr_edit_news.php?edit=1&post={$post->getId()}' target='blank'>Editer</a> - <a href='supr_edit_news.php?edit=0&post={$post->getId()}' target='blank'>Supprimer</a>";
                        echo "</li>";
                    }
                    echo "</ul>";
                    
                    echo "<hr />";
                    
                    // les commentaires (tous)
                    echo "<br />";
                    echo "Liste des commentaires de tous les articles : <br />";
                    echo "<ul>";
                    $count = 0;
                    $max = 100;
                    foreach ($pm->findAll() as $post) {
                        foreach($post->getCommentairesSorted() as $commentaire) {
                            if ($count < $max){
                                echo "<li>";
                                echo "Par {$commentaire->getPseudo()} (ip:{$commentaire->getIp()}) - <a onclick=\"s('" . $count . "');\">{$commentaire->getDisplayableDate()}</a> - 
                                      <a href=\"ad-com-ed-su.php?action=delete&postid={$post->getId()}&comts={$commentaire->getTimestamp()}\" target=blank>Supprimer</a> - 
                                      <a href=\"ad-com-ed-su.php?action=edit&postid={$post->getId()}&comts={$commentaire->getTimestamp()}\" target=blank>Editer</a><br />";
                                echo "<div class=\"spoiler\" id='" . $count . "'>{$commentaire->getMessage()}</div>";
                                echo "</li>";
                                
                                ++$count;
                            }
                        }
                        if ($count >= $max){
                            break;
                        }
                    }
                    echo "</ul>";
                }
            ?>
            
            <?php
                include(__DIR__ . '/../footer.php');
            ?>
        </div>
    </body>
</HTML>