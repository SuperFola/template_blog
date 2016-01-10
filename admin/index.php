<?php
    session_start();
?>

<!DOCTYPE html>

<HTML>
    <head>
        <title>titre</title>
        <meta charset="utf-8" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="Content-Language" content="fr-FR" />
        <meta name="robots" content="all" />
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no" />
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <!-- Website Style -->
        <link rel="stylesheet" href="../css/style.css">
        <script src="../scripts/spoiler.js"></script>
        <?php
            include(__DIR__ . '/../private/postmanager.php');
            include(__DIR__ . '/../private/usermanager.php');
        ?>
    </head>
    <body>
        <?php
            include(__DIR__ . '/../header.php');
        ?>
        <div class="container">
            <?php
                if (!isset($_SESSION['pseudo']) and (!isset($_SESSION['role']) or $_SESSION['role'] != 'ADMINISTRATEUR')){
                    include('connect.php');
                }else if (true and $_SESSION['role'] == 'ADMINISTRATEUR'){
                    // connexion réussie
                    // pas de session créée ici pour le moment
                    echo "Interface d'administration, bonjour {$_SESSION['pseudo']}";
                    
                    echo "<div class=\"breadcrumb-container\">
                            <ol class=\"breadcrumb\">
                                <li><a href=\"index.php\">Accueil</a></li>
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