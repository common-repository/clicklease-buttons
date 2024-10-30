<?php
require_once(plugin_dir_path(__FILE__) . 'CLSWP_PropertyValidator.php');

if(! class_exists('CLSWP_ValidToken')):

class CLSWP_ValidToken implements CLSWP_PropertyValidator
{
    private string $errMess;
    private string $inVal;

    public function __construct(string $customErrMes = "Formatt is invalid")
    {
        $this->setErrMess($customErrMes);
    }

    public function isValid(): bool
    {
        if ($this->getInputVal() !== null) {
            $pattern = "/\w{8}-\w{4}-\w{4}-\w{4}-\w{12}$/i";
            $result = preg_match($pattern, $this->getInputVal());
            if ($result) {
                return true;
            }
        };
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