<?php

class ControllerPaymentHumm extends Controller
{
    const IS_DEBUG = true;
    const HUMM_MINIMUM_PURCHASE = 1;

    /**
     * ControllerPaymentHumm constructor.
     */
    public function __construct()
    {

        $this->load->language('payment/humm');
        $this->load->model('payment/humm');
        $this->load->model('checkout/order');
    }

    /**
     * @return string
     */
    public function index()
    {

        if ($this->cart->getTotal() >= static::HUMM_MINIMUM_PURCHASE) {
            $this->data['button_confirm'] = $this->language->get('button_confirm');

            $this->data['text_loading'] = $this->language->get('text_loading');

            $this->data['params'] = $this->model_payment_humm->getParams();

            $this->data['action'] = $this->model_payment_humm->getGatewayUrl();

        } else {
            $this->data['error'] = sprintf($this->language->get('error_amount'), $this->currency->format(static::HUMM_MINIMUM_PURCHASE, $this->session->data['currency'], 1));
        }

        $this->id = 'payment';

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/humm.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/payment/humm.tpl';
        } else {
            $this->template = 'default/template/payment/humm.tpl';
        }
        $this->render();
    }

    /**
     * @return void
     */
    public function callback()
    {
        $this->debugLogIncoming('Callback');

        // Validate Response
        try {
            $order_info = $this->getOrderAndVerifyResponse($this->request->post);
        } catch (\Exception $e) {
            $reference_id = "";
            if (isset($this->request->post['x_reference'])) {
                $reference_id = $this->request->post['x_reference'];
            }
            return $this->callbackBadRequest($reference_id, $e->getMessage());
        }

        $result = $this->updateOrder($order_info, $this->request->post);
        $this->response->addHeader('Content-type', 'application/json');
        $this->response->setOutput(json_encode([
            'reference_id' => $this->request->post['x_reference'],
            'status' => $result
        ]));
    }

    /**
     * @param string $type
     */
    private function debugLogIncoming($type, $data = null)
    {
        if (static::IS_DEBUG) {
            if ($type == 'data') {
                $this->log->write('Humm-Data-update:' . $type . 'Debug' . var_export($data, true));
            } else {
                $str = var_export([
                    'get' => $_GET,
                    'post' => $_POST,
                ], true);
                $this->log->write('Humm ' . $type . ' Debug:  ' . $str);
            }
        }
    }

    /**
     * @param $request
     * @return mixed
     * @throws Exception
     */
    private function getOrderAndVerifyResponse($request)
    {
        $required = [
            'x_account_id',
            'x_reference',
            'x_currency',
            'x_test',
            'x_amount',
            'x_gateway_reference',
            'x_timestamp',
            'x_result',
        ];
        foreach ($required as $seq => $value) {
            if (isset($request[$value]) && !empty($request[$value])) {
                unset($required[$seq]);
            }
        }
        if (!empty($required)) {
            throw new \Exception('Bad Request. Missing required fields: ' . implode(', ', $required) . '.');
        }
        // Validate Signature
        if (!$this->model_payment_humm->validateSignature($request)) {
            throw new \Exception('Bad Request. Unable to validate signature.');
        }
        $order_info = $this->model_checkout_order->getOrder($request['x_reference']);
        // Order Exists
        if (empty($order_info)) {
            throw new \Exception('Bad Request. Invalid Order ID.');
        }

        return $order_info;
    }

    /**
     * @param string $comment
     *
     * @return void
     */
    private function callbackBadRequest($reference_id, $comment)
    {
        $params = [];

        foreach ($this->request->post as $key => $value) {
            $params[] = $key . '=' . $value;
        }

        $this->log->write('Humm Error: ' . $comment . ' (' . implode('; ', $params) . ')');
        $this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 400 Bad Request');
        $this->response->addHeader('Content-type: application/json');
        $this->response->setOutput(json_encode(["reference_id" => $reference_id, "status" => $comment]));
    }

    /**
     * @param $order_info
     * @param $request
     */
    private function updateOrder($order_info, $request)
    {

        $order_status_id = $this->model_payment_humm->getStatus($request['x_result']);

        if ($order_status_id == $order_info['order_status_id']) {
            return;
        }

        $this->debugLogIncoming('data', $order_info);

        $comment = '';
        $comment .= 'Test: ' . $request['x_test'] . "\n";
        $comment .= 'Timestamp: ' . $request['x_timestamp'] . "\n";
        $comment .= 'Result: ' . $request['x_result'] . "\n";
        $comment .= 'Gateway Reference: ' . $request['x_gateway_reference'] . "\n";
        $comment .= 'Amount: ' . $request['x_amount'] . "\n";
        $comment .= 'Currency: ' . $request['x_currency'] . "\n";
        $comment = strip_tags($comment);

        $this->model_checkout_order->confirm($order_info['order_id'], $order_status_id);

        $this->model_checkout_order->update($order_info['order_id'], $order_status_id, $comment, false);

        return $request['x_result'];
    }

    /**
     * @return void
     */
    public function complete()
    {
        $this->debugLogIncoming('Complete');
        try {
            $order_info = $this->getOrderAndVerifyResponse($this->request->get);
        } catch (\Exception $e) {
            // Give the customer a general error
            $this->session->data['error'] = $this->language->get('text_transaction_verification');

            $this->response->redirect($this->url->https('checkout/cart', '', true));
            return;
        }
        $this->updateOrder($order_info, $this->request->get);
        if ($this->request->get['x_result'] == 'failed') {
            $this->response->redirect($this->url->https('checkout/cart', '', true));
        }

        // Success!
        $this->response->redirect($this->url->https('checkout/success', '', true));
    }

    /**
     * @return void
     */
    public function cancel()
    {
        $this->debugLogIncoming('Cancel');
        $this->response->redirect($this->url->https('checkout/cart', '', true));
    }
}
