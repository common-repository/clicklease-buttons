<?php

require_once(plugin_dir_path(__FILE__) . 'rules/CLSWP_PropertyValidator.php');

if(! class_exists('CLSWP_Validator')):

class CLSWP_Validator
{
    private string $fldId;
    private array $rules;
    private string $input;

    public function __construct()
    {
        $this->fldId = '';
        $this->rules = [];
    }

    public function addRule(CLSWP_PropertyValidator $validationRule)
    {
        // error_log(print_r('VALIDATION_LOG', true));
        $crrArr = $this->rules;
        array_push($crrArr, $validationRule);
        $this->rules = $crrArr;
        return $this;
    }

    public function validate($input)
    {
        // error_log(print_r('VALIDATION_LOG_@@@@@' . $input, true));
        foreach ($this->getRules() as $i => $rule) {
            error_log(print_r($input, true));
            $rule->setInputVal($input);
            if (!$rule->isValid()) {
                add_settings_error($this->fldId, ($this->fldId . '-error'), $rule->getErrMess());
                return get_option($this->fldId);
            }
        }
        return $input;
    }

    public function getRules()
    {
        return $this->rules;
    }

    public function setFldId(string $pFldId)
    {
        $this->fldId = $pFldId;
    }
}

endif;
