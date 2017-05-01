<?php
    session_start();
?>

<!DOCTYPE html>

<HTML>
    <?php include('head.php'); ?>
    <body>
        <?php
            $projectManager = new ProjectManager();
            $validation = array(
                'valid' => true,
                'errors' => array()
            );
            if (isset($_GET['action']) and isset($_GET['project'])) {
                if ($_GET['action'] == 'post_edit' && in_array($_SESSION['pseudo'], $projectManager->findProject(intval($_GET['project']))->getMembers())) {
                    $project = $projectManager->findProject(intval($_GET['project']));
                } else {
                    $project = new Project();
                }
            } else {
                $project = new Project();
            }

            if (isset($_POST['cmd']) and isset($_SESSION) and in_array($_SESSION['role'], array('MODERATEUR', 'ADMINISTRATEUR', 'AUTEUR')) and isset($_POST['post_titre']) and isset($_POST['post_categorie']) and isset($_POST['post_content'])) {
                if ($_POST['cmd'] == 'post_add') {
                    $project = new Project();
                    if (strlen($_POST['post_members']) != 0)
                        $members = explode(",", $_POST['post_members']);
                    else
                        $members = array();
                    $members[] = $_SESSION['pseudo'];
                    $project->handlePostRequest($_POST['post_titre'], $_POST['post_categorie'], $_POST['post_content'], $members);
                    $validation = $project->validate();
                    if ($validation['valid']) {
                        $projectManager->persistProject($project);
                    }
                } else if($_POST['cmd'] == 'post_edit' and isset($_POST['post'])) {
                    $project->setTitre($_POST['post_titre']);
                    $project->setCategorie($_POST['post_categorie']);
                    $project->setTimestampEdition(time());
                    $project->setPresentation($_POST['post_content']);
                    if ($validation['valid']) {
                        $projectManager->updateProject($project);
                    }
                }
                header('Location: index.php');
            }
        ?>
        <?php include('header.php'); ?>
        <script src="../scripts/wysiwyg.js"></script>
        <div class="container">
            <div class="posts">
                <?php
                    $projectManager = new ProjectManager();
                    $um = new UserManager();
                    $Parsedown = new Parsedown();
                ?>

                <h1 style="display: inline;">Ajouter un projet</h1>
                <?php if (isset($_SESSION['pseudo']) && $um->findUserByPseudo($_SESSION['pseudo'])->getRole() != 'MEMBRE') { ?>
                <div style="display: inline; float: right">
                    <a href="add.php" class="btn btn-primary">Ajouter un projet</a>&nbsp;&nbsp;
                    <a href="manage.php" class="btn btn-primary">Gérer mes projets</a>
                </div>
                <br />
                <div class="writing-form-container">
                    <form method="post" class="form-horizontal">
                        <div class="well col-md-12 col-lg-10 col-lg-offset-1">
                            <div class="container-fluid">
                                <div class="form-group">
                                    <i>Ne vous préocupez pas d'ajouter la date au projet, cela est fait automatiquement :)</i><br />
                                    <i>La syntaxe Markdown est supportée pour la rédaction</i><br />
                                    <br />
                                    <div class="form-group<?php if(array_key_exists('post_titre', $validation['errors'])): ?> has-error<?php endif; ?>">
                                        <label for="post_titre" class="col-sm-2 control-label">Titre</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="post_titre" name="post_titre" placeholder="Titre" value="<?php echo $project->getTitre(); ?>">
                                            <?php if(array_key_exists('post_titre', $validation['errors'])): ?>
                                                <span class="help-block"><?php echo $validation['errors']['post_titre']; ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="form-group<?php if(array_key_exists('post_categorie', $validation['errors'])): ?> has-error<?php endif; ?>">
                                        <label for="post_categorie" class="col-sm-2 control-label">Catégorie(s)</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="post_categorie" name="post_categorie" placeholder="Catégorie(s)" value="<?php echo $project->getCategorie(); ?>">
                                            <?php if(array_key_exists('post_categorie', $validation['errors'])): ?>
                                                <span class="help-block"><?php echo $validation['errors']['post_categorie']; ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="form-group<?php if(array_key_exists('post_members', $validation['errors'])): ?> has-error<?php endif; ?>">
                                        <label for="post_members" class="col-sm-2 control-label">Ajouter des membres</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="post_members" name="post_members" placeholder="Membre(s)" value="<?php echo implode(",", $project->getMembers()); ?>">
                                            <?php if(array_key_exists('post_members', $validation['errors'])): ?>
                                                <span class="help-block"><?php echo $validation['errors']['post_members']; ?></span>
                                            <?php endif; ?>
                                            <i>Séparez les pseudos par des virgules, sans espace supplémentaire</i>
                                        </div>
                                    </div>
                                    <br />
                                    <div id="completeEditeur">
                                        <a href="../private/cible_envoi.php" target="blank" class="btn btn-default">Héberger une image</a>
                                        <br /><br />
                                        <div class="<?php if(array_key_exists('post_content', $validation['errors'])): ?>has-error<?php endif; ?>">
                                            <textarea class="form-control" rows="24" id="editeur" name="post_content"><?php echo $project->getPresentation() ?></textarea>
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
                            <input type="hidden" name="post" value="<?php echo $project->getId() ?>" />
                            <?php endif; ?>
                            <input type="submit" class="btn btn-primary" value="Ajouter" onclick="document.getElementById('hidden_content').value = document.getElementById('editeur').innerHTML;" />
                        </div>
                    </form>
                </div>
                <?php } else { header('Location: index.php'); } ?>
            </div>
            <?php
                include('footer.php');
            ?>
        </div>
    </body>
</HTML>