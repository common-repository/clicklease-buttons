<?php

require_once(plugin_dir_path(__FILE__) . 'CLSWP_PropertyValidator.php');

if(! class_exists('CLSWP_MaxLenRule')):

class CLSWP_MaxLenRule implements CLSWP_PropertyValidator
{
    private string $errMess;
    private string $inVal;
    private int $maxVal;

    public function __construct(int $maxVal, string $customErrMes = "Value enter is over the expected")
    {
        $this->setErrMess($customErrMes);
        $this->maxVal = $maxVal;
    }

    public function isValid(): bool
    {
        return $this->getInputVal() !== null && intval(trim($this->getInputVal())) <= $this->maxVal;
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