<?php
    session_start();
?>
<!DOCTYPE HTML>

<HTML>
    <?php include('head.php'); ?>
    <body>
        <?php include('header.php'); ?>
        <div class="container">
            <?php
                $userManager = new UserManager();
                $user = null;
                if (isset($_GET['id']))
                    $user = $userManager->findUser($_GET['id']);
                else
                    $user = $userManager->findUserByPseudo($_SESSION['pseudo']);
                if (!$user) {
                    http_response_code(404);
                    exit();
                }
                
                if (isset($_POST["email"]) and isset($_POST["bio"]) and $user != null and $user->getPseudo() == $_SESSION['pseudo']) {
                    // we are editing the user :D
                    $user->setEmail(htmlentities($_POST["email"]));
                    $user->setBio(htmlentities($_POST["bio"]));
                    $userManager->editUser($user);
                    $userManager->updateUsers();
                }
            ?>
            <div class="commentaire-form-container well col-md-8 col-md-offset-2">
                <h3 style="display: inline;"><?php echo $user->getPseudo() ?></h3>&nbsp;&nbsp;&nbsp;
                <?php echo '<img src="http://identicon.org?t=' . $user->getPseudo() . '&s=50" class="img-responsive" style="display: inline;">'; ?>
                <br /><br />
                <?php if ($user != null and isset($_SESSION['pseudo']) and $user->getPseudo() == $_SESSION['pseudo']) { ?>
                <form class="commentaire-form form-horizontal" method="post">
                    <div class="container-fluid">
                        <div class="form-group">
                            <label for="email" class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10" style="margin-bottom: 10px">
                                <input type="text" class="form-control" id="email" name="email" placeholder="email@domain.truc" value="<?php echo $user->getEmail() ?>">
                            </div>
                            <label for="bio" class="col-sm-2 control-label">Biographie</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" row="5" placeholder="Votre biographie..." name="bio" id="bio"><?php echo $user->getBio() ?></textarea>
                            </div>
                        </div>
                    </div>
                    <input type="submit" class="btn btn-warning" value="Editer" />&nbsp;
                    <a href="logout.php" class="btn btn-danger">Déconnexion</a>
                </form>
                <?php } else { ?>
                Rôle : <?php echo $user->getRole() ?> <br />
                Biographie : <br />
                <pre style="margin-top: 10px"><?php echo $user->getBio() ?></pre>
                <?php } ?>
                Dernière connexion : <?php echo $user->getLastLogin() ?>
            </div>
            <br />
            <?php include('footer.php'); ?>
        </div>
    </body>
</html>