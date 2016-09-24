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
                    
                    if (isset($_GET['id'])) {
                        
                    } else {
                        header('Location: error.php?error=404');
                    }
                ?>
                <h2>Articles dans la catégorie `<?php echo $_GET['id']; ?>`</h2>

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
                            foreach($projects as $p) { if($p->getCategorie() == $_GET['id']) $result[$p->getTimestampCreation()] = $p;}
                            foreach($articles as $a) {if(array_key_exists($a->getTimestampCreation(), $result) and $_GET['id'] == 'Article') {$result[$a->getTimestampCreation() + 1] = $a;} else {if ($_GET['id'] == 'Article') $result[$a->getTimestampCreation()] = $a;}}
                            foreach($posts as $r) {if(array_key_exists($r->getTimestampCreation(), $result) and $r->getCategorie() == $_GET['id']) {$result[$r->getTimestampCreation() + 1] = $r;} else { if ($r->getCategorie() == $_GET['id']) $result[$r->getTimestampCreation()] = $r;}}
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
                                            <h4>
                                                <span class="label label-default" id="c<?php echo $i; ?>" onclick="window.location='categorie.php?id=<?php if (is_a($post, 'Post') or is_a($post, 'Project')) {echo $post->getCategorie();} else if (is_a($post, 'Article')) {echo "Article";} ?>';">
                                                    <?php if (is_a($post, 'Post') or is_a($post, 'Project')) {echo $post->getCategorie();} else if (is_a($post, 'Article')) {echo "Article";} ?>
                                                </span> par
                                                <?php if (is_a($post, 'Post') or is_a($post, 'Article')) {echo $post->getAuthor();} else if (is_a($post, 'Project')) {echo implode(", ", $post->getMembers());} ?>
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
                            <?php
                            }}
                        ?>
                    </ul>
                    <?php
                        if ($page - 1 >= 0) {
                            echo "<a href='categorie.php?id=" . $_GET['id'] . "&page=" . intval($page - 1) . "'>News précédentes</a>&nbsp;&nbsp;";
                        }
                        if (($page + 1) * 12 <= count($posts)) {
                            echo "<a href='categorie.php?id=" . $_GET['id'] . "&page=" . intval($page + 1) . "'>News suivantes</a>";
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