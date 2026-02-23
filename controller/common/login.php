<?php
class ControllerCommonLogin extends Controller {
	private $error = array();
	public function test()
	{
		
		$this->load->model('user/deviceakses');
		//$q = $this->model_user_deviceakses->getDevices($data); gudang_id 1 tgr
		/*
 [row] => Array ( [product_id] => 3294 [name] => Gas Argon WG 2 M3 [barcode] => 2017053294 [quantity] => 3 [jenistabung] => 0 [status] => 0 [date_added] => 2017-05-02 03:33:05 [date_modified] => 2017-05-02 03:33:05 [hapus] => 0 [satuan] => 7 [ukuran_tabung] => [jenisproduk] => 1 ) [rows] => Array ( [0] => Array ( [product_id] => 3294 [name] => Gas Argon WG 2 M3 [barcode] => 2017053294 [quantity] => 3 [jenistabung] => 0 [status] => 0 [date_added] => 2017-05-02 03:33:05 [date_modified] => 2017-05-02 03:33:05 [hapus] => 0 [satuan] => 7 [ukuran_tabung] => [jenisproduk] => 1 ) ) [num_rows] => 1 )
		
[row] => Array ( [product_id] => 3295 [name] => Gas Argon WG 6 M3 [barcode] => 2017053295 [quantity] => 14 [jenistabung] => 0 [status] => 0 [date_added] => 2017-05-02 03:33:13 [date_modified] => 2017-05-02 03:33:13 [hapus] => 0 [satuan] => 7 [ukuran_tabung] => [jenisproduk] => 1 ) [rows] => Array ( [0] => Array ( [product_id] => 3295 [name] => Gas Argon WG 6 M3 [barcode] => 2017053295 [quantity] => 14 [jenistabung] => 0 [status] => 0 [date_added] => 2017-05-02 03:33:13 [date_modified] => 2017-05-02 03:33:13 [hapus] => 0 [satuan] => 7 [ukuran_tabung] => [jenisproduk] => 1 ) ) [num_rows] => 1 )		
		*/
		//$q = $this->db->query("SELECT pg.product_gudang_id,p.product_id,p.name,pg.gudang_id,pg.quantity,pg.status,nama,pg.net_cost,p.jenistabung,pg.premijual,pg.premikirim,pg.premiambil,pg.premibongkar FROM ".DB_PREFIX."product p LEFT JOIN ".DB_PREFIX."product_gudang pg ON(p.product_id=pg.product_id) LEFT JOIN ".DB_PREFIX."gudang g ON(pg.gudang_id=g.gudang_id) where p.hapus=0 AND pg.product_id=3294 AND pg.gudang_id=3");
		$q = $this->db->query("SELECT * FROM product_gudang where product_id=3305 order by product_gudang_id desc limit 2");
		/*$q =$this->db->query("INSERT INTO product_gudang(product_id,gudang_id,quantity,net_cos,date_added) VALUES(3294,1,0,0,now())");
		$product=array(
			'gudang_id'	=> 1,
			'product_id'	=> 3305,
			'quantity'	=> empty($data['qty'])?0:$data['qty'],
			'status'	=>1,
			'net_cost'	=> empty($data['net_cost'])?0:$data['net_cost'],
			'date_added'	=>date('Y-m-d H:i:s',time())
		);

		$this->db->insert("product_gudang",$product);*/
		echo "<pre>";
		print_r($q);
	}
	public function index() {
    	$this->load->language('common/login');

		$this->document->setTitle($this->language->get('heading_title'));

		if ($this->user->isLogged() && isset($this->request->get['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
			$this->redirect($this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'));
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->session->data['token'] = md5(mt_rand());

			if (isset($this->request->post['redirect'])) {
				$this->redirect($this->request->post['redirect'] . '&token=' . $this->session->data['token']);
			} else {
				$this->redirect($this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}

    	$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_login'] = $this->language->get('text_login');
		$this->data['text_forgotten'] = $this->language->get('text_forgotten');

		$this->data['entry_username'] = $this->language->get('entry_username');
    	$this->data['entry_password'] = $this->language->get('entry_password');

    	$this->data['button_login'] = $this->language->get('button_login');
			$this->data['unregistered']=false;
		if(!isset($_COOKIE["validatedevice"])) {
    	$this->error['warning']="Device belum terdaftar.";
			$this->data['unregistered']=true;
		}else{
			$devicetoken=$_COOKIE["validatedevice"];

			$this->load->model('user/deviceakses');
			$cek=$this->model_user_deviceakses->getDeviceByToken($devicetoken);
			if(empty($cek)){
				$this->error['warning']="Device tidak terdaftar.";
				$this->data['unregistered']=true;
			}else{
				if($cek['status'] == 3){
					$this->error['warning']="Device tidak diijinkan untuk mengakses sistem.";
				}
				if($cek['status'] == 1){
					$this->load->model('user/deviceakses');
					$expire=86400*3000;
					setcookie("validatedevice", $cek['token'], time() + (86400 * 3000), "/");
				}
			}
		}

		/*if ((isset($this->session->data['token']) && !isset($this->request->get['token'])) || ((isset($this->request->get['token']) && (isset($this->session->data['token']) && ($this->request->get['token'] != $this->session->data['token']))))) {
			$this->error['warning'] = $this->language->get('error_token');
		}*/

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

    	$this->data['action'] = $this->url->link('common/login', '', 'SSL');

		if (isset($this->request->post['username'])) {
			$this->data['username'] = $this->request->post['username'];
		} else {
			$this->data['username'] = '';
		}

		if (isset($this->request->post['password'])) {
			$this->data['password'] = $this->request->post['password'];
		} else {
			$this->data['password'] = '';
		}

		if (isset($this->request->get['route'])) {
			$route = $this->request->get['route'];

			unset($this->request->get['route']);

			if (isset($this->request->get['token'])) {
				unset($this->request->get['token']);
			}

			$url = '';

			if ($this->request->get) {
				$url .= http_build_query($this->request->get);
			}

			$this->data['redirect'] = $this->url->link($route, $url, 'SSL');
		} else {
			$this->data['redirect'] = '';
		}

		$this->data['forgotten'] = $this->url->link('common/forgotten', '', 'SSL');
		$this->data['registerdevice'] = $this->url->link('common/login/registerdevice', '', 'SSL');

		$this->template = 'common/login.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
  	}

	private function validate() {
		if(!isset($_COOKIE["validatedevice"])) {
    	$this->error['warning']="Device belum terdaftar.";
		} else {
			$devicetoken=$_COOKIE["validatedevice"];

			$this->load->model('user/deviceakses');
			$cek=$this->model_user_deviceakses->getDeviceByToken($devicetoken);
			if(empty($cek)){
				$this->error['warning']="Device tidak terdaftar.";
			}else{
				if($cek['status'] == 3){
					$this->error['warning']="Device tidak diijinkan untuk mengakses sistem.";
				}
				if($cek['status'] == 2){
					$this->error['warning']="Device menunggu persetujuan untuk mengakses sistem.";
				}
				/*if($cek['status'] == 1){
					$this->session->data['user_id']
				}*/
			}
		}
		if (isset($this->request->post['username']) && isset($this->request->post['password']) && !$this->user->login($this->request->post['username'], $this->request->post['password'])) {
			$this->error['warning'] = $this->language->get('error_login');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function registerdevice(){
		$cookiename="validatedevice";
		$token=md5(mt_rand()).time();
		$os=$this->user->getOs();
		$browser=$this->user->getBrowser();

		$this->load->model('user/deviceakses');
		$expire=86400*3000;
		setcookie($cookiename, $token, time() + (86400 * 30), "/");

		$device=array(
			'location'=>$this->request->get['location'],
			'username'=>$this->request->get['username'],
			'token'	=> $token,
			'os'	=> $os,
			'browser'	=> $browser,
		);
		$this->model_user_deviceakses->addDevice($device);
		$this->session->data['success']="Permintaan pendaftaran perangkat berhasil disimpan. Mohon tunggu persetujuan untuk bisa mengakses sistem.";
		$this->redirect($this->url->link('common/login'));
	}
}
?>
