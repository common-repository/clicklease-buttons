<?php
require_once(plugin_dir_path(__FILE__) . '../CLSWP_Button.php');
require_once(plugin_dir_path(__FILE__) . '../api/CLSWP_LeaseHandlr.php');

if (!class_exists('CLSWP_CategoryPage')) :

    class CLSWP_CategoryPage
    {
        private int $showBtn;
        private int $minPrice;
        private int $maxPrice;

        function __construct(bool $show)
        {
            $this->showBtn = $show;
            $this->minPrice = 500;
            $this->maxPrice = intval(get_option('clk_op_max_price'));
            add_action('woocommerce_after_shop_loop_item', [$this, 'render'], 10);
            add_action('woocommerce_after_shop_loop', [$this, 'startCustomScripts'], 10, 1);
        }


        private function createHTML(): array
        {
            global $product;
            if (
                $product->get_price() >= $this->getMinPrice()
                && $product->get_price() <= $this->getMaxPrice()
            ) {
                $clkMobBtnBuilder = (new CLSWP_Button(''))
                    ->setPartnerLink(get_option('clk_op_redurl', ''))
                    ->setBtnType(get_option('clk_op_btn_style_mobile', 'btn_8_prim'))
                    ->setVwMode('mobile')
                    ->setPriceAmount(strval($product->get_price()))
                    ->setMobAlways(true);
                //
                return [
                    'mobile' => $clkMobBtnBuilder
                ];
            }
            return [];
        }

        public function render()
        {
            $result = $this->createHTML();
            if (!is_product() && is_plugin_active('woocommerce/woocommerce.php')) {
                foreach ($result as $k => $builder) {
                    echo wp_kses('<div class="clLinkWrapp--center">' . $builder->toHtml() . '</div>', $builder->getSanatizeMap());
                }
            }
        }

        public function startCustomScripts(): void
        {
            if (!is_product() && is_plugin_active('woocommerce/woocommerce.php')) {
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
