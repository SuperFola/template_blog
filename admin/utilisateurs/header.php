        <div class="login-btn col-md-12">
            <?php
                $cm = new ConfigManager();
                $title = $cm->getBlogTitle();
            ?>
            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <!--
                    <div class="navbar-header">
                        <a class="navbar-brand" href="index.php" style="padding: 0px;"><img src="pic/logo.png" alt="WeAreCoders" style="max-height: 50px;height: 50px;"/></a>
                    </div>
                    -->

                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            <li><a style="color: #000;"><?php echo $title ?></a></li>
                            
                            <li id="navbar-accueil-lnk"><a href="../index.php" style="color: #000;">Accueil</a></li>
                            
                            <!-- Projets (en construction, est amené à changer) -->
                            <li class="dropdown" id="navbar-projects-dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Nos projets <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="">Test</a></li>
                                    <li><a href="">Test</a></li>
                                    <li><a href="">Test</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="">Test</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="">Test</a></li>
                              </ul>
                            </li>
                        </ul>

                        <!-- Recherche -->
                        <form class="navbar-form navbar-left" action="../../index.php?action=search" method="get">
                            <div class="row">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Rechercher" />
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="submit">Go!</button>
                                    </span>
                                </div>
                            </div>
                        </form>

                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="color: #000;"><?php echo $_SESSION['pseudo'] ?> <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="../../ucp.php">Profil</a></li>
                                    <li><a href="../index.php">Interface Administrateur</a></li>
                                    <li><a href="../writing.php">Ecrire un article</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="../../logout.php">Déconnexion</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div><!-- /.navbar-collapse -->
                </div><!-- /.container-fluid -->
            </nav>
        </div>