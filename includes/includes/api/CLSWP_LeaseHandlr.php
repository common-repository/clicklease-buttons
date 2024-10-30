<?php
if(! class_exists('CLSWP_LeaseHandlr')):
class CLSWP_LeaseHandlr
{
    private $api_url;

    function __construct(string $pUrl)
    {
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
}
endif;
