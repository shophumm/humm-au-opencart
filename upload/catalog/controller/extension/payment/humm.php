<?php

class ControllerExtensionPaymentHumm extends Controller {
    const IS_DEBUG = false;

    /**
     * @param object $registry
     *
     * @return void
     */
    public function __construct( $registry ) {
        parent::__construct( $registry );

        $this->load->language( 'extension/payment/humm' );
        $this->load->model( 'extension/payment/humm' );
        $this->load->model( 'checkout/order' );
    }

    /**
     * @return string
     */
    public function index() {
        if ( $this->cart->getTotal() >= 20 ) {
            $data['button_confirm'] = $this->language->get( 'button_confirm' );

            $data['text_loading'] = $this->language->get( 'text_loading' );

            $data['params'] = $this->model_extension_payment_humm->getParams();

            $data['action'] = $this->model_extension_payment_humm->getGatewayUrl();
        } else {
            $data['error'] = sprintf( $this->language->get( 'error_amount' ), $this->currency->format( 20, $this->session->data['currency'], 1 ) );
        }

        return $this->load->view( 'extension/payment/humm', $data );
    }

    /**
     * @return void
     */
    public function callback() {
        $this->debugLogIncoming( 'Callback' );

        // Validate Response
        try {
            $order_info = $this->getOrderAndVerifyResponse( $this->request->post );
        } catch ( \Exception $e ) {
            // Handle callback error
            $reference_id = "";
            if ( isset( $this->request->post['x_reference'] ) ) {
                $reference_id = $this->request->post['x_reference'];
            }

            return $this->callbackBadRequest( $reference_id, $e->getMessage() );
        }

        $result = $this->updateOrder( $order_info, $this->request->post );

        $this->response->addHeader( 'Content-type: application/json' );
        $this->response->setOutput( json_encode( [
            'reference_id' => $this->request->post['x_reference'],
            'status'       => $result
        ] ) );
    }

    /**
     * @return void
     */
    public function complete() {
        $this->debugLogIncoming( 'Complete' );

        // Validate Response
        try {
            $order_info = $this->getOrderAndVerifyResponse( $this->request->get );
        } catch ( \Exception $e ) {
            // Give the customer a general error
            $this->session->data['error'] = $this->language->get( 'text_transaction_verification' );

            $this->response->redirect( $this->url->link( 'checkout/checkout', '', true ) );

            return;
        }

        $this->updateOrder( $order_info, $this->request->get );

        // Failed transaction outcome
        if ( $this->request->get['x_result'] == 'failed' ) {
            $this->session->data['error'] = $this->language->get( 'text_transaction_failed' );

            $this->response->redirect( $this->url->link( 'checkout/checkout', '', true ) );
        }

        // Success!
        $this->response->redirect( $this->url->link( 'checkout/success', '', true ) );
    }

    /**
     * @return void
     */
    public function cancel() {
        $this->debugLogIncoming( 'Cancel' );

        $this->session->data['error'] = $this->language->get( 'text_transaction_cancelled' );

        $this->response->redirect( $this->url->link( 'checkout/checkout', '', true ) );
    }

    /**
     * @param string $comment
     *
     * @return void
     */
    private function callbackBadRequest( $reference_id, $comment ) {
        $params = [];

        foreach ( $this->request->post as $key => $value ) {
            $params[] = $key . '=' . $value;
        }

        $this->log->write( 'Humm Error: ' . $comment . ' (' . implode( '; ', $params ) . ')' );

        $this->response->addHeader( $this->request->server['SERVER_PROTOCOL'] . ' 400 Bad Request' );

        $this->response->addHeader( 'Content-type: application/json' );
        $this->response->setOutput( json_encode( [ "reference_id" => $reference_id, "status" => $comment ] ) );
    }

    /**
     * @param mixed[] $request
     *
     * @return mixed
     */
    private function getOrderAndVerifyResponse( $request ) {
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

        // Required
        // if an item exists(and is not empty) in $request, remove it from the $required array
        // $required should be empty by the end to indicate all required items have been provided.
        foreach ( $required as $seq => $value ) {
            if ( isset( $request[ $value ] ) && ! empty( $request[ $value ] ) ) {
                unset( $required[ $seq ] );
            }
        }

        if ( ! empty( $required ) ) {
            throw new \Exception( 'Bad Request. Missing required fields: ' . implode( ', ', $required ) . '.' );
        }

        // Validate Signature
        if ( ! $this->model_extension_payment_humm->validateSignature( $request ) ) {
            throw new \Exception( 'Bad Request. Unable to validate signature.' );
        }

        $order_info = $this->model_checkout_order->getOrder( $request['x_reference'] );

        // Order Exists
        if ( empty( $order_info ) ) {
            throw new \Exception( 'Bad Request. Invalid Order ID.' );
        }

        return $order_info;
    }

    /**
     * @param mixed[] $request
     */
    private function updateOrder( $order_info, $request ) {
        $order_status_id = $this->model_extension_payment_humm->getStatus( $request['x_result'] );

        if ( $order_status_id == $order_info['order_status_id'] ) {
            return;
        }

        $comment = '';
        $comment .= 'Test: ' . $request['x_test'] . "\n";
        $comment .= 'Timestamp: ' . $request['x_timestamp'] . "\n";
        $comment .= 'Result: ' . $request['x_result'] . "\n";
        $comment .= 'Gateway Reference: ' . $request['x_gateway_reference'] . "\n";
        $comment .= 'Amount: ' . $request['x_amount'] . "\n";
        $comment .= 'Currency: ' . $request['x_currency'] . "\n";
        $comment = strip_tags( $comment );

        $this->model_checkout_order->addOrderHistory( $order_info['order_id'], $order_status_id, $comment, false );

        return $request['x_result'];
    }

    /**
     * @param string $type
     */
    private function debugLogIncoming( $type ) {
        if ( static::IS_DEBUG ) {
            $str = var_export( [
                'get' => $_GET,
                'post' => $_POST,
            ], true );

            $this->log->write( 'Humm ' . $type . ' Debug: ' . $str );
        }
    }
}
