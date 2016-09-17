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
                    $projectManager = new ProjectManager();
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
                            $projects = $projectManager->findAll();
                            $articles = array();
                            foreach ($projects as $proj) {
                                $articles = $articles + $proj->getArticlesSorted();
                            }
                            $result = array();
                            foreach($projects as $p) {$result[$p->getTimestampCreation()] = $p;}
                            foreach($articles as $a) {if(array_key_exists($a->getTimestampCreation(), $result)) {$result[$a->getTimestampCreation() + 1] = $a;} else {$result[$a->getTimestampCreation()] = $a;}}
                            foreach($posts as $r) {if(array_key_exists($r->getTimestampCreation(), $result)) {$result[$r->getTimestampCreation() + 1] = $r;} else {$result[$r->getTimestampCreation()] = $r;}}
                            ksort($result);
                            $result = array_values(array_reverse($result));
                            
                            if (isset($_GET['page']) && intval($_GET['page']) * 12 <= count($result)) {
                                $page = intval($_GET['page']);
                            } else {
                                $page = 0;
                            }
                            
                            $i = 0;
                            foreach($result as $post) {
                                $i++;
                                if ($i - $page * 12 >= 0 && $i < ($page + 1) * 12) {
                                ?>
                                <li>
                                    <?php
                                    if (is_a($post, 'Article')) {
                                        echo '<div class="preview-article">';
                                    } else if (is_a($post, 'Project')) {
                                        echo '<div class="preview-project">';
                                    } else {
                                        echo '<div>';
                                    }
                                    ?>
                                        <div class="posts-list-item-header">
                                            <?php
                                            if (is_a($post, 'Post')) {
                                                echo '<h3><a href="post.php?id='.$post->getId().'">'.$post->getTitre().'</a></h3>';
                                            } else if (is_a($post, 'Article')) {
                                                echo '<h3><a href="projets/article.php?project='.$post->getDad().'&id='.$post->getId().'">'.$post->getTitre().'</a></h3>';
                                            } else if (is_a($post, 'Project')) {
                                                echo '<h3><a href="projets/project.php?id='.$post->getId().'">'.$post->getTitre().'</a></h3>';
                                            }
                                            ?>
                                            <h4><span class="label label-default"><?php if (is_a($post, 'Post') or is_a($post, 'Project')) {echo $post->getCategorie();} else if (is_a($post, 'Article')) {echo "Article";} ?></span> par <?php if (is_a($post, 'Post') or is_a($post, 'Article')) {echo $post->getAuthor();} else if (is_a($post, 'Project')) {echo implode(", ", $post->getMembers());} ?></h4>
                                        </div>
                                        <?php
                                            echo '<div class="content-preview">';
                                            if (is_a($post, 'Post')) {
                                                echo $Parsedown->text($post->getContent());
                                            } else if (is_a($post, 'Article')) {
                                                echo $Parsedown->text($post->getContent());
                                            } else if (is_a($post, 'Project')) {
                                                echo $Parsedown->text($post->getPresentation());
                                            }
                                            echo '</div>';
                                        ?>
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
                
                <hr />
                
                <div>
                    <a class="twitter-timeline" data-lang="fr" data-width="540" data-height="420" href="https://twitter.com/Hxokunlug">Tweets de @Hxokunlug</a> <script async src="scripts/widgets.js" charset="utf-8"></script>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <a class="twitter-timeline" data-lang="fr" data-width="540" data-height="420" href="https://twitter.com/the_new_sky">Tweets de @the_new_sky</a> <script async src="scripts/widgets.js" charset="utf-8"></script>
                </div>
            </div>
            <?php
                include('footer.php');
            ?>
        </div>
    </body>
</HTML>