<?php
    session_start();
?>

<!DOCTYPE html>

<HTML>
    <?php include('head.php'); ?>
    <body>
        <?php include('header.php'); ?>
        <div class="container">
            <?php
                if (!isset($_SESSION['pseudo']) and !isset($_SESSION['role'])) {
                    header("Location: ../login.php");
                } else if ($_SESSION['role'] == 'ADMINISTRATEUR' or $_SESSION['role'] == 'MODERATEUR' or $_SESSION['role'] == 'AUTEUR') {
                    // connexion réussie
                    echo "Bonjour {$_SESSION['pseudo']}";
                    
                    // Testons si le fichier a bien été envoyé et s'il n'y a pas d'erreur
                    if (isset($_FILES['monfichier']) AND $_FILES['monfichier']['error'] == 0)
                    {
                        // Testons si le fichier n'est pas trop gros
                        if ($_FILES['monfichier']['size'] <= 3000000) {  // 3 Mo max
                            // Testons si l'extension est autorisée
                            $infosfichier = pathinfo($_FILES['monfichier']['name']);
                            $extension_upload = $infosfichier['extension'];
                            $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
                            if (in_array($extension_upload, $extensions_autorisees)) {
                                // On peut valider le fichier et le stocker définitivement
                                move_uploaded_file($_FILES['monfichier']['tmp_name'], '../pic/' . basename($_FILES['monfichier']['name']));
                                echo "L'envoi a bien été effectué !<br />Votre lien : http://folaefolc.hostux.fr/pic/" . basename($_FILES['monfichier']['name']) . "<br /><br /><br /><br /><center><img src='http://folaefolc.hostux.fr/pic/" . basename($_FILES['monfichier']['name']) . "' /></center>";
                            } else {
                                echo 'Extension invalide';
                            }
                        } else {
                            echo 'Fichier trop gros';
                        }
                    } else {
                    ?>
                    <form action="cible_envoi.php" method="post" enctype="multipart/form-data">
                        <p>
                            Formulaire d'envoi de fichier :<br />
                            <div class="form-group">
                                Fichier : <input class="form-control" type="file" name="monfichier" />
                            </div>
                            <br />
                            <i>3 Mo par fichier maximum</i><br />
                            <input class="btn btn-primary" type="submit" value="Envoyer le fichier" />
                        </p>
                    </form>
                <?php }
                }
                include(__DIR__ . '/../footer.php');
            ?>
        </div>
    </body>
</HTML>