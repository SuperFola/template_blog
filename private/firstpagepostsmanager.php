<?php
class FirstPagePostsManager {
    protected $posts_details;
    protected $max_posts;
    protected $directory;
    protected $filename;
    
    public function __construct() {
        $posts_details = array();
        $max_posts = 4;
        $this->directory = __DIR__.'/config';
        $this->filename = 'posts.json';
        
        $filepath = $this->directory.'/'.$this->filename;

        if (!is_dir($this->directory)) {
            mkdir($this->directory);
        }
        if (!is_file($filepath)) {
            $this->persistPostsList();
        }

        $this->users = $this->getFile($filepath);
    }
    
    public function getPost($number) {
        if ($number >= 0 && $number < $max_posts) {
            return $this->posts_details[$number];
        }
        
        return null;
    }
    
    public function hydrate($array) {
        if (count($posts_details) < $max_posts) {
            $posts_details[]['id'] = $array['post_id'];
            $post_details[]['image'] = $array['post_image'];
        } else {
            $post_details[0]['id'] = $array['post_id'];
            $post_details[0]['image'] = $array['post_image'];
        }
    }
    
    private function persistPostsList() {
        $filepath = $this->directory.'/'.$this->filename;

        if (is_file($filepath)) {
            throw new Exception('Le fichier "'. $filepath .' existe deja...');
        }

        $this->saveFile($filepath, serialize($this->users));
    }
    
    private function saveFile($filepath, $string) {
        $file = fopen($filepath, 'w+');
        fwrite($file, $string);
        fclose($file);
    }
    
    private function getFile($filepath) {
        return unserialize(file_get_contents($filepath));
    }
} 
?>