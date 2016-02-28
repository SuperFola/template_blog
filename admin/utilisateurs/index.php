<?php
    session_start();
    
    include('../../private/usermanager.php');

    $userManager = new UserManager();
    $users = $userManager->getUsers();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Utilisateurs</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <!-- Website Style -->
    <link rel="stylesheet" href="../../css/style.css">
    <?php include('../../private/configmanager.php'); ?>
</head>
<body>
    <div class="jumbotron">
        <div class="login-btn col-md-12">
            <?php
                if (!isset($_SESSION) or !isset($_SESSION['pseudo'])){
             ?>
            <a class="btn btn-primary btn-xs" href="../../login.php">Connexion</a>
            <?php
                } else {
                    if ($_SESSION['role'] == 'ADMINISTRATEUR'){
                        echo "<a class=\"btn btn-primary btn-xs\" href=\"../\">" . $_SESSION['pseudo'] . "</a>";
                    } else {
                        echo "<a class=\"btn btn-primary btn-xs\">" . $_SESSION['pseudo'] . "</a>";
                    }
                }
            ?>
        </div>
        <?php
            $cm = new ConfigManager();
            $title = $cm->getBlogTitle();
            $slogan = $cm->getBlogSlogan();
        ?>
        <h1><?php echo $title; ?></h1>
        <h3><?php echo $slogan; ?></h3>
    </div>
    <div class="container">
        <?php if (isset($_SESSION) and $_SESSION['role'] == 'ADMINISTRATEUR') { ?>
        <h2>Utilisateurs</h2>
        <div class="text-right">
            <a class="btn btn-primary" href="ajouter.php">Nouvel utilisateur</a>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th>Pseudo</th>
                <th>Email</th>
                <th>Role</th>
                <th>Dernière activité</th>
                <th style="width: 100px;" class="text-center">Action</th>
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
                    <td><?php echo $user->getPseudo() ?></td>
                    <td><?php echo $user->getEmail() ?></td>
                    <td><?php echo $user->getRole() ?></td>
                    <td><?php echo $user->getLastLogin() ?></td>
                    <td class="text-center"><a class="btn btn-default" href="modifier.php?id=<?php echo $user->getId() ?>">Modifier</a></td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
        <?php } else {
            header("Location: ../../error.php?error=403");
        } ?>
    </div>
</body>
</html>