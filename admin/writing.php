<?php
    include('../private/abstract.php');
    include('../private/postmanager.php');

    $postManager = new PostManager();
    $validation = array(
        'valid' => false,
        'errors' => array()
    );
    $post = new Post();
    $categories = array('Programmation', 'Vie du blog', 'Windows', 'Android', 'Github', 'Moi');

    if (isset($_POST['cmd'])) {
        if ($_POST['cmd'] == 'post_add') {
            $post = new Post();
            $post->handlePostRequest();
            $validation = $post->validate();
            if ($validation['valid']) {
                $postManager->persistPost($post);

                header('Location: ../index.php');
                exit('Post succefuly added');
            }
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Writing</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no" />
        <link meta="screen" rel="stylesheet" type="text/css" href="../css/wysiwyg.css" />

        <script type='text/javascript' src='../scripts/wysiwyg.js'></script>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <!-- Website Style -->
        <link rel="stylesheet" href="../css/style.css">

    </head>
    <body>
        <div class="jumbotron">
            <h1>Titre</h1>
            <h3>Rédiger un article</h3>
        </div>
        <div class="container">
            <div class="writing-form-container">
                <form method="post" class="form-horizontal">
                    <div class="well col-md-8 col-md-offset-2">
                        <div class="container-fluid">
                            <div class="form-group">
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
                                <div class="<?php if(array_key_exists('post_content', $validation['errors'])): ?>has-error<?php endif ?>">
                                    <textarea class="form-control" name="post_content" placeholder="Exprimes toi !" rows="10"></textarea>
                                    <?php if(array_key_exists('post_content', $validation['errors'])): ?>
                                        <span class="help-block"><?php echo $validation['errors']['post_content'] ?></span>
                                    <?php endif ?>
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
        </div>
    </body>
</html>