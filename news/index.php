<?php
    error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Mon éditeur WYSIWYG</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no" />
        <link meta="screen" rel="stylesheet" type="text/css" href="../css/wysiwyg.css" />
        
        <script type='text/javascript' src='../scripts/wysiwyg.js'></script>
    </head>
    <body>
        <?php
            include('../private/_check_access.php');
            include('../public/count_news.php');
            include('../private/post_storage.php');
        ?>
        <?php
        if (isset($_POST['titre']) and isset($_POST['contentHTML']) and $_POST['titre'] != '' and $_POST['contentHTML'] != ''
                    and isset($_POST['user']) and isset($_POST['pass']) and isset($_POST['categorie'])){
            $access_granted = check_access($_POST['user'], $_POST['pass'], $registred_users);
            
            if ($access_granted){
                echo 'Access Granted ! Creating news ...';


                $post = new Post();
                $post
                    ->setTitre($_POST['titre'])
                    ->setContent($_POST['contentHTML'])
                    ->setCategorie($_POST['categorie']);

                $postManager = new PostManager();
                $postManager->persistPost($post);

                echo $post->asJSON();
                
                header('Location: ..');
            }
        }else if (isset($_POST['user']) and isset($_POST['pass']) and isset($_POST['news_to_delete'])){
            $access_granted = check_access($_POST['user'], $_POST['pass'], $registred_users);
            
            if ($access_granted){
                echo 'Access Granted ! Deleting file ...';
                
                @unlink(realpath($_POST['news_to_delete']));
                
                header('Location: ..');
            }
        }else if (isset($_POST['user']) and isset($_POST['pass']) and isset($_POST['news_to_edit']) and isset($_POST['contentHTML2'])){
            $access_granted = check_access($_POST['user'], $_POST['pass'], $registred_users);
            
            if ($access_granted){
                echo 'Access Granted ! Deleting file ...';
                
                $nouvelle_news = fopen($_POST['news_to_edit'], 'w');
                fputs($nouvelle_news, $_POST['contentHTML2']);
                fclose($nouvelle_news);
                
                header('Location: ..');
            }
        }
        ?>
        <form method="post" action="index.php">
            Ne vous préocupez pas d'ajouter la date à la news, cela est fait automatiquement :)
            <br />
            <div id="completeEditeur">
                Catégorie : <input type="text" name="categorie" id="categorie" /> <br />
                Titre : <input type="text" name="titre" id="titre" /> <br />
                
                <div id="buttons">
                    <input type="button" value="G" style="font-weight:bold;" onclick="command('bold');" />
                    <input type="button" value="I" style="font-style:italic;" onclick="command('italic');" />
                    <input type="button" value="S" style="text-decoration:underline;" onclick="command('underline');" />
                    
                    <input type="button" value="Lien" onclick="command('createLink');" >
                    <input type="button" value="Image" onclick="command('insertImage');" >
                </div>
                
                <div id="selecters">
                    <select onchange="command('heading', this.value); this.selectedIndex = 0;">
                        <option value="">Titre</option>
                        <option value="h1">Titre 1</option>
                        <option value="h2">Titre 2</option>
                        <option value="h3">Titre 3</option>
                        <option value="h4">Titre 4</option>
                        <option value="h5">Titre 5</option>
                        <option value="h6">Titre 6</option>
                    </select>
                    
                    <br />
                    
                    <select onchange="command(this.value); this.selectedIndex = 0;">
                        <option value="">Alignement</option>
                        <option value="justifyleft">Aligné à gauche</option>
                        <option value="justifyright">Aligné à droite</option>
                        <option value="justifycenter">Centré</option>
                        <option value="justifyfull">Justifié</option>
                    </select>
                    
                    <br />
                    
                    <select onchange="command(this.value); this.selectedIndex = 0;">
                        <option value="">Indice / Exposant</option>
                        <option value="subscript">Mettre en indice</option>
                        <option value="superscript">Mettre en exposant</option>
                    </select>
                    
                    <br />
                    
                    <select onchange="command(this.value); this.selectedIndex = 0;">
                        <option value="">Liste</option>
                        <option value="insertunorderedlist">Liste à puces</option>
                        <option value="insertorderedlist">Liste numérotée</option>
                    </select>
                    
                    <br />
                    
                    <select onchange="command('forecolor', this.value); this.selectedIndex = 0;">
                        <option value="">Couleur du texte</option>
                        <option value="blue">Bleu</option>
                        <option value="red">Rouge</option>
                        <option value="yellow">Jaune</option>
                        <option value="green">Vert</option>
                        <option value="black">Noir</option>
                        <option value="white">Blanc</option>
                    </select>
                </div>
                
                <div id="editeur" contentEditable></div><br />
                
                <div id="html_editeur">
                    <input type="button" onclick="show_resultat();" value="Obtenir le HTML" id="btn_html">
                    <input type="button" onclick="refresh_html();" value="Raffraichir" id="btn_html_refresh">
                    <input type="button" onclick="maj_html();" value="Mettre à jour l'HTML" id="btn_maj_html">
                    <textarea id="resultat" name="contentHTML"></textarea>
                </div>
            
                Utilisateur : <input type="text" name="user" /> ; Mot de passe : <input type="password" name="pass" />
                <button type="submit" onclick="refresh_html();">Envoyer la news</button>
            </div>
        </form>
        
        <br />
        
        <div id="maj_news">
            <a name="edit" style="color: black; text-decoration: none;">Editer une news :</a>
            <form method="post" action="index.php#edit">
                <select name="edit_news_nbr" id="selecter_news_edit">
                <?php
                    $tmp = opendir("../news/") or die('Erreur');
                    while($entry = @readdir($tmp)) {
                        if(!is_dir($entry) && $entry != '.' && $entry != '..' && $entry != 'index.php') {
                            echo '<option value="' . $entry . '">' . $entry . '</option>';
                        }
                    }
                    closedir($tmp);
                ?>
                </select>
                <button type="submit">Valider</button>
            </form>
            
            <?php
                if (isset($_POST['edit_news_nbr'])){
            ?>
            
                <form method="post" action="index.php">
                    <input type="hidden" value="<?php echo $_POST['edit_news_nbr']; ?>" name="news_to_edit" />
                    
                    <div id="completeEditeur">
                        <div id="buttons">
                            <input type="button" value="G" style="font-weight:bold;" onclick="command('bold');" />
                            <input type="button" value="I" style="font-style:italic;" onclick="command('italic');" />
                            <input type="button" value="S" style="text-decoration:underline;" onclick="command('underline');" />
                            
                            <input type="button" value="Lien" onclick="command('createLink');" >
                            <input type="button" value="Image" onclick="command('insertImage');" >
                        </div>
                        
                        <div id="selecters">
                            <select onchange="command('heading', this.value); this.selectedIndex = 0;">
                                <option value="">Titre</option>
                                <option value="h1">Titre 1</option>
                                <option value="h2">Titre 2</option>
                                <option value="h3">Titre 3</option>
                                <option value="h4">Titre 4</option>
                                <option value="h5">Titre 5</option>
                                <option value="h6">Titre 6</option>
                            </select>
                            
                            <br />
                            
                            <select onchange="command(this.value); this.selectedIndex = 0;">
                                <option value="">Alignement</option>
                                <option value="justifyleft">Aligné à gauche</option>
                                <option value="justifyright">Aligné à droite</option>
                                <option value="justifycenter">Centré</option>
                                <option value="justifyfull">Justifié</option>
                            </select>
                            
                            <br />
                            
                            <select onchange="command(this.value); this.selectedIndex = 0;">
                                <option value="">Indice / Exposant</option>
                                <option value="subscript">Mettre en indice</option>
                                <option value="superscript">Mettre en exposant</option>
                            </select>
                            
                            <br />
                            
                            <select onchange="command(this.value); this.selectedIndex = 0;">
                                <option value="">Liste</option>
                                <option value="insertunorderedlist">Liste à puces</option>
                                <option value="insertorderedlist">Liste numérotée</option>
                            </select>
                            
                            <br />
                            
                            <select onchange="command('forecolor', this.value); this.selectedIndex = 0;">
                                <option value="">Couleur du texte</option>
                                <option value="blue">Bleu</option>
                                <option value="red">Rouge</option>
                                <option value="yellow">Jaune</option>
                                <option value="green">Vert</option>
                                <option value="black">Noir</option>
                                <option value="white">Blanc</option>
                            </select>
                        </div>
                        
                        <div id="editeur2" contentEditable>
                        <?php
                            $file = fopen($_POST['edit_news_nbr'], 'r');
                            $content = "";
                            while (!feof($file)){
                                $content .= fgets($file);
                            }
                            echo $content;
                            fclose($file);
                        ?>
                        </div><br />
                        
                        
                        <div id="html_editeur_news">
                            <input type="button" onclick="show_resultat2();" value="Obtenir le HTML" id="btn_html2">
                            <input type="button" onclick="refresh_html2();" value="Raffraichir" id="btn_html_refresh2">
                            <input type="button" onclick="maj_html2();" value="Mettre à jour l'HTML" id="btn_maj_html2">
                            <textarea id="resultat2" name="contentHTML2"></textarea>
                        </div>
                    </div>
                    
                    <br /><br />
                    Utilisateur : <input type="text" name="user" /> ; Mot de passe : <input type="password" name="pass" />
                    <br /><br />
                    <button type="submit">Editer la news sélectionnée</button>
                </form>
            </div>
        <?php
                }else{
                    echo '</div>';
                }
        ?>
        
        <br />
        
        <div id="del_news">
            <form method="post" action="index.php">
                Supprimer une news :
                <select name="news_to_delete" id="selecter_news_del" onchange="alert('Vous êtes sur le point de supprimer une news, soyez sûr de ce que vous faites !');">
                <?php
                    $tmp = opendir("../news/") or die('Erreur');
                    $i = 0;
                    while($entry = @readdir($tmp)) {
                        if(!is_dir($entry) && $entry != '.' && $entry != '..' && $entry != 'index.php') {
                            echo '<option value="' . $i . '">' . $entry . '</option>';
                        }
                        $i++;
                    }
                    closedir($tmp);
                ?>
                </select>
                <br /><br />
                Utilisateur : <input type="text" name="user" /> ; Mot de passe : <input type="password" name="pass" />
                <br /><br />
                <button type="submit">Supprimer la news sélectionnée</button>
            </form>
        </div>
    </body>
</html>