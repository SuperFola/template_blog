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
                    
                    $block_users = new BlockedUsersManager();
                    
                    if (isset($_POST['addr_ip'])) {
                        $block_users->hydrate(array("executeur" => $_SESSION['pseudo'], "ip" => $_POST['addr_ip']));
                        $block_users->persistList();
                        echo '<div class="alert alert-success" role="alert">IP bloquée avec succès !</div>';
                    }
                    else if (isset($_POST['unlock_addr_ip'])) {
                        if ($block_users->unlock($_POST['unlock_addr_ip']))
                            echo '<div class="alert alert-success" role="alert">IP débloquée avec succès !</div>';
                        else
                            echo '<div class="alert alert-danger" role="alert">Impossible de débloquer l\'IP</div>';
                        $block_users->persistList();
                    }
                    
                    echo "Liste des IP bloquées : <br />";
                    if ($block_users->getSize() != 0) {
                        echo '<ul>';
                        $nb = 0;
                        foreach ($block_users->findAll() as $user) {
                            if (isset($user['ip'])) {
                                echo '<li>';
                                echo $user['ip'] . " - Par " . $user['by'] . " - Numéro " . $nb;
                                echo '</li>';
                                if ($nb == $block_users->getSize() - 1)
                                    break;
                                $nb++;
                            }
                        }
                        echo '</ul>';
                    } else {
                        echo '<div class="alert alert-info" role="alert">Aucune IP n\'est actuellement bloquée</div>';
                    }
                }
            ?>
            
            <hr />
            
            <form action="block_ip.php" method="post">
                Bloquer une IP :
                <br /><br />
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1">IP</span>
                    <input type="text" class="form-control" placeholder="0.0.0.0" name="addr_ip" aria-describedby="basic-addon1">
                </div>
                <br />
                <button type="submit" class="btn btn-primary">Valider</button>
            </form>
            
            <hr />
            
            <form action="block_ip.php" method="post">
                Débloquer une IP :
                <br /><br />
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1">IP</span>
                    <input type="text" class="form-control" placeholder="0.0.0.0" name="unlock_addr_ip" aria-describedby="basic-addon1">
                </div>
                <br />
                <button type="submit" class="btn btn-primary">Valider</button>
            </form>
            
            <?php
                include(__DIR__ . '/../footer.php');
            ?>
        </div>
    </body>
</HTML>