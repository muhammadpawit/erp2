<?php
class ControllerCommonHome extends Controller {
	public function index() {
    	$this->load->language('common/home');

		$this->document->setTitle($this->language->get('heading_title'));


		$file = DIR_IMAGE . 'test';

		$handle = fopen($file, 'a+');

		fwrite($handle, '');

		fclose($handle);

		if (!file_exists($file)) {
			$this->data['error_image'] = sprintf($this->language->get('error_image'). DIR_IMAGE);
		} else {
			$this->data['error_image'] = '';

			unlink($file);
		}

		// Check image cache directory is writable
		$file = DIR_IMAGE . 'cache/test';

		$handle = fopen($file, 'a+');

		fwrite($handle, '');

		fclose($handle);

		if (!file_exists($file)) {
			$this->data['error_image_cache'] = sprintf($this->language->get('error_image_cache'). DIR_IMAGE . 'cache/');
		} else {
			$this->data['error_image_cache'] = '';

			unlink($file);
		}

		// Check cache directory is writable
		$file = DIR_CACHE . 'test';

		$handle = fopen($file, 'a+');

		fwrite($handle, '');

		fclose($handle);

		if (!file_exists($file)) {
			$this->data['error_cache'] = sprintf($this->language->get('error_image_cache'). DIR_CACHE);
		} else {
			$this->data['error_cache'] = '';

			unlink($file);
		}

			$file = DIR_LOGS . 'test';

		$handle = fopen($file, 'a+');

		fwrite($handle, '');

		fclose($handle);

		if (!file_exists($file)) {
			$this->data['errorlogs'] = sprintf($this->language->get('error_logs'). DIR_LOGS);
		} else {
			$this->data['error_logs'] = '';

			unlink($file);
		}


		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		if (isset($this->session->data['error'])) {
			$this->data['error'] = $this->session->data['error'];
			unset($this->session->data['error']);
		} else {
			$this->data['error'] = '';
		}

		$this->data['token'] = $this->session->data['token'];





		$this->template = 'common/home.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
  	}



	public function login() {
		$route = '';

		if (isset($this->request->get['route'])) {
			$part = explode('/', $this->request->get['route']);

			if (isset($part[0])) {
				$route .= $part[0];
			}

			if (isset($part[1])) {
				$route .= '/' . $part[1];
			}
		}

		$ignore = array(
			'common/login',
			'common/forgotten',
			'common/reset',
			'api/penyesuaian'
		);

		if (!$this->user->isLogged() && !in_array($route, $ignore)) {
			return $this->forward('common/login');
		}

		if (isset($this->request->get['route'])) {
			$ignore = array(
				'common/login',
				'common/logout',
				'common/forgotten',
				'common/reset',
				'common/filemanager',
				'error/not_found',
				'error/permission',
				'api/penyesuaian'
			);

			$config_ignore = array();

			if ($this->config->get('config_token_ignore')) {
				$config_ignore = unserialize($this->config->get('config_token_ignore'));
			}

			$ignore = array_merge($ignore, $config_ignore);

			if (!in_array($route, $ignore) && (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token']))) {
				return $this->forward('common/login');
			}
		} else {
			if (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
				return $this->forward('common/login');
			}
		}
	}

	public function permission() {
		if (isset($this->request->get['route'])) {
			$route = '';

			$part = explode('/', $this->request->get['route']);

			if (isset($part[0])) {
				$route .= $part[0];
			}

			if (isset($part[1])) {

				$route .= '/' . $part[1];
			}

			if($part[0] == 'pameran' | $part[0] == 'toko'){
				if (isset($part[2])) {
					if($part[2] == 'koreksistok'){
						$route .= '/' . $part[2];
					}
					if($part[1] == 'toko' | $part[1] == 'pameran' ){
						if($part[2] == 'update'){
							$route .= '/' . $part[2];
						}
					}
				}
			}

			if($part[0] == 'tukartabung'){
				if (isset($part[2])) {
					if($part[2] == 'batalkanproses'){
						$route .= '/' . $part[2];
					}
					
				}
			}
            if($part[0] == 'sale'){
                if($part[1] == 'customer'){
										if(isset($part[2])){
	                    if($part[2] == 'activate'){
	                        $route .= '/' . $part[2];
	                    }
										}
                }
            }

			$ignore = array(
				'common/home',
				'common/login',
				'common/logout',
				'common/forgotten',
				'common/reset',
				'error/not_found',
				'error/permission',
				'common/filemanager',
				'api/penyesuaian'
				/*'catalog/bahanbaku',
				'catalog/kelompokaset',
				'catalog/options',
				'catalog/tabungmp',
				'gudang/stokopname',*/

			);

			if (!in_array($route, $ignore)){
				if(!isset($_COOKIE["validatedevice"])) {
		    	return $this->forward('common/permission');
				} else {
					$devicetoken=$_COOKIE["validatedevice"];
				$this->load->model('user/deviceakses');
				$cek=$this->model_user_deviceakses->getDeviceByToken($devicetoken);
				if(empty($cek)){
					//$this->error['warning']="Device tidak terdaftar.";
					return $this->forward('common/permission');
				}else{
					if($cek['status'] == 3){
						return $this->forward('common/permission');
					}
					if($cek['status'] == 2){
						return $this->forward('common/permission');
					}
					if($cek['status'] == 1){
						//cek permission
						$this->load->model('website/menu');
						$akses=$this->model_website_menu->cekakses($route,$this->user->getId());
						$ignorer=false;
								if(isset($part[2])){
									if(substr_count($part[2],'autocomplete') >= 1){
										$ignorer=true;
									}
									if(substr_count($part[2],'detail') >= 1){
										$ignorer=true;
									}
									if(substr_count($part[2],'country') >= 1){
										$ignorer=true;
									}
									if(substr_count($part[2],'zone') >= 1){
										$ignorer=true;
									}
									if(substr_count($part[2],'city') >= 1){
										$ignorer=true;
									}
								}
						if(!$ignorer){
							$this->model_website_menu->addhistoryakses($_SERVER['QUERY_STRING'],$this->user->getId());
						}
						if (!in_array($route, $ignore) && !$akses) {

											if(!$ignorer){
												return $this->forward('common/permission');

										}
						}
					}
				}
			}
		}

			/*if (!in_array($route, $ignore) && !$this->user->hasPermission('access', $route)) {
						$ignore=false;
								if(isset($part[2])){
									if(substr_count($part[2],'autocomplete') >= 1){
										$ignore=true;
									}
									if(substr_count($part[2],'detail') >= 1){
										$ignore=true;
									}
								}
								if(!$ignore){
									return $this->forward('error/permission');

							}
			}*/

		}
	}
}
?>
