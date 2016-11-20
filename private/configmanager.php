<?php
class ConfigManager {
    public $path;
    private $config;
    
    public function __construct() {
        $this->path = __DIR__.'/config.json';
        
        if (!is_file($this->path)) {
            $this->persistConfig();
        }
        
        $this->data = "";
        $this->config = $this->getFile($this->path);
    }
    
    /**
     * Retourne la configuration
     *
     * @return array
     */
    public function getConfig() {
        return $this->config;
    }
    
    public function getData() {
        return $this->data;
    }
    
    public function persistConfig() {
        $this->saveFile($this->path, $this->config);
    }
    
    private function saveFile($filepath, $string) {
        $file = fopen($filepath, 'w+');
        fwrite($file, json_encode($string));
        fclose($file);
    }
    
    private function getFile($filepath) {
        $this->data = file_get_contents($filepath);
        return json_decode($this->data, true);
    }
    
    public function getBlogTitle() {
        $config = $this->getConfig();
        if (isset($config['blogtitle'])) {
            return $config['blogtitle'];
        }
        return "Title";
    }
    
    public function getBlogSlogan() {
        $config = $this->getConfig();
        if (isset($config['blogslogan'])) {
            return $config['blogslogan'];
        }
        return "Slogan";
    }
    
    public function getBlogFooter() {
        $config = $this->getConfig();
        if (isset($config['blogfooter'])) {
            return $config['blogfooter'];
        }
        return "Footer";
    }
    
    /**
     * Permet de configurer un champ spécifique de la config
     *
     * @param $key
     */
    public function setConfigKey($key, $value) {
        $config = $this->getConfig();
        $this->config[$key] = $value;
    }
    
    public function setConfig($content) {
        $this->config = json_decode($content, true);
        $this->persistConfig();
    }
}
?>