<?php

/**
 * Class ControllerPaymentHumm
 */

class ControllerPaymentHumm extends Controller
{
    private $error = [];
    /**
     * @return string
     */
    public function index()
    {
        $this->load->language('payment/humm');

        $this->document->title = $this->language->get('heading_title');

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('humm', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');
            $this->redirect($this->url->https('extension/payment'));
        }
        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');
        $this->data['text_all_zones'] = $this->language->get('text_all_zones');
        $this->data['text_test'] = $this->language->get('text_test');
        $this->data['text_live'] = $this->language->get('text_live');
        $this->data['text_payment'] = $this->language->get('text_payment');
        $this->data['text_capture'] = $this->language->get('text_capture');
        $this->data['text_authenticate'] = $this->language->get('text_authenticate');
        $this->data['text_nz'] = $this->language->get('text_nz');
        $this->data['text_au'] = $this->language->get('text_au');

        $this->data['entry_merchantNo'] = $this->language->get('entry_merchantNo');
        $this->data['entry_apikey'] = $this->language->get('entry_apikey');
        $this->data['entry_gateway'] = $this->language->get('entry_gateway');
        $this->data['entry_test'] = $this->language->get('entry_test');
        $this->data['entry_title'] = $this->language->get('entry_title');
        $this->data['entry_transaction'] = $this->language->get('entry_transaction');
        $this->data['entry_total'] = $this->language->get('entry_total');
        $this->data['entry_order_status'] = $this->language->get('entry_order_status');
        $this->data['entry_order_status_pending'] = $this->language->get('entry_order_status_pending');
        $this->data['entry_order_status_completed'] = $this->language->get('entry_order_status_completed');
        $this->data['entry_order_status_failed'] = $this->language->get('entry_order_status_failed');
        $this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
        $this->data['entry_status'] = $this->language->get('entry_status');
        $this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');

        $this->data['tab_general'] = $this->language->get('tab_general');

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->error['error_humm'])) {
            $this->data['error_humm'] = $this->error['error_humm'];
        } else {
            $this->data['error_humm'] = '';
        }

        if (isset($this->error['password'])) {
            $this->data['error_password'] = $this->error['password'];
        } else {
            $this->data['error_password'] = '';
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->https('common/home'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_payment'),
            'href' => $this->url->https('extension/payment'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->https('payment/humm'),
            'separator' => ' :: '
        );

        $this->data['action'] = $this->url->https('payment/humm');

        $this->data['cancel'] = $this->url->https('extension/payment');

        if (isset($this->request->post['humm_merchantNo'])) {
            $this->data['humm_merchantNo'] = $this->request->post['humm_merchantNo'];
        } else {
            $this->data['humm_merchantNo'] = $this->config->get('humm_merchantNo');
        }

        if (isset($this->request->post['humm_apikey'])) {
            $this->data['humm_apikey'] = $this->request->post['humm_apikey'];
        } else {
            $this->data['humm_apikey'] = $this->config->get('humm_apikey');
        }


        if (isset($this->request->post['humm_gateway_environment'])) {
            $this->data['humm_gateway_environment'] = $this->request->post['humm_gateway_environment'];
        } else {
            $this->data['humm_gateway_environment'] = $this->config->get('humm_gateway_environment');
        }


        if (isset($this->request->post['humm_test'])) {
            $this->data['humm_test'] = $this->request->post['humm_test'];
        } else {
            $this->data['humm_test'] = $this->config->get('humm_test');
        }

        if (isset($this->request->post['humm_transaction'])) {
            $this->data['humm_transaction'] = $this->request->post['humm_transaction'];
        } else {
            $this->data['humm_transaction'] = $this->config->get('humm_transaction');
        }

        if (isset($this->request->post['humm_total'])) {
            $this->data['humm_total'] = $this->request->post['humm_total'];
        } else {
            $this->data['humm_total'] = $this->config->get('humm_total');
        }

        if (isset($this->request->post['humm_title'])) {
            $this->data['humm_title'] = $this->request->post['humm_title'];
        } else {
            $this->data['humm_title'] = $this->config->get('humm_title');
        }

        if (isset($this->request->post['humm_order_status_id'])) {
            $this->data['humm_order_status_id'] = $this->request->post['humm_order_status_id'];
        } else {
            $this->data['humm_order_status_id'] = $this->config->get('humm_order_status_id');
        }

        if (isset($this->request->post['humm_order_status_completed'])) {
            $this->data['humm_order_status_completed'] = $this->request->post['humm_order_status_completed'];
        } else {
            $this->data['humm_order_status_completed'] = $this->config->get('humm_order_status_completed');
        }

        if (isset($this->request->post['humm_order_status_pending'])) {
            $this->data['humm_order_status_pending'] = $this->request->post['humm_order_status_pending'];
        } else {
            $this->data['humm_order_status_pending'] = $this->config->get('humm_order_status_pending');
        }

        if (isset($this->request->post['humm_order_status_failed'])) {
            $this->data['humm_order_status_failed'] = $this->request->post['humm_order_status_failed'];
        } else {
            $this->data['humm_order_status_failed'] = $this->config->get('humm_order_status_failed');
        }


        $this->load->model('localisation/order_status');

        $this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();


        $this->data['humm_region'] = $this->getRegions();
        $this->data['humm_title_show'] = $this->getTitle();

        $this->data['humm_env'] = $this->getGatewayEnvironments();


        if (isset($this->request->post['humm_geo_zone_id'])) {
            $this->data['humm_geo_zone_id'] = $this->request->post['humm_geo_zone_id'];
        } else {
            $this->data['humm_geo_zone_id'] = $this->config->get('humm_geo_zone_id');
        }

        $this->load->model('localisation/geo_zone');

        $this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        if (isset($this->request->post['humm_status'])) {
            $this->data['humm_status'] = $this->request->post['humm_status'];
        } else {
            $this->data['humm_status'] = $this->config->get('humm_status');
        }

        if (isset($this->request->post['humm_sort_order'])) {
            $this->data['humm_sort_order'] = $this->request->post['humm_sort_order'];
        } else {
            $this->data['humm_sort_order'] = $this->config->get('humm_sort_order');
        }

        $this->template = 'payment/humm.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
    }

    /**
     * @return bool
     */
    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/payment')) {
            $this->error['humm_warning'] = $this->language->get('error_permission');
        }

        $keys = [
            'humm_title' => 'Title',
            'humm_merchantNo' => 'Merchant ID',
            'humm_apikey' => 'API Key',
        ];

        foreach ($keys as $key => $name) {
            if (!isset($this->request->post[$key]) || empty($this->request->post[$key])) {
                $this->error[$key] = sprintf($this->language->get('error_required'), $name);
            }
        }

        if (
            $this->request->post['humm_gateway_environment'] == 'other'
            && preg_match('@^https://@', $this->request->post['humm_gateway_url']) !== 1
        ) {
            $this->error['humm_gateway_url'] = $this->language->get('error_gateway_url_format');
        }

        return !$this->error;
    }

    /**
     * @return mixed[]
     */
    private function getRegions()
    {
        return [
            [
                'code' => 'AU',
                'name' => 'Australia',
            ],
            [
                'code' => 'NZ',
                'name' => 'New Zealand',
            ],
        ];
    }

    /**
     * @return mixed[]
     */
    private function getGatewayEnvironments()
    {
        return [
            [
                'code' => 'sandbox',
                'name' => 'Sandbox',
            ],
            [
                'code' => 'live',
                'name' => 'Live',
            ],
            [
                'code' => 'other',
                'name' => 'Other',
            ],
        ];
    }

    /**
     *
     */

    private function getTitle()
    {
        return [
            [
                'code' => 'AU',
                'name' => 'HUMM AU',
            ],
            [
                'code' => 'NZ',
                'name' => 'HUMM NZ',
            ],
        ];
    }

}