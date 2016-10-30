<?php
function count_projects($from){
    $cur_new = 0;

    $tmp = opendir($from) or die('Erreur');
    while($entry = @readdir($tmp)) {
        if(!is_dir($from . '/' . $entry) && $entry != '.' && $entry != '..') {
            $cur_new++;
        }
    }
    closedir($tmp);
    
    return $cur_new;
}

class ProjectManager {
    protected $directory;

    public function __construct() {
        $this->directory = __DIR__.'/projects';
        if (!is_dir($this->directory)) {
            mkdir($this->directory);
        }
    }

    /**
     * @param $id
     * @return Post
     */
    public function findProject($id) {
        $project = new Project($id);
        $array = $this->getFile($this->generateFilepath($project));
        $project->hydrate($array);

        return $project;
    }

    /**
     * Retourne une array de tous les posts
     *
     * @return array
     */
    public function findAll() {
        $projects = array();
        $files = count_projects($this->directory);
        $i = 1;
        while($i <= $files) {
            if (file_exists($this->directory.'/project'.$i.'.json')) {
                $project = new Project();
                $array = $this->getFile($this->directory.'/project'.$i.'.json');
                $project->hydrate($array);
                $projects[$project->getTimestampCreation()] = $project;
            }
            $i++;
        }

        # On range le tableaux par ordre de timestamp croissant
        ksort($projects);

        # On retourne uniquement les valeurs du tableaux que l'on a préalablement inversé
        return array_values(array_reverse($projects));
    }

    /**
     * Créer un fichier et y stocke $project
     *
     * @param Project $project
     * @throws Exception
     */
    public function persistProject(Project $project) {
        $project->setId($this->generateProjectId());
        $filepath = $this->generateFilepath($project);

        if (is_file($filepath)) {
            throw new Exception('Un projet ayant l\'id '.$project->getId().' existe déjà');
        }
        $this->saveFile($filepath, json_encode($project->asArray()));
    }

    /**
     * Met à jour le fichier attaché à $project
     *
     * @param Project $project
     * @throws Exception
     */
    public function updateProject(Project $project) {
        $filepath = $this->generateFilepath($project);
        $project->setTimestampEdition(time());

        if (!is_file($filepath)) {
            throw new Exception('Le projet ayant l\'id '.$project->getId().' n\'existe pas');
        }
        $this->saveFile($filepath, json_encode($project->asArray()));
    }

    /**
     * Supprime fichier associé à $project
     *
     * @param Project $project
     * @throws Exception
     */
    public function deleteProject(Post $project) {
        $filepath = $this->generateFilepath($project);

        if (!is_file($filepath)) {
            throw new Exception('Le projet ayant l\'id '.$project->getId().' n\'existe pas');
        }

        unlink($filepath);
    }

    /**
     * Ecrit $json dans le fichier spécifié par $filepath
     *
     * @param $filepath
     * @param $json
     */
    private function saveFile($filepath, $json) {
        $file = fopen($filepath, 'w+');
        fwrite($file, $json);
        fclose($file);
    }

    /**
     * Renvoie le contenu d'un fichier sous forme d'array
     *
     * @param $filepath
     * @return array
     */
    private function getFile($filepath) {
        return json_decode(file_get_contents($filepath), true);
    }

    /**
     * Génère le chemin absolu du fichier du project
     *
     * @param $project
     * @return string
     */
    private function generateFilepath($project) {
        return $this->directory.'/project'.$project->getId().'.json';
    }

    /**
     * Génère un id à partir du nom des fichiers des projets existants
     *
     * @return integer
     */
    private function generateProjectId() {
        $project = new Project();
        $project->setId(1);
        while(is_file($this->generateFilepath($project))) {
            $project->setId($project->getId() + 1);
        }

        return $project->getId();
    }
}

class Project {
    protected $id;
    protected $titre;
    protected $timestampCreation;
    protected $timestampEdition;
    protected $presentation;
    protected $articles;
    protected $categorie;
    protected $storedData;
    protected $commentaires;
    protected $edited;
    protected $members;
    protected $minus;
    protected $plus;
    protected $votants;

    public function __construct($id = 0) {
        $this->id = $id;
        $this->timestampCreation = time();
        $this->commentaires = array();
        $this->articles = array();
        $this->presentation = "";
        $this->edited = false;
        $this->members = array();
        $this->votants = array();
        $this->minus = 0;
        $this->plus = 0;
    }

