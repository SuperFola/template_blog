<?php
    session_start();
?>

<!DOCTYPE html>

<HTML>
    <?php include("head.php"); ?>
    <body>
        <?php include('header.php');
        $validation = array('errors' => array());
        if (isset($_POST['user']) && isset($_POST['pwd']) && isset($_POST['pwdc']) && isset($_POST['email'])) {
            if ($_POST['user'] == '') {
                $validation['errors']['pseudo'] = "Le pseudo ne peut pas être vide !";
            }
            if ($_POST['email'] == '') {
                $validation['errors']['email'] = "L'email ne peut pas être vide !";
            }
            if ($_POST['pwd'] == $_POST['pwdc'] && $_POST['pwd'] != '') {
                $um = new UserManager();
                if (!$um->findUserByPseudo($_POST['user'])) {
                    if (true) {
                        $new_user = new User();
                        $new_user->handlePostRequest($_POST['user'], $_POST['pwd'], $_POST['email'], 'MEMBRE');
                        $validation = $new_user->validate();
                        if ($validation['valid']) {
                            $um->addUser($new_user);
                            $um->updateUsers();
                        }
                        $_SESSION['pseudo'] = $new_user->getPseudo();
                        $_SESSION['role'] = $new_user->getRole();
                        header('Location: index.php');
                    } else {
                        $validation['errors']['email'] = "Votre email n'est pas valide";
                    }
                } else {
                    $validation['errors']['pseudo'] = "Ce pseudo est déjà utilisé par un membre";
                }
            } else {
                if ($_POST['pwd'] == '' || $_POST['pwdc'] == '') {
                    $validation['errors']['password'] = "Le mot de passe ne pas être vide !";
                } else {
                    $validation['errors']['password'] = "Vos mots de passe ne correspondent pas";
                }
            }
        } ?>
        <div class="container">
            <div class="connection">
                <form method="post" class="login" action="signup.php">
                    <div class="form-group">
                        <label for="user">Nom d'utilisateur :</label>
                        <?php if(array_key_exists('pseudo', $validation['errors'])): ?>
                            <div class="alert alert-danger" role="alert"><?php echo $validation['errors']['pseudo'] ?></div>
                        <?php endif ?>
                        <input type="text" class="form-control" id="user" name="user" placeholder="Nom d'utilisateur">
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <?php if(array_key_exists('password', $validation['errors'])): ?>
                            <div class="alert alert-danger" role="alert"><?php echo $validation['errors']['password'] ?></div>
                        <?php endif ?>
                        <input type="password" class="form-control" id="pwd" name="pwd" placeholder="Mot de passe">
                        <br />
                        <label for="pwdc">Confirmer le mot de passe</label>
                        <input type="password" class="form-control" id="pwd" name="pwdc" placeholder="Mot de passe">
                    </div>
                    <div class="form-group">
                        <label for="email">Adresse e-mail</label>
                        <?php if(array_key_exists('email', $validation['errors'])): ?>
                            <div class="alert alert-danger" role="alert"><?php echo $validation['errors']['email'] ?></div>
                        <?php endif ?>
                        <input type="email" class="form-control" id="email" name="email" placeholder="exemple@domain.fr">
                    </div>
                    <input type="submit" class="btn btn-primary" value="S'inscrire">
                </form>
            </div>
        <?php include('../footer.php'); ?>
        </div>
    </body>
</HTML>