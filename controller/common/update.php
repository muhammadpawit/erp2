<?php
class ControllerCommonUpdate extends Controller {
	public function index() {
		if (!$this->user->isLogged() || !isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
			$this->response->redirect($this->url->link('common/login', '', 'SSL'));
		}

		$output = shell_exec('git pull 2>&1');
		
		$this->session->data['success'] = 'ERP updated successfully! Output: <pre>' . $output . '</pre>';
		
		$this->response->redirect($this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'));
	}
}
?>
