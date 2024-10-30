<?php

require_once(plugin_dir_path(__FILE__) . 'CLSWP_PropertyValidator.php');

if (!class_exists('CLSWP_MaxAmountStored')) :

    class CLSWP_MaxAmountStored implements CLSWP_PropertyValidator
    {

        private float $productPrice;

        public function __construct(float $productPrice, string $customErrMes = "This field cannot accept zero numbers")
        {
            $this->setErrMess($customErrMes);
            $this->productPrice = $productPrice;
        }

        public function isValid(): bool
        {
            $maxAmount = floatval(get_option('clk_max_amount', ''));
            if (
                empty($maxAmount)
                || $this->productPrice >= $maxAmount
            ) {
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
