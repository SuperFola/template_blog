        <div class="jumbotron">
            <div class="login-btn col-md-12">
                <?php
                    if (!isset($_SESSION) or !isset($_SESSION['user'])){
                 ?>
                <a class="btn btn-primary btn-xs" href="login.php">Connexion</a>
                <?php
                    } else {
                        echo "<a class=\"btn btn-primary btn-xs\">" . $_SESSION['user']['pseudo'] . "</a>";
                    }
                ?>
            </div>
            <h1>Titre</h1>
            <h3>Slogan</h3>
        </div>