<?php

require_once(plugin_dir_path(__FILE__) . 'CLSWP_PropertyValidator.php');
require_once(plugin_dir_path(__FILE__) . '../../../api/CLSWP_LeaseHandlr.php');

if (!class_exists('CLSWP_ZeroValidToken')) :

    class CLSWP_ZeroValidToken implements CLSWP_PropertyValidator
    {
        private string $token;
        private string $errMess;
        private string $inVal;
        protected CLSWP_LeaseHandlr $clLeaseHandlr;

        public function __construct(string $customErrMes = "This field cannot accept zero numbers")
        {
            $this->setErrMess($customErrMes);
            $this->clLeaseHandlr = new CLSWP_LeaseHandlr();
        }

        public function isValid(): bool

        {
            $vendorInfoMap = $this->clLeaseHandlr->getMaxApplicationAmount($this->getInputVal());
            if (empty($vendorInfoMap) || !isset($vendorInfoMap['object']) || $vendorInfoMap['code'] != 200) {
                return false;
            }
            add_option('clk_max_amount', floatval($vendorInfoMap['object']['maxApplicationAmount']));
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
