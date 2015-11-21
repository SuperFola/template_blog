<!DOCTYPE html>

<HTML>
    <head>
        <title>Blog title</title>
        <link meta="screen" rel="stylesheet" type="text/css" href="css/design.css" />
        <meta charset="utf-8" />
        <meta name="description" content="desc" />
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no" />
        
        <script src="scripts/spoiler.js"></script>
        <script src="http://strapdownjs.com/v/0.2/strapdown.js"></script>
        
        <?php
            include('analytics/ip_analytics.php');
            include('analytics/cptviews_analytics.php');
            include('public/count_news.php');
        ?>
    </head>
    <body>
        <p id="start">
            welcome message
        </p>
        
        <div class="main" tabindex=0>
            <div id="submain">
                <h1>Articles</h1>
                    <ul>
                        <?php
                            $cur_new = count_news();
                            
                            while($cur_new > 0){
                                include('news/new' . $cur_new . '.php');
                                $cur_new--;
                            }
                        ?>
                        <br />
                        <?php
                            include('portfolio/portfolio.php');
                        ?>
                    </ul>
            </div>
            
            <?php include('public/search.php'); ?>
            
            <!-- NE RIEN ECRIRE APRES CETTE BALISE -->
        </div>
        
        <div class="videos" tabindex=1>
            <h1>Mes videos</h1>
                Video sur blablabla du XX-XX-XX : <a href="" target="blank">YouTube</a>
                
                <br /><br />
                <!-- NE RIEN ECRIRE APRES CETTE BALISE -->
        </div>
        
        <div class="liens" id="l" tabindex=2>
            <h1>Liens</h1>
                <ul>
                    <li> <a href="link" target="blank">link name</a></li>
                </ul>
                <!-- NE RIEN ECRIRE APRES CETTE BALISE -->
        </div>
        
        <div class="recrutement" id="r" tabindex=3>
            <h1>Recrutement</h1>
                <ul>
                    <li>recrutement</li>
                </ul>
                Pour me contacter, envoyez moi un message privé sur blablabla !
                <br /><br />
                <!-- NE RIEN ECRIRE APRES CETTE BALISE -->
        </div>       
        
        <br />
        <br />
    </body>
</HTML>