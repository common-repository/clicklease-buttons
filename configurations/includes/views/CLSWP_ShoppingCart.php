<?php
require_once(plugin_dir_path(__FILE__) . './CLSWP_ViewPage.php');
require_once(plugin_dir_path(__FILE__) . '../CLSWP_Button.php');
require_once(plugin_dir_path(__FILE__) . '../api/CLSWP_LeaseHandlr.php');
require_once(plugin_dir_path(__FILE__) . '../form/validation/CLSWP_ValidatorPages.php');
require_once(plugin_dir_path(__FILE__) . '../form/validation/rules/CLSWP_ZeroValidToken.php');
require_once(plugin_dir_path(__FILE__) . '../form/validation/rules/CLSWP_MaxAmoutStored.php');

if (!class_exists('CLSWP_ShoppingCart')) :

    class CLSWP_ShoppingCart extends CLSWP_ViewPage
    {
        private int $minPrice;
        private array $posMap;
        private CLSWP_ButtonIframeBuilder $clsBtnBuilder;


        public function __construct(int $minPrice = 500, string $eShopPos = 'pos_2')
        {
            $this->minPrice = $minPrice;
            $this->posMap = [
                'pos_0' => ['woocommerce_proceed_to_checkout', 40],
                'pos_1' => ['woocommerce_proceed_to_checkout', 10],
                'pos_2' => ['woocommerce_after_cart_contents', 40],
                'pos_3' => ['woocommerce_cart_coupon', 1],
                'pos_4' => ['woocommerce_cart_actions', 40],
                'pos_5' => ['woocommerce_cart_actions', 40],
            ];
            $this->crrBtnPos = $eShopPos;
            if (empty($eShopPos)) $eShopPos = 'pos_3';
            add_action($this->posMap[$eShopPos][0], [$this, 'render'], $this->posMap[$eShopPos][1]);
            parent::__construct();
            $this->clLeaseHandlr = new CLSWP_LeaseHandlr();
            $this->clsBtnBuilder = (new CLSWP_ButtonIframeBuilder());
        }

        function isValidaPage(): bool
        {
            global $totalPrice;
            $this->pagesValidator = (new CLSWP_ValidatorPages())
                ->addRule(new CLSWP_NotEmpty('Token error: Token field is mandatory'))
                ->addRule(new CLSWP_ValidToken("Token error: token formatt must be like this xxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx"))
                ->addRule(new CLSWP_ZeroValidToken('Token error: Invalid Token'))
                ->addRule(new CLSWP_MaxAmountStored($totalPrice = floatval(WC()->cart->get_cart_contents_total()), 'Token error: Product Price higher than Max Amount'));
            //$isMaxAmountVal=
            $isValidToken = $this->pagesValidator->validate(get_option('clk_token'));
            $isValidToken = !isset($isValidToken["errors"]);
            return is_cart() && $isValidToken;
        }

        private function createHTML(): array
        {
            $totalPrice = floatval(WC()->cart->get_cart_contents_total());
            $clkDesktopBtnBuilder = $this->clsBtnBuilder;
            if ($totalPrice >= $this->minPrice  && strlen(get_option('clk_op_is_in_cart')) > 0) {
                $clkDesktopBtnBuilder
                    ->setBtnType(get_option('clk_op_button_design', 'default-light-1'))
                    ->setToken(get_option('clk_token'))
                    ->setPriceAmount(floatval($totalPrice))
                    ->setBenefitsPage(get_option('clk_has_benefits_page', false))
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
                return;
            }
            echo wp_kses('<script> console.log("max amount its lower that product price"); </script>',  ['script' => []]);
        }

        public function startCustomScripts()
        {
            if (!is_cart()) return;
            $cart = WC()->cart;
            error_log(print_r('Shopping Cart ====>>>>  ' . json_encode($cart->get_cart()), true));
            parent::getRenderScript('', ['token' => $this->clsBtnBuilder->getToken(), 'withInfoPage' => $this->clsBtnBuilder->getBenefitsPage()])
?>
            <script type="text/javascript">
                const clsRenderIntervalCart = setInterval(function() {
                    try {
                        if (document.querySelector('#cls-btn')) {
                            clsGlobalObj.registerClsBtnFuncionality();
                            clsGlobalObj.registerBtnCustomPositioning('<?php echo esc_js($this->crrBtnPos) ?>');
                            clearInterval(clsRenderIntervalCart);
                        }
                    } catch (e) {
                        console.log("%c" + e.message, "background: red;");
                    }
                }, 500);
            </script>
            <?php
        }

        public function execHooks()
        {
            add_action('woocommerce_after_cart', function () {
            ?>
                <script id="cls-render-button-cart">
                    try {
                        const importScrCart = setInterval(() => {
                            clsGlobalObj && clsGlobalObj.attachRenderBtnsScript;
                            clearInterval(importScrCart);
                            clsGlobalObj.blueprintTag = document.querySelector('clk-button');
                            clsGlobalObj.attachRenderBtnsScript('cls-render-button', '<?php echo CLSWP_EnvConfig::getEnvConfig('gatewayJsdk') . '/static/js/build/cls-jsdk-script.bundle.js' ?>')
                                .then(res => {
                                    if (!res) return;
                                    console.log("%c===> CLS button was rendered in Shopping Cart Page", "background: #1982b7;");
                                })
                        }, 700);
                        setTimeout(() => clearInterval(importScrCart), 20 * 1000)
                    } catch (err) {
                        console.log("%cclsGlobalObj is not available yet", "background: #1982b7;");
                    }
                </script>
<?php
            }, 100, 1);
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
