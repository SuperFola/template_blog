<?php
    class ConfigManager {
        public $path;
        
        public function __construct() {
            $this->path = __DIR__.'/../private/config.json';
        }
        
        /**
         * Retourne la configuration
         *
         * @return array
         */
        public function getConfig() {
            return json_decode(file_get_contents($this->path), true);
        }
        
        public function getBlogTitle() {
            if (isset($this->getConfig()['blogtitle'])) {
                return $this->getConfig()['blogtitle'];
            }
            return "Title";
        }
        
        public function getBlogSlogan() {
            if (isset($this->getConfig()['blogslogan'])) {
                return $this->getConfig()['blogslogan'];
            }
            return "Slogan";
        }
        
        public function getBlogFooter() {
            if (isset($this->getConfig()['blogfooter'])) {
                return $this->getConfig()['blogfooter'];
            }
            return "";
        }
        
        /**
         * Permet de configurer un champ spécifique de la config
         *
         * @param $key
         */
        public function setConfig($key) {
            if (isset($this->getConfig()[$key])) {
                // do some stuff
            }
        }
    }
?>