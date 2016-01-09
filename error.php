<!DOCTYPE html>

<HTML>
    <head>
        <title>titre</title>
        <meta charset="utf-8" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="Content-Language" content="fr-FR" />
        <meta name="robots" content="all" />
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no" />
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <!-- Website Style -->
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <?php
            include('header.php');
        ?>
        <div class="container">
            <?php
                if (isset($_GET['error'])){
                    if ($_GET['error'] == 'connexion'){
                        echo 'Impossible de se connecter. Vérifiez votre nom d\'utilisateur et votre mot de passe ...';
                    } else if ($_GET['error'] == '404'){
                        echo 'La page que vous cherchez n\'existe probablement plus (ou n\'a jamais existée)';
                    } else if ($_GET['error'] == '403') {
                        echo 'Vous n\'avez pas les droits suffisants pour accéder à cette page';
                    }
                } else {
                    echo 'Aucune erreur ne s\'est produite';
                }
            ?>
            <?php
                include('footer.php');
            ?>
        </div>
    </body>
</HTML>