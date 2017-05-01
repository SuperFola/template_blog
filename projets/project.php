<?php
    session_start();
?>

<!DOCTYPE html>

<HTML>
    <?php include('head.php'); ?>
    <link rel="stylesheet" href="../css/font-awesome/css/font-awesome.min.css">
    <body>
        <?php include('header.php'); ?>
        <?php
            $projectManager = new ProjectManager();
            $um = new UserManager();
            $Parsedown = new Parsedown();
            if (!isset($_GET['id'])) { header('Location: index.php'); }
            try {
                $max = 1;
                foreach ($projectManager->findAll() as $proj) {
                    if ($proj->getId() > $max)
                        $max = $proj->getId();
                }
                
                if (intval($_GET['id']) >= 1 && intval($_GET['id']) <= $max)
                    $project = $projectManager->findProject(intval($_GET['id']));
                else
                    header('Location: index.php');
            } catch (Exception $e) {
                header('Location: index.php');
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
                    $project->addCommentaire($commentaire);
                    $projectManager->updateProject($project);
                }
                header("Location: project.php?id=" . $_GET['id'] . "#comments");
            }
        ?>
        <div class="container">
            <br />
            <div class="breadcrumb-container">
                <ol class="breadcrumb">
                    <li><a href="../index.php">Accueil</a></li>
                    <li><a href="index.php">Liste des projets</a></li>
                    <li><a class="nolink"><?php echo $project->getTitre(); ?></a></li>
                </ol>
            </div>
            <hr>
            <div class="post">
                <div class="post-header">
                    <h1 style="display: inline;"><?php echo $project->getTitre(); ?></h1>
                    <h4 style="display: inline;"><span class="label label-default"><?php echo $project->getCategorie() ?></span></h4>
                    <?php if (isset($_SESSION['pseudo']) && $um->findUserByPseudo($_SESSION['pseudo'])->getRole() != 'MEMBRE') { ?>
                    <div style="display: inline; float: right">
                        <?php if (in_array($_SESSION['pseudo'], $projectManager->findProject(intval($_GET['id']))->getMembers()) || in_array($_SESSION['role'], array('ADMINISTRATEUR', 'MODERATEUR'))) { ?>
                        <a href="manage.php?id=<?php echo intval($_GET['id']); ?>" class="btn btn-warning">Modifier le projet</a>&nbsp;&nbsp;
                        <?php } ?>
                        <a href="add.php" class="btn btn-primary">Ajouter un projet</a>&nbsp;&nbsp;
                        <a href="manage.php" class="btn btn-primary">Gérer mes projets</a>
                    </div>
                    <h4><?php echo $project->getDisplayableDate(); ?> par <?php if (count($project->getMembers()) > 1) {echo implode(", ", $project->getMembers());} else {echo $project->getMembers()[0];} ?></h4>
                    <?php echo $project->getUpVote(); ?>&nbsp;<i class="fa fa-thumbs-up" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;
                    <?php echo $project->getDownVote(); ?>&nbsp;<i class="fa fa-thumbs-down" aria-hidden="true"></i>
                    <?php } ?>
                </div>
                <div class="post-content">
                    <?php echo $Parsedown->text($project->getPresentation()); ?>
                </div>
            </div>
            <br><hr><br>
            <div class="posts">
                <h1>Articles dans le projet : <?php echo $project->getTitre(); ?></h1>
                <ul class="posts-list">
                    <?php
                    $articles = $project->getArticlesSorted();
                    if (count($articles) == 0)
                        echo "Pas d'article disponnible sur ce projet.";
                    foreach($articles as $article) { ?>
                    <li>
                        <div>
                            <div class="posts-list-item-header">
                                <h3><a href="article.php?project=<?php echo $_GET['id']; ?>&id=<?php echo $article->getId(); ?>"><?php echo $article->getTitre(); ?></a> par <?php echo $article->getAuthor(); ?></h3>
                            </div>
                            <div class="content-preview">
                                <?php echo $Parsedown->text($article->getContent()); ?>
                            </div>
                            <hr />
                        </div>
                    </li>
                    <?php } ?>
                </ul>
            </div>
            <br><br><br>
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
                        <?php if (count($project->getCommentaires()) < 1): ?>
                                <h3 style="text-align: center">Aucun commentaire</h3>
                        <?php else: ?>
                                <h3 id="comments"><?php echo count($project->getCommentaires()) ?> Commentaire<?php if (count($project->getCommentaires()) > 1): ?>s<?php endif ?> : </h3>
                        <?php endif ?>
                        <?php
                        foreach($project->getCommentairesSorted() as $commentaire) {
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