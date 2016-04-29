<?php
    session_start();
?>

<!DOCTYPE html>
<html>
    <?php include("head.php"); ?>
    <link rel="stylesheet" href="../css/font-awesome/css/font-awesome.min.css">
    <body>
        <?php include('header.php'); ?>
        <?php
            $postManager = new PostManager();
            $validation = array(
                'valid' => false,
                'errors' => array()
            );
            if (isset($_GET['action']) and isset($_GET['post'])) {
                if ($_GET['action'] == 'post_edit') {
                    $post = $postManager->findPost(intval($_GET['post']));
                } else {
                    $post = new Post();
                }
            } else {
                $post = new Post();
            }
            $categories = array('Programmation', 'Vie du blog', 'Windows', 'Android', 'Github', 'Les langages du web', 'Python', 'La famille C');

            if (isset($_POST['cmd']) and isset($_SESSION) and in_array($_SESSION['role'], array('MODERATEUR', 'ADMINISTRATEUR', 'AUTEUR'))) {
                if ($_POST['cmd'] == 'post_add') {
                    $post = new Post();
                    //handlePostRequest($title, $categorie, $content, $author_name)
                    $post->handlePostRequest($_POST['post_titre'], $_POST['post_categorie'], $_POST['post_content'], $_SESSION['pseudo']);
                    $validation = $post->validate();
                    if ($validation['valid']) {
                        $postManager->persistPost($post);

                        header('Location: ../index.php');
                        exit('Post successfuly added');
                    }
                } else if($_POST['cmd'] == 'post_edit' and isset($_POST['post'])) {
                    $post->setTitre($_POST['post_titre']);
                    $post->setCategorie($_POST['post_categorie']);
                    $post->setTimestampEdition(time());
                    $post->setContent($_POST['post_content']);
                    
                    $validation = $post->validate();
                    if ($validation['valid']) {
                        $postManager->updatePost($post);

                        header('Location: ../index.php');
                        exit('Post successfuly edited');
                    }
                }
            }
        ?>
        <?php if (isset($_SESSION) and in_array($_SESSION['role'], array('MODERATEUR', 'ADMINISTRATEUR', 'AUTEUR'))) { ?>
        <script src="../scripts/wysiwyg.js"></script>
        <div class="container">
            <div class="writing-form-container">
                <form method="post" class="form-horizontal">
                    <div class="well col-md-12 col-lg-10 col-lg-offset-1">
                        <div class="container-fluid">
                            <div class="form-group">
                                <i>Ne vous préocupez pas d'ajouter la date à la news, cela est fait automatiquement :)</i><br /><br />
                                <div class="form-group<?php if(array_key_exists('post_titre', $validation['errors'])): ?> has-error<?php endif; ?>">
                                    <label for="post_titre" class="col-sm-2 control-label">Titre</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="post_titre" name="post_titre" placeholder="Titre" value="<?php echo $post->getTitre(); ?>">
                                        <?php if(array_key_exists('post_titre', $validation['errors'])): ?>
                                            <span class="help-block"><?php echo $validation['errors']['post_titre']; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="form-group<?php if(array_key_exists('post_categorie', $validation['errors'])): ?> has-error<?php endif; ?>">
                                    <label for="post_categorie" class="col-sm-2 control-label">Catégorie</label>
                                    <div class="col-sm-10">
                                        <select id="post_categorie" name="post_categorie" class="form-control">
                                            <?php foreach($categories as $categorie): ?>
                                                <option value="<?php echo $categorie; ?>"<?php if($categorie == $post->getCategorie()): ?> selected<?php endif; ?>><?php echo $categorie; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php if(array_key_exists('post_categorie', $validation['errors'])): ?>
                                            <span class="help-block"><?php echo $validation['errors']['post_categorie']; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <br />
                                <div id="completeEditeur">
                                    <a class="btn btn-default" onclick="command('bold');"><i class="fa fa-bold fa-lg"></i></a>
                                    <a class="btn btn-default" onclick="command('italic');"><i class="fa fa-italic fa-lg"></i></a>
                                    <a class="btn btn-default" onclick="command('underline');"><i class="fa fa-underline fa-lg"></i></a>
                                    <a class="btn btn-default" onclick="command('createLink');"><i class="fa fa-link fa-lg"></i></a>
                                    <a class="btn btn-default" onclick="command('insertImage');"><i class="fa fa-picture-o fa-lg"></i></a>
                                    
                                    <a href="../private/cible_envoi.php" target="blank" class="btn btn-default">Héberger une image</a>
                                    
                                    <div class="btn-group">
                                        <a class="btn btn-default"><i class="fa fa-header fa-fw"></i> Titre</a>
                                        <a class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <span class="fa fa-caret-down"></span></a>
                                            <ul class="dropdown-menu">
                                                <li><a onclick="command('heading', 'h1');"><i class="fa fa-header fa-fw"></i>1</a></li>
                                                <li><a onclick="command('heading', 'h2');"><i class="fa fa-header fa-fw"></i>2</a></li>
                                                <li><a onclick="command('heading', 'h3');"><i class="fa fa-header fa-fw"></i>3</a></li>
                                                <li><a onclick="command('heading', 'h4');"><i class="fa fa-header fa-fw"></i>4</a></li>
                                                <li><a onclick="command('heading', 'h5');"><i class="fa fa-header fa-fw"></i>5</a></li>
                                                <li><a onclick="command('heading', 'h6');"><i class="fa fa-header fa-fw"></i>6</a></li>
                                            </ul>
                                    </div>
                                    
                                    <div class="btn-group">
                                        <a class="btn btn-default"><i class="fa fa-indent fa-fw"></i> Mise en forme</a>
                                        <a class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <span class="fa fa-caret-down"></span></a>
                                            <ul class="dropdown-menu">
                                                <li><a onclick="command('justifycenter');"><i class="fa fa-align-center fa-fw"></i> Centrer</a></li>
                                                <li><a onclick="command('justifyfull');"><i class="fa fa-align-justify fa-fw"></i> Justifier</a></li>
                                                <li class="divider"></li>
                                                <li><a onclick="command('justifyleft');"><i class="fa fa-align-left fa-fw"></i> A gauche</a></li>
                                                <li><a onclick="command('justifyright');"><i class="fa fa-align-right fa-fw"></i> A droite</a></li>
                                                <li class="divider"></li>
                                                <li><a onclick="command('subscript');"><i class="fa fa-subscript fa-fw"></i> Indice</a></li>
                                                <li><a onclick="command('superscript');"><i class="fa fa-superscript fa-fw"></i> Exposant</a></li>
                                            </ul>
                                    </div>
                                    
                                    <div class="btn-group">
                                        <a class="btn btn-default"><i class="fa fa-list fa-fw"></i> Listes</a>
                                        <a class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <span class="fa fa-caret-down"></span></a>
                                            <ul class="dropdown-menu">
                                                <li><a onclick="command('insertunorderedlist');"><i class="fa fa-list-ul fa-fw"></i> A puce</a></li>
                                                <li><a onclick="command('insertorderedlist');"><i class="fa fa-list-ol fa-fw"></i> Numérotée</a></li>
                                            </ul>
                                    </div>
                                    
                                    <div class="btn-group">
                                        <a class="btn btn-default"><i class="fa fa-paperclip fa-fw"></i> Couleur</a>
                                        <a class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <span class="fa fa-caret-down"></span></a>
                                            <ul class="dropdown-menu">
                                                <li><a onclick="command('forecolor', 'blue');"> Bleu</a></li>
                                                <li><a onclick="command('forecolor', 'red');"> Rouge</a></li>
                                                <li><a onclick="command('forecolor', 'yellow');"> Jaune</a></li>
                                                <li><a onclick="command('forecolor', 'green');"> Vert</a></li>
                                                <li><a onclick="command('forecolor', 'black');"> Noir</a></li>
                                                <li><a onclick="command('forecolor', 'white');"> Blanc</a></li>
                                            </ul>
                                    </div>
                                    
                                    <br />
                                    <br />
                                    
                                    <div class="<?php if(array_key_exists('post_content', $validation['errors'])): ?>has-error<?php endif; ?>">
                                        <div class="form-control" height="600" id="editeur" contentEditable></div>
                                        <script type="text/javascript">document.getElementById('editeur').innerHTML = "<?php echo str_replace("\r\n", "\\\r\n", addslashes($post->getContent())); ?>";</script>
                                        <?php if(array_key_exists('post_content', $validation['errors'])): ?>
                                            <span class="help-block"><?php echo $validation['errors']['post_content']; ?></span>
                                        <?php endif; ?>
                                        <br />
                                        Code source HTML :
                                        <br />
                                        <textarea name="post_content" id="hidden_content" rows="12" cols="64"></textarea>
                                        <br />
                                        <a class="btn btn-default" onclick="document.getElementById('hidden_content').value = document.getElementById('editeur').innerHTML;">Metter à jour ce code</a>
                                        <a class="btn btn-primary" onclick="document.getElementById('editeur').innerHTML = document.getElementById('hidden_content').value;">Metter à jour la news</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-footer col-md-8 col-md-offset-2">
                        <a href="../index.php" class="btn btn-default">Retour</a>
                        <?php if (!isset($_GET['action']) or (isset($_GET['action']) and $_GET['action'] != 'post_edit')): ?>
                        <input type="hidden" name="cmd" value="post_add" />
                        <?php else: ?>
                        <input type="hidden" name="cmd" value="post_edit" />
                        <input type="hidden" name="post" value="<?php echo $post->getId() ?>" />
                        <?php endif; ?>
                        <input type="submit" class="btn btn-primary" value="Ajouter" onclick="document.getElementById('hidden_content').value = document.getElementById('editeur').innerHTML;" />
                    </div>

                </form>
        <?php } else {
            header('Location: ../error.php?error=403');
        } ?>
        </div>
        <script src="https://code.jquery.com/jquery-2.2.1.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    </body>
</html>