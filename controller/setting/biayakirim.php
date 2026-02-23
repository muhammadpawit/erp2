<?php
class ControllerSettingBiayaKirim extends Controller {
	private $error = array();
	public function update(){
		$this->load->model('setting/setting');
		$post = $this->request->post;
		//echo "<pre>";print_r($post);exit;
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->model_setting_setting->updatebiayakirim($this->request->post);
			$this->session->data['success'] = 'Data berhasil diupdate';
			$this->redirect($this->url->link('setting/biayakirim', 'token=' . $this->session->data['token'], 'SSL'));
		}
	}
	public function hapusnew(){
		if(isset($this->request->get['id'])){
			$id=$this->request->get['id'];
			$this->db->update('biaya_kirim',array('hapus'=>1),array('id'=>$id));
			echo $id;
		}else{
			echo "gagal";
		}
	}
	public function simpan(){
		$this->load->model('setting/setting');
		$post = $this->request->post;
		echo "<pre>";print_r($post);exit;
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->model_setting_setting->simpanbiayakirim($this->request->post);
			$this->session->data['success'] = 'Data berhasil disimpan';
			$this->redirect($this->url->link('setting/biayakirim', 'token=' . $this->session->data['token'], 'SSL'));
		}
	}
	public function insert() {
		$this->document->setTitle("Tambah biaya pengiriman");
		$this->load->language('setting/setting');
		$this->load->model('setting/setting');
		$this->data['biayas'] = $this->model_setting_setting->getbiayakirimnew();
		$this->data['biayasdetail'] = $this->model_setting_setting->getbiayakirimdetailnew();
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->model_setting_setting->addbiayakirim($this->request->post);
			$this->session->data['success'] = 'Data berhasil disimpan';
			$this->redirect($this->url->link('setting/biayakirim', 'token=' . $this->session->data['token'], 'SSL'));
		}
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
 		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}
  		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		$this->data['action'] = $this->url->link('setting/biayakirim', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['insert'] = $this->url->link('setting/biayakirim/insert', 'token=' . $this->session->data['token'], 'SSL');
		if(isset($this->request->get['id'])){
			$this->data['simpan'] = $this->url->link('setting/biayakirim/update', 'token=' . $this->session->data['token'], 'SSL');
		}else{
			$this->data['simpan'] = $this->url->link('setting/biayakirim/simpan', 'token=' . $this->session->data['token'], 'SSL');
		}
		$this->data['token'] = $this->session->data['token'];
		$this->template = 'setting/biayakirim_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}

	public function index() {
		$this->load->language('setting/setting');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('setting/setting');
		$this->data['biaya'] = $this->model_setting_setting->getbiayakirim();
		$this->data['biayas'] = $this->model_setting_setting->getbiayakirimnew();
		$this->data['biayasdetail'] = $this->model_setting_setting->getbiayakirimdetailnew();
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->model_setting_setting->addbiayakirim($this->request->post);
			$this->session->data['success'] = 'Data berhasil disimpan';
			$this->redirect($this->url->link('setting/biayakirim', 'token=' . $this->session->data['token'], 'SSL'));
		}
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
 		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}
  		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		$this->data['action'] = $this->url->link('setting/biayakirim', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['insert'] = $this->url->link('setting/biayakirim/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['edit'] = $this->url->link('setting/biayakirim/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['token'] = $this->session->data['token'];
		$this->template = 'setting/biayakirim.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}

	public function hapus(){
		if(isset($this->request->get['id'])){
			$id=$this->request->get['id'];
			$this->db->update('biaya_pengiriman',array('hapus'=>1),array('id'=>$id));
			echo $id;
		}else{
			echo "gagal";
		}
	}

	private function validate() {
	

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	
}
?>
