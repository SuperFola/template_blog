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
                    $fpp_mgr = new FirstPagePostsManager();
                    $Parsedown = new Parsedown();
                    
                    if (isset($_GET['action'])) {
                        if ($_GET['action'] == 'activated') {
                            echo "<div class=\"alert alert-success alert-dismissible\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button><strong>Inscription réussie !</strong> Bienvenue sur WeAreCoders, " . $_SESSION['pseudo'] . "</div>";
                        }
                        if ($_GET['action'] == 'failed_activation') {
                            echo "<div class=\"alert alert-warning alert-dismissible\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button><strong>Erreur !</strong> La clé d'activation du compte ne correspond à aucun compte connu.</div>";
                        }
                    }
                ?>
                <h1>A la une</h1>

                <div class="top-posts-container container-fluid">
                    <?php
                        if ($fpp_mgr->getSize() == 0) {
                            echo "<center><h4>Aucun post n'est actuellement mis en avant</h4></center>";
                        } else {
                            echo "<ul class='top-posts'>";
                            for ($i=0; $i < $fpp_mgr->getSize(); $i++) {
                                $post_details = $fpp_mgr->getPost($i);
                                if ($post_details) {
                                    echo "<li class='col-md-3'>";
                                    echo "<div style=\"background-image: url('" . $post_details['image'] . "'); background-size: 100%;\">";
                                    echo "<a href='post.php?id=" . $post_details['id'] . "' style='color: black;'><h3>" . $postManager->findPost($post_details['id'])->getTitre() . "</h3></a>";
                                    echo "</div>";
                                    echo "</li>";
                                }
                            }
                            echo "</ul>";
                        }
                    ?>
                </div>

                <hr />

                <h1>Articles</h1>
                <div class="posts-list-container container-fluid">
                    <ul class="posts-list">
                        <?php
                            $posts = $postManager->findAll();
                            if (isset($_GET['page']) && intval($_GET['page']) * 12 <= count($posts)) {
                                $page = intval($_GET['page']);
                            } else {
                                $page = intval(count($posts) / 12);
                            }
                            
                            foreach($posts as $post) {
                                if ($post->getId() - $page * 12 >= 0 && $post->getId() < ($page + 1) * 12) {
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
                            <?php
                            }}
                        ?>
                    </ul>
                    <?php
                        if ($page - 1 >= 0) {
                            echo "<a href='index.php?page=" . intval($page - 1) . "'>News précédentes</a>&nbsp;&nbsp;";
                        }
                        if (($page + 1) * 12 <= count($posts)) {
                            echo "<a href='index.php?page=" . intval($page + 1) . "'>News suivantes</a>";
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