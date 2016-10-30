<?php
    session_start();
?>

<!DOCTYPE html>

<HTML>
    <?php include('head.php'); ?>
    <?php
        $projectManager = new ProjectManager();
        $um = new UserManager();
        $Parsedown = new Parsedown();
        
        if (!isset($_SESSION['pseudo']) || $um->findUserByPseudo($_SESSION['pseudo'])->getRole() == 'MEMBRE') {
            header('Location: index.php');
        }
        if (isset($_GET['project']) && intval($_GET['project']) >= 1 && intval($_GET['project']) <= count($projectManager->findAll()))
            $project = $projectManager->findProject(intval($_GET['project']));
        else
            header('Location: ../error.php?error=404');
        
        $validation = array(
            'valid' => false,
            'errors' => array()
        );
        if (isset($_GET['project']))
            $article = new Article();
        if (isset($_GET['action']) and isset($_GET['project']) and isset($_GET['id'])) {
            if ($_GET['action'] == 'post_edit' && in_array($_SESSION['pseudo'], $project->getMembers())) {
                $article = $project->findArticle(intval($_GET['id']));
            } else {
                header('Location: index.php');
            }
        }

        if (isset($_POST['cmd']) and isset($_SESSION) and in_array($_SESSION['role'], array('MODERATEUR', 'ADMINISTRATEUR', 'AUTEUR')) and isset($_POST['post_titre']) and isset($_POST['post_content'])) {
            if ($_POST['cmd'] == 'post_add') {
                $article = new Article();
                echo $project->getId();
                $article->handlePostRequest($_POST['post_titre'], $_POST['post_content'], $_SESSION['pseudo'], $project->getId());
                $validation = $article->validate();
                if ($validation['valid']) {
                    $project->addArticle($article);
                    $projectManager->updateProject($project);
                }
            } else if($_POST['cmd'] == 'post_edit' and isset($_POST['post'])) {
                $article->setTitre($_POST['post_titre']);
                $article->setTimestampEdition(time());
                $article->setContent($_POST['post_content']);
                
                $validation = $article->validate();
                if ($validation['valid']) {
                    $project->updateArticle($article);
                    $projectManager->updateProject($project);
                }
            }
            header('Location: index.php');
        }
    ?>
    <link rel="stylesheet" href="../css/font-awesome/css/font-awesome.min.css">
    <body>
        <?php include('header.php'); ?>
        <script src="../scripts/wysiwyg.js"></script>
        <div class="container">
            <div class="posts">
                <h1 style="display: inline;">Ajout d'un article sur le projet : <?php echo $project->getTitre(); ?></h1>
                <div style="display: inline; float: right">
                </div>
                <br />
                <div class="writing-form-container">
                    <form method="post" class="form-horizontal">
                        <div class="well col-md-12 col-lg-10 col-lg-offset-1">
                            <div class="container-fluid">
                                <div class="form-group">
                                    <i>Ne vous préocupez pas d'ajouter la date à l'article, cela est fait automatiquement :)</i><br />
                                    <i>La syntaxe Markdown est supportée pour la rédaction</i><br />
                                    <br />
                                    <div class="form-group<?php if(array_key_exists('post_titre', $validation['errors'])): ?> has-error<?php endif; ?>">
                                        <label for="post_titre" class="col-sm-2 control-label">Titre</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="post_titre" name="post_titre" placeholder="Titre" value="<?php echo $article->getTitre(); ?>">
                                            <?php if(array_key_exists('post_titre', $validation['errors'])): ?>
                                                <span class="help-block"><?php echo $validation['errors']['post_titre']; ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <br />
                                    <div id="completeEditeur">
                                        <a href="../private/cible_envoi.php" target="blank" class="btn btn-default">Héberger une image</a>
                                        <br /><br />
                                        <div class="<?php if(array_key_exists('post_content', $validation['errors'])): ?>has-error<?php endif; ?>">
                                            <textarea class="form-control" rows="24" id="editeur" name="post_content"><?php echo $article->getContent() ?></textarea>
                                            <?php if(array_key_exists('post_content', $validation['errors'])): ?>
                                                <span class="help-block"><?php echo $validation['errors']['post_content']; ?></span>
                                            <?php endif; ?>
                                            <br />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-footer col-md-8 col-md-offset-2">
                            <a href="../index.php" class="btn btn-default">Retour</a>
                            <?php if (!isset($_GET['action']) or (isset($_GET['action']) and $_GET['action'] != 'post_edit')): ?>
                            <input type="hidden" name="cmd" value="post_add" />
                            <?php else: ?>
                            <input type="hidden" name="cmd" value="post_edit" />
                            <input type="hidden" name="post" value="<?php echo $article->getId() ?>" />
                            <?php endif; ?>
                            <input type="submit" class="btn btn-primary" value="Ajouter" onclick="document.getElementById('hidden_content').value = document.getElementById('editeur').innerHTML;" />
                        </div>
                    </form>
                </div>
            </div>
            <?php
                include('../footer.php');
            ?>
        </div>
    </body>
</HTML>