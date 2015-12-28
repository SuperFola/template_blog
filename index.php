<!DOCTYPE html>

<HTML>
    <head>
        <title>titre</title>
        <link meta="screen" rel="stylesheet" type="text/css" href="css/design.css" />
        <meta charset="utf-8" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="Content-Language" content="fr-FR" />
        <meta name="robots" content="all" />
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no" />
        
        <script src="scripts/spoiler.js"></script>
        <script src="scripts/images.js"></script>
        <script src="http://strapdownjs.com/v/0.2/strapdown.js"></script>
        
        <?php
            include('analytics/ip_analytics.php');
            include('analytics/cptviews_analytics.php');
            include('public/count_news.php');
        ?>
    </head>
    <body>
        <div class="start">
            <h1>Titre</h1>
            <h3>Slogan</h3>
            <?php include('public/search.php'); ?>
        </div>
        
        <div class="main">
            <h1>Articles</h1>
                <ul>
                    <?php
                        $cur_new = count_news();
                        if (!isset($_GET['post'])){
                            $fin = $cur_new - 10;
                            if ($fin < 0){
                                $fin = 0;
                            }
                            while($cur_new > $fin){
                                include('news/new' . $cur_new . '.php');
                                $cur_new--;
                            }
                        }else if(isset($_GET['post']) and $_GET['post'] == 'all'){
                            while($cur_new > 0){
                                include('news/new' . $cur_new . '.php');
                                $cur_new--;
                            }
                        }else if(isset($_GET['post']) and is_numeric($_GET['post'])){
                            $post = intval($_GET['POST']);
                            if (0 < $post and $post <= count_news()){
                                include('news/new' . $post . '.php');
                            }else{
                                $fin = $cur_new - 10;
                                if ($fin < 0){
                                    $fin = 0;
                                }
                                while($cur_new > $fin){
                                    include('news/new' . $cur_new . '.php');
                                    $cur_new--;
                                }
                            }
                        }
                    ?>
                    <br />
                    <li><a href="portfolio/">Mon portfolio</a></li>
                </ul>
                <br />
                <a href="index.php?post=all">Voir tous les posts</a>
                <br />
        <!-- NE RIEN ECRIRE APRES CETTE BALISE -->
        </div>
        
        <div class="liens">
            <h1>Liens</h1>
                <ul>
                    <li></li>
                </ul>
                <!-- NE RIEN ECRIRE APRES CETTE BALISE -->
        </div>
        
        <div class="recrutement">
            <h1>Recrutement</h1>
                <ul>
                    <li></li>
                </ul>
                Pour me contacter, [...]
                <br /><br />
                <!-- NE RIEN ECRIRE APRES CETTE BALISE -->
        </div>
        
        <footer>
            <br /><br />
            <!--<a href="news/"><img src="pic/end_picture.png" /></a>-->
        </footer>
        
        <?php
            include('public/modalebox.php');
        ?>
    </body>
</HTML>