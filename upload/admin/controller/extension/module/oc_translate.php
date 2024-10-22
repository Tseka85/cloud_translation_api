<?php
class ControllerExtensionModuleOcTranslate extends Controller {
    private $error = array();
    
    public function index() {
        $this->load->language('extension/module/oc_translate');
        
        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('setting/setting');
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('module_oc_translate', $this->request->post);
            
            $this->session->data['success'] = $this->language->get('text_success');
            
            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
        }
        
        $data['heading_title'] = $this->language->get('heading_title');
        
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        
        if (isset($this->error['api_key'])) {
            $data['error_api_key'] = $this->error['api_key'];
        } else {
            $data['error_api_key'] = '';
        }
        
        $data['breadcrumbs'] = array();
        
        $data['action'] = $this->url->link('extension/module/oc_translate', 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
        
        if (isset($this->request->post['module_oc_translate_status'])) {
            $data['module_oc_translate_status'] = $this->request->post['module_oc_translate_status'];
        } else {
            $data['module_oc_translate_status'] = $this->config->get('module_oc_translate_status');
        }
        
        if (isset($this->request->post['module_oc_translate_api_key'])) {
            $data['module_oc_translate_api_key'] = $this->request->post['module_oc_translate_api_key'];
        } else {
            $data['module_oc_translate_api_key'] = $this->config->get('module_oc_translate_api_key');
        }
        
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        
        $this->response->setOutput($this->load->view('extension/module/oc_translate', $data));
    }
    
    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/oc_translate')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        
        if (empty($this->request->post['module_oc_translate_api_key'])) {
            $this->error['api_key'] = $this->language->get('error_api_key');
        }
        
        return !$this->error;
    }
}