    /**
     * @param $array
     */
    public function hydrate($array) {
        $this->id = $array['id'];
        $this->titre = $array['titre'];
        $this->timestampCreation = $array['timestamp'];
        $this->presentation = $array['presentation'];
        $this->categorie = $array['categorie'];
        $this->storedData = $array;
        $this->members = $array['members'];
        $this->minus = $array['minus'];
        $this->plus = $array['plus'];
        $this->votants = $array['votants'];
        
        foreach($array['commentaires'] as $commentaireArray) {
            $commentaire = new Commentaire();
            $commentaire->hydrate($commentaireArray);
            $this->commentaires[] = $commentaire;
        }
        foreach($array['articles'] as $articleArray) {
            $article = new Article();
            $article->hydrate($articleArray);
            $this->articles[] = $article;
        }
    }

    /**
     * Premet d'obtenir un extrait du debut du texte du Project
     *
     * @return string
     */
    public function getContentPreview() {
        $preview = "";
        $size_max = 158;
        $count = 0;
        
        foreach(str_split($this->presentation) as $char) {
            $count++;
            $preview .= $char;
            
            if ($count >= $size_max and ! in_array($char, array('"', '\\', '`', '(', ')', '[', ']', '_', '*', '#')))
                break;
        }

        $preview .= "\n";

        return $preview;
    }

    /**
     * Remplit le Project à partir de la requête
     */
    public function handlePostRequest($title, $categorie, $presentation, $members) {
        $this->titre = htmlentities($title);
        $this->categorie = htmlentities($categorie);
        $this->presentation = $presentation;
        $this->members = $members;

        $this->edited = true;
    }

    /**
     * Génère une version sous forme d'array du Post
     *
     * @return array
     */
    public function asArray() {
        $array = array(
            'id' => $this->id,
            'titre' => $this->titre,
            'timestamp' => $this->timestampCreation,
            'presentation' => $this->presentation,
            'articles' => array(),
            'categorie' => $this->categorie,
            'members' => $this->members,
            'minus' => $this->minus,
            'plus' => $this->plus,
            'commentaires' => array(),
            'votants' => $this->votants
        );

        foreach($this->commentaires as $commentaire) {
            $array['commentaires'][] = $commentaire->asArray();
        }
        foreach($this->articles as $article) {
            $array['articles'][] = $article->asArray();
        }

        return $array;
    }

    /**
     * Retourne une array composé d'un boolean de validité et d'une array des toutes les erreurs rencontrées
     *
     * @return array
     */
    public function validate() {
        $validation = array(
            'valid' => true,
            'errors' => array()
        );

        if ($this->titre == '') {
            $validation['valid'] = false;
            $validation['errors']['post_titre'] = 'Votre projet doit avoir un titre !';
        }
        if ($this->presentation == '') {
            $validation['valid'] = false;
            $validation['errors']['post_content'] = 'Votre présentation de projet doit avoir un contenu !';
        }
        if ($this->categorie == '') {
            $validation['valid'] = false;
            $validation['errors']['post_categorie'] = 'Votre projet doit avoir une catégorie !';
        }

        return $validation;
    }

    /**
     * Retourne sous forme de string une date formulée de façon sympa
     *
     * @return string
     */
    public function getDisplayableDate() {
        $diff = time() - $this->timestampCreation;
        if ($diff < 120) {
            return 'à l\'instant';
        }
        if ($diff < 3600) {
            return 'Il y a '. date('i', $diff) . ' minutes';
        } elseif ($diff < 86400) {
            if (intval(date('G', $diff)) > 1) {
                return 'Il y a '. date('G', $diff) . ' heures';
            } else {
                return 'Il y a '. date('G', $diff) . ' heure';
            }
        } elseif ($diff < 172800) {
            return 'Hier à '. date('H', $this->timestampCreation) . 'h';
        } else {
            if (intval(date('Y')) != intval(date('Y', $this->timestampCreation))) {
                return date('j', $this->timestampCreation) . ' ' . date('F', $this->timestampCreation) . ' ' . date('Y', $this->timestampCreation) . ', ' . date('H', $this->timestampCreation) . 'h' . date('i', $this->timestampCreation);
            } else {
                return date('j', $this->timestampCreation) . ' ' . date('F', $this->timestampCreation) . ', ' . date('H', $this->timestampCreation) . 'h' . date('i', $this->timestampCreation);
            }
        }
    }

    /**
     * Retourne une array de tous les commentaires rangé par date croissante
     *
     * @return array
     */
    public function getCommentairesSorted() {
        $commentaires = array();
        foreach($this->commentaires as $commentaire) {
            $commentaires[$commentaire->getTimestamp()] = $commentaire;
        }

        # On range le tableaux par ordre de timestamp croissant
        ksort($commentaires);

        # On retourne uniquement les valeurs du tableaux
        return array_values(array_reverse($commentaires));
    }
    
    /**
     * Retourne une array de tous les articles rangé par date croissante
     *
     * @return array
     */
    public function getArticlesSorted() {
        $articles = array();
        foreach($this->articles as $article) {
            $articles[$article->getTimestampCreation()] = $article;
        }

        # On range le tableaux par ordre de timestamp croissant
        ksort($articles);

        # On retourne uniquement les valeurs du tableaux
        return array_values(array_reverse($articles));
    }
    
