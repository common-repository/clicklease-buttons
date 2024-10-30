<?php
require_once(plugin_dir_path(__FILE__) . '../../configurations/env_config.php');

if (!class_exists('CLSWP_LeaseHandlr')) :
    class CLSWP_LeaseHandlr
    {
        private $api_url;

        function __construct(string $pUrl = "")
        {
            if (empty($pUrl)) $pUrl = CLSWP_EnvConfig::getEnvConfigs()["gatewayBe"];
            $this->api_url  = $pUrl;
        }

        public function getLeaseAmount(int $price): string
        {
            $amount = '';
            $endpoint = $this->api_url . "/estimatedPaymentAmount/" . $price;
            $res = wp_remote_get(esc_url_raw($endpoint));
            $body = wp_remote_retrieve_body($res);
            $bodyArr = json_decode($body, true);
            if (is_array($bodyArr) && isset($bodyArr['code']) && $bodyArr['code'] === '200') {
                $amount =  strval($bodyArr['object']);
            }
            // error_log(print_r('ClLeaseHandlr: ' . $amount, true));
            return $amount;
        }

        public function getMaxApplicationAmount(string $token)
        {
            $maxLeaseAmount = '';
            $maxAmountEndpoint = $this->api_url . "/vendor/info" . "?token=" . $token;
            // error_log(print_r($maxAmountEndpoint, true));
            $response = wp_remote_post(esc_url_raw($maxAmountEndpoint));
            $response = wp_remote_post(esc_url_raw($maxAmountEndpoint), [
                'headers' => [
                    'x-clk-token' => '70B1150811FCA95C5E2D9A92CAE90B2FA91DE2768C47E12D4E2C9D45C948853C61978DA7A6CB6A25',
                    'x-3scale-proxy-secret-token' => '2F2989B64E87F7E7A8B5EEA53073C48EA0F890F4B9663C5A0970C26B9E22C06D9329F7428B8DB28F',
                ]
            ]);
            $bodyRes = wp_remote_retrieve_body($response);
            $bodyAmountArr = json_decode($bodyRes, true);
            // error_log(print_r("getMaxApplicationAmount " . json_encode($bodyAmountArr), true));
            if (is_array($bodyAmountArr) && isset($bodyAmountArr['code']) && $bodyAmountArr['code'] === '200') {
                $maxLeaseAmount = ($bodyAmountArr);
            }

            return $maxLeaseAmount;
        }
    }
endif;
