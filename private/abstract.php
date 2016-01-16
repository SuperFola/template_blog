<?php

abstract class AbstractClass {

    /**
     * Méthode rapide pour obtenir rapidement un attribut à partir de son nom (Ex: depuis le FormBuilder)
     *
     * @param $attribute
     * @return null
     */
    public function get($attribute) {
        if (property_exists($this, $attribute)) {
            return $this->$attribute;
        }

        return null;
    }

    /**
     * Méthode rapide pour obtenir rapidement un attribut à partir de son nom (Ex: depuis le FormBuilder)
     *
     * @param $attribute, $value
     * @return null
     */
    public function set($attribute, $value) {
        if (property_exists($this, $attribute)) {
            $this->$attribute = $value;
        }

        return $this;
    }
}