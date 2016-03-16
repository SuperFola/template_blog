<!DOCTYPE html>

<HTML>
    <head>
        <title>Changez mon avatar !</title>
        <link rel="shortcut icon" type="image/x-icon" href="pic/favicon.ICO" />
        <link rel="stylesheet" type="text/css" href="css/design.css" />
        <meta charset="utf-8" />
        
        <style>
        ._avatars {
            display: inline-block;
        }

        ._avatars img {
            height: 150px;
            width: auto;
        }
        </style>
    </head>
    <body>
        <div class="start">
            <h1>Yaalval</h1>
            <h3>Du code, des jeux et de l'innovation !</h3>
        </div>
        
        <div class="main">
            <?php
                $count = fopen('avatars/count_change.txt', 'r');
                $changes = fgets($count);
                fclose($count);
                
                echo "L'avatar a été changé <b>" . $changes . "</b> fois ! <br /><br />";
                
                $fichier = fopen('avatars/image.txt', 'r'); 
                $image = fgets($fichier); 
                fclose($fichier);
                
                if (!file_exists('canch.txt')){
                    $i = 1;
                    while ($i <= 12){
                        echo '<div class="_avatars">';
                        if ($i != $image){
                            echo '<a href="avatar.php?i=' .$i .'"><img src="avatars/avatar-' . $i . '.jpg" /><br/>' . $i . '.';
                        }else{
                            echo '<a href="avatar.php?i=' .$i .'"><img src="avatars/avatar-' . $i . '.jpg" /><br />Actuel';
                        }
                        echo '</a>';
                        echo '</div>';
                        
                        $i++;
                    }
                }else{
                    echo "Impossible de modifier l'avatar en ce moment ! Désolé";
                }
            ?>
            
            <br />
            <a href="index.php">Retour à l'accueil</a>
            <!-- NE RIEN ECRIRE APRES CETTE BALISE -->
        </div>
        
        <?php include('footer.php'); ?>
    </body>
</HTML>