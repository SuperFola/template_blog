<?php

    class FormBuilder {
        protected $entity;
        protected $templatePath;
        protected $DOMTemplate;
        protected $submitText;

        protected $action;
        protected $method;

        protected $safeForm;
        protected $timeout;

        protected $alerts;
        protected $inputs;

        public function __construct($entity, $options = array()) {
            $this->entity = $entity;
            $this->rows = array();
            $this->errors = array();
            $this->submitText = 'Valider';

            // Récupération des options
            $this->action = (isset($options['action'])) ? $options['action'] : '';
            $this->method = (isset($options['method'])) ? $options['method'] : 'post';

            $this->templatePath = (isset($options['template'])) ? $options['template'] : __DIR__.'/templates/bootstrap_form.html';
            $this->safeForm = (isset($options['safeForm'])) ? $options['safeForm'] : true;
            $this->timeout = (isset($options['timeout'])) ? intval($options['timeout']) : 1800;

            if (!is_file($this->templatePath)) {
                throw new Exception('le fichier de template "'. $this->templatePath .'" n\'existe pas');
            }
            $this->DOMTemplate = new DOMDocument();
            if (!$this->DOMTemplate->loadHTMLFile($this->templatePath)) {
                throw new Exception('le fichier de template "'. $this->templatePath .'" n\'a pas pu être chargé correctement');
            }
        }

        public function add($name, $type, $options) {
            if ($type == 'select') {
                $input = new Select();
            } else {
                $input = new Input();
            }

            // On génére soit un nom du type "entity_type[input_name]" ou si l'entité est null simplement "name"
            $input->setName((gettype($this->entity) == 'object') ? strtolower(get_class($this->entity)).'_'.$name : $name);
            $input->setProperty($name);
            $input->setType($type);
            $input->loadOptions($options);

            $this->inputs[] = $input;

            return $this;
        }

        /**
         * Génére une version HTML du formulaire
         *
         * @return string
         */
        public function renderForm() {
            $DOMForm = $this->createDOMForm();
            $NodeForm = $DOMForm->getElementsByTagName('form')->item(0);

            $DOMTokenInput = $this->createDOMTokenInput();
            $NodeForm->appendChild(
                $NodeForm->ownerDocument->importNode($DOMTokenInput->documentElement, true)
            );

            foreach ($this->alerts as $alert) {
                $DOMAlert = $this->createDOMAlert($alert);

                $NodeForm->appendChild(
                    $NodeForm->ownerDocument->importNode($DOMAlert->documentElement, true)
                );
            }

            foreach($this->inputs as $input) {
                $DOMFormGroup = $this->createDOMFormGroup($input);
                $NodeFormGroup = $DOMFormGroup->getElementsByTagName('div')->item(1);

                if ($input->getType() == 'select') {
                    $DOMInput = $this->createDOMSelect($input);
                } else {
                    $DOMInput = $this->createDOMInput($input);
                }

                $NodeFormGroup->appendChild(
                    $NodeFormGroup->ownerDocument->importNode($DOMInput->documentElement, true)
                );

                $NodeForm->appendChild(
                    $NodeForm->ownerDocument->importNode($DOMFormGroup->documentElement, true)
                );
            }

            $DOMFormFooter = $this->createDOMFormFooter();
            $NodeForm->appendChild(
                $NodeForm->ownerDocument->importNode($DOMFormFooter->documentElement, true)
            );

            if ($this->safeForm) {
                $DOMTokenInput = $this->createDOMTokenInput();
                $NodeForm->appendChild(
                    $NodeForm->ownerDocument->importNode($DOMTokenInput->documentElement, true)
                );
            }

            return $DOMForm->saveHTML();
        }

        public function handleRequest() {
            // On vérifie la méthode employée
            if (strtolower($_SERVER['REQUEST_METHOD']) != strtolower($this->method)) {
                return false;
            }

            if ($this->safeForm) {
                $tokenError = array('name' => '_token', 'type' => 'danger', 'message' => 'Une erreur s\'est produite dans le traitement du formulaire... Veuillez réessayer');

                if (session_status() == PHP_SESSION_DISABLED || session_status() == PHP_SESSION_NONE) {
                    exit('Veuillez activer les session avec session_start en début de page si vous voulez utiliser la fonction de protection des formulaires');
                }
                if (isset($_REQUEST['_token'])) {
                    $token = $_REQUEST['_token'];
                    if ($token == $_SESSION['_token']) {
                        $diff = time() - $_SESSION['_tokentime'];
                        if ($diff > $this->timeout) {
                            $this->alerts[] = array(
                                '_name' => '_token',
                                'type' => 'danger',
                                'message' => 'Vous avez dépassé le delai limite pour valider le formulaire... Veuillez réessayer'
                            );
                        }
                    } else {
                        echo '2';
                        $this->alerts[] = $tokenError;
                    }
                } else {
                    echo '1';
                    $this->alerts[] = $tokenError;
                }
            }

            foreach ($this->inputs as $input) {
                $property = $input->getProperty();
                if (isset($property)) {
                    $value = htmlentities($_REQUEST[$input->getName()]);
                    $this->entity->set($property, $value);
                }
            }

            return true;
        }

        /**
         * @return DOMDocument
         */
        private function createDOMForm() {
            $formContainer = $this->DOMTemplate->getElementById('form');
            $DOMForm = new DOMDocument();
            $formTemplate = new Template($this->DOMInnerHTML($formContainer));

            $formTemplate
                ->set('action', $this->action)
                ->set('method', $this->method);

            $DOMForm->loadHTML(mb_convert_encoding($formTemplate->output(), 'HTML-ENTITIES', 'UTF-8'));

            return $DOMForm;
        }

        /**
         * @param array $alert
         * @return DOMDocument
         */
        private function createDOMAlert($alert) {
            $alertContainer = $this->DOMTemplate->getElementById('alert');
            $DOMAlert = new DOMDocument();
            $alertTemplate = new Template($this->DOMInnerHTML($alertContainer));

            $alertTemplate
                ->set('type', $alert['type'])
                ->set('message', htmlspecialchars($alert['message']))
            ;

            $DOMAlert->loadHTML(mb_convert_encoding($alertTemplate->output(), 'HTML-ENTITIES', 'UTF-8'));

            return  $DOMAlert;
        }

        /**
         * @param Input|Select $input
         * @return DOMDocument
         */
        private function createDOMFormGroup($input) {
            $formGroupContainer = $this->DOMTemplate->getElementById('formgroup');
            $DOMFormGroup = new DOMDocument();
            $formGroupTemplate = new Template($this->DOMInnerHTML($formGroupContainer));

            $formGroupTemplate
                ->set('name', $input->getName())
                ->set('label', ($input->getLabel()) ? $input->getLabel() : $input->getProperty())
                ->set('error', '')
            ;

            $DOMFormGroup->loadHTML(mb_convert_encoding($formGroupTemplate->output(), 'HTML-ENTITIES', 'UTF-8'));

            return  $DOMFormGroup;
        }

        private function createDOMTokenInput() {
            $token = uniqid(rand(), true);
            $tokenTime = time();
            $tokenInput = new Input();

            $_SESSION['_token'] = $token;
            $_SESSION['_tokentime'] = $tokenTime;

            $tokenInput
                ->setName('_token')
                ->setValue($token)
                ->setType('hidden')
                ->setProperty(null)
            ;

            return $this->createDOMInput($tokenInput);
        }

        /**
         * @param Input $input
         * @return DOMDocument
         */
        private function createDOMInput(Input $input) {
            $inputContainer = $this->DOMTemplate->getElementById('input');
            $DOMInput = new DOMDocument('1.0', 'UTF-8');
            $inputTemplate = new Template($this->DOMInnerHTML($inputContainer));

            $attr = '';
            foreach($input->getAttr() as $key => $value) {
                $attr .= $key.'="'.$value.'" "';
            }

            $value = ($input->getValue()) ? $input->getValue() : '';
            if ($input->getProperty()) {
                if (method_exists($this->entity, 'get')) {
                    $value = $this->entity->get($input->getProperty());
                }
            }

            $inputTemplate
                ->set('name', $input->getName())
                ->set('type', $input->getType())
                ->set('class', $input->getClass())
                ->set('attr', $attr)
                ->set('value', $value)
            ;

            $DOMInput->loadHTML(mb_convert_encoding($inputTemplate->output(), 'HTML-ENTITIES', 'UTF-8'));

            return  $DOMInput;
        }

        /**
         * @param Select $select
         * @return DOMDocument
         */
        private function createDOMSelect(Select $select) {
            $selectContainer = $this->DOMTemplate->getElementById('select');
            $DOMSelect = new DOMDocument();
            $selectTemplate = new Template($this->DOMInnerHTML($selectContainer));

            $attr = '';
            foreach($select->getAttr() as $key => $value) {
                $attr .= $key.'="'.$value.'" "';
            }

            $selectTemplate
                ->set('name', $select->getName())
                ->set('type', $select->getType())
                ->set('class', $select->getClass())
                ->set('attr', $attr)
            ;

            $DOMSelect->loadHTML(mb_convert_encoding($selectTemplate->output(), 'HTML-ENTITIES', 'UTF-8'));
            $NodeSelect = $DOMSelect->getElementsByTagName('select')->item(0);

            foreach($select->getChoices() as $id => $value) {
                // TODO : Vérifier si la valeur est sélectionner à partir des attribut de l'entité

                $entityValue = '';
                if (method_exists($this->entity, 'get')) {
                    $entityValue = $this->entity->get($select->getProperty());
                }

                $selected = ($entityValue == $value) ? 'selected' : '';
                $DOMOption = $this->createDOMOption($id, $value, $selected);
                $NodeSelect->appendChild(
                    $NodeSelect->ownerDocument->importNode($DOMOption->documentElement, true)
                );
            }

            return $DOMSelect;
        }

        /**
         * @param $id
         * @param $value
         * @param string $selected
         * @return DOMDocument
         */
        private function createDOMOption($id, $value, $selected = '') {
            $inputContainer = $this->DOMTemplate->getElementById('option');
            $DOMOption = new DOMDocument();
            $optionTemplate = new Template($this->DOMInnerHTML($inputContainer));

            $optionTemplate
                ->set('id',$id)
                ->set('selected', $selected)
                ->set('value', $value);

            $DOMOption->loadHTML(mb_convert_encoding($optionTemplate->output(), 'HTML-ENTITIES', 'UTF-8'));

            return $DOMOption;
        }

        /**
         * @return DOMDocument
         */
        private function createDOMFormFooter() {
            $formFooterContainer = $this->DOMTemplate->getElementById('formfooter');
            $DOMFormFooter = new DOMDocument();
            $formGroupTemplate = new Template($this->DOMInnerHTML($formFooterContainer));

            $formGroupTemplate
                ->set('submit_value', $this->submitText)
            ;

            $DOMFormFooter->loadHTML(mb_convert_encoding($formGroupTemplate->output(), 'HTML-ENTITIES', 'UTF-8'));

            return $DOMFormFooter;
        }

        private function DOMInnerHTML(DOMElement $element) {
            $innerHTML = "";
            $children  = $element->childNodes;

            foreach ($children as $child)
            {
                $innerHTML .= $element->ownerDocument->saveHTML($child);
            }

            return $innerHTML;
        }

        /**
         * @return mixed
         */
        public function getEntity()
        {
            return $this->entity;
        }

        /**
         * @param mixed $entity
         * @return FormBuilder
         */
        public function setEntity($entity)
        {
            $this->entity = $entity;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getAction()
        {
            return $this->action;
        }

        /**
         * @param mixed $action
         * @return FormBuilder
         */
        public function setAction($action)
        {
            $this->action = $action;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getMethod()
        {
            return $this->method;
        }

        /**
         * @param mixed $method
         * @return FormBuilder
         */
        public function setMethod($method)
        {
            $this->method = $method;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getInputs()
        {
            return $this->inputs;
        }

        /**
         * @param mixed $inputs
         * @return FormBuilder
         */
        public function setInputs($inputs)
        {
            $this->inputs = $inputs;
            return $this;
        }

        /**
         * @return string
         */
        public function getSubmitText()
        {
            return $this->submitText;
        }

        /**
         * @param string $submitText
         */
        public function setSubmitText($submitText)
        {
            $this->submitText = $submitText;
        }
    }

    class Template {
        protected $text;
        protected $values;

        public function __construct($text) {
            $this->text = $text;
        }

        public function set($key, $value) {
            $this->values[$key] = $value;

            return $this;
        }

        public function output() {
            $output = $this->text;

            foreach($this->values as $key => $value) {
                $output = str_replace('__'.$key.'__', $value, $output);
            }

            return $output;
        }
    }

    class Input {
        protected $name;
        protected $property;
        protected $type;
        protected $class;
        protected $attr;
        protected $value;
        protected $label;
        protected $labelAttr;


        /**
         * Permet de charger les options en vérifiant l'existence de chaque clé
         *
         * @param array $options
         */
        public function loadOptions(array $options) {
            if (isset($options['class'])) {
                $this->class = $options['class'];
            }
            if (isset($options['attr'])) {
                $this->attr = $options['attr'];
            }
            if (isset($options['label'])) {
                $this->label = $options['label'];
            }
            if (isset($options['labelAttr'])) {
                $this->labelAttr = $options['labelAttr'];
            }
        }

        /**
         * @return mixed
         */
        public function getName()
        {
            return $this->name;
        }

        /**
         * @param mixed $name
         * @return Input
         */
        public function setName($name)
        {
            $this->name = $name;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getProperty()
        {
            return $this->property;
        }

        /**
         * @param mixed $property
         * @return Input
         */
        public function setProperty($property)
        {
            $this->property = $property;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getType()
        {
            return $this->type;
        }

        /**
         * @param mixed $type
         * @return Input
         */
        public function setType($type)
        {
            $this->type = $type;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getClass()
        {
            return $this->class;
        }

        /**
         * @param mixed $class
         * @return Input
         */
        public function setClass($class)
        {
            $this->class = $class;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getAttr()
        {
            return $this->attr;
        }

        /**
         * @param mixed $attr
         * @return Input
         */
        public function setAttr($attr)
        {
            $this->attr = $attr;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getValue()
        {
            return $this->value;
        }

        /**
         * @param mixed $value
         * @return Input
         */
        public function setValue($value)
        {
            $this->value = $value;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getLabel()
        {
            return $this->label;
        }

        /**
         * @param mixed $label
         * @return Input
         */
        public function setLabel($label)
        {
            $this->label = $label;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getLabelAttr()
        {
            return $this->labelAttr;
        }

        /**
         * @param mixed $labelAttr
         * @return Input
         */
        public function setLabelAttr($labelAttr)
        {
            $this->labelAttr = $labelAttr;
            return $this;
        }
    }

    class Select extends Input {
        protected $choices;

        /**
         * Permet de charger les options en vérifiant l'existence de chaque clé
         *
         * @param array $options
         */
        public function loadOptions(array $options) {
            parent::loadOptions($options);
            if (isset($options['choices'])) {
                $this->choices = $options['choices'];
            }
        }

        /**
         * @return mixed
         */
        public function getChoices()
        {
            return $this->choices;
        }

        /**
         * @param mixed $choices
         * @return Select
         */
        public function setChoices($choices)
        {
            $this->choices = $choices;
            return $this;
        }

    }

