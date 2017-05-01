<?php
    session_start();
?>

<!DOCTYPE html>

<HTML>
    <?php include('head.php'); ?>
    <link rel="stylesheet" href="../css/font-awesome/css/font-awesome.min.css">
    <body>
        <?php
            $projectManager = new ProjectManager();
            $um = new UserManager();
            $Parsedown = new Parsedown();
            if (!isset($_GET['project']) || !isset($_GET['id'])) { header('Location: index.php'); }
            try {
                $max_proj = 0;
                foreach ($projectManager->findAll() as $p) {
                    if ($p->getId() > $max_proj)
                        $max_proj = $p->getId();
                }
                if (intval($_GET['project']) >= 1 && intval($_GET['project']) <= $max_proj)
                    $project = $projectManager->findProject(intval($_GET['project']));
                else
                    header('Location: project.php?id=' . $_GET['project']);
                $max = 0;
                foreach ($project->getArticlesSorted() as $a) {
                    if ($a->getId() > $max)
                        $max = $a->getId();
                }
                if (intval($_GET['id']) >= 0 && intval($_GET['id']) <= $max)
                    $article = $project->findArticle(intval($_GET['id']));
                else
                    header('Location: project.php?id=' . $_GET['project']);
            } catch (Exception $e) {
                header('Location: project.php?id=' . $_GET['project']);
            }
            
            if (isset($_POST['cmd']) and $_POST['cmd'] == 'post_comment_add') {
                $commentaire = new Commentaire();
                $pseudo = "*(nullptr)";
                if (!isset($_SESSION) or !isset($_SESSION['pseudo'])) {
                    http_response_code(404);
                    exit();
                }
                else
                    $pseudo = $_SESSION['pseudo'];
                $message = htmlentities($_POST['post_comment_message']);
                $commentaire->handlePostRequest($pseudo, $message);
                $validation = $commentaire->validate();
                if ($validation['valid']) {
                    $article->addCommentaire($commentaire);
                    $project->updateArticle($article);
                    $projectManager->updateProject($project);
                }
                header("Location: article.php?id=" . $_GET['id'] . "&project=" . $_GET['project'] . "#comments");
            }
        ?>
        <?php include('header.php'); ?>
        <div class="container">
            <br />
            <div class="breadcrumb-container">
                <ol class="breadcrumb">
                    <li><a href="../index.php">Accueil</a></li>
                    <li><a href="index.php">Liste des projets</a></li>
                    <li><a href="project.php?id=<?php echo $project->getId(); ?>">Présentation de : <?php echo $project->getTitre(); ?></a></li>
                    <li><a class="nolink"><?php echo $article->getTitre(); ?></a></li>
                </ol>
            </div>
            <hr>
            <div class="post">
                <div class="post-header">
                    <h1 style="display: inline;"><?php echo $article->getTitre(); ?></h1>&nbsp;&nbsp;
                    <?php if (isset($_SESSION['pseudo']) && $um->findUserByPseudo($_SESSION['pseudo'])->getRole() != 'MEMBRE') { ?>
                    <div style="display: inline; float: right">
                        <?php if (in_array($_SESSION['pseudo'], $projectManager->findProject(intval($_GET['project']))->getMembers()) || in_array($_SESSION['role'], array('ADMINISTRATEUR', 'MODERATEUR'))) { ?>
                        <a href="manage.php?id=<?php echo intval($_GET['project']); ?>" class="btn btn-warning">Modifier le projet</a>&nbsp;&nbsp;
                        <?php } ?>
                        <a href="add.php" class="btn btn-primary">Ajouter un projet</a>&nbsp;&nbsp;
                        <a href="manage.php" class="btn btn-primary">Gérer mes projets</a>
                    </div>
                    <?php } ?>
                    <h4><?php echo $article->getDisplayableDate(); ?> par <?php echo $article->getAuthor(); ?></h4>
                </div>
                <div class="post-content">
                    <?php echo $Parsedown->text($article->getContent()); ?>
                </div>
            </div>
            <br><hr><br>
            <div>
                <div class="row">
                    <div class="commentaire-form-container well col-md-8 col-md-offset-2">
                        <?php
                            $blocked_mgr = new BlockedUsersManager();
                            if (! $blocked_mgr->isBlocked($_SERVER['REMOTE_ADDR'])) {
                        ?>
                        <form class="commentaire-form form-horizontal" method="post">
                            <div class="container-fluid">
                                <?php if (!isset($_SESSION) or !isset($_SESSION['pseudo'])) { ?>
                                <h4>Donnez votre opinion !</h4>
                                Ah mince, vous devez être connecté pour continuer :( <br />
                                    <a onclick="load_modal('signup_mod', '../');" class="btn btn-default" style="margin-top: 10px;">Inscription</a>&nbsp;
                                    <a onclick="load_modal('login_mod', '../');" class="btn btn-default" style="margin-top: 10px;">Connexion</a>
                                <?php } else {
                                    echo '<h4 style="display: inline;">Donnez votre opinion !</h4>';
                                    echo '<div class="avatar" style="display: inline; float: right; line-height: 20px;">';
                                    echo '<b>' . $_SESSION['pseudo'] . '</b>';
                                    echo '<img src="http://identicon.org?t=' . $_SESSION['pseudo'] . '&s=50" class="img-responsive">';
                                    echo '<br />';
                                    echo '</div>';
                                ?>
                                <div class="form-group">
                                    <textarea class="form-control" row="5" placeholder="Votre message..." name="post_comment_message"></textarea>
                                </div>
                                <div class="form-footer">
                                    <input type="hidden" name="cmd" value="post_comment_add" />
                                    <input type="submit" class="btn btn-primary" value="Poster" />
                                </div>
                                <?php } ?>
                            </div>
                        </form>
                        <?php } else {echo 'Votre adresse IP a été bloquée. Veuillez nous envoyer un mail si vous pensez que c\'est une erreur';} ?>
                    </div>
                </div>
                <div class="row">
                    <div class="commentaires-container col-md-10 col-md-offset-1">
                        <?php if (count($article->getCommentaires()) < 1): ?>
                                <h3 style="text-align: center">Aucun commentaire</h3>
                        <?php else: ?>
                                <h3 id="comments"><?php echo count($article->getCommentaires()) ?> Commentaire<?php if (count($article->getCommentaires()) > 1): ?>s<?php endif ?> : </h3>
                        <?php endif ?>
                        <?php
                        foreach($article->getCommentairesSorted() as $commentaire) {
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
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php
                include('footer.php');
            ?>
        </div>
    </body>
</HTML>