<?php
class ControllerUserAksesmenu extends Controller {
	private $error = array();
	public function resignnew(){
		if(isset($this->request->get['user_id'])){
			$user_id=$this->request->get['user_id'];
			$this->db->query("UPDATE users set resign='1', status=0 WHERE user_id='$user_id' ");

			$this->session->data['success'] ="Data Berhasil di reset!";
			
			$url = '';
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}

			//$this->redirect($this->url->link('user/aksesmenu', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			echo "sukses";
		}else{
			echo "gagal";
		}
	}
	// baru 15 Juni 2020
	public function resign(){
		if(isset($this->request->get['user_id'])){
			$user_id=$this->request->get['user_id'];
			// kosongkan akses data table akses_data
			$ad=$this->db->query("SELECT * FROM akses_data WHERE user_id='$user_id' ");
			$cekad=$ad->rows;
			if(!empty($cekad)){
				$this->db->query("DELETE FROM akses_data WHERE user_id='$user_id' ");
			}
			// kosongkan akses gudang table user_gudang
			$ug=$this->db->query("SELECT * FROM user_gudang WHERE user_id='$user_id' ");
			$cekug=$ug->rows;
			if(!empty($cekug)){
				$this->db->query("DELETE FROM user_gudang WHERE user_id='$user_id' ");
			}
			// kosongkan akses menu table user_menu	
			$um=$this->db->query("SELECT * FROM user_menu WHERE user_id='$user_id' ");
			$cekum=$um->rows;
			if(!empty($cekum)){
				$this->db->query("DELETE FROM user_menu WHERE user_id='$user_id' ");
			}

			$this->db->query("UPDATE users set resign='1' WHERE user_id='$user_id' ");

			$this->session->data['success'] ="Data Berhasil di reset!";
			
			$url = '';
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}

			//$this->redirect($this->url->link('user/aksesmenu', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			echo "sukses";
		}else{
			$this->session->data['warning'] ="Data Berhasil di reset!";

			$url = '';
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}

			$this->redirect($this->url->link('user/aksesmenu', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		
	}
	// end baru
  	public function index() {
    	$this->load->language('user/user');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('user/user');

    	$this->getList();
  	}

  	private function getList() {
			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}
			if (isset($this->request->get['filter_gudang_id'])) {
				$filter_gudang_id = $this->request->get['filter_gudang_id'];
			} else {
				$filter_gudang_id = '';
			}
			if (isset($this->request->get['filter_tglakhir'])) {
				$filter_tglakhir = $this->request->get['filter_tglakhir'];
			} else {
				$filter_tglakhir = '';
			}
			if (isset($this->request->get['filter_jabatan'])) {
				$filter_jabatan = $this->request->get['filter_jabatan'];
			} else {
				$filter_jabatan = '';
			}
			if (isset($this->request->get['filter_divisi'])) {
				$filter_divisi = $this->request->get['filter_divisi'];
			} else {
				$filter_divisi = '';
			}
			if (isset($this->request->get['filter_statuspegawai'])) {
				$filter_statuspegawai = $this->request->get['filter_statuspegawai'];
			} else {
				$filter_statuspegawai = '';
			}
			if (isset($this->request->get['filter_status'])) {
				$filter_status = $this->request->get['filter_status'];
			} else {

				$filter_status = null;
			}
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'username';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_tglakhir'])) {
			$url .= '&filter_tglakhir=' . $this->request->get['filter_tglakhir'];
		}
		if (isset($this->request->get['filter_divisi'])) {
			$url .= '&filter_divisi=' . $this->request->get['filter_divisi'];
		}
		if (isset($this->request->get['filter_jabatan'])) {
			$url .= '&filter_jabatan=' . $this->request->get['filter_jabatan'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['filter_statuspegawai'])) {
			$url .= '&filter_statuspegawai=' . $this->request->get['filter_statuspegawai'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

  	$this->data['users'] = array();

		$data = array(
			'filter_name'	=> $filter_name,
			'filter_gudang_id'	=> $filter_gudang_id,
			'filter_tglakhir'	=> $filter_tglakhir,
			'filter_divisi'	=> $filter_divisi,
			'filter_jabatan'	=> $filter_jabatan,
			'filter_status'	=> 1,
			'filter_statuspegawai'	=> $filter_statuspegawai,
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);

		$user_total = $this->model_user_user->getTotalUsers($data);

		$results = $this->model_user_user->getUsers($data);
		//$this->data['user']= $this->url->link('user/aksesmenu/resign', 'token=' . $this->session->data['token'] . '&user_id=' . $result['user_id'] . $url, 'SSL');
		//print_r($results);
		foreach ($results as $result) {
			$action = array();



			if($result['status'] & $result['resign']==0){
				$action[] = array(
					'text' => "Akses Gudang",
					'href' => $this->url->link('user/aksesmenu/gudang', 'token=' . $this->session->data['token'] . '&user_id=' . $result['user_id'] . $url, 'SSL')
				);
				$action[] = array(
					'text' => 'Akses Data',
					'href' => $this->url->link('user/aksesmenu/aksesdata', 'token=' . $this->session->data['token'] . '&user_id=' . $result['user_id'] . $url, 'SSL')
				);
				$action[] = array(
					'text' => 'Akses Menu',
					'href' => $this->url->link('user/aksesmenu/usermenu', 'token=' . $this->session->data['token'] . '&user_id=' . $result['user_id'] . $url, 'SSL')
				);
				/*
				$action[] = array(
					'text' => 'Resign',
					'href' => $this->url->link('user/aksesmenu/resign', 'token=' . $this->session->data['token'] . '&user_id=' . $result['user_id'] . $url, 'SSL')
				);*/
			}else{

			}

      		$this->data['users'][] = array(
				'user_id'    => $result['user_id'],
				'namagudang'	=> $result['nama'],
				'firstname'   => $result['firstname'],
				'username'   => $result['username'],
				'email'	=> $result['email'],
				'telephone'	=> $result['telephone'],
				'hp'	=> $result['hp'],
				'alamat'	=> $result['alamat'],
				'tglakhir'	=> date('d/m/y',strtotime($result['tglakhir'])),
				'jenispegawai'	=> $result['jenispegawai'],
				'divisi'	=> $result['namadivisi'],
				'jabatan'	=> $result['namajabatan'],
				'status'     => ($result['status'] ? 'Aktif' : 'Tidak Aktif'),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'selected'   => isset($this->request->post['selected']) && in_array($result['user_id'], $this->request->post['selected']),
				'resign'	=> $result['resign'],
				'action'     => $action
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_username'] = $this->language->get('column_username');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');

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

		$url = '';
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_tglakhir'])) {
			$url .= '&filter_tglakhir=' . $this->request->get['filter_tglakhir'];
		}
		if (isset($this->request->get['filter_divisi'])) {
			$url .= '&filter_divisi=' . $this->request->get['filter_divisi'];
		}
		if (isset($this->request->get['filter_jabatan'])) {
			$url .= '&filter_jabatan=' . $this->request->get['filter_jabatan'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['filter_statuspegawai'])) {
			$url .= '&filter_statuspegawai=' . $this->request->get['filter_statuspegawai'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['sort_username'] = $this->url->link('user/aksesmenu', 'token=' . $this->session->data['token'] . '&sort=username' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('user/aksesmenu', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
		$this->data['sort_date_added'] = $this->url->link('user/aksesmenu', 'token=' . $this->session->data['token'] . '&sort=date_added' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $user_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('user/aksesmenu', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->load->model('user/user_group');

    	$this->data['user_groups'] = $this->model_user_user_group->getUserGroups();

			$this->load->model('catalog/gudang');
			$this->data['gudangs']=$this->model_catalog_gudang->getGudangs();
    	$this->load->model('user/divisi');
			$this->data['divisis']=$this->model_user_divisi->getDivisis();

		$this->template = 'user/aksesmenu_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
  	}

	public function gudang() {
    	$this->load->language('catalog/product');

    	$this->document->setTitle("Akses gudang pegawai");

			$this->load->model('user/user');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
			$this->model_user_user->addGudang($this->request->get['user_id'], $this->request->post['gudang']);

			$this->session->data['success'] = 'Data akses gudang berhasil diperbarui.';

			$url = '';
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
			if (isset($this->request->get['filter_gudang_id'])) {
				$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
			}
			if (isset($this->request->get['filter_tglakhir'])) {
				$url .= '&filter_tglakhir=' . $this->request->get['filter_tglakhir'];
			}
			if (isset($this->request->get['filter_divisi'])) {
				$url .= '&filter_divisi=' . $this->request->get['filter_divisi'];
			}
			if (isset($this->request->get['filter_jabatan'])) {
				$url .= '&filter_jabatan=' . $this->request->get['filter_jabatan'];
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['filter_statuspegawai'])) {
				$url .= '&filter_statuspegawai=' . $this->request->get['filter_statuspegawai'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('user/aksesmenu', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		$url = '';
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_tglakhir'])) {
			$url .= '&filter_tglakhir=' . $this->request->get['filter_tglakhir'];
		}
		if (isset($this->request->get['filter_divisi'])) {
			$url .= '&filter_divisi=' . $this->request->get['filter_divisi'];
		}
		if (isset($this->request->get['filter_jabatan'])) {
			$url .= '&filter_jabatan=' . $this->request->get['filter_jabatan'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['filter_statuspegawai'])) {
			$url .= '&filter_statuspegawai=' . $this->request->get['filter_statuspegawai'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

    	if(!isset($this->request->get['user_id'])){
				$this->redirect($this->url->link('user/aksesmenu', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}else{
				if(empty($this->request->get['user_id'])){
					$this->redirect($this->url->link('user/aksesmenu', 'token=' . $this->session->data['token'] . $url, 'SSL'));
				}else{
					$this->data['token'] = $this->session->data['token'];
					$this->load->model('catalog/gudang');

						$this->data['gudangs'] = $this->model_catalog_gudang->getGudangs();

						if (isset($this->request->post['gudang'])) {
							$this->data['gudang'] = $this->request->post['gudang'];
						} elseif (isset($this->request->get['user_id'])) {
							$this->data['gudang'] = $this->model_user_user->getUserGudang($this->request->get['user_id']);
						} else {
							$this->data['gudang'] = array();
						}


				  	$this->data['action'] = $this->url->link('user/aksesmenu/gudang', 'token=' . $this->session->data['token'].'&user_id='.$this->request->get['user_id'] . $url, 'SSL');
						$this->data['cancel'] = $this->url->link('user/aksesmenu', 'token=' . $this->session->data['token'] . $url, 'SSL');

						$this->template = 'user/gudang.tpl';
						$this->children = array(
							'common/header',
							'common/footer'
						);

						$this->response->setOutput($this->render());
				}
			}
  	}

		public function usermenu() {
    	$this->document->setTitle("Akses Menu User");

			$this->load->model('user/user');
			$this->load->model('website/menu');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
			$this->model_user_user->editAkses($this->request->get['user_id'], $this->request->post['user_menu']);

			$this->session->data['success'] = 'Data akses menu user berhasil diperbarui.';

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
			if (isset($this->request->get['filter_tglakhir'])) {
				$url .= '&filter_tglakhir=' . $this->request->get['filter_tglakhir'];
			}
			if (isset($this->request->get['filter_divisi'])) {
				$url .= '&filter_divisi=' . $this->request->get['filter_divisi'];
			}
			if (isset($this->request->get['filter_jabatan'])) {
				$url .= '&filter_jabatan=' . $this->request->get['filter_jabatan'];
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['filter_statuspegawai'])) {
				$url .= '&filter_statuspegawai=' . $this->request->get['filter_statuspegawai'];
			}
			if (isset($this->request->get['user_id'])) {
				$url .= '&user_id=' . $this->request->get['user_id'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('user/aksesmenu', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		if (isset($this->request->get['filter_tglakhir'])) {
			$url .= '&filter_tglakhir=' . $this->request->get['filter_tglakhir'];
		}
		if (isset($this->request->get['filter_divisi'])) {
			$url .= '&filter_divisi=' . $this->request->get['filter_divisi'];
		}
		if (isset($this->request->get['filter_jabatan'])) {
			$url .= '&filter_jabatan=' . $this->request->get['filter_jabatan'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['filter_statuspegawai'])) {
			$url .= '&filter_statuspegawai=' . $this->request->get['filter_statuspegawai'];
		}
		if (isset($this->request->get['user_id'])) {
			$url .= '&user_id=' . $this->request->get['user_id'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

    	if(!isset($this->request->get['user_id'])){
				$this->redirect($this->url->link('user/aksesmenu', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}else{
				if(empty($this->request->get['user_id'])){
					$this->redirect($this->url->link('user/aksesmenu', 'token=' . $this->session->data['token'] . $url, 'SSL'));
				}else{
					$this->data['token'] = $this->session->data['token'];
					$this->load->model('website/menu');

						$this->data['masterdata'] = $this->model_website_menu->getMenuByGroup("master data");
						$this->data['persediaan'] = $this->model_website_menu->getMenuByGroup("persediaan");
						$this->data['customer'] = $this->model_website_menu->getMenuByGroup("customer");
						$this->data['pembelian'] = $this->model_website_menu->getMenuByGroup("pembelian");
						$this->data['penjualan'] = $this->model_website_menu->getMenuByGroup("penjualan");
						$this->data['produksi'] = $this->model_website_menu->getMenuByGroup("produksi");
						$this->data['keuangan'] = $this->model_website_menu->getMenuByGroup("keuangan");
						$this->data['akuntansi'] = $this->model_website_menu->getMenuByGroup("akuntansi");
						$this->data['kepegawaian'] = $this->model_website_menu->getMenuByGroup("kepegawaian");
						$this->data['laporan'] = $this->model_website_menu->getMenuByGroup("laporan");
						$this->data['pengaturan'] = $this->model_website_menu->getMenuByGroup("pengaturan");
						$this->data['pajak'] = $this->model_website_menu->getMenuByGroup("pajak");

						if (isset($this->request->post['user_menu'])) {
							$this->data['user_menu'] = $this->request->post['user_menu'];
						} elseif (isset($this->request->get['user_id'])) {
							$this->data['user_menu'] = $this->model_user_user->getUserMenus($this->request->get['user_id']);
						} else {
							$this->data['user_menu'] = array();
						}


				  	$this->data['action'] = $this->url->link('user/aksesmenu/usermenu', 'token=' . $this->session->data['token'].'&user_id='.$this->request->get['user_id'] . $url, 'SSL');
						$this->data['cancel'] = $this->url->link('user/aksesmenu', 'token=' . $this->session->data['token'] . $url, 'SSL');

						$this->template = 'user/usermenu.tpl';
						$this->children = array(
							'common/header',
							'common/footer'
						);

						$this->response->setOutput($this->render());
				}
			}
  	}
		public function aksesdata() {
    	$this->document->setTitle("Akses Data User");

			$this->load->model('user/user');
			//$this->load->model('user/akses');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
			$this->model_user_user->editAksesData($this->request->get['user_id'], $this->request->post['user_menu']);

			$this->session->data['success'] = 'Akses data user berhasil diperbarui.';

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
			if (isset($this->request->get['filter_tglakhir'])) {
				$url .= '&filter_tglakhir=' . $this->request->get['filter_tglakhir'];
			}
			if (isset($this->request->get['filter_divisi'])) {
				$url .= '&filter_divisi=' . $this->request->get['filter_divisi'];
			}
			if (isset($this->request->get['filter_jabatan'])) {
				$url .= '&filter_jabatan=' . $this->request->get['filter_jabatan'];
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['filter_statuspegawai'])) {
				$url .= '&filter_statuspegawai=' . $this->request->get['filter_statuspegawai'];
			}
			if (isset($this->request->get['user_id'])) {
				$url .= '&user_id=' . $this->request->get['user_id'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('user/aksesmenu', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		if (isset($this->request->get['filter_tglakhir'])) {
			$url .= '&filter_tglakhir=' . $this->request->get['filter_tglakhir'];
		}
		if (isset($this->request->get['filter_divisi'])) {
			$url .= '&filter_divisi=' . $this->request->get['filter_divisi'];
		}
		if (isset($this->request->get['filter_jabatan'])) {
			$url .= '&filter_jabatan=' . $this->request->get['filter_jabatan'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['filter_statuspegawai'])) {
			$url .= '&filter_statuspegawai=' . $this->request->get['filter_statuspegawai'];
		}
		if (isset($this->request->get['user_id'])) {
			$url .= '&user_id=' . $this->request->get['user_id'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

    	if(!isset($this->request->get['user_id'])){
				$this->redirect($this->url->link('user/aksesmenu', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}else{
				if(empty($this->request->get['user_id'])){
					$this->redirect($this->url->link('user/aksesmenu', 'token=' . $this->session->data['token'] . $url, 'SSL'));
				}else{
					$this->data['token'] = $this->session->data['token'];


						if (isset($this->request->post['user_menu'])) {
							$this->data['user_menu'] = $this->request->post['user_menu'];
						} elseif (isset($this->request->get['user_id'])) {
							$this->data['user_menu'] = $this->model_user_user->getUserAksesData($this->request->get['user_id']);
						} else {
							$this->data['user_menu'] = array();
						}


				  	$this->data['action'] = $this->url->link('user/aksesmenu/aksesdata', 'token=' . $this->session->data['token'].'&user_id='.$this->request->get['user_id'] . $url, 'SSL');
						$this->data['cancel'] = $this->url->link('user/aksesmenu', 'token=' . $this->session->data['token'] . $url, 'SSL');

						$this->template = 'user/aksesdata.tpl';
						$this->children = array(
							'common/header',
							'common/footer'
						);

						$this->response->setOutput($this->render());
				}
			}
  	}
}
?>
