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
                    echo "<br /><br />";
                    
                    $cm = new ConfigManager();
                    
                    if (isset($_POST['config_content'])) {
                        $cm->setConfig($_POST['config_content']);
                        echo '<div class="alert alert-success" role="alert">Configuration éditée avec succès !</div>';
                    }
                    
                    echo "Configuration JSON du blog : <br />";
                   ?>
           <form action="edit_config.php" method="post">
                <div class="input-group">
                    <textarea name="config_content" rows="12" cols="128"><?php echo "{'" . implode("','", $cm->getConfig()) . "'}"; ?></textarea>
                </div>
                <br />
                <button type="submit" class="btn btn-primary">Valider</button>
            </form>
            <br />
            Configuration par défaut si erreur dans la source proposée :
            <pre><?php echo htmlentities('{"blogtitle": "We Are Coders", "blogslogan": "Du code, des jeux et de l\'innovation !", "blogfooter": "Blog créé par iReplace et Yaalval<br />Code source trouvable à cette <a href=\'https://github.com/Loodoor/template_blog\'>adresse</a><br />Copier le contenu de ce blog est strictement interdit<br />&copy; Copyright iReplace et Yaalval<br />En cas de flood, l\'IP / les IP se verront bloquées"}'); ?></pre>
            <?php } ?>
            <hr />
            <?php
                include(__DIR__ . '/../footer.php');
            ?>
        </div>
    </body>
</HTML>