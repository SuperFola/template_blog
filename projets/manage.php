<?php
    session_start();
?>

<!DOCTYPE html>

<HTML>
    <?php include('head.php'); ?>
    <link rel="stylesheet" href="../css/font-awesome/css/font-awesome.min.css">
    <body>
        <?php include('header.php'); ?>
        <div class="container">
            <div class="posts">
                <?php
                    $projectManager = new ProjectManager();
                    $um = new UserManager();
                    $Parsedown = new Parsedown();
                    if (!isset($_SESSION['pseudo']) || $um->findUserByPseudo($_SESSION['pseudo'])->getRole() == 'MEMBRE') {
                        header('Location: index.php');
                    }
                ?>

                <h1 style="display: inline;">Gestion des projets</h1>
                <div style="display: inline; float: right">
                    <?php if(isset($_GET['id'])) { ?>
                    <a class="btn btn-warning" href="add.php?action=post_edit&project=<?php echo $_GET['id']; ?>">Editer la présentation</a>&nbsp;&nbsp;
                    <a class="btn btn-primary" href="add_article.php?project=<?php echo $_GET['id']; ?>">Ajouter un article</a>&nbsp;&nbsp;
                    <?php } ?>
                    <a href="add.php" class="btn btn-primary">Ajouter un projet</a>
                </div>
                <br />
                <?php if(!isset($_GET['id'])) { ?>
                <div class="posts-list-container container-fluid">
                    <ul class="posts-list">
                    <?php
                        $projects = $projectManager->findAll();
                        $my_projects = array();
                        foreach($projects as $project) {
                            if (in_array($_SESSION['pseudo'], $project->getMembers())) {
                                $my_projects[] = $project;
                            }
                        }
                        
                        if (count($my_projects) == 0)
                            echo "Pas de projets pour le moment ...<br>Voulez-vous peut-être en ajouter un ?";
                        
                        foreach($my_projects as $project) { ?>
                        <li>
                            <div>
                                <div class="posts-list-item-header">
                                    <h3><a href="manage.php?id=<?php echo $project->getId() ?>"><?php echo $project->getTitre() ?></a></h3>
                                    <h4><span class="label label-default"><?php echo $project->getCategorie() ?></span></h4>
                                </div>
                                <div class="content-preview">
                                    <?php echo $Parsedown->text($project->getPresentation()); ?>
                                </div>
                                <hr />
                            </div>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
                <?php } else {
                    $project = $projectManager->findProject(intval($_GET['id']));
                ?>
                <br><br>
                <div class="posts-list-item-header" id="<?php echo "id-plih-" . $project->getId(); ?>">
                    <h1 style="display: inline;"><?php echo $project->getTitre(); ?></h1>&nbsp;&nbsp;
                    <h4 style="display: inline;"><span class="label label-default"><?php echo $project->getCategorie() ?></span></h4>
                    <h4><?php echo $project->getDisplayableDate(); ?> par <?php echo implode(", ", $project->getMembers()); ?></h4>
                    <?php echo $project->getUpVote(); ?>&nbsp;<i class="fa fa-thumbs-up" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;
                    <?php echo $project->getDownVote(); ?>&nbsp;<i class="fa fa-thumbs-down" aria-hidden="true"></i>
                </div>
                <?php } ?>
            </div>
            <?php
                include('../footer.php');
            ?>
        </div>
    </body>
</HTML>