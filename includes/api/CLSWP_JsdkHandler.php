<?php

require_once(plugin_dir_path(__FILE__) . '../../configurations/env_config.php');

if (!class_exists('CLSWP_JsdkHandler')) :
    class CLSWP_JsdkHandler
    {
        private $api_url;

        function __construct(string $pUrl = '')
        {
            if (empty($pUrl)) $pUrl = CLSWP_EnvConfig::getEnvConfigs()["gatewayJsdk"];
            $this->api_url  = $pUrl;
        }

        public function createButton(string $token, string $btnDesign, float $price, bool $iframe = false, bool $withBenefitPage = false)
        {
            $endpoint = $this->api_url . "/buttons?";
            $response = [];
            //TODO: should be an array of query params instead of few specifc params
            if ($iframe) $endpoint = $endpoint . 'iframe=' . ($iframe ? 'true' : 'false');
            $reqBody =  [
                "description" => "description",
                "button" => $btnDesign,
                "token" => $token,
                "withInfoPage" =>  $withBenefitPage
            ];
            if ($price > 0) $reqBody["price"] = $price;
            $res = wp_remote_post(esc_url_raw($endpoint), [
                'method' => 'POST',
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'body' => wp_json_encode($reqBody)
            ]);
            // error_log(print_r('SCRIPTS: ' . json_encode($res), true));
            $body = wp_remote_retrieve_body($res);
            $bodyArr = json_decode($body, true);
            // error_log(print_r('CLSWP_JsdkHandler->toHTML: ' . json_encode($endpoint), true));
            if (is_array($bodyArr) && isset($bodyArr['code'])) {
                if ($bodyArr['code'] === intval(200)) {
                    $response =  $bodyArr['object'];
                }
                if (isset($bodyArr['errors'])) {
                    // TODO: Here could be logged some errors
                    error_log(print_r('CLSWP_JsdkHandler->createButton: Invalid token or price or both', true));
                    error_log(print_r(json_encode($bodyArr['errors']), true));
                }
            }
            return $response;
        }
    }
endif;
