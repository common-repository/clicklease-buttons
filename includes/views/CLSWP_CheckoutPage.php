<?php
require_once(plugin_dir_path(__FILE__) . './CLSWP_ViewPage.php');
require_once(plugin_dir_path(__FILE__) . '../CLSWP_Button.php');
require_once(plugin_dir_path(__FILE__) . '../api/CLSWP_LeaseHandlr.php');
require_once(plugin_dir_path(__FILE__) . '../form/validation/CLSWP_ValidatorPages.php');
require_once(plugin_dir_path(__FILE__) . '../form/validation/rules/CLSWP_ZeroValidToken.php');
require_once(plugin_dir_path(__FILE__) . '../form/validation/rules/CLSWP_MaxAmoutStored.php');
require_once(plugin_dir_path(__FILE__) . '../services/buttons/builders/CLSWP_ButtonIframeBuilder.php');
require_once(plugin_dir_path(__FILE__) . '../../configurations/env_config.php');


if (!class_exists('CLSWP_CheckoutPage')) :

    class CLSWP_CheckoutPage extends CLSWP_ViewPage
    {
        private int $minPrice;
        private CLSWP_ValidatorPages $pagesValidator;
        private CLSWP_ButtonIframeBuilder $clsBtnBuilder;


        public function __construct(int $minPrice = 500)
        {
            $this->minPrice = $minPrice;
            parent::__construct();
        }
        public function execHooks()
        {
            add_action('woocommerce_review_order_before_submit', [$this, 'render'], 40);
            // add_action('woocommerce_after_checkout_form', [$this, 'startCustomScripts'], 100, 1);
            add_action('wp_footer', [$this, 'startCustomScripts'], 100, 1);
            $this->clsBtnBuilder = (new CLSWP_ButtonIframeBuilder());
        }


        function isValidaPage(): bool
        {
            $totalPrice = floatval(WC()->cart->get_cart_contents_total());
            $isValidToken = false;
            $isCheckoutActive = get_option('clk_op_is_at_checkout') == 1;
            if (!empty($totalPrice)) {
                $this->pagesValidator = (new CLSWP_ValidatorPages())
                    ->addRule(new CLSWP_NotEmpty('Token error: Token field is mandatory'))
                    ->addRule(new CLSWP_ValidToken("Token error: token formatt must be like this xxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx"))
                    ->addRule(new CLSWP_ZeroValidToken('Token error: Invalid Token'))
                    ->addRule(new CLSWP_MaxAmountStored($totalPrice, 'Token error: Product Price higher than Max Amount'));
                $isValidToken = $this->pagesValidator->validate(get_option('clk_token'));
                $isValidToken = !isset($isValidToken["errors"]);
            }
            return  is_checkout() && is_plugin_active('woocommerce/woocommerce.php') && $isValidToken && $isCheckoutActive;
        }

        private function createHTML(): array
        {
            $totalPrice = floatval(WC()->cart->get_cart_contents_total());
            if ($totalPrice >= $this->minPrice) {
                $clkDesktopBtnBuilder = $this->clsBtnBuilder;
                $clkDesktopBtnBuilder
                    ->setBtnType(get_option('clk_op_button_design', 'default-light-1'))
                    ->setToken(get_option('clk_token'))
                    ->setBenefitsPage(get_option('clk_has_benefits_page', false))
                    ->setPriceAmount(($totalPrice))
                    ->setIframe(false);
                return [
                    'desktop' => $clkDesktopBtnBuilder,
                ];
            }
            return [];
        }

        public function render()
        {
            if (!$this->isValidaPage()) return;
            $result = $this->createHTML();
            if (!empty($result)) {
                foreach ($result as $k => $builder) {
                    // echo wp_kses($builder->toHtml(), $builder->getSanatizeMap());
                    echo $builder->toHtml();
                }
                echo wp_kses('<script> console.log("MAIN CONTENT HOOK"); </script>',  ['script' => []]);
            }
            return;
            echo wp_kses('<script> console.log("max amount its lower that product price"); </script>',  ['script' => []]);
        }

        public function startCustomScripts()
        {
            if (!is_checkout() or !is_plugin_active('woocommerce/woocommerce.php')) return;
?>
            <script type="text/javascript">
                (function() {
                    const clsRenderInterv = () => setInterval(function() {
                        try {
                            if (!document.querySelector('clk-button') || !clsGlobalObj) return;
                            clearInterval(clsRenderInterv);
                            const createClsBtn = () =>
                                clsGlobalObj.attachRenderBtnsScript('cls-render-button',
                                    '<?php echo CLSWP_EnvConfig::getEnvConfig('gatewayJsdk') ?>' + '/static/js/build/cls-jsdk-script.bundle.js')
                                .then(res => {
                                    if (!res) return;
                                    console.log('====> SCRIPT ATTACHED');
                                    clsGlobalObj.registerClsBtnFuncionality();
                                    window.clsGlobalObj.createClsBtn = createClsBtn;
                                });
                            createClsBtn();
                        } catch (e) {
                            console.log("%c" + e.message, "background: red;");
                        }
                    }, 700);
                    clsRenderInterv();
                })();
            </script>
<?php
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
        public function setTotalPrice(int $totalPrice): void
        {
            $this->totalPrice = $totalPrice;
        }
        public function getTotalPrice(): int
        {
            return $this->totalPrice;
        }
    }
endif;
