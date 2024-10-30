<?php
if (!class_exists('CLSWP_Validate')) :
    interface CLSWP_Validate
    {
        //Validator
        public function addRule(CLSWP_PropertyValidator $validationRule);
        public function validate($input);
        public function getRules();
        public function setFldId(string $pFldId);
        public function removeRule(int $pos = -1);
    }
endif;
