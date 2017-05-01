<?php
    session_start();
?>
<!DOCTYPE HTML>

<HTML>
    <?php include('head.php'); ?>
    <body>
        <?php include('header.php'); ?>
        <div class="container">
            <h2>Utilisateurs</h2>
            <?php
                $userManager = new UserManager();
                $users = $userManager->getUsers();
            ?>
            <table class="table">
                <thead>
                <tr>
                    <th>Avatar</th>
                    <th>Pseudo</th>
                    <th>Rôle</th>
                    <th>Dernière activité</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php if (count($users) == 0): ?>
                    <tr>
                        <td colspan="5" class="text-center">Aucun utilisateur enregistré</td>
                    </tr>
                <?php endif ?>
                <?php foreach($users as $user): ?>
                    <tr>
                        <td><?php echo '<img src="http://identicon.org?t=' . $user->getPseudo() . '&s=50" class="img-responsive">'; ?></td>
                        <td><?php echo $user->getPseudo() ?></td>
                        <td><?php echo $user->getRole() ?></td>
                        <td><?php echo $user->getLastLogin() ?></td>
                        <td><a href="ucp.php?id=<?php echo $user->getId() ?>">Voir le profil</a></td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
            <?php include('footer.php'); ?>
        </div>
    </body>
</html>