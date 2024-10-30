<?php

require_once(plugin_dir_path(__FILE__) . 'CLSWP_FieldType.php');
require_once(plugin_dir_path(__FILE__) . 'validation/CLSWP_Validator.php');

if (!class_exists('CLSWP_FormSection')) {

    class CLSWP_FormSection
    {

        private $pageName;
        private $secName;
        private $title;
        // private array $formFlds;

        public function __construct(string $pageName, string $section, string $title)
        {
            $this->pageName = $pageName;
            $this->secName = $section;
            $this->title = $title;
        }

        public function setField(string $fldId, CLSWP_FieldType $fldType, CLSWP_Validator $validator)
        {
            $fldType->setFldId($fldId);
            $validator->setFldId($fldId);
            register_setting('randbtn_opts', $fldId, ['sanitize_callback' => array($validator, 'validate'), 'default' => $fldType->getDefault()]);
            add_settings_field($fldId, $fldType->getLabel(), [$fldType, 'render'], $this->pageName, $this->secName);
            return $this;
        }

        public function create()
        {
            add_settings_section($this->secName, $this->title, null, $this->pageName);
        }

        // protected function getFormFields(): array
        // {
        //     return $this->formFlds;
        // }
    }
}
