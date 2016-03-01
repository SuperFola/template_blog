<?php
include('_check_access.php');

// Testons si le fichier a bien été envoyé et s'il n'y a pas d'erreur
if (isset($_FILES['monfichier']) AND $_FILES['monfichier']['error'] == 0 AND isset($_POST['user']) AND isset($_POST['pwd']))
{
    $access_granted = check_access($_POST['user'], $_POST['pwd'], $registred_users);
    
    if ($access_granted) {
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
        echo 'Authenfication ratée';
    }
} else {
?>
<form action="cible_envoi.php" method="post" enctype="multipart/form-data">
    <p>
        Formulaire d'envoi de fichier :<br />
        Login : <input type="text" name="user" /><br />
        Mot de passe : <input type="password" name="pwd" /><br />
        Fichier : <input type="file" name="monfichier" /><br />
        <i>3 Mo par fichier maximum</i><br />
        <input type="submit" value="Envoyer le fichier" />
    </p>
</form>
<?php } ?>