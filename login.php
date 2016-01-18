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
        
        <?php include("private/configmanager.php"); ?>
    </head>
    <body>
        <?php
            include('header.php');
        ?>
        <div class="container">
            <div class="connection">
                <form method="post" action="loginconfirm.php">
                    Utilisateur : <input type="text" name="user" /><br />
                    Mot de passe : <input type="password" name="pwd" /><br /><br />
                    <button type="submit">Connexion</button>
                </form>
            </div>
            <?php
                include('footer.php');
            ?>
        </div>
    </body>
</HTML>