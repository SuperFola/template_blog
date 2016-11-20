<?php
    session_start();
?>

<!DOCTYPE html>

<HTML>
    <?php include('head.php'); ?>
    <body>
        <?php include('header.php'); ?>
        <div class="container">
            <div class="posts">
                <h1 style="display: inline;">Nos partenariats</h1>
                <div style="display: inline; float: right">
                    <?php if(isset($_SESSION) && $_SESSION['role'] == 'ADMINISTRATEUR') { ?>
                    <a class="btn btn-primary" href="partenariats.php?action=edit">Editer</a>
                    <?php } ?>
                </div>
                <br />
                <?php
                    if (isset($_GET['action']) && isset($_SESSION) && $_SESSION['role'] == 'ADMINISTRATEUR') {
                        if ($_GET['action'] == 'edit') {
                        ?>
                        <br />
                        <form method="post" action="partenariats.php?action=update">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon" id="labnom">Nom du partenaire</span>
                                    <input type="text" class="form-control" placeholder="Partenaire" name="nom" aria-describedby="labnom">
                                </div>
                                <br />
                                <div class="input-group">
                                    <span class="input-group-addon" id="labdesc">Description</span>
                                    <input type="text" class="form-control" placeholder="Particulier" name="desc" aria-describedby="labdesc">
                                </div>
                                <br />
                                <div class="input-group">
                                    <span class="input-group-addon" id="lablogo">URL du logo</span>
                                    <input type="text" class="form-control" placeholder="http://url.domain/image.png" name="image" aria-describedby="lablogo">
                                </div>
                                <br />
                                <div class="input-group">
                                    <span class="input-group-addon" id="laburl">URL du partenaire</span>
                                    <input type="text" class="form-control" placeholder="http://url.domain" name="url" aria-describedby="laburl">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Modifier</button>
                        </form>
                        <br />
                        <?php
                        } else if ($_GET['action'] == 'update') {
                            if (isset($_POST['nom']) && isset($_POST['desc']) && isset($_POST['image']) && isset($_POST['url'])) {
                                $content = array(
                                    $_POST['nom'] => array(
                                        "desc" => $_POST['desc'],
                                        "url" => $_POST['url'],
                                        "image" => $_POST['image']
                                    )
                                );
                                if (isset($cm->getConfig['partenaires'])) {
                                    $cm->setConfigKey("partenaires", array_merge($cm->getConfig()["partenaires"], $content));
                                } else {
                                    $cm->setConfigKey("partenaires", $content);
                                }
                                $cm->persistConfig();
                                echo '<div class="alert alert-success" role="alert">Partenaire ajouté avec succès !</div>';
                            } else {
                                echo "Impossible de procéder à la requête";
                            }
                        }
                    }
                ?>
                <div class="posts-list-container container-fluid">
                    <?php
                        if (!isset($cm->getConfig()["partenaires"]))
                            echo "Actuellement vide :'(";
                        else {
                            foreach ($cm->getConfig()["partenaires"] as $partenaire_name => $part_content) {
                                echo "<img src='{$part_content['image']}' style='display: inline' width='64px' /> <a href='{$part_content['url']}'>{$partenaire_name}</a> {$part_content['desc']}";
                            }
                        }
                    ?>
                </div>
            </div>
            <?php
                include('footer.php');
            ?>
        </div>
    </body>
</HTML>