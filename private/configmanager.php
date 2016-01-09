<?php
    class ConfigManager {
        public function __construct() {
            $this->path = realpath(__DIR__ . '/../private/config.json');
        }
        
        /**
         * Retourne la configuration
         *
         * @return array
         */
        public function getConfig() {
            return json_decode(file_get_contents($this->path), true);
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