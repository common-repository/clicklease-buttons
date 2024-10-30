<?php
require_once(plugin_dir_path(__FILE__) . './CLSWP_ViewPage.php');
require_once(plugin_dir_path(__FILE__) . '../CLSWP_Button.php');
require_once(plugin_dir_path(__FILE__) . '../services/buttons/builders/CLSWP_ButtonIframeBuilder.php');
require_once(plugin_dir_path(__FILE__) . '../form/validation/CLSWP_ValidatorPages.php');
require_once(plugin_dir_path(__FILE__) . '../form/validation/rules/CLSWP_ZeroValidToken.php');
require_once(plugin_dir_path(__FILE__) . '../form/validation/rules/CLSWP_MaxAmoutStored.php');

if (!class_exists('CLSWP_CategoryPage')) :

    class CLSWP_CategoryPage extends CLSWP_ViewPage
    {
        private bool $isIframeFetched;
        private int $minPrice;
        private CLSWP_ButtonIframeBuilder $clsBtnBuilder;
        private CLSWP_Validate $pagesValidator;


        function __construct(bool $show)
        {
            $this->isIframeFetched = false;
            $this->minPrice = 500;
            parent::__construct();
            $this->clsBtnBuilder = new CLSWP_ButtonIframeBuilder();
            $this->pagesValidator = (new CLSWP_ValidatorPages())
                ->addRule(new CLSWP_NotEmpty('Token error: Token field is mandatory'))
                ->addRule(new CLSWP_ValidToken("Token error: token formatt must be like this xxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx"));
        }

        function isValidaPage(): bool
        {
            global $product;
            $isShownNoPriceProducts = empty($product->get_price()) ? !empty(get_option('clk_is_show_no_price', false)) : true;
            // error_log(print_r('CLSWP_NoPrice: ' . json_encode($isShownNoPriceProducts), true));
            if (!empty($product->get_price())) {
                $this->pagesValidator = $this->pagesValidator->addRule(new CLSWP_MaxAmountStored($product->get_price(), 'Token error: Product Price higher than Max Amount'));
            }
            $isValidToken = $this->pagesValidator->validate(get_option('clk_token'));
            $isValidToken = !isset($isValidToken["errors"]);
            $this->pagesValidator->removeRule();
            return !is_product() && is_plugin_active('woocommerce/woocommerce.php') && $isValidToken && $isShownNoPriceProducts;
        }

        private function createHTML(): array
        {
            global $product;
            $resultArr = [];
            // checks if it should be shown in the category page
            if (!(strlen(get_option('clk_op_is_in_category_page')) > 0)) return $resultArr;
            if (!empty($product->get_price())) {
                if ($product->get_price() < $this->getMinPrice()) return $resultArr;
                $this->clsBtnBuilder
                    ->setBtnType(get_option('clk_op_button_design', 'default-light-1'))
                    ->setToken(get_option('clk_token'))
                    ->setPriceAmount(floatval($product->get_price()));
            } else {
                $this->clsBtnBuilder
                    ->setBtnType(get_option('clk_op_button_design', 'default-light-1'))
                    ->setToken(get_option('clk_token'))
                    ->setPriceAmount(0);
            }
            // if (!$this->isIframeFetched) {
            //     $this->clsBtnBuilder->setIframe(false);
            //     $this->isIframeFetched = true;
            // };
            $this->clsBtnBuilder->setBenefitsPage(get_option('clk_has_benefits_page', false));
            $resultArr['mobile'] = $this->clsBtnBuilder;
            return $resultArr;
        }

        public function render()
        {
            if (!$this->isValidaPage()) return;
            $result = $this->createHTML();
            foreach ($result as $k => $builder) {
                // error_log(print_r('CATEGORY_PAGE->render: ' .  $builder->getBenefitsPage(), true));
                // echo wp_kses('<div class="cls-category-wrapp">' . $builder->toHtml() . '</div>', $builder->getSanatizeMap());
                echo "<div class='cls-category-wrapp'>" . $builder->toHtml() . "</div>";
            }
        }

        public function startCustomScripts(): void
        {
?>
            <script id="cls-render-button">
                try {
                    const importScrCate = setInterval(() => {
                        clsGlobalObj && clsGlobalObj.attachRenderBtnsScript;
                        clearInterval(importScrCate);
                        clsGlobalObj.attachRenderBtnsScript('cls-render-button', '<?php echo CLSWP_EnvConfig::getEnvConfig('gatewayJsdk') . '/static/js/build/cls-jsdk-script.bundle.js' ?>')
                            .then(res => {
                                if (!res) return;
                                console.log("%c===> CLS button was rendered in the Category Page", "background: #1982b7;");
                            })
                    }, 700);
                    setTimeout(() => clearInterval(importScrCate), 20 * 1000)
                } catch (err) {
                    console.log("%cclsGlobalObj is not available yet", "background: #1982b7;");
                }
            </script>
<?php
            parent::getRenderScript('', ['token' => $this->clsBtnBuilder->getToken(), 'withInfoPage' => $this->clsBtnBuilder->getBenefitsPage()]);
        }

        public function execHooks()
        {
            add_action('woocommerce_after_shop_loop_item', [$this, 'render'], 10);
            add_action('woocommerce_after_shop_loop', [$this, 'startCustomScripts'], 100, 1);
            // add_action('woocommerce_before_shop_loop', [$this, 'startCustomScripts'], 100, 1);
            add_action('wp_footer', [$this, 'startCustomScripts'], 100, 1);
        }



        public function getPageName(): string
        {
            return $this->pageName;
        }

        public function getMinPrice(): int
        {
            return $this->minPrice;
        }

        public function setMinPrice(int $minPrice): void
        {
            $this->minPrice = $minPrice;
        }
    }

endif;
