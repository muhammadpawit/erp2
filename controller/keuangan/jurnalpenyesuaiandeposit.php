<?php
class ControllerKeuanganJurnalpenyesuaiandeposit extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('setting/setting');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			$this->model_setting_setting->editSetting('config', $this->request->post);

		/*	if ($this->config->get('config_currency_auto')) {
				$this->load->model('localisation/currency');

				$this->model_localisation_currency->updateCurrencies();
			}
*/
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('keuangan/jurnalpenyesuaiandeposit', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');



 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}


  if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->data['action'] = $this->url->link('keuangan/jurnalpenyesuaiandeposit', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->post['config_kelebihan'])) {
			$this->data['config_kelebihan'] = $this->request->post['config_kelebihan'];
		} else {
			$this->data['config_kelebihan'] = $this->config->get('config_kelebihan');
		}

		if (isset($this->request->post['config_kekurangan'])) {
			$this->data['config_kekurangan'] = $this->request->post['config_kekurangan'];
		} else {
			$this->data['config_kekurangan'] = $this->config->get('config_kekurangan');
		}

		

		$this->load->model('keuangan/coa');

		$categories = $this->model_keuangan_coa->getCategories();

		// Remove own id from list
		if (!empty($category_info)) {
			foreach ($categories as $key => $category) {
				if ($category['category_id'] == $category_info['category_id']) {
					unset($categories[$key]);
				}
			}
		}

		$this->data['categories'] = $categories;

		$this->template = 'keuangan/jurnalpenyesuaiandeposit.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validate() {

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}


}
?>
