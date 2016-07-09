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
                    
                    if (isset($_GET['action']) && isset($_GET['id'])) {
                        $temp = $projectManager->findProject(intval($_GET['id']));
                        if ($_GET['action'] == 'up') {
                            $temp->upVote();
                        } else if ($_GET['action'] == 'down') {
                            $temp->downVote();
                        }
                        $projectManager->updateProject($temp);
                    }
                ?>

                <h1 style="display: inline;">Nos projets</h1>
                <?php if (isset($_SESSION['pseudo']) && $um->findUserByPseudo($_SESSION['pseudo'])->getRole() != 'MEMBRE') { ?>
                <div style="display: inline; float: right">
                    <a href="add.php" class="btn btn-primary">Ajouter un projet</a>&nbsp;&nbsp;
                    <a href="manage.php" class="btn btn-primary">Gérer mes projets</a>
                </div>
                <?php } ?>
                <br />
                <div class="posts-list-container container-fluid">
                    <ul class="posts-list">
                        <?php
                            $projects = $projectManager->findAll();
                            if (isset($_GET['page']) && intval($_GET['page']) * 12 <= count($projects)) {
                                $page = intval($_GET['page']);
                            } else {
                                $page = intval(count($projects) / 12);
                            }
                            
                            if (count($projects) == 0)
                                echo "Pas de projets pour le moment ...";
                            
                            foreach($projects as $project) {
                                if ($project->getId() - $page * 12 >= 0 && $project->getId() < ($page + 1) * 12) {
                                ?>
                                <li>
                                    <div>
                                        <div class="posts-list-item-header" id="<?php echo "id-plih-" . $project->getId(); ?>">
                                            <h3><a href="project.php?id=<?php echo $project->getId() ?>"><?php echo $project->getTitre() ?></a></h3>
                                            <h4><span class="label label-default"><?php echo $project->getCategorie() ?></span></h4>
                                            <?php echo $project->getUpVote(); ?>&nbsp;<a class="fa fa-thumbs-up nolink" href="index.php?action=up&id=<?php echo $project->getId(); ?>#<?php echo "id-plih-" . $project->getId(); ?>" aria-hidden="true"></a>&nbsp;&nbsp;&nbsp;
                                            <?php echo $project->getDownVote(); ?>&nbsp;<a class="fa fa-thumbs-down nolink" href="index.php?action=down&id=<?php echo $project->getId(); ?>#<?php echo "id-plih-" . $project->getId(); ?>" aria-hidden="true"></a>
                                        </div>
                                        <div class="content-preview">
                                            <?php echo $Parsedown->text($project->getPresentation()); ?>
                                        </div>
                                        <hr />
                                    </div>
                                </li>
                            <?php
                            }}
                        ?>
                    </ul>
                    <?php
                        if ($page - 1 >= 0) {
                            echo "<a href='index.php?page=" . intval($page - 1) . "'>Projets précédents</a>&nbsp;&nbsp;";
                        }
                        if (($page + 1) * 12 <= count($projects)) {
                            echo "<a href='index.php?page=" . intval($page + 1) . "'>Projets suivants</a>";
                        }
                    ?>
                </div>
            </div>
            <?php
                include('../footer.php');
            ?>
        </div>
    </body>
</HTML>