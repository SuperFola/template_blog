<?php
    session_start();
?>

<!DOCTYPE html>

<HTML>
    <?php include('head.php'); ?>
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