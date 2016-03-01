<?php
    session_start();
?>

<!DOCTYPE html>

<HTML>
    <?php include('head.php'); ?>
    <body>
        <?php include(__DIR__ . '/header.php'); ?>
        <div class="container">
            <?php
                if (!isset($_SESSION['pseudo']) and (!isset($_SESSION['role']) or $_SESSION['role'] != 'ADMINISTRATEUR')){
                    header("Location: ../login.php");
                } else if ($_SESSION['role'] == 'ADMINISTRATEUR'){
                    // connexion réussie
                    echo "Interface d'administration, bonjour {$_SESSION['pseudo']}";
                    echo "<br />";
                    echo "Liste des articles mis en avant : <br />";
                }
            ?>
            
            <?php
                include(__DIR__ . '/../footer.php');
            ?>
        </div>
    </body>
</HTML>