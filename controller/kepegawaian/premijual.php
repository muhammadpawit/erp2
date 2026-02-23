<?php
class ControllerKepegawaianPremijual extends Controller {
	private $error = array();

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
			if (isset($this->request->get['filter_periode'])) {
				$filter_periode = $this->request->get['filter_periode'];
			} else {
				$filter_periode = '';
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
		if (isset($this->request->get['filter_periode'])) {
			$url .= '&filter_periode=' . $this->request->get['filter_periode'];
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}


    	$this->data['users'] = array();
		if(!empty($filter_periode)){
			$this->load->model('kepegawaian/periode');
			$this->load->model('kepegawaian/premijual');
			$periode=$this->model_kepegawaian_periode->getPeriode($filter_periode);
			$this->data['periode']=$periode;

			$date_start=date('Y-m-01',strtotime($periode['tgl_awal']));
			$date_end=date('Y-m-t',strtotime($periode['tgl_awal']));
			/*if($filter_periode == 48){
				$date_start='2017-06-01';
				$date_end='2017-06-25';
			}*/

			$this->data['date_start']=date('d/m/y',strtotime($date_start));
			$this->data['date_end']=date('d/m/y',strtotime($date_end));
			//akumulasi premijual kernet
			//akumulasi premijual sopir

			$data = array(
				'filter_name'	=> $filter_name,
				'start' => ($page - 1) * $this->config->get('config_admin_limit'),
				'limit' => $this->config->get('config_admin_limit')
			);

			$user_total = $this->model_user_user->getTotalUsers($data);

			$results = $this->model_user_user->getUsers($data);
			//print_r($results);
			foreach ($results as $result) {
				$premi=$this->model_kepegawaian_premijual->hitungpremi($result['user_id'],$date_start,$date_end);
				//$kernet=$this->model_kepegawaian_premijual->akumulasiKernet($result['user_id'],$date_start,$date_end);
				//print_r($premi);
				$action = array();

				/*$action[] = array(
					'text' => $this->language->get('text_edit'),
					'href' => $this->url->link('user/user/update', 'token=' . $this->session->data['token'] . '&user_id=' . $result['user_id'] . $url, 'SSL')
				);
				if($result['status']){
					$action[] = array(
						'text' => "Akses Gudang",
						'href' => $this->url->link('user/user/gudang', 'token=' . $this->session->data['token'] . '&user_id=' . $result['user_id'] . $url, 'SSL')
					);
				}
				*/
	      $this->data['users'][] = array(
					'user_id'    => $result['user_id'],
					'firstname'   => $result['firstname'],
					'akumulasisopir' => $premi['akumulasi'],
					'total'	=> $this->currency->format($premi['total'])

				);
			}
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
		if (isset($this->request->get['filter_periode'])) {
			$url .= '&filter_periode=' . $this->request->get['filter_periode'];
		}


		$pagination = new Pagination();
		$pagination->total = $user_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('kepegawaian/premijual', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;


		$this->load->model('kepegawaian/periode');

    	$this->data['periodes'] = $this->model_kepegawaian_periode->getPeriodes();
			$this->template = 'kepegawaian/premijual_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
  	}

		public function autocomplete() {
			$json = array();

			$this->load->model('user/user');

				if (isset($this->request->get['q'])) {
					$filter_name = $this->request->get['q'];
				} else {
					$filter_name = null;
				}

				if (isset($this->request->get['j'])) {
					$filter_jabatan = $this->request->get['j'];
				} else {
					$filter_jabatan = null;
				}

				if (isset($this->request->get['limit'])) {
					$limit = $this->request->get['limit'];
				} else {
					$limit = 20;
				}

				$data = array(
				'filter_name'	  => $filter_name,
				'filter_jabatan'	=> $filter_jabatan,
				'start'	=>0,
				'limit'	=> 10
					//'start'               => 0,
					//'limit'               => $limit
				);

				$results = $this->model_user_user->getUsers($data);

				foreach ($results as $result) {
					$json[] = array(
						'id' => strip_tags(html_entity_decode($result['firstname'], ENT_QUOTES, 'UTF-8')),
						'text'       => strip_tags(html_entity_decode($result['firstname'], ENT_QUOTES, 'UTF-8')),

					);
				}


			$this->response->setOutput(json_encode($json));
		}
}
?>
