<?php
    function generateDate($timestamp) {
        $diff = time() - $timestamp;
        if ($diff < 120) {
            return 'à l\'instant';
        }
        if ($diff < 3600) {
            return 'Il y a '. date('i', $diff) . ' minutes';
        } elseif ($diff < 86400) {
            if (intval(date('G', $diff)) > 1) {
                return 'Il y a '. date('G', $diff) . ' heures';
            } else {
                return 'Il y a '. date('G', $diff) . ' heure';
            }
        } elseif ($diff < 172800) {
            return 'Hier à '. date('H', $timestamp) . ' heure';
        } else {
            return date('j', $timestamp) . ' ' . date('f', $timestamp) . ', ' . date('H', $timestamp) . 'h' . date('i', $timestamp);
        }
    }
?>

<!DOCTYPE html>
<HTML>
    <head>
        <title>Le blog d'un codeur</title>
        <link rel="shortcut icon" type="image/x-icon" href="pic/favicon.ICO" />

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <!-- <link meta="screen" rel="stylesheet" type="text/css" href="css/design.css" /> -->
        <meta charset="utf-8" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="author" content="Folaefolc / Yaalval" />
        <meta name="copyright" content="© Folaefolc / Yaaval" />
        <meta http-equiv="Content-Language" content="fr-FR" />
        <meta name="robots" content="all" />
        <meta name="description" content="Site web de Yaaval, programmeur Python et Java. Ici je vous partage des idées de codes et vous fait part des nouveautés sur mes projets" />
        <meta name="keywords" content="yaalval, python, java, programmation, programmeur, unamed, urworld, blog, code" />
        <meta name="google-site-verification" content="v2kVdvGvgW7zqnTKRloIN8H1sT50rWVnL_yPdNMpsTc" />
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no" />
        
        <script src="scripts/spoiler.js"></script>
        <script src="scripts/images.js"></script>
        <script src="scripts/commenter.js"></script>
        <script src="http://strapdownjs.com/v/0.2/strapdown.js"></script>
        
        <?php
            include('analytics/ip_analytics.php');
            include('analytics/cptviews_analytics.php');
            include('public/count_news.php');
            include('private/comments_code.php');
        ?>
        
        <style>
            body {
                background-color: #2B2B2B;
            }
            img {
                width: 100%;
            }
            .container {
                background-color: white;
                padding-top: 15px;
            } 
            .jumbotron {
                text-align: center;
            }
            form {
                padding: 5px;
            }
            .commentaires-container {
                margin-top: 20px;
                margin-bottom: 20px;
            }
            .commentaire .avatar {
                float: left;
                margin-right: 15px;
            }
            .commentaire .post {
                padding-left: 75px;
                position: relative;
            }
            .commentaire {
                margin-bottom: 25px;
            }
        </style>
    </head>
    <body>
        <div class="jumbotron">
            <h1>Yaalval</h1>
            <h3>Du code, des jeux et de l'innovation !</h3>
            <?php include('public/search.php'); ?>
        </div>
        
        <div class="container">
            <ol class="breadcrumb">
              <li><a href="index.php">Accueil</a></li>
              <li><a>[Nom de l'actualité]</a></li>
            </ol>
            <hr>
            <?php
                if (isset($_GET['news'])){
                    if (file_exists('news/new' . $_GET['news'] . '.php')){
                        include('news/new' . $_GET['news'] . '.php');
                    }else{
                        echo 'La news demandée n\'existe pas ...';
                    }
                }
            ?>
            <hr>
            <div>
                <?php
                    $comments = get_commentaires_from_file($_GET['news']);
                ?>
                <div class="container-fluid">
                    <div class="well col-md-8 col-md-offset-2">
                        <form class="form-horizontal" method="post" action="public/post_comment.php">
                            <input type="hidden" value="<?php echo $_GET['news'] ?>" name="fromnews" id="newsnb" />
                            <div class="form-group">
                                <input class="form-control" placeholder="Pseudo" name="pseudo" />
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" row="5" placeholder="Votre message..." name="message"></textarea>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary" value="Poster" />
                            </div>
                        </form>
                    </div>
                </div>
                <h3><?php if (count($comments['commentaires']) < 1): ?>Aucun<?php else: ?><?php echo count($comments['commentaires']) ?><?php endif ?> Commentaire<?php if (count($comments['commentaires']) > 1): ?>s<?php endif ?> : </h3>
                <div class="commentaires-container col-md-10 col-md-offset-1">
                    <?php
                        if (file_exists('news/new' . $_GET['news'] . '.php')){
                            foreach(array_reverse($comments['commentaires']) as $commentaire){
                                $date = $commentaire['date'];
                                $nom = $commentaire['nom'];
                                $content = $commentaire['contenu'];
                        ?>
                                <div class="commentaire">
                                    <div>
                                        <div class="avatar">
                                            <img src="http://identicon.org?t=<?php echo $commentaire['nom'] ?>&s=50" class="img-responsive">
                                        </div>
                                        <div class="post">
                                            <div class="header">
                                                <p><b><?php echo $commentaire['nom'] ?></b>, <?php echo generateDate($commentaire['date']) ?></p>
                                            </div>
                                            <div class="content">
                                                <?php echo nl2br($commentaire['contenu']) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            }
                        }
                    ?>
                </div>
            </div>
            <br />
            <br />
            
        </div>
        
        <?php
            include('public/modalebox.php');
            include('public/modalebox_comment.php');
        ?>
    </body>
</HTML>