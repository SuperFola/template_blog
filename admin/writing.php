<?php
    session_start();
?>

<!DOCTYPE html>
<html>
    <?php include("head.php"); ?>
    <body>
        <?php include('header.php'); ?>
        <?php
            $postManager = new PostManager();
            $validation = array(
                'valid' => false,
                'errors' => array()
            );
            $post = new Post();
            $categories = array('Programmation', 'Vie du blog', 'Windows', 'Android', 'Github', 'Les langages du web', 'Python', 'La famille C');

            if (isset($_POST['cmd'])) {
                if ($_POST['cmd'] == 'post_add') {
                    $post = new Post();
                    $post->handlePostRequest();
                    $validation = $post->validate();
                    if ($validation['valid']) {
                        $postManager->persistPost($post);

                        header('Location: ../index.php');
                        exit('Post successfuly added');
                    }
                }
            }
        ?>
        <?php if (isset($_SESSION) and in_array($_SESSION['role'], array('MODERATEUR', 'ADMINISTRATEUR'))) { ?>
        <script src="../scripts/wysiwyg.js"></script>
        <div class="container">
            <div class="writing-form-container">
                <form method="post" class="form-horizontal">
                    <div class="well col-md-12 col-lg-10 col-lg-offset-1">
                        <div class="container-fluid">
                            <div class="form-group">
                                <i>Ne vous préocupez pas d'ajouter la date à la news, cela est fait automatiquement :)</i><br /><br />
                                <div class="form-group<?php if(array_key_exists('post_titre', $validation['errors'])): ?> has-error<?php endif ?>">
                                    <label for="post_titre" class="col-sm-2 control-label">Titre</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="post_titre" name="post_titre" placeholder="Titre" value="<?php echo $post->getTitre() ?>">
                                        <?php if(array_key_exists('post_titre', $validation['errors'])): ?>
                                            <span class="help-block"><?php echo $validation['errors']['post_titre'] ?></span>
                                        <?php endif ?>
                                    </div>
                                </div>
                                <div class="form-group<?php if(array_key_exists('post_categorie', $validation['errors'])): ?> has-error<?php endif ?>">
                                    <label for="post_categorie" class="col-sm-2 control-label">Catégorie</label>
                                    <div class="col-sm-10">
                                        <select id="post_categorie" name="post_categorie" class="form-control">
                                            <?php foreach($categories as $categorie): ?>
                                                <option value="<?php echo $categorie ?>"<?php if($categorie == $post->getCategorie()): ?> selected<?php endif ?>><?php echo $categorie ?></option>
                                            <?php endforeach ?>
                                        </select>
                                        <?php if(array_key_exists('post_categorie', $validation['errors'])): ?>
                                            <span class="help-block"><?php echo $validation['errors']['post_categorie'] ?></span>
                                        <?php endif ?>
                                    </div>
                                </div>
                                <br />
                                <div id="completeEditeur">
                                    <input type="button" value="G" style="font-weight:bold;" onclick="command('bold');" class="btn btn-default" />
                                    <input type="button" value="I" style="font-style:italic;" onclick="command('italic');" class="btn btn-default" />
                                    <input type="button" value="S" style="text-decoration:underline;" onclick="command('underline');" class="btn btn-default" />
                                    
                                    <input type="button" value="Lien" onclick="command('createLink');" class="btn btn-default" />
                                    <input type="button" value="Image" onclick="command('insertImage');" class="btn btn-default" />
                                    
                                    <a href="../private/cible_envoi.php" target="blank" class="btn btn-default">Héberger une image</a>
                                    
                                    <select onchange="command('heading', this.value); this.selectedIndex = 0;" class="form-control" style="display: inline; width: 15%;">
                                        <option value="">Titre</option>
                                        <option value="h1">Titre 1</option>
                                        <option value="h2">Titre 2</option>
                                        <option value="h3">Titre 3</option>
                                        <option value="h4">Titre 4</option>
                                        <option value="h5">Titre 5</option>
                                        <option value="h6">Titre 6</option>
                                    </select>
                                    
                                    <select onchange="command(this.value); this.selectedIndex = 0;" class="form-control" style="display: inline; width: 15%;">
                                        <option value="">Alignement</option>
                                        <option value="justifyleft">Aligné à gauche</option>
                                        <option value="justifyright">Aligné à droite</option>
                                        <option value="justifycenter">Centré</option>
                                        <option value="justifyfull">Justifié</option>
                                    </select>
                                    
                                    <select onchange="command(this.value); this.selectedIndex = 0;" class="form-control" style="display: inline; width: 22%;">
                                        <option value="">Indice / Exposant</option>
                                        <option value="subscript">Mettre en indice</option>
                                        <option value="superscript">Mettre en exposant</option>
                                    </select>
                                    
                                    <select onchange="command(this.value); this.selectedIndex = 0;" class="form-control" style="display: inline; width: 15%;">
                                        <option value="">Liste</option>
                                        <option value="insertunorderedlist">Liste à puces</option>
                                        <option value="insertorderedlist">Liste numérotée</option>
                                    </select>
                                    
                                    <select onchange="command('forecolor', this.value); this.selectedIndex = 0;" class="form-control" style="display: inline; width: 21%;">
                                        <option value="">Couleur du texte</option>
                                        <option value="blue">Bleu</option>
                                        <option value="red">Rouge</option>
                                        <option value="yellow">Jaune</option>
                                        <option value="green">Vert</option>
                                        <option value="black">Noir</option>
                                        <option value="white">Blanc</option>
                                    </select>
                                    
                                    <br />
                                    <br />
                                    
                                    <div class="<?php if(array_key_exists('post_content', $validation['errors'])): ?>has-error<?php endif ?>">
                                        <div class="form-control" name="post_content" placeholder="Exprimes toi !" rows=30 id="editeur" contentEditable></div>
                                        <?php if(array_key_exists('post_content', $validation['errors'])): ?>
                                            <span class="help-block"><?php echo $validation['errors']['post_content'] ?></span>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-footer col-md-8 col-md-offset-2">
                        <a href="../index.php" class="btn btn-default">Retour</a>
                        <input type="hidden" name="cmd" value="post_add" />
                        <input type="submit" class="btn btn-primary" value="Ajouter" />
                    </div>

                </form>
        <? } else {
            header('Location: ../error.php?error=403');
        } ?>
        </div>
    </body>
</html>