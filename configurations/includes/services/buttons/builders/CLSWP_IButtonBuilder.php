<?php

if (!interface_exists('CLSWP_IButtonBuilder')) :

    interface CLSWP_IButtonBuilder
    {
        public function setPartnerLink(string $partnerPage);
        public function setBtnType(string $pBtnType);
        public function setPriceAmount(float $priceVal);
    }

endif;
