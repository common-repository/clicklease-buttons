<?php

require_once(plugin_dir_path(__FILE__) . 'CLSWP_PropertyValidator.php');
if(! class_exists('CLSWP_MinLenRule')):

class CLSWP_MinLenRule implements CLSWP_PropertyValidator
{
    private string $errMess;
    private string $inVal;
    private int $minVal;

    public function __construct(int $minVal, string $customErrMes = "Value enter is under the valid")
    {
        $this->setErrMess($customErrMes);
        $this->minVal = $minVal;
    }

    public function isValid(): bool
    {
        error_log(print_r('MinLenRule: ' . trim($this->getInputVal()), true));
        if ($this->getInputVal() !== null && intval(trim($this->getInputVal())) >= $this->minVal) {
            return true;
        }
        return false;
    }

    public function setErrMess(string $errMess): void
    {
        $this->errMess = $errMess;
    }

    public function getErrMess(): string
    {
        return $this->errMess;
    }

    public function setInputVal(string $pInVal): void
    {
        $this->inVal = $pInVal;
    }

    public function getInputVal(): string
    {
        return $this->inVal;
    }
}
endif;