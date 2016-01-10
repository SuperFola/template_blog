<?php
    class PostManager {
        protected $directory;

        public function __construct() {
            $this->directory = realpath(__DIR__.'/../posts');
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
            $files = scandir($this->directory);
            foreach($files as $file) {
                if (preg_match('/^post([0-9]+).json/', $file)) {
                    $post = new Post();
                    $array = $this->getFile($this->directory.'/'.$file);
                    $post->hydrate($array);
                    $posts[$post->getTimestamp()] = $post;
                }
            }

            # On range le tableaux par ordre de timestamp croissant
            ksort($posts);

            # On retourne uniquement les valeurs du tableaux que l'on a préalablement inverser
            return array_values(array_reverse($posts));
        }

        /**
         * Créer un fichier et y stocke $post
         *
         * @param $post
         * @throws Exception
         */
        public function persistPost($post) {
            $post->setId($this->generatePostId());
            $filepath = $this->generateFilepath($post);

            if (is_file($filepath)) {
                throw new Exception('Un post ayant l\'id '.$post->getId().' existe déjà');
            }
            $this->saveFile($filepath, $post->asJSON());
        }

        /**
         * Met à jour le fichier attaché à $post
         *
         * @param $post
         * @throws Exception
         */
        public function updatePost($post) {
            $filepath = $this->generateFilepath($post);

            if (!is_file($filepath)) {
                throw new Exception('Le post ayant l\'id '.$post->getId().' n\'existe pas');
            }
            $this->saveFile($filepath, $post->asJSON());
        }

        /**
         * Supprime fichier associé à $post
         *
         * @param $post
         * @throws Exception
         */
        public function deletePost($post) {
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
        protected $timestamp;
        protected $content;
        protected $categorie;
        protected $storedData;
        protected $commentaires;
        protected $edited;

        public function __construct($id = 0) {
            $this->id = $id;
            $this->timestamp = time();
            $this->commentaires = array();
            $this->edited = false;
        }

        /**
         * @param $array
         */
        public function hydrate($array) {
            $this->id = $array['id'];
            $this->titre = $array['titre'];
            $this->timestamp = $array['timestamp'];
            $this->content = $array['content'];
            $this->categorie = $array['categorie'];
            $this->storedData = $array;
            foreach($array['commentaires'] as $commentaireArray) {
                $commentaire = new Commentaire();
                $commentaire->hydrate($commentaireArray);
                $this->commentaires[] = $commentaire;
            }
        }

        /**
         * Créer un objet post à partir de la requête
         *
         * @return Post
         */
        public function handlePostRequest() {
            $titre = htmlentities($_POST['post_titre']);
            $categorie = htmlentities($_POST['post_categorie']);
            $content = htmlentities($_POST['post_content']);

            $this->titre = $titre;
            $this->categorie = $categorie;
            $this->content = $content;
            $this->edited = true;
        }

        /**
         * Génère une version JSON de Post
         *
         * @return string
         */
        public function asJSON() {
            $json = array(
                'id' => $this->id,
                'titre' => $this->titre,
                'timestamp' => $this->timestamp,
                'content' => $this->content,
                'categorie' => $this->categorie,
                'commentaires' => array()
            );

            foreach($this->commentaires as $commentaire) {
                $json['commentaires'][] = $commentaire->asArray();
            }

            return json_encode($json);
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
            return array_values($commentaires);
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

        public function setTimestamp($timestamp) {
            $this->timestamp = $timestamp;
            
            return $this;
        }
        
        public function getTimestamp() {
            return $this->timestamp;
        }

        public function setContent($content) {
            $this->content = $content;

            return $this;
        }

        public function getContent() {
            return $this->content;
        }
        
        public function getContentPreview() {
            $contentPreview = "";
            $contentPreviewSize = 158;
            $count = 0;
            foreach(array($this->content) as $char) {
                $contentPreview .= $char;
                
                ++$count;
                
                if ($count >= $contentPreviewSize and in_array($char, array(' ', ',', ':', ';', '.', '!', '?'))) {
                    break;
                }
            }
            $contentPreview .= '<br />';
            return $contentPreview;
        }

        public function setCategorie($categorie) {
            $this->categorie = $categorie;
            
            return $this;
        }

        public function addCommentaire(Commentaire $commentaire) {
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

        public function getEdited() {
            return $this->edited;
        }
        
        public function getStoredData() {
            return $this->storedData;
        }
    }

    class Commentaire {
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
         * Créer un objet post à partir de la requête
         *
         * @return Post
         */
        public function handlePostRequest() {
            $pseudo = htmlentities($_POST['post_comment_pseudo']);
            $message = htmlentities($_POST['post_comment_message']);

            $this->pseudo =  $pseudo;
            $this->message = $message;
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

        public function setPseudo($pseudo) {
            $this->pseudo = $pseudo;

            return $this;
        }

        public function getPseudo() {
            return $this->pseudo;
        }

        public function setTimestamp($timestamp) {
            $this->timestamp = $timestamp;

            return $this;
        }

        public function getTimestamp() {
            return $this->timestamp;
        }

        public function setIp($ip) {
            $this->ip = $ip;

            return $this;
        }

        public function  getIp() {
            return $this->ip;
        }

        public function setMessage($message) {
            $this->message = $message;

            return $this;
        }

        public function getMessage() {
            return $this->message;
        }
    }

