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
                    
                    // les commentaires (tous)
                    $pm = new PostManager();
                    $um = new UserManager();
                    
                    echo "Liste des commentaires de tous les articles";
                    echo "<br><br>";
                    echo "<ul>";
                    
                    $begin = 0;
                    if (isset($_GET['count'])) {
                        $count = intval($_GET['count']);
                        $begin = $count;
                    }
                    else
                        $count = 0;
                    $max = 100;
                    
                    foreach ($pm->findAll() as $post) {
                        if (count($post->getCommentairesSorted()) > 0)
                            echo "<h4>Sur le post : <a href=\"../post.php?id=" . $post->getId() . "\">{$post->getId()} : {$post->getTitre()}</a></h4>";
                        
                        foreach($post->getCommentairesSorted() as $commentaire) {
                            if ($count < $max){
                                $pseudoFormated = $commentaire->getPseudo();
                                if ($um->findUserByPseudo($pseudoFormated))
                                    $pseudoFormated = "<a href=\"../ucp.php?id=" . $um->findUserByPseudo($pseudoFormated)->getId() . "\">" . $pseudoFormated . "</a>";
                                echo "<li>";
                                echo "Par {$pseudoFormated} (ip:{$commentaire->getIp()}) - ";
                                echo "<a onclick=\"s('" . $count . "');\">{$commentaire->getDisplayableDate()}</a> - ";
                                echo "<a href=\"ad-com-ed-su.php?action=delete&postid={$post->getId()}&comts={$commentaire->getTimestamp()}\" target=blank>Supprimer</a> - ";
                                echo "<a href=\"ad-com-ed-su.php?action=edit&postid={$post->getId()}&comts={$commentaire->getTimestamp()}\" target=blank>Editer</a>";
                                echo "<br />";
                                echo "<div class=\"spoiler\" id='" . $count . "'>{$commentaire->getMessage()}</div>";
                                echo "</li>";
                                ++$count;
                            }
                        }
                        if (count($post->getCommentairesSorted()) > 0)
                            echo "<br>";
                        if ($count > $max)
                            break;
                    }
                    echo "</ul>";
                    if ($count > $max)
                        echo "<a class=\"btn btn-primary\" href=\"comments.php?count=" . $count . "\">Suite des commentaires</a>";
                    echo ($count - $begin) . " commentaire(s) sont actuellement affichés";
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