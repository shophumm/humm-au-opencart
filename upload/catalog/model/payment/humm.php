<?php
const HUMM_VERSION = '1.6.1-OC1.3';
const HUMM_DESCRIPTION = "Pay in slices. No interest ever.";

/**
 * Class ModelPaymentHumm
 */
class ModelPaymentHumm extends Model
{
    /**
     * @param mixed[] $address
     * @param double $total
     *
     * @return mixed[]
     */

    const IS_DEBUG = true;
    const HUMM_MINIMUM_PURCHASE = 1;

    public function getMethod($country_id, $zone_id)
    {
        $this->load->language('payment/humm');


        if ($this->config->get('humm_status')) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('humm_geo_zone_id') . "' AND country_id = '" . (int)$country_id . "' AND (zone_id = '" . (int)$zone_id . "' OR zone_id = '0')");


            if (!$this->config->get('humm_geo_zone_id')) {
                $status = TRUE;
            } elseif ($query->num_rows) {
                $status = TRUE;
            } else {
                $status = FALSE;
            }
        } else {
            $status = FALSE;
        }
        $method_data = [];
        if ($status) {
            $method_data = [
                'id' => 'humm',
                'title' => 'HUMM-' . $this->config->get('humm_title'),
                'sort_order' => $this->config->get('humm_sort_order'),
            ];
        }
        if ($this->cart->getTotal() > floatval($this->config->get('humm_total'))) {
            return $method_data;
        } else {
            return [];
        }
    }

    /**
     * Generate HMAC-SHA256 signature
     *
     * @param string[] $params
     *
     * @return string
     */
    public function getSignature($params)
    {
        $string = '';
        ksort($params);
        foreach ($params as $key => $value) {
            if (substr($key, 0, 2) === 'x_') {
                $string .= $key . $value;
            }
        }
        $hash = hash_hmac('sha256', $string, $this->config->get('humm_apikey'));

        return str_replace('-', '', $hash);
    }

    /**
     * Validate HMAC-SHA256 signature
     *
     * @param string[] $params
     *
     * @return bool
     */
    public function validateSignature($params)
    {
        if (!isset($params['x_signature'])) {
            return false;
        }

        $signature = $params['x_signature'];

        unset($params['x_signature']);

        return $signature == $this->getSignature($params);
    }

    /**
     * Generate array of parameters to be passed onto humm
     *
     * @return mixed[]
     */
    public function getParams()
    {
        $this->load->model('checkout/order');

        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        $payment_country_info = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$order_info['payment_country_id'] . "' LIMIT 1")->row;
        $payment_zone_info = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE zone_id = '" . (int)$order_info['payment_zone_id'] . "' AND country_id = '" . (int)$order_info['payment_country_id'] . "' LIMIT 1")->row;

        $shipping_country_info = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$order_info['shipping_country_id'] . "' LIMIT 1")->row;
        $shipping_zone_info = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE zone_id = '" . (int)$order_info['shipping_zone_id'] . "'  AND country_id = '" . (int)$order_info['shipping_country_id'] . "' LIMIT 1")->row;

        $params = [
            // Required
            'x_account_id' => $this->config->get('humm_merchantNo'),
            'x_amount' => $this->currency->format($order_info['total'], $order_info['currency'], $order_info['value'], false),
            'x_currency' => $order_info['currency'],
            'x_reference' => $this->session->data['order_id'],
            'x_shop_country' => $this->config->get('humm_title'),
            'x_shop_name' => $this->config->get('humm_shop_name'),
            'x_test' => 'false',
            'x_url_callback' => $this->url->https('payment/humm/callback', '', true),
            'x_url_cancel' => $this->url->https('payment/humm/cancel', '', true),
            'x_url_complete' => $this->url->https('payment/humm/complete', '', true),

            // Optional
            'x_customer_first_name' => $order_info['payment_firstname'],
            'x_customer_last_name' => $order_info['payment_lastname'],
            'x_customer_email' => $order_info['email'],
            'x_customer_phone' => $order_info['telephone'],
            'x_customer_billing_address1' => $order_info['payment_address_1'],
            'x_customer_billing_address2' => $order_info['payment_address_2'],
            'x_customer_billing_city' => $order_info['payment_city'],
            'x_customer_billing_state' => '',
            'x_customer_billing_postcode' => $order_info['payment_postcode'],
            'x_customer_billing_country' => '',
            'x_customer_shipping_first_name' => $order_info['shipping_firstname'],
            'x_customer_shipping_last_name' => $order_info['shipping_lastname'],
            'x_customer_shipping_address1' => $order_info['shipping_address_1'],
            'x_customer_shipping_address2' => $order_info['shipping_address_2'],
            'x_customer_shipping_city' => $order_info['shipping_city'],
            'x_customer_shipping_state' => '',
            'x_customer_shipping_postcode' => $order_info['shipping_postcode'],
            'x_customer_shipping_country' => '',
            'x_description' => 'Order #' . $order_info['order_id'],
            'version_info' => 'Humm_' . HUMM_VERSION . '_on_OC_' . '1.3.4',
        ];

        if ($payment_country_info) {
            $params['x_customer_billing_country'] = $payment_country_info['iso_code_2'];
        }

        if ($payment_zone_info) {
            $params['x_customer_billing_state'] = $payment_zone_info['code'];
        }

        if ($shipping_country_info) {
            $params['x_customer_shipping_country'] = $shipping_country_info['iso_code_2'];
        }

        if ($shipping_zone_info) {
            $params['x_customer_shipping_state'] = $shipping_zone_info['code'];
        }

        $params['x_signature'] = $this->getSignature($params);

        $this->debugLogIncoming('data-payload--', $params);

        return $params;
    }

    /**
     * @return mixed[]
     */
    public function getStatuses()
    {
        return [
            'completed' => $this->config->get('humm_order_status_completed'),
            'pending' => $this->config->get('humm_order_status_pending'),
            'failed' => $this->config->get('humm_order_status_failed'),
        ];
    }

    /**
     * @param string $outcome
     *
     * @return string|null
     */
    public function getStatus($outcome)
    {
        $statuses = $this->getStatuses();

        return (
        isset($statuses[$outcome])
            ? $statuses[$outcome]
            : 0
        );
    }

    /**
     * @return string
     */
    public function getGatewayUrl()
    {
        if (preg_match('@^https://@', $this->config->get('humm_gateway_environment')) == 1) {
            return $this->config->get('humm_gateway_environment');
        }
        $environment = $this->config->get('humm_test');

        $region = $this->config->get('humm_title');
        $country_domain = ($region == 'NZ') ? 'co.nz' : 'com.au';

        $domainsTest = array(
            'AU' => 'integration-cart.shophumm.',
            'NZ' => 'securesandbox.oxipay.'
        );
        $domains = array(
            'AU' => 'cart.shophumm.',
            'NZ' => 'secure.oxipay.'
        );
        return 'https://' . ($environment == 'live' ? $domains[$region] : $domainsTest[$region]) . $country_domain . '/Checkout?platform=Default';
    }

    /**
     * @return string
     */

    public function getDescription()
    {

    }


    /**
     * @param string $type
     */
    private function debugLogIncoming($type, $data = null)
    {
        if (static::IS_DEBUG) {
            if ($type == 'data-payload') {
                $this->log->write('Humm-Payload-start:' . $type . 'Debug' . var_export($data, true));
            } else {
                $str = var_export([
                    'get' => $_GET,
                    'post' => $_POST,
                ], true);
                $this->log->write('Humm ' . $type . ' Debug:  ' . $str);
            }
        }
    }
}
