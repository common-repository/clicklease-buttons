<?php
require_once(plugin_dir_path(__FILE__) . './CLSWP_ViewPage.php');
require_once(plugin_dir_path(__FILE__) . '../api/CLSWP_LeaseHandlr.php');
require_once(plugin_dir_path(__FILE__) . '../CLSWP_Button.php');
require_once(plugin_dir_path(__FILE__) . '../form/validation/CLSWP_ValidatorPages.php');
require_once(plugin_dir_path(__FILE__) . '../form/validation/rules/CLSWP_ZeroValidToken.php');
require_once(plugin_dir_path(__FILE__) . '../form/validation/rules/CLSWP_MaxAmoutStored.php');
require_once(plugin_dir_path(__FILE__) . '../services/buttons/builders/CLSWP_IButtonBuilder.php');
require_once(plugin_dir_path(__FILE__) . '../services/buttons/builders/CLSWP_ButtonIframeBuilder.php');
require_once(plugin_dir_path(__FILE__) . '../../configurations/env_config.php');

if (!class_exists('CLSWP_ProductPage')) :

    class CLSWP_ProductPage extends CLSWP_ViewPage
    {
        private int $minPrice;
        private int $maxPrice;
        private bool $isRenderBtnExec;
        private CLSWP_ValidatorPages $pagesValidator;
        private CLSWP_ButtonIframeBuilder $clsBtnBuilder;

        public function __construct(int $minPrice = 500, int $maxPrice = 15000, string $eShopPos = 'pos_2')
        {
            $this->isRenderBtnExec = false;
            $this->setMinPrice($minPrice);
            $this->setMaxPrice($maxPrice);
            parent::__construct();
            $this->clLeaseHandlr = new CLSWP_LeaseHandlr();
            $this->clsBtnBuilder = (new CLSWP_ButtonIframeBuilder());
        }

        public function execHooks()
        {
            add_action('woocommerce_single_product_summary', [$this, 'render'], intval(get_option('clk_op_position', 10)));
            $hookPosPriority = 30;
            add_action('woocommerce_before_add_to_cart_quantity', function ($args) use ($hookPosPriority) {
                $this->tryRenderBtn($hookPosPriority);
            }, 10, 1);
            $hookPosPriority = 40;
            add_action('woocommerce_after_add_to_cart_button', function ($args) use ($hookPosPriority) {
                $this->tryRenderBtn($hookPosPriority);
            }, 10,  1);
            // woocommerce_after_single_product
            add_action('woocommerce_after_single_product', function () {
?>
                <script id="cls-render-button">
                    try {
                        const importScrProd = setInterval(() => {
                            clsGlobalObj && clsGlobalObj.attachRenderBtnsScript;
                            clearInterval(importScrProd);
                            clsGlobalObj.attachRenderBtnsScript('cls-render-button', '<?php echo CLSWP_EnvConfig::getEnvConfig('gatewayJsdk') . '/static/js/build/cls-jsdk-script.bundle.js' ?>')
                                .then(res => {
                                    if (!res) return;
                                    clsGlobalObj.registerClsBtnFuncionality();
                                    console.log("%c===> CLS button was rendered in the Product Page", "background: #1982b7;");
                                })
                        }, 700);
                        setTimeout(() => clearInterval(importScrProd), 20 * 1000)
                    } catch (err) {
                        console.log("%cclsGlobalObj is not available yet", "background: #1982b7;");
                    }
                </script>
            <?php
            });
            add_action('wp_footer', [$this, 'startCustomScripts'], 100, 1);
        }

        function isValidaPage(): bool
        {
            global $product;
            $checkNoPriceProducts = get_option('clk_is_show_no_price', false);
            $this->pagesValidator = (new CLSWP_ValidatorPages())
                ->addRule(new CLSWP_NotEmpty('Token error: Token field is mandatory'))
                ->addRule(new CLSWP_ValidToken("Token error: token formatt must be like this xxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx"));
            if (!empty($product->get_price())) {
                $this->pagesValidator = $this->pagesValidator->addRule(new CLSWP_MaxAmountStored($product->get_price(), 'Token error: Product Price higher than Max Amount'));
                $isValidToken =  $this->pagesValidator->validate(get_option('clk_token'));
            } else {
                $isValidToken =  $checkNoPriceProducts
                    ? $this->pagesValidator->validate(get_option('clk_token'))
                    : ["errors" => []];
                // error_log(print_r('CLSWP_NoPrice: ' . json_encode($checkNoPriceProducts), true));
            }
            $isValidToken = !isset($isValidToken["errors"]);

            return is_product() && is_plugin_active('woocommerce/woocommerce.php') && $isValidToken;
        }

        private function createHTML(): array
        {
            global $product;
            $resArr = [];
            if (!empty($product->get_price())) {
                if ($product->get_price() < $this->getMinPrice()) return $resArr;

                $this->clsBtnBuilder
                    ->setBtnType(get_option('clk_op_button_design', 'default-light-1'))
                    ->setToken(get_option('clk_token'))
                    ->setPriceAmount(floatval($product->get_price()));
            } else {
                $this->clsBtnBuilder = (new CLSWP_ButtonIframeBuilder())
                    ->setBtnType(get_option('clk_op_button_design', 'default-light-1'))
                    ->setToken(get_option('clk_token'));
            }
            $this->clsBtnBuilder
                ->setBenefitsPage(get_option('clk_has_benefits_page', false))
                ->setIframe(false);
            $resArr = ['desktop' => $this->clsBtnBuilder,];
            return $resArr;
        }

        public function render()
        {
            if (!$this->isValidaPage()) return;
            $result = $this->createHTML();
            if (!empty($result)) {
                foreach ($result as $k => $builder) {
                    echo $builder->toHtml();
                }
                echo wp_kses('<script> console.log("MAIN CONTENT HOOK"); </script>',  ['script' => []]);
            }
            return;
        }

        public function renderBtnManually()
        {
            error_log(print_r('Doesnt executed woocommerce_single_product_summary hook', true));
            ?>
            <script>
                const btnsBluePrintTag = `<?php
                                            foreach ($this->createHTML() as $k => $builder) {
                                                echo $builder->toHtml();
                                            } ?>`;
                const posPriorityVal = '<?php echo esc_js(get_option('clk_op_position', 10))  ?>';
                var clsRenderInterval = setInterval(function() {
                    try {
                        clsGlobalObj.renderBtnsManually(btnsBluePrintTag, posPriorityVal);
                        clearInterval(clsRenderInterval);
                    } catch (e) {
                        console.log("%c" + e.message, "background: red;");
                    }
                }, 500);
            </script>
<?php
        }

        public function startCustomScripts(): void
        {
            if (!is_product() || !is_plugin_active('woocommerce/woocommerce.php')) return;
            $isExecWcPosHook = did_action('woocommerce_single_product_summary') > 0;
            if (!$isExecWcPosHook && !$this->isRenderBtnExec) {
                $this->renderBtnManually();
            }
            if (is_product()) parent::getRenderScript('', ['token' => $this->clsBtnBuilder->getToken(), 'withInfoPage' => $this->clsBtnBuilder->getBenefitsPage()]);
        }

        function tryRenderBtn($pHookPosPriority): void
        {
            if (!is_product() && is_plugin_active('woocommerce/woocommerce.php')) return;
            $posPriorityVal = intval(get_option('clk_op_position', 10));
            if (did_action('woocommerce_single_product_summary') == 0 && !$this->isRenderBtnExec) {
                if ($pHookPosPriority ==  $posPriorityVal) {
                    $this->render();
                    $this->isRenderBtnExec = true;
                }
            }
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

        public function setMaxPrice(int $maxPrice): void
        {
            $this->maxPrice = $maxPrice;
        }

        public function getMaxPrice(): int
        {
            return $this->maxPrice;
        }
    }

endif;
