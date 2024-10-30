<?php
// require_once(plugin_dir_path(__FILE__) . '../CLSWP_Button.php');
require_once(plugin_dir_path(__FILE__) . '../api/CLSWP_LeaseHandlr.php');
require_once(plugin_dir_path(__FILE__) . '../CLSWP_Button.php');

if (!class_exists('CLSWP_ProductPage')) :

    class CLSWP_ProductPage
    {
        private int $minPrice;
        private int $maxPrice;

        public function __construct(int $minPrice = 500, int $maxPrice = 15000, string $eShopPos = 'pos_2')
        {
            $this->setMinPrice($minPrice);
            $this->setMaxPrice($maxPrice);
            add_action('woocommerce_single_product_summary', [$this, 'ifProductPage'], intval(get_option('clk_op_position', 10)));
        }

        function ifProductPage()
        {
            if (is_product() && is_plugin_active('woocommerce/woocommerce.php')) {
                return $this->render();
            } else {
                echo '<script>console.log("must not render CL button");</script>';
            }
        }

        private function createHTML(): array
        {
            global $product;
            if (
                $product->get_price() >= $this->getMinPrice()
                && $product->get_price() <= $this->getMaxPrice()
            ) {
                $redirectUrl = get_option('clk_op_redurl', '');
                $clkDesktopBtnBuilder = (new CLSWP_Button(''))
                    ->setPartnerLink($redirectUrl)
                    ->setBtnType(get_option('clk_op_btn_style_desktop'))
                    ->setVwMode('desktop')
                    ->setPriceAmount(strval($product->get_price()));
                $clkMobBtnBuilder = (new CLSWP_Button(''))
                    ->setPartnerLink($redirectUrl)
                    ->setBtnType(get_option('clk_op_btn_style_mobile'))
                    ->setVwMode('mobile')
                    ->setPriceAmount(strval($product->get_price()));
                //
                return [
                    'desktop' => $clkDesktopBtnBuilder,
                    'mobile' => $clkMobBtnBuilder,
                ];
            }
            return [];
        }

        public function render()
        {
            $result = $this->createHTML();
            if (!empty($result)) {
                foreach ($result as $k => $builder) {
                    echo wp_kses($builder->toHtml(), $builder->getSanatizeMap());
                }
                echo wp_kses('<script> registerRenderSvg() </script>',  ['script' => []]);
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
