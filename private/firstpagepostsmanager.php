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

        $this->posts_details = $this->getFile($filepath);
    }
    
    public function getSize() {
        return count($this->posts_details);
    }
    
    public function getPost($number) {
        if ($number >= 0 && $number < $this->getSize()) {
            return $this->posts_details[$number];
        }
        
        return null;
    }
    
    public function findAll() {
        return $this->posts_details;
    }
    
    public function hydrate($array) {
        if (count($this->posts_details) < $this->max_posts) {
            $this->posts_details[]['id'] = $array['post_id'];
            $this->posts_details[]['image'] = $array['post_image'];
        } else {
            $this->posts_details[3] = $this->posts_details[2];
            $this->posts_details[2] = $this->posts_details[1];
            $this->posts_details[1] = $this->posts_details[0];
            
            $this->posts_details[0]['id'] = $array['post_id'];
            $this->posts_details[0]['image'] = $array['post_image'];
        }
    }
    
    public function deletePostNumber($number) {
        if ($number >= 0 && $this->getSize() > 0 && $number < $this->getSize()) {
            unset($this->posts_details[$number]);
            return true;
        }
        return false;
    }
    
    public function persistPostsList() {
        $filepath = $this->directory.'/'.$this->filename;

        $this->saveFile($filepath, serialize($this->posts_details));
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