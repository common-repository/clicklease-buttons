<?php
require_once(plugin_dir_path(__FILE__) . '../../../api/CLSWP_JsdkHandler.php');
require_once(plugin_dir_path(__FILE__) . './CLSWP_IButtonBuilder.php');

if (!class_exists('CLSWP_ButtonIframeBuilder')) :

    class CLSWP_ButtonIframeBuilder implements CLSWP_IButtonBuilder
    {

        private CLSWP_JsdkHandler $jsdkHandlr;
        private string $token;
        private string $btnDesign;
        private float $price;
        private bool $hasIframe;
        private bool $withBenefitsPage;
        private array $extraSanatizeTags;

        public function __construct()
        {
            $this->jsdkHandlr = new CLSWP_JsdkHandler();
            $this->btnDesign = "default-light-1";
            $this->token = '';
            $this->price = 0;
            $this->hasIframe = false;
            $this->withBenefitsPage = false;
            $this->extraSanatizeTags = [];
        }

        public function setToken(string $pToken)
        {
            $this->token = $pToken;
            return $this;
        }
        public function getToken(): string
        {
            return $this->token;
        }
        public function setPartnerLink(string $partnerPage)
        {
            // $this->token = $pToken;
            return $this;
        }
        public function setBtnType(string $pBtnType)
        {
            $this->btnDesign = $pBtnType;
            return $this;
        }
        public function setPriceAmount(float $priceVal)
        {
            $this->price = $priceVal;
            return $this;
        }

        public function setIframe(bool $pHasIframe)
        {
            $this->hasIframe = $pHasIframe;
            return  $this;
        }

        public function setBenefitsPage(bool $pWithBenefitsPage)
        {
            $this->withBenefitsPage = $pWithBenefitsPage;
            return  $this;
        }

        public function getBenefitsPage(): bool
        {
            return $this->withBenefitsPage;
        }

        public function toHTML()
        {
            // $newClsBtn = $this->jsdkHandlr->createButton($this->token, $this->btnDesign, $this->price, $this->hasIframe, $this->withBenefitsPage);
            $priceAttr = $this->price < 1 ? '' : "price = '" . $this->price . "' ";
            $hasBenefitsPage =  strval($this->withBenefitsPage) === '1' ? 'true' : 'false';
            $newClsBtn = "<clk-button source='woocommerce' " . $priceAttr . "desc='apple' button='" . $this->btnDesign . "' token='" . $this->token .  "' withInfoPage='" . $hasBenefitsPage . "'>
                </clk-button>";
            return $newClsBtn;
        }

        public function getSanatizeMap(): array
        {
            $clsBtnArr = [
                "clk-button" => ["id" => [""], "class" => [""], "style" => [""]],
                "iframe" => ["loading" => [""], "src" => [""], "id" => [""], "frameborder" => [""]],
                "script" => ["src" => [""], "async" => [""]],
                "link" => ["rel" => [""], "href" => [""]],
                "span" => ["class" => [""], "id" => [""], "style" => [""]],
                "strong" => ["class" => [""], "id" => [""], "style" => [""]],
                "svg" => ["class" => [""], "id" => [""], "xmlns" => [""], "viewbox" => []],
                "path" => ["class" => [""], "id" => [""], "transform" => [""], "data-name" => [], "d" => [], "fill" => []],
                "div" => ["class" => [""], "id" => [""], "style" => [""]],
            ];
            if (!empty($this->extraSanatizeTags)) {
            }
            return $clsBtnArr;
        }
    }

endif;
