<?php
class ControllerLocalisationCity extends Controller {

	private $error = array();

	public function index() {
		$this->load->language('localisation/city');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('localisation/city');
		
    	$this->getList();
	}

	public function insert() {
		$this->load->language('localisation/city');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('localisation/city');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_localisation_city->addCity($this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
		  
			$url = '';

			if (isset($this->request->get['filter_zone'])) {
				$url .= '&filter_zone=' . $this->request->get['filter_zone'];
			}
			
			if (isset($this->request->get['filter_city'])) {
				$url .= '&filter_city=' . $this->request->get['filter_city'];
			}
			
			if (isset($this->request->get['filter_code'])) {
				$url .= '&filter_code=' . $this->request->get['filter_code'];
			}
			
			if (isset($this->request->get['filter_country_id'])) {
				$url .= '&filter_country_id=' . $this->request->get['filter_country_id'];
			}
			
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
							
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect($this->url->link('localisation/city', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
    	
    	$this->getForm();

	}

	public function update() {
		$this->load->language('localisation/city');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('localisation/city');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_localisation_city->editCity($this->request->get['city_id'], $this->request->post);
	  		
			$this->session->data['success'] = $this->language->get('text_success');
	  
			$url = '';

			if (isset($this->request->get['filter_zone'])) {
				$url .= '&filter_zone=' . $this->request->get['filter_zone'];
			}
			
			if (isset($this->request->get['filter_city'])) {
				$url .= '&filter_city=' . $this->request->get['filter_city'];
			}
			
			if (isset($this->request->get['filter_code'])) {
				$url .= '&filter_code=' . $this->request->get['filter_code'];
			}
			
			if (isset($this->request->get['filter_country_id'])) {
				$url .= '&filter_country_id=' . $this->request->get['filter_country_id'];
			}
			
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
						
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect($this->url->link('localisation/city', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
    
    	$this->getForm();
  	}   

	public function delete() {
		$this->load->language('localisation/city');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('localisation/city');
			
    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $city_id) {
				$this->model_localisation_city->deleteCity($city_id);
			}
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_zone'])) {
				$url .= '&filter_zone=' . $this->request->get['filter_zone'];
			}
			
			if (isset($this->request->get['filter_city'])) {
				$url .= '&filter_city=' . $this->request->get['filter_city'];
			}
			
			if (isset($this->request->get['filter_code'])) {
				$url .= '&filter_code=' . $this->request->get['filter_code'];
			}
			
			if (isset($this->request->get['filter_country_id'])) {
				$url .= '&filter_country_id=' . $this->request->get['filter_country_id'];
			}
			
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
						
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect($this->url->link('localisation/city', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}
    
    	$this->getList();
  	}  

	private function getList() {
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'city_zone'; 
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		if (isset($this->request->get['filter_zone'])) {
			$filter_zone = $this->request->get['filter_zone'];
		} else {
			$filter_zone = NULL;
		}

		if (isset($this->request->get['filter_city'])) {
			$filter_city = $this->request->get['filter_city'];
		} else {
			$filter_city = NULL;
		}
		
		if (isset($this->request->get['filter_code'])) {
			$filter_code = $this->request->get['filter_code'];
		} else {
			$filter_code = NULL;
		}

		if (isset($this->request->get['filter_country_id'])) {
			$filter_country_id = $this->request->get['filter_country_id'];
		} else {
			$filter_country_id = NULL;
		}
		
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = NULL;
		}
				
		$url = '';

		if (isset($this->request->get['filter_zone'])) {
			$url .= '&filter_zone=' . $this->request->get['filter_zone'];
		}
		
		if (isset($this->request->get['filter_city'])) {
			$url .= '&filter_city=' . $this->request->get['filter_city'];
		}
		
		if (isset($this->request->get['filter_code'])) {
			$url .= '&filter_code=' . $this->request->get['filter_code'];
		}
			
		if (isset($this->request->get['filter_country_id'])) {
			$url .= '&filter_country_id=' . $this->request->get['filter_country_id'];
		}
		
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}	
			
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('localisation/city', 'token=' . $this->session->data['token'] . $url, 'SSL'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['insert'] = $this->url->link('localisation/city/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('localisation/city/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['reset'] = $this->url->link('localisation/city', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cities'] = array();

		$data = array(
			'filter_zone'              => $filter_zone, 
			'filter_city'              => $filter_city, 
			'filter_code'			   => $filter_code, 
			'filter_country_id'        => $filter_country_id, 
			'filter_status'            => $filter_status, 
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                    => $this->config->get('config_admin_limit')
		);
		
		$city_total = $this->model_localisation_city->getTotalCities($data);
	
		$results = $this->model_localisation_city->getCities($data);
 
    	foreach ($results as $result) {
			$action = array();
		
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('localisation/city/update', 'token=' . $this->session->data['token'] . '&city_id=' . $result['city_id'] . $url, 'SSL')
			);
			
			$this->data['cities'][] = array(
				'zone'			=> $result['city_zone'],
				'city_id'		=> $result['city_id'],
				'name'			=> $result['city_name'],
				'code'			=> $result['city_code'],
				'country'		=> $result['city_country'],
				'status'		=> ($result['city_status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'selected'		=> isset($this->request->post['selected']) && in_array($result['city_id'], $this->request->post['selected']),
				'jnereg'		=> $result['city_jnereg'],
				'jneoke'		=> $result['city_jneoke'],
				'jneyes'		=> $result['city_jneyes'],
				'tikireg'		=> $result['city_tikireg'],
				'tikions'		=> $result['city_tikions'],
				'kgp'           => $result['city_kgp'],
				'action'		=> $action
			);
		}	
					
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');		
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_zone']		= $this->language->get('column_zone');
		$this->data['column_name']		= $this->language->get('column_name');
		$this->data['column_code']		= $this->language->get('column_code');
		$this->data['column_country']   = $this->language->get('column_country');
		$this->data['column_status']	= $this->language->get('column_status');
		$this->data['column_jnereg']	= $this->language->get('column_jnereg');
		$this->data['column_jneoke']	= $this->language->get('column_jneoke');
		$this->data['column_jneyes']	= $this->language->get('column_jneyes');
		$this->data['column_tikireg']	= $this->language->get('column_tikireg');
		$this->data['column_tikions']	= $this->language->get('column_tikions');
		$this->data['column_kgp']       = $this->language->get('column_kgp');
		$this->data['column_action']    = $this->language->get('column_action');	
		
		$this->data['button_insert']	= $this->language->get('button_insert');
		$this->data['button_delete']	= $this->language->get('button_delete');
		$this->data['button_filter']	= $this->language->get('button_filter');

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->session->data['error'])) {
			$this->data['error_warning'] = $this->session->data['error'];
			
			unset($this->session->data['error']);
		} elseif (isset($this->error['warning'])) {
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

		if (isset($this->request->get['filter_zone'])) {
			$url .= '&filter_zone=' . $this->request->get['filter_zone'];
		}
		
		if (isset($this->request->get['filter_city'])) {
			$url .= '&filter_city=' . $this->request->get['filter_city'];
		}
		
		if (isset($this->request->get['filter_code'])) {
			$url .= '&filter_code=' . $this->request->get['filter_code'];
		}
			
		if (isset($this->request->get['filter_country_id'])) {
			$url .= '&filter_country_id=' . $this->request->get['filter_country_id'];
		}
		
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}	
			
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['sort_zone'] = $this->url->link('localisation/city', 'token=' . $this->session->data['token'] . '&sort=city_zone' . $url, 'SSL');
		$this->data['sort_name'] = $this->url->link('localisation/city', 'token=' . $this->session->data['token'] . '&sort=city_name' . $url, 'SSL');
		$this->data['sort_code'] = $this->url->link('localisation/city', 'token=' . $this->session->data['token'] . '&sort=city_code' . $url, 'SSL');
		$this->data['sort_country'] = $this->url->link('localisation/city', 'token=' . $this->session->data['token'] . '&sort=city_country' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('localisation/city', 'token=' . $this->session->data['token'] . '&sort=city_status' . $url, 'SSL');
				
		$url = '';

		if (isset($this->request->get['filter_zone'])) {
			$url .= '&filter_zone=' . $this->request->get['filter_zone'];
		}
		
		if (isset($this->request->get['filter_city'])) {
			$url .= '&filter_city=' . $this->request->get['filter_city'];
		}
		
		if (isset($this->request->get['filter_code'])) {
			$url .= '&filter_code=' . $this->request->get['filter_code'];
		}

		if (isset($this->request->get['filter_country_id'])) {
			$url .= '&filter_country_id=' . $this->request->get['filter_country_id'];
		}
		
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
					
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $city_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('localisation/city', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['filter_zone'] = $filter_zone;
		$this->data['filter_city'] = $filter_city;
		$this->data['filter_code'] = $filter_code;
		$this->data['filter_country_id'] = $filter_country_id;
		$this->data['filter_status'] = $filter_status;
		
		$this->load->model('localisation/country');
		
    	$this->data['countries'] = $this->model_localisation_country->getCountries(array('status' => 1));
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'localisation/city_list.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render());
  	}

	private function getForm() {
    	$this->data['heading_title'] = $this->language->get('heading_title');
 
    	$this->data['text_enabled']		= $this->language->get('text_enabled');
    	$this->data['text_disabled']	= $this->language->get('text_disabled');
		$this->data['text_select']		= $this->language->get('text_select');
    	
		$this->data['entry_country']	= $this->language->get('entry_country');
		$this->data['entry_zone']		= $this->language->get('entry_zone');
		$this->data['entry_name']		= $this->language->get('entry_name');
		$this->data['entry_code']		= $this->language->get('entry_code');
		$this->data['entry_status']		= $this->language->get('entry_status');
		$this->data['entry_jnereg']		= $this->language->get('entry_jnereg');
		$this->data['entry_jneoke']		= $this->language->get('entry_jneoke');
		$this->data['entry_jneyes']		= $this->language->get('entry_jneyes');
		$this->data['entry_tikireg']	= $this->language->get('entry_tikireg');
		$this->data['entry_tikions']	= $this->language->get('entry_tikions');
		$this->data['entry_kgp']		= $this->language->get('entry_kgp');

		$this->data['button_save']		= $this->language->get('button_save');
    	$this->data['button_cancel']	= $this->language->get('button_cancel');

		$this->data['token'] = $this->session->data['token'];

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['country'])) {
			$this->data['error_country'] = $this->error['country'];
		} else {
			$this->data['error_country'] = '';
		}

		if (isset($this->error['zone'])) {
			$this->data['error_zone'] = $this->error['zone'];
		} else {
			$this->data['error_zone'] = '';
		}

 		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}
		
		$url = '';
		
		if (isset($this->request->get['filter_zone'])) {
			$url .= '&filter_zone=' . $this->request->get['filter_zone'];
		}
		
		if (isset($this->request->get['filter_city'])) {
			$url .= '&filter_city=' . $this->request->get['filter_city'];
		}

		if (isset($this->request->get['filter_code'])) {
			$url .= '&filter_code=' . $this->request->get['filter_code'];
		}
		
		if (isset($this->request->get['filter_country_id'])) {
			$url .= '&filter_country_id=' . $this->request->get['filter_country_id'];
		}
		
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
								
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
 			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('localisation/city', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		if (!isset($this->request->get['city_id'])) {
			$this->data['action'] = $this->url->link('localisation/city/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('localisation/city/update', 'token=' . $this->session->data['token'] . '&city_id=' . $this->request->get['city_id'] . $url, 'SSL');
		}
		  
		$this->data['cancel'] = $this->url->link('localisation/city', 'token=' . $this->session->data['token'] . $url, 'SSL');

    	if (isset($this->request->get['city_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$city_info = $this->model_localisation_city->getCity($this->request->get['city_id']);
    	}

		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (isset($city_info)) {
			$this->data['status'] = $city_info['city_status'];
		} else {
			$this->data['status'] = '1';
		}
		
		if (isset($this->request->post['jnereg'])) {
			$this->data['jnereg'] = $this->request->post['jnereg'];
		} elseif (isset($city_info)) {
			$this->data['jnereg'] = $city_info['city_jnereg'];
		} else {
			$this->data['jnereg'] = '0';
		}
		
		if (isset($this->request->post['jneoke'])) {
			$this->data['jneoke'] = $this->request->post['jneoke'];
		} elseif (isset($city_info)) {
			$this->data['jneoke'] = $city_info['city_jneoke'];
		} else {
			$this->data['jneoke'] = '0';
		}
		
		if (isset($this->request->post['jneyes'])) {
			$this->data['jneyes'] = $this->request->post['jneyes'];
		} elseif (isset($city_info)) {
			$this->data['jneyes'] = $city_info['city_jneyes'];
		} else {
			$this->data['jneyes'] = '0';
		}
		
		if (isset($this->request->post['tikireg'])) {
			$this->data['tikireg'] = $this->request->post['tikireg'];
		} elseif (isset($city_info)) {
			$this->data['tikireg'] = $city_info['city_tikireg'];
		} else {
			$this->data['tikireg'] = '0';
		}
		
		if (isset($this->request->post['tikions'])) {
			$this->data['tikions'] = $this->request->post['tikions'];
		} elseif (isset($city_info)) {
			$this->data['tikions'] = $city_info['city_tikions'];
		} else {
			$this->data['tikions'] = '0';
		}
		
		if (isset($this->request->post['kgp'])) {
			$this->data['kgp'] = $this->request->post['kgp'];
		} elseif (isset($city_info)) {
			$this->data['kgp'] = $city_info['city_kgp'];
		} else {
			$this->data['kgp'] = '0';
		}
		
		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} elseif (isset($city_info)) {
			$this->data['name'] = $city_info['city_name'];
		} else {
			$this->data['name'] = '';
		}

		if (isset($this->request->post['code'])) {
			$this->data['code'] = $this->request->post['code'];
		} elseif (isset($city_info)) {
			$this->data['code'] = $city_info['city_code'];
		} else {
			$this->data['code'] = '';
		}

		if (isset($this->request->post['zone_id'])) {
			$this->data['zone_id'] = $this->request->post['zone_id'];
		} elseif (isset($city_info)) {
			$this->data['zone_id'] = $city_info['city_zone_id'];
		} else {
			$this->data['zone_id'] = '';
		}

		if (isset($this->request->post['country_id'])) {
			$this->data['country_id'] = $this->request->post['country_id'];
		} elseif (isset($city_info)) {
			$this->data['country_id'] = $city_info['city_country_id'];
		} else {
			$this->data['country_id'] = '';
		}
						
		$this->load->model('localisation/country');
		
    	$this->data['countries'] = $this->model_localisation_country->getCountries(array('status' => 1));
			
		$this->template = 'localisation/city_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render());
	}

	public function zone() {
		$output = '';
		
		$this->load->model('localisation/zone');
		
		$results = $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']);
		
		foreach ($results as $result) {
			$output .= '<option value="' . $result['zone_id'] . '"';

			if (isset($this->request->get['zone_id']) && ($this->request->get['zone_id'] == $result['zone_id'])) {
				$output .= ' selected="selected"';
			}

			$output .= '>' . $result['name'] . '</option>';
		}

		if (!$results) {
			if (!$this->request->get['zone_id']) {
		  		$output .= '<option value="0" selected="selected">' . $this->language->get('text_none') . '</option>';
			} else {
				$output .= '<option value="0">' . $this->language->get('text_none') . '</option>';
			}
		}

		$this->response->setOutput($output);
	}

	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'localisation/city')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

		if ($this->request->post['country_id'] == 'FALSE') {
      		$this->error['country'] = $this->language->get('error_country');
    	}

		if ($this->request->post['zone_id'] == 'FALSE') {
      		$this->error['zone'] = $this->language->get('error_zone');
    	}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 128)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}

	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'localisation/city')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}	
	  	 
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}  
  	} 	


}
?>
