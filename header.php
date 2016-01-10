        <div class="jumbotron">
            <div class="login-btn col-md-12">
                <?php
                    if (!isset($_SESSION) or !isset($_SESSION['pseudo'])){
                 ?>
                <a class="btn btn-primary btn-xs" href="login.php">Connexion</a>
                <?php
                    } else {
                        if ($_SESSION['role'] == 'ADMINISTRATEUR'){
                            echo "<a class=\"btn btn-primary btn-xs\" href=\"admin/index.php\">" . $_SESSION['pseudo'] . "</a>";
                        } else {
                            echo "<a class=\"btn btn-primary btn-xs\">" . $_SESSION['pseudo'] . "</a>";
                        }
                    }
                ?>
            </div>
            <h1>Titre</h1>
            <h3>Slogan</h3>
        </div>