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
                    $Parsedown = new Parsedown();
                ?>
                <div class="post-header">
                    <h1>Recherche</h1>
                    <h4><?php echo $_GET["search"]; ?></h4>
                    <hr />
                </div>
                
                <?php
                    $posts = $postManager->findAll();
                    $projects = $projectManager->findAll();
                    $articles = array();
                    foreach ($projects as $proj) { $articles = $articles + $proj->getArticlesSorted(); }
                    $result = array();
                    foreach($projects as $p) {$result[$p->getTimestampCreation()] = $p;}
                    foreach($articles as $a) {if(array_key_exists($a->getTimestampCreation(), $result)) {$result[$a->getTimestampCreation() + 1] = $a;} else {$result[$a->getTimestampCreation()] = $a;}}
                    foreach($posts as $r) {if(array_key_exists($r->getTimestampCreation(), $result)) {$result[$r->getTimestampCreation() + 1] = $r;} else {$result[$r->getTimestampCreation()] = $r;}}
                    ksort($result);
                    $result = array_values(array_reverse($result));

                    $matching_post = array();
                    foreach($result as $post) {
                        $content = "";
                        if (is_a($post, 'Article')) {
                            $content = $post->getContent();
                        } else if (is_a($post, 'Project')) {
                            $content = $post->getPresentation();
                        } else {
                            $content = $post->getContent();
                        }
                        if (preg_match("#".$_GET["search"]."#", $content) or preg_match("#".$_GET["search"]."#", $post->getTitre())) {
                            $matching_post[] = $post;
                        }
                        if (is_a($post, "Article") or is_a($post, "Post")) {
                            if (preg_match("#".$_GET["search"]."#", $post->getAuthor()))
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
                        $i = 0;
                        foreach($matching_post as $post) {
                            $i += 1;
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
                                    <h4>
                                        <span class="label label-default" id="c<?php echo $i; ?>" onclick="window.location='categorie.php?id=<?php if (is_a($post, 'Post') or is_a($post, 'Project')) {echo $post->getCategorie();} else if (is_a($post, 'Article')) {echo "Article";} ?>';">
                                            <?php if (is_a($post, 'Post') or is_a($post, 'Project')) {echo $post->getCategorie();} else if (is_a($post, 'Article')) {echo "Article";} ?></span> par
                                        <?php if (is_a($post, 'Post') or is_a($post, 'Article')) {echo $post->getAuthor();} else if (is_a($post, 'Project')) {echo implode(", ", $post->getMembers());} ?>,
                                        <?php echo $post->getDisplayableDate() ?>
                                    </h4>
                                    <script type="text/javascript">dce("c<?php echo $i; ?>").style.cursor = "pointer";</script>
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