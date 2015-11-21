<!DOCTYPE html>

<HTML>
    <head>
        <title>LBDUC - 
        <?php
            if (isset($_GET['recherche'])){
                echo 'recherche: ' . $_GET['r'];
            }else{
                echo 'recherche';
            }
        ?>
        </title>
        <link rel="stylesheet" type="text/css" href="css/design.css" />
        <meta charset="utf-8" />
        
        <script src="scripts/spoiler.js"></script>
        <script src="scripts/expand.js"></script>
        <script src="http://strapdownjs.com/v/0.2/strapdown.js"></script>
    </head>
    <body>
        <p id="start">
            Salut à tous ! Je vous souhaite la bienvenue sur mon blog :D !
        </p>
        
        <div class="main" tabindex=0>
            <div id="submain">
                <h1>Résultats</h1>
                    <ul>
                        <?php
                            $db = array();
                            $dirs_to_add = array('news', 'portfolio');
                            
                            foreach($dirs_to_add as $directory){
                                $tmp = opendir($directory) or die('Erreur');
                                while($entry = @readdir($tmp)) {
                                    if(!is_dir($directory . '/' . $entry) && $entry != '.' && $entry != '..' && $entry != 'index.php') {
                                        $f = fopen($directory . '/' . $entry, 'r');
                                        $file = fgets($f);
                                        fclose($f);
                                        $db[preg_replace("#<li><a href=\"\#\" onclick=\"s\('.+'\)\">(.+)</a></li>#i", "$1", $file)] = $directory . '/' . $entry;
                                    }
                                }
                                closedir($tmp);
                            }
                            
                            $count_results = 0;
                            if ($_GET['recherche'] != ''){
                                foreach($db as $cle => $valeur){
                                    if (preg_match("#" . $_GET['recherche'] . "#i", $cle)){
                                        include($valeur);
                                        $count_results++;
                                    }
                                }
                                
                                if ($count_results == 0){
                                    echo 'Nous sommes désolés, mais la recherche a été infructueuse ...';
                                }
                            }else{
                                echo "Veuillez renseigner votre recherche";
                            }
                        ?>
                    </ul>
                    
                    <br />
                    <a href="index.php">Retour à l'accueil</a>
            </div>
            
            <?php include('public/search.php'); ?>

            <!-- NE RIEN ECRIRE APRES CETTE BALISE -->
        </div>
    </body>
</HTML>