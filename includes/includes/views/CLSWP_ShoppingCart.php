<?php
require_once(plugin_dir_path(__FILE__) . '../CLSWP_Button.php');
require_once(plugin_dir_path(__FILE__) . '../api/CLSWP_LeaseHandlr.php');

if (!class_exists('CLSWP_ShoppingCart')) :

    class CLSWP_ShoppingCart
    {
        private int $minPrice;
        private int $maxPrice;
        private array $posMap;
        private string $crrBtnPos;

        public function __construct(int $minPrice, int $maxPrice, string $eShopPos = 'pos_2')
        {
            $this->minPrice = $minPrice;
            $this->maxPrice = $maxPrice;
            $this->posMap = [
                'pos_0' => ['woocommerce_proceed_to_checkout', 10],
                'pos_1' => ['woocommerce_proceed_to_checkout', 40],
                'pos_2' => ['woocommerce_after_cart_contents', 40],
                'pos_3' => ['woocommerce_cart_coupon', 1],
                'pos_4' => ['woocommerce_cart_actions', 40],
                'pos_5' => ['woocommerce_cart_actions', 40],
            ];
            $this->crrBtnPos = $eShopPos;
            add_action($this->posMap[$eShopPos][0], [$this, 'render'], $this->posMap[$eShopPos][1]);
            add_action('woocommerce_after_cart', [$this, 'startCustomScripts']);
        }

        private function createHTML(): array
        {
            $totalPrice = floatval(WC()->cart->get_cart_contents_total());
            if ($totalPrice >= $this->minPrice  && $totalPrice <= $this->maxPrice && strlen(get_option('clk_op_is_in_cart')) > 0) {
                $clkDesktopBtnBuilder = (new CLSWP_Button(''))
                    ->setPartnerLink(get_option('clk_op_redurl', ''))
                    ->setBtnType(get_option('clk_op_btn_style_desktop', 'btn_8_prim'))
                    ->setVwMode('desktop')
                    ->setPriceAmount(strval($totalPrice))
                    ->setShoppingCartPos(get_option('clk_cart_pos'));
                $clkMobBtnBuilder = (new CLSWP_Button(''))
                    ->setPartnerLink(get_option('clk_op_redurl', ''))
                    ->setBtnType(get_option('clk_op_btn_style_mobile', ''))
                    ->setVwMode('mobile')
                    ->setPriceAmount(strval($totalPrice))
                    ->setShoppingCartPos(get_option('clk_cart_pos'));

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
                echo wp_kses('<script> registerRenderClBtnCart() </script>',  ['script' => []]);
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
