<?php

if(! class_exists('CLSWP_PropertyValidator')):

interface CLSWP_PropertyValidator
{
    public function isValid(): bool;
    public function setErrMess(string $errMess): void;
    public function getErrMess(): string;
    public function setInputVal(string $pInVal): void;
    public function getInputVal(): string;
}

endif;