    /**
     * @param $id
     * @return Article
     */
    public function findArticle($id) {
        return $this->articles[$id];
    }
    
    public function upVote() {
        if (!in_array($_SERVER['REMOTE_ADDR'], $this->votants)) {
            $votants[] = $_SERVER['REMOTE_ADDR'];
            $this->plus++;
        } else {
            $tmp = array();
            foreach($this->votants as $vot) {
                if ($vot != $_SERVER['REMOTE_ADDR'])
                    $tmp[] = $vot;
            }
            $this->votants = $tmp;
            $this->plus--;
        }
    }
    
    public function downVote() {
        if (!in_array($_SERVER['REMOTE_ADDR'], $this->votants)) {
            $votants[] = $_SERVER['REMOTE_ADDR'];
            $this->minus++;
        } else {
            $tmp = array();
            foreach($this->votants as $vot) {
                if ($vot != $_SERVER['REMOTE_ADDR'])
                    $tmp[] = $vot;
            }
            $this->votants = $tmp;
            $this->minus--;
        }
    }
    
    public function getUpVote() {
        return $this->plus;
    }
    
    public function getDownVote() {
        return $this->minus;
    }
    
    public function setUpVote($value) {
        $this->plus = $value;
    }
    
    public function setDownVote($value) {
        $this->minus = $value;
    }

    public function setId($id) {
        $this->id = $id;

        return $this;
    }

    public function getId() {
        return $this->id;
    }

    public function setTitre($titre) {
        $this->titre = $titre;

        return $this;
    }

    public function getTitre() {
        return $this->titre;
    }

    public function setTimestampCreation($timestampCreation) {
        $this->timestampCreation = $timestampCreation;

        return $this;
    }

    public function getTimestampCreation() {
        return $this->timestampCreation;
    }

    public function setTimestampEdition($timestampEdition) {
        $this->timestampEdition = $timestampEdition;

        return $this;
    }

    public function getTimestampEdition() {
        return $this->timestampEdition;
    }

    public function setPresentation($presentation) {
        $this->presentation = $presentation;

        return $this;
    }

    public function getPresentation() {
        return $this->presentation;
    }
    
    public function addArticle(Article $article) {
        $article->setId(count($this->articles));
        $this->articles[] = $article;
        
        return $this;
    }
    
    public function updateArticle(Article $article) {
        $this->articles[$article->getId()] = $article;
        
        return $this;
    }
    
    public function removeArticle(Article $article) {
        unset($this->articles[$article->getId()]);
        $this->articles = array_values($this->articles);
    }

    public function setCategorie($categorie) {
        $this->categorie = $categorie;

        return $this;
    }

    public function addCommentaire(Commentaire $commentaire) {
        $commentaire->setId(count($this->commentaires));
        $this->commentaires[] = $commentaire;
    }

    public function removeCommentaire(Commentaire $commentaire) {
        unset($this->commentaires[$commentaire->getId()]);
        $this->commentaires = array_values($this->commentaires);
    }

    public function getCommentaires() {
        return $this->commentaires;
    }

    public function getCategorie() {
        return $this->categorie;
    }

    public function setEdited($edited) {
        $this->edited = $edited;

        return $this;
    }
    
    public function getMembers() {
        return $this->members;
    }

    public function getEdited() {
        return $this->edited;
    }

    public function getStoredData() {
        return $this->storedData;
    }
}

class Article {
    protected $id;
    protected $titre;
    protected $timestampCreation;
    protected $timestampEdition;
    protected $content;
    protected $storedData;
    protected $commentaires;
    protected $edited;
    protected $author;
    protected $dad;

    public function __construct($id = 0) {
        $this->id = $id;
        $this->timestampCreation = time();
        $this->commentaires = array();
        $this->edited = false;
        $this->author = "Anonyme";
        $this->dad = 1;  // default value
    }

    /**
     * @param $array
     */
    public function hydrate($array) {
        $this->id = $array['id'];
        $this->titre = $array['titre'];
        $this->timestampCreation = $array['timestamp'];
        $this->content = $array['content'];
        $this->storedData = $array;
        $this->author = $array['author'];
        $this->dad = $array['dad'];
        foreach($array['commentaires'] as $commentaireArray) {
            $commentaire = new Commentaire();
            $commentaire->hydrate($commentaireArray);
            $this->commentaires[] = $commentaire;
        }
    }

    /**
     * Premet d'obtenir un extrait du debut du texte de l'Article
     *
     * @return string
     */
    public function getContentPreview() {
        $preview = "";
        $size_max = 158;
        $count = 0;
        
        foreach(str_split($this->content) as $char) {
            $count++;
            $preview .= $char;
            
            if ($count >= $size_max and ! in_array($char, array('"', '\\', '`', '(', ')', '[', ']', '_', '*', '#')))
                break;
        }

        $preview .= "\n";

        return $preview;
    }

