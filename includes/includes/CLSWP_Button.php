<?php
require_once(plugin_dir_path(__FILE__) . 'api/CLSWP_LeaseHandlr.php');

if (!class_exists('CLSWP_Button')) :

    class CLSWP_Button
    {
        private string $svgUrl;
        private string $partnerLink;
        private string $btnType;
        private string $leaseAmount;
        private string $btnPos;
        private string $vwMode;
        private bool $isMobAlways;
        private array $btnSettings;

        public function __construct(string $newSvgUrl)
        {
            $this->svgUrl = $newSvgUrl;
            // Default values for optional values
            $this->partnerLink = '';
            $this->btnType = 'btn_8';
            $this->leaseAmount = '0';
            $this->btnPos = '';
            $this->btnSettings = [];
        }

        public function setPartnerLink(string $newPartnerLink)
        {
            $this->partnerLink = $newPartnerLink;
            $this->btnSettings['cl-redirect-url'] = $this->partnerLink;
            return $this;
        }

        public function setBtnType(string $eBtnType)
        {
            $this->btnType = $eBtnType;
            $this->btnSettings['cl-btn-type'] =  $eBtnType;
            return $this;
        }

        public function setCalcLeaseAmount(string $leaseVal)
        {
            $clLeaseHandlr = new CLSWP_LeaseHandlr('https://app.clicklease.com/api');
            $this->leaseAmount = $clLeaseHandlr->getLeaseAmount($leaseVal);
            return $this;
        }

        public function setPriceAmount(string $leaseVal)
        {
            $this->leaseAmount = $leaseVal;
            $this->btnSettings['cl-data-leasing'] = $leaseVal;
            return $this;
        }
        /**
         * sets view port size of the button
         * @param $vwMode values can be desktop or mobile
         */
        public function setVwMode(string $vwMode)
        {
            $this->vwMode = $vwMode;
            $this->btnSettings['id'] = 'clicklease-button-' . $vwMode;
            $this->btnSettings['cl-vw-mode'] =  $vwMode;
            return $this;
        }
        /**
         * Set an attr for the html tag generated that indicates if mobile version should
         * alway be enable
         * @param isAlwaysMob inticated if mob svg should always be used
         */
        public function setMobAlways(bool $isAlwayMob)
        {
            $this->isMobAlways = $isAlwayMob;
            $this->btnSettings['cl-mobile-always'] = esc_attr($isAlwayMob);
            return $this;
        }
        /**
         * sets attr for the html tag generated indicating the shopping cart position code
         * that is using to place the item in there
         * @param $wpPosCode code used by wp to position item with an action hook
         */
        public function setShoppingCartPos(string $wpPosCode)
        {
            $this->btnSettings['cl-cart-pos'] = esc_attr($wpPosCode);
            return $this;
        }
        /**
         * sets attr for the html tag generated indicating the product cart position code
         * @param newBtnPos code used by wp to position item with an action hook
         */
        public function setProductPos(string $newBtnPos = '')
        {
            $this->btnPos = $newBtnPos;
            $this->btnSettings['cl-cart-pos'] = esc_attr($newBtnPos);
            return $this;
        }
        /**
         * Return options used to build clk buttun blueprint
         * @return array of options used to build button blueprint 
         */
        public function getSanatizeMap(): array
        {
            $escInfoMap = [
                'div' => ['style' => [''], 'class' => []],
            ];
            foreach ($this->btnSettings as $kAttr => $value) {
                $escInfoMap['div'][$kAttr] = [];
            }
            return $escInfoMap;
        }
        /**
         * Generates an html tag with values store in btnSettings to create attrs
         * from every value in there
         */
        public function toHTML()
        {
            $clBtnRef = '<div style="display:none;" ';
            foreach ($this->btnSettings as $kAttr => $value) {
                $clBtnRef .= $kAttr . '="' . $value . '" ';
            }
            $clBtnRef .= '></div>';
            return $clBtnRef;
        }
    }

endif;
