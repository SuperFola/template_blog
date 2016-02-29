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
            $filecontent = file_get_contents($this->path);
            $array = json_decode($filecontent, true);
            return $array;
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
            return "";
        }
        
        /**
         * Permet de configurer un champ spécifique de la config
         *
         * @param $key
         */
        public function setConfig($key) {
            $config = $this->getConfig();
            if (isset($config[$key])) {
                // do some stuff
            }
        }
    }
?>