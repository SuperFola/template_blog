<?php
    session_start();
?>

<!DOCTYPE html>

<HTML>
    <head>
        <?php
            include('private/configmanager.php');
            $cm = new ConfigManager();
            $title = $cm->getBlogTitle();
            $slogan = $cm->getBlogSlogan();
        ?>
        <title><?php echo $title . ' - ' . $slogan; ?></title>
        <meta charset="utf-8" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="Content-Language" content="fr-FR" />
        <meta name="robots" content="all" />
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no" />
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <!-- Website Style -->
        <link rel="stylesheet" href="css/style.css">
        <?php
            include('private/postmanager.php');
        ?>
    </head>
    <body>
        <?php
            include('header.php');
        ?>
        <div class="container">
            <div class="posts">
                <h1>Derniers articles</h1>

                <div class="top-posts-container container-fluid">
                    <ul class="top-posts">
                        <li class="col-md-3">
                            <div>
                                <h3>Titre</h3>
                            </div>
                        </li>
                        <li class="col-md-3">
                            <div>
                                <h3>Titre</h3>
                            </div>
                        </li>
                        <li class="col-md-3">
                            <div>
                                <h3>Titre</h3>
                            </div>
                        </li>
                        <li class="col-md-3">
                            <div>
                                <h3>Titre</h3>
                            </div>
                        </li>
                    </ul>
                </div>

                <hr />

                <div class="posts-list-container container-fluid">
                    <ul class="posts-list">
                        <?php
                            $postManager = new PostManager();
                            $posts = $postManager->findAll();

                            foreach($posts as $post) {
                                ?>
                                <li>
                                    <div>
                                        <div class="posts-list-item-header">
                                            <h3><a href="post.php?id=<?php echo $post->getId() ?>"><?php echo $post->getTitre() ?></a></h3>
                                            <h4><span class="label label-default"><?php echo $post->getCategorie() ?></span></h4>
                                        </div>
                                        <p class="content-preview">
                                            <?php echo nl2br($post->getContentPreview()); ?>
                                        </p>
                                    </div>
                                </li>
                            <?php
                            }
                        ?>
                    </ul>
                </div>
            </div>
            <?php
                include('footer.php');
            ?>
        </div>
    </body>
</HTML>