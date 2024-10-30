<?php

require_once(plugin_dir_path(__FILE__) . 'CLSWP_PropertyValidator.php');

if(! class_exists('CLSWP_NotEmpty')):

class CLSWP_NotEmpty implements CLSWP_PropertyValidator
{
    private string $errMess;
    private string $inVal;

    public function __construct(string $customErrMes = "This field cannot be empty")
    {
        $this->setErrMess($customErrMes);
    }

    public function isValid(): bool
    {
        if ($this->getInputVal() === null || strlen(trim($this->getInputVal())) === 0) {
            return false;
        }
        return true;
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
