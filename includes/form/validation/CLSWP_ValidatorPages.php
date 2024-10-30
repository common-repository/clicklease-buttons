<?php

require_once(plugin_dir_path(__FILE__) . 'rules/CLSWP_PropertyValidator.php');

if(! class_exists('CLSWP_ValidatorPages')):

class CLSWP_ValidatorPages implements CLSWP_Validate
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
        $crrArr = $this->rules;
        array_push($crrArr, $validationRule);
        $this->rules = $crrArr;
        return $this;
    }

    public function validate($input)
    {
        $result = [];
        foreach ($this->getRules() as $i => $rule) {
            $rule->setInputVal($input);
            //error_log(print_r("Bellacooooo ".json_encode($rule->isValid()), true));
            if (!$rule->isValid()) {
                ///return get_option($this->fldId);
                if(!isset( $result["errors"])){
                    $result["errors"]=[];
                }
                array_push($result["errors"], $rule->getErrMess());
            }

        }
        return $result;
    }

    public function getRules(): array
    {
        return $this->rules;
    }

    public function removeRule(int $pos =-1) {
        if($pos < 0) array_pop($this->rules);
        else unset($this->rules[$pos]); 
    }

    public function setFldId(string $pFldId)
    {
        $this->fldId = $pFldId;
    }
}

endif;
