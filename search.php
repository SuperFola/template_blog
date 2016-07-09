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
                <?php
                    $postManager = new PostManager();
                    $Parsedown = new Parsedown();
                ?>
                <div class="post-header">
                    <h1>Recherche</h1>
                    <h4><?php echo $_GET["search"]; ?></h4>
                    <hr />
                </div>
                
                <?php
                    $posts = $postManager->findAll();
                    $matching_post = array();
                    foreach($posts as $post) {
                        if (preg_match("#".$_GET["search"]."#", $post->getContent()) or preg_match("#".$_GET["search"]."#", $post->getTitre()) or preg_match("#".$_GET["search"]."#", $post->getAuthor())) {
                            $matching_post[] = $post;
                        }
                    }
                ?>
                
                <h1>Articles</h1>
                <div class="posts-list-container container-fluid">
                    <ul class="posts-list">
                    <?php
                        if (count($matching_post) == 0)
                            echo "La recherche a été infructeuse ... :(";
                        foreach($matching_post as $post) {
                        ?>
                        <li>
                            <div>
                                <div class="posts-list-item-header">
                                    <h3><a href="post.php?id=<?php echo $post->getId() ?>"><?php echo $post->getTitre() ?></a></h3>
                                    <h4><span class="label label-default"><?php echo $post->getCategorie() ?></span></h4>
                                </div>
                                <div class="content-preview">
                                    <?php echo $Parsedown->text($post->getContent()); ?>
                                </div>
                                <hr />
                            </div>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <?php
                include('footer.php');
            ?>
        </div>
    </body>
</HTML>