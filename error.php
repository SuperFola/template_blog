<?php
    session_start();
?>

<!DOCTYPE html>
<HTML>
    <?php include(__DIR__ . '/head.php'); ?>
    <body>
        <?php
            include('header.php');
        ?>
        <div class="container">
            <br />
            <center>
                <?php
                    if (isset($_GET['error'])){
                        if ($_GET['error'] == 'connexion'){
                            echo 'Impossible de se connecter. Vérifiez votre nom d\'utilisateur et votre mot de passe ...';
                        } else if ($_GET['error'] == '404'){
                            echo 'La page que vous cherchez n\'existe probablement plus (ou n\'a jamais existée)';
                        } else if ($_GET['error'] == '403') {
                            echo 'Vous n\'avez pas les droits suffisants pour accéder à cette page';
                        } else {
                            echo 'Aucune erreur ne s\'est produite';
                        }
                    } else {
                        echo 'Aucune erreur ne s\'est produite';
                    }
                ?>
                <?php
                    include('footer.php');
                ?>
            </center>
        </div>
    </body>
</HTML>