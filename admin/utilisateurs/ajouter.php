<?php
    session_start();

    include('../../private/usermanager.php');

    $roles = array('ADMINISTRATEUR', 'AUTEUR', 'MODERATEUR', 'MEMBRE');

    $userManager = new UserManager();
    $user = new User();

    if (!$user) {
        header('Location: index.php');
        http_response_code(404);
        exit();
    }

    if (isset($_POST['witness'])) {
        $user->handlePostRequest($_POST['user_pseudo'], $_POST['user_password'], $_POST['user_email'], $_POST['user_role']);
        $validation = $user->validate();
        if ($validation['valid']) {
            $userManager->addUser($user);
            $userManager->updateUsers();
            header('Location: index.php');
            exit();
        }
    }

?>
<!DOCTYPE HTML>

<HTML>
    <?php include('head.php'); ?>
    <body>
        <?php include('header.php'); ?>
        <div class="container">
            <?php if (isset($_SESSION) and $_SESSION['role'] == 'ADMINISTRATEUR') { ?>
            <h2>Nouvel utilisateur</h2>
            <p class="text-left">
                <a class="btn btn-default" href="index.php">Retour</a>
            </p>
            <form class="form-horizontal" method="post">
                <div class="form-group">
                    <label for="user_pseudo" class="col-sm-2 control-label">Pseudo</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="user_pseudo" name="user_pseudo" value="<?php echo $user->getPseudo() ?>" placeholder="Pseudo">
                    </div>
                </div>
                <div class="form-group">
                    <label for="user_email" class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" id="user_email" name="user_email" value="<?php echo $user->getEmail() ?>" placeholder="Email">
                    </div>
                </div>
                <div class="form-group">
                    <label for="user_role" class="col-sm-2 control-label">Role</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="user_role" name="user_role">
                            <?php foreach($roles as $role): ?>
                                <option value="<?php echo $role ?>"<?php if($user->is($role)): ?> selected<?php endif ?>><?php echo $role ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="user_password" class="col-sm-2 control-label">Mot de passe</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="user_password" name="user_password" placeholder="Mot de passe">
                    </div>
                </div>
                <div class="text-right">
                    <input type="hidden" name="witness" value="X">
                    <input type="submit" class="btn btn-primary" value="Ajouter !">
                </div>
            </form>
            <?php } else {
                header('Location: ../../error.php?error=403');
            } ?>
        </div>
        <br />
    </body>
</html>