    /**
     * Remplit l'Article à partir de la requête
     */
    public function handlePostRequest($title, $content, $author_name, $dad) {
        $this->titre = htmlentities($title);
        $this->content = $content;
        $this->author = htmlentities($author_name);
        $this->dad = $dad;

        $this->edited = true;
    }

    /**
     * Génère une version sous forme d'array de l'Article
     *
     * @return array
     */
    public function asArray() {
        $array = array(
            'id' => $this->id,
            'titre' => $this->titre,
            'timestamp' => $this->timestampCreation,
            'content' => $this->content,
            'author' => $this->author,
            'commentaires' => array(),
            'dad' => $this->dad
        );

        foreach($this->commentaires as $commentaire) {
            $array['commentaires'][] = $commentaire->asArray();
        }

        return $array;
    }

    /**
     * Retourne une array composé d'un boolean de validité et d'une array des toutes les erreurs rencontrées
     *
     * @return array
     */
    public function validate() {
        $validation = array(
            'valid' => true,
            'errors' => array()
        );

        if ($this->titre == '') {
            $validation['valid'] = false;
            $validation['errors']['post_titre'] = 'Votre article doit avoir un titre !';
        }
        if ($this->content == '') {
            $validation['valid'] = false;
            $validation['errors']['post_content'] = 'Votre article doit avoir un contenu !';
        }

        return $validation;
    }

    /**
     * Retourne sous forme de string une date formulée de façon sympa
     *
     * @return string
     */
    public function getDisplayableDate() {
        $diff = time() - $this->timestampCreation;
        if ($diff < 120) {
            return 'à l\'instant';
        }
        if ($diff < 3600) {
            return 'Il y a '. date('i', $diff) . ' minutes';
        } elseif ($diff < 86400) {
            if (intval(date('G', $diff)) > 1) {
                return 'Il y a '. date('G', $diff) . ' heures';
            } else {
                return 'Il y a '. date('G', $diff) . ' heure';
            }
        } elseif ($diff < 172800) {
            return 'Hier à '. date('H', $this->timestampCreation) . 'h';
        } else {
            if (intval(date('Y')) != intval(date('Y', $this->timestampCreation))) {
                return date('j', $this->timestampCreation) . ' ' . date('F', $this->timestampCreation) . ' ' . date('Y', $this->timestampCreation) . ', ' . date('H', $this->timestampCreation) . 'h' . date('i', $this->timestampCreation);
            } else {
                return date('j', $this->timestampCreation) . ' ' . date('F', $this->timestampCreation) . ', ' . date('H', $this->timestampCreation) . 'h' . date('i', $this->timestampCreation);
            }
        }
    }

    /**
     * Retourne une array de tous les commentaire rangé par date croissante
     *
     * @return array
     */
    public function getCommentairesSorted() {
        $commentaires = array();
        foreach($this->commentaires as $commentaire) {
            $commentaires[$commentaire->getTimestamp()] = $commentaire;
        }

        # On range le tableaux par ordre de timestamp croissant
        ksort($commentaires);

        # On retourne uniquement les valeurs du tableaux
        return array_values(array_reverse($commentaires));
    }

    public function setId($id) {
        $this->id = $id;

        return $this;
    }

    public function getId() {
        return $this->id;
    }
    
    public function getDad() {
        return $this->dad;
    }

    public function setTitre($titre) {
        $this->titre = $titre;

        return $this;
    }

    public function getTitre() {
        return $this->titre;
    }

    public function setTimestampCreation($timestampCreation) {
        $this->timestampCreation = $timestampCreation;

        return $this;
    }

    public function getTimestampCreation() {
        return $this->timestampCreation;
    }

    public function setTimestampEdition($timestampEdition) {
        $this->timestampEdition = $timestampEdition;

        return $this;
    }

    public function getTimestampEdition() {
        return $this->timestampEdition;
    }

    public function setContent($content) {
        $this->content = $content;

        return $this;
    }

    public function getContent() {
        return $this->content;
    }

    public function addCommentaire(Commentaire $commentaire) {
        $commentaire->setId(count($this->commentaires));
        $this->commentaires[] = $commentaire;
    }

    public function removeCommentaire(Commentaire $commentaire) {
        unset($this->commentaires[$commentaire->getId()]);
        $this->commentaires = array_values($this->commentaires);
    }

    public function getCommentaires() {
        return $this->commentaires;
    }

    public function setEdited($edited) {
        $this->edited = $edited;

        return $this;
    }
    
    public function getAuthor() {
        return $this->author;
    }

    public function getEdited() {
        return $this->edited;
    }

    public function getStoredData() {
        return $this->storedData;
    }
}
