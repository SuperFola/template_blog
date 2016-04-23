<?php
function count_news($from){
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

class PostManager {
    protected $directory;

    public function __construct() {
        $this->directory = __DIR__.'/../posts';
        if (!is_dir($this->directory)) {
            mkdir($this->directory);
        }
    }

    /**
     * @param $id
     * @return Post
     */
    public function findPost($id) {
        $post = new Post($id);
        $array = $this->getFile($this->generateFilepath($post));
        $post->hydrate($array);

        return $post;
    }

    /**
     * Retourne une array de tous les posts
     *
     * @return array
     */
    public function findAll() {
        $posts = array();
        $files = count_news($this->directory);
        $i = 1;
        while($i <= $files) {
            if (file_exists($this->directory.'/post'.$i.'.json')) {
                $post = new Post();
                $array = $this->getFile($this->directory.'/post'.$i.'.json');
                $post->hydrate($array);
                $posts[$post->getTimestampCreation()] = $post;
            }
            $i++;
        }

        # On range le tableaux par ordre de timestamp croissant
        ksort($posts);

        # On retourne uniquement les valeurs du tableaux que l'on a préalablement inversé
        return array_values(array_reverse($posts));
    }

    /**
     * Créer un fichier et y stocke $post
     *
     * @param Post $post
     * @throws Exception
     */
    public function persistPost(Post $post) {
        $post->setId($this->generatePostId());
        $filepath = $this->generateFilepath($post);

        if (is_file($filepath)) {
            throw new Exception('Un post ayant l\'id '.$post->getId().' existe déjà');
        }
        $this->saveFile($filepath, json_encode($post->asArray()));
    }

    /**
     * Met à jour le fichier attaché à $post
     *
     * @param Post $post
     * @throws Exception
     */
    public function updatePost(Post $post) {
        $filepath = $this->generateFilepath($post);
        $post->setTimestampEdition(time());

        if (!is_file($filepath)) {
            throw new Exception('Le post ayant l\'id '.$post->getId().' n\'existe pas');
        }
        $this->saveFile($filepath, json_encode($post->asArray()));
    }

    /**
     * Supprime fichier associé à $post
     *
     * @param Post $post
     * @throws Exception
     */
    public function deletePost(Post $post) {
        $filepath = $this->generateFilepath($post);

        if (!is_file($filepath)) {
            throw new Exception('Le post ayant l\'id '.$post->getId().' n\'existe pas');
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
     * Génère le chemin absolu du du fichier du post
     *
     * @param $post
     * @return string
     */
    private function generateFilepath($post) {
        return $this->directory.'/post'.$post->getId().'.json';
    }

    /**
     * Génère un id à partir du nom des fichiers des posts existants
     *
     * @return integer
     */
    private function generatePostId() {
        $post = new Post();
        $post->setId(1);
        while(is_file($this->generateFilepath($post))) {
            $post->setId($post->getId() + 1);
        }

        return $post->getId();
    }
}

class Post {
    protected $id;
    protected $titre;
    protected $timestampCreation;
    protected $timestampEdition;
    protected $content;
    protected $categorie;
    protected $storedData;
    protected $commentaires;
    protected $edited;
    protected $author;

    public function __construct($id = 0) {
        $this->id = $id;
        $this->timestampCreation = time();
        $this->commentaires = array();
        $this->edited = false;
        $author = "Anonyme";
    }

    /**
     * @param $array
     */
    public function hydrate($array) {
        $this->id = $array['id'];
        $this->titre = $array['titre'];
        $this->timestampCreation = $array['timestamp'];
        $this->content = $array['content'];
        $this->categorie = $array['categorie'];
        $this->storedData = $array;
        $this->author = $array['author'];
        foreach($array['commentaires'] as $commentaireArray) {
            $commentaire = new Commentaire();
            $commentaire->hydrate($commentaireArray);
            $this->commentaires[] = $commentaire;
        }
    }

    /**
     * Premet d'obtenir un extrait du debut du texte du Post
     *
     * @return string
     */
    public function getContentPreview() {
        $preview = "";
        $size_max = 158;
        $count = 0;
        
        foreach(str_split($this->content) as $char) {
            $preview .= $char;
            $count++;
            
            if ($count >= $size_max){
                break;
            }
        }

        $preview .= '<br />';

        return $preview;
    }

    /**
     * Remplit le Post à partir de la requête
     */
    public function handlePostRequest($title, $categorie, $content, $author_name) {
        $this->titre = htmlentities($title);
        $this->categorie = htmlentities($categorie);
        $this->content = $content;
        $this->author = htmlentities($author_name);

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
            'content' => $this->content,
            'categorie' => $this->categorie,
            'author' => $this->author,
            'commentaires' => array()
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
        if ($this->categorie == '') {
            $validation['valid'] = false;
            $validation['errors']['post_categorie'] = 'Votre article doit avoir une catégorie !';
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

class Commentaire {
    protected $id;
    protected $pseudo;
    protected $timestamp;
    protected $ip;
    protected $message;
    protected $storedData;

    public function  __construct() {
        $this->timestamp = time();
    }

    public function hydrate($array) {
        $this->pseudo = $array['pseudo'];
        $this->timestamp = $array['timestamp'];
        $this->ip = $array['ip'];
        $this->message = $array['message'];
        $this->storedData = $array;
    }

    /**
     * Remplit le Commentaire à partir de la requête
     */
    public function handlePostRequest($pseudo, $message) {
        $this->pseudo = $pseudo;
        $this->message = $message;
        if (isset($REMOTE_ADDR))
            $this->ip = $REMOTE_ADDR;
        else
            $this->ip = $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Génère une version sous forme d'array de Commentaire
     *
     * @return array
     */
    public function asArray() {
        $array = array(
            'pseudo' => $this->pseudo,
            'timestamp' => $this->timestamp,
            'ip' => $this->ip,
            'message' => $this->message
        );

        return $array;
    }

    /**
     * Retourne une array composé d'un boolean de validaté et d'une array des toutes les erreurs rencontrées
     *
     * @return array
     */
    public function validate() {
        $validation = array(
            'valid' => true,
            'errors' => array()
        );

        if ($this->pseudo == '') {
            $validation['valid'] = false;
            $validation['errors']['post_comment_pseudo'] = 'Vous devez spécifier un pseudo';
        }
        if ($this->message == '') {
            $validation['valid'] = false;
            $validation['errors']['post_comment_message'] = 'Votre message doit avoir un contenu !';
        }

        return $validation;
    }

    /**
     * Retourne sous forme de string une date formulée de façon sympa
     *
     * @return string
     */
    public function getDisplayableDate() {
        $diff = time() - $this->timestamp;
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
            return 'Hier à '. date('H', $this->timestamp) . 'h';
        } else {
            if (intval(date('Y')) != intval(date('Y', $this->timestamp))) {
                return date('j', $this->timestamp) . ' ' . date('F', $this->timestamp) . ' ' . date('Y', $this->timestamp) . ', ' . date('H', $this->timestamp) . 'h' . date('i', $this->timestamp);
            } else {
                return date('j', $this->timestamp) . ' ' . date('F', $this->timestamp) . ', ' . date('H', $this->timestamp) . 'h' . date('i', $this->timestamp);
            }
        }
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Commentaire
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * @param mixed $pseudo
     * @return Commentaire
     */
    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;
        return $this;
    }

    /**
     * @return int
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param int $timestamp
     * @return Commentaire
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     * @return Commentaire
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     * @return Commentaire
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStoredData()
    {
        return $this->storedData;
    }

    /**
     * @param mixed $storedData
     * @return Commentaire
     */
    public function setStoredData($storedData)
    {
        $this->storedData = $storedData;
        return $this;
    }
}

