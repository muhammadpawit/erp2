 <?php
class ControllerPerakitanPerakitan extends Controller {
	private $error=array();
	
	// baru 2 September 2019
	public function proses(){
		$this->load->model('perakitan/perakitan');
		$this->load->language('catalog/pembelian');
		$this->load->model('gudang/product');
		$this->document->setTitle('Tukar Tabung');

		$this->load->model('perakitan/perakitan');
			$post = $this->request->post;
			if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
				if($this->user->getUsername()=="pawit"){
					echo "<pre>";
					print_r($post);
					exit;
				}
				$si = $this->model_perakitan_perakitan->simpanproses($this->request->post);
				
				$url = '';
				if (isset($this->request->get['filter_tanggal'])) {
					$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
				}


				if (isset($this->request->get['filter_gudang_id'])) {
					$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
				}
				if (isset($this->request->get['filter_tabunghasil'])) {
					$url .= '&filter_tabunghasil=' . $this->request->get['filter_tabunghasil'];
				}

				if (isset($this->request->get['filter_status'])) {
					$url .= '&filter_status=' . $this->request->get['filter_status'];
				}

					if (isset($this->request->get['page'])) {
						$url .= '&page=' . $this->request->get['page'];
					}

				$this->redirect($this->url->link('perakitan/perakitan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
				
			}
	}
	
	public function modaldetail(){
		
		$rests = array();

		$this->load->model('pembelian/permintaanpembelian');
		$this->load->model('pembelian/pembeliankredit');
		$this->load->model('pembelian/pembelianimport');
		

			if (isset($this->request->get['q'])) {
				$filter_no_surat = $this->request->get['q'];
			} else {
				$filter_no_surat = '';
			}
			if (isset($this->request->get['j'])) {
				$jenis_pembelian = $this->request->get['j'];
			} else {
				$jenis_pembelian = '';
			}


			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			if(isset($this->request->get['status'])){
				if(!empty($this->request->get['status'])){
					if($this->request->get['status'] == 5){
						$status=array('<>',3);
					}else{
						$status=$this->request->get['status'];
					}
				}else{
					$status=2;
				}
			}else{
				$status=2;
			}
			$data = array(
				'no_surat'         => array('LIKE',$filter_no_surat),
				'status'	=> $status,
				'jenis_pembelian'	=> $jenis_pembelian
				//'jenis_pembelian'	=> !mpty()$jenis_pembelian
			);
			$start=0;
			$limit=0;
			$column=array('id','no_surat');
			$join=array();
			
			$results = $this->model_pembelian_permintaanpembelian->getPermintaanPembelians($column,$join,array(),$data,array(),$limit,$start);
			foreach($results as $r){
				$tampil=true;
				/*if (isset($this->request->get['s'])) {
					if($jenis_pembelian == 2){
						$pem=$this->model_pembelian_pembeliankredit->getPermintaanPembelian(array(),array(),array('surat_id'=>$r['id'],'status'=>array('<>',3)));
					}
					if($jenis_pembelian == 3){
						$pem=$this->model_pembelian_pembelianimport->getPermintaanPembelian(array(),array(),array('surat_id'=>$r['id'],'status'=>array('<>',3)));
					}
					if(!empty($pem)){
						$tampil=false;
					}
				}
				if($tampil){
					$rests[]=array(
						'id'	=> $r['id'],
						'text'	=> $r['no_surat']
					);
				}*/
			}
			// baru 2 September 2019 
			$this->load->model('perakitan/perakitan');
			$this->load->model('gudang/product');
			$this->load->model('catalog/gudang');
			$r = $this->model_perakitan_perakitan->getOne($this->request->get['id']);
			$gudang = $this->model_catalog_gudang->getGudang($r['gudang_id']);
			$this->data['products'] = $this->model_perakitan_perakitan->getOne($this->request->get['id']);
			$this->data['kproducts'] = $this->model_perakitan_perakitan->getDetail($this->request->get['id']);
			$this->data['namagudang'] = $gudang['nama'];
				$rests=array(
						'id'	=> $r['id'],
						'product_id'	=> $r['product_id'],
						'nama_product'	=> $r['nama_product'],
						'qty'	=> $r['qty'],
						'gudang_id'	=> $r['gudang_id'],
						'tanggal_perakitan'	=> $r['tanggal_perakitan'],
						'products' => $this->data['kproducts'],
						'text'	=> $r
					);
			// end baru
			//$this->response->setOutput(json_encode($rests));
			// baru 18 September 2019
			$products = $this->model_perakitan_perakitan->getDetail($this->request->get['id']);
			$status=null;
			$act=null;
			$notif=null;
			$pro=0;
			$proses = $this->url->link('perakitan/perakitan/proses', 'token=' . $this->session->data['token'].$url, 'SSL');
			echo '
			<form method="post" action="'.$proses.'">
          <input type="hidden" name="id" class="form-control" value="'.$this->request->get['id'].'">
          <input type="hidden" name="gudang_id" value="'.$r['gudang_id'].'">
          <input type="hidden" name="qtyperakitan" value="'.$r['qty'].'">
          <input type="hidden" name="product_id" value="'.$r['product_id'].'">
          <input type="hidden" name="nama_product" value="'.$r['nama_product'].'">
          <div class="form-group">
            <table class="table table-bordered" id="tblp">
              <tr>
                <thead>
				  <th>Gudang</th>
                  <th>Produk Hasil</th>
                  <th>Qty</th>
                </thead>
              </tr>
              <tbody>
				<tr>
					<td>'.$gudang['nama'].'</td>
					<td>'.$r['nama_product'].'</td>
					<td>'.$r['qty'].'</td>
				</tr>
              </tbody>
            </table>
          </div>
          <div class="form-group">
            <label>Komponen Produk</label>
            <table class="table table-bordered" id="tbl">
               <thead>
                 <tr>
                   <th>Nama Produk</th>
                   <th>Qty Perakitan</th>
                   <th>Stok saat ini</th>
                   <th>Status</th>
                 </tr>
               </thead>
               <tbody>';
                foreach($products as $p){
					$qtygudang = $this->model_perakitan_perakitan->getOneproductGudang($p['product_id'],$r['gudang_id']);
					if($qtygudang['quantity']>=$p['quantity']){
						$status="Dapat diproses";
						$pro+=1;
					}else{
						$status="Terdapat komponen produk yang tidak dapat diproses";
						$pro+=2;
					}
					echo "<tr>";
					echo "<td>".$p['product_name']."</td>";
					echo "<td>".$p['quantity']."</td>";
					echo "<td>".$qtygudang['quantity']."</td>";
					echo "<td>".$status."</td>";
					echo "</tr>";
				}
				if($pro==2){
					$statuss="Dapat diproses";
					$act='<p class="text-center"><button type="submit" class="btn btn-success">Ya</button>&nbsp;&nbsp;&nbsp;';
				}else{
					$statuss="Tidak Dapat diproses";
					$act='<button onclick="refresh()" type="button" class="btn btn-default" data-dismiss="modal" disabled>Ya</button>';
					$notif='<p class="alert alert-warning">'.$statuss.'</p>';
				}
               echo '</tbody>
            </table>
          </div>
          <div class="form-group">
			'.$notif.'
		  </div>
		  <div class="form-group">
          <p class="text-center">
            '.$act.'
          <button onclick="refresh()" type="button" class="btn btn-danger" data-dismiss="modal">No</button>  
          </p>
          </div>
        </form>';
			// end baru
	}
	
	public function perdeetail(){
		
		$rests = array();

		$this->load->model('pembelian/permintaanpembelian');
		$this->load->model('pembelian/pembeliankredit');
		$this->load->model('pembelian/pembelianimport');
		

			if (isset($this->request->get['q'])) {
				$filter_no_surat = $this->request->get['q'];
			} else {
				$filter_no_surat = '';
			}
			if (isset($this->request->get['j'])) {
				$jenis_pembelian = $this->request->get['j'];
			} else {
				$jenis_pembelian = '';
			}


			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			if(isset($this->request->get['status'])){
				if(!empty($this->request->get['status'])){
					if($this->request->get['status'] == 5){
						$status=array('<>',3);
					}else{
						$status=$this->request->get['status'];
					}
				}else{
					$status=2;
				}
			}else{
				$status=2;
			}
			$data = array(
				'no_surat'         => array('LIKE',$filter_no_surat),
				'status'	=> $status,
				'jenis_pembelian'	=> $jenis_pembelian
				//'jenis_pembelian'	=> !mpty()$jenis_pembelian
			);
			$start=0;
			$limit=0;
			$column=array('id','no_surat');
			$join=array();
			
			$results = $this->model_pembelian_permintaanpembelian->getPermintaanPembelians($column,$join,array(),$data,array(),$limit,$start);
			foreach($results as $r){
				$tampil=true;
				
			}
			// baru 2 September 2019 
			$this->load->model('perakitan/perakitan');
			$this->load->model('gudang/product');
			$this->load->model('catalog/gudang');
			$r = $this->model_perakitan_perakitan->getOne($this->request->get['id']);
			// baru 18 September 2019
				echo "<tr>";
				echo "<td>".$r['nama_product']."</td>";
				echo "<td>".$r['qty']."</td>";			
			// end baru
	}
	
	// end baru 
	
	public function index() {
		$this->load->model('perakitan/perakitan');
		$this->load->model('catalog/gudang');
		$this->document->setTitle('Perakitan Produk');
		$this->data['proses'] = $this->url->link('perakitan/perakitan/proses', 'token=' . $this->session->data['token'].$url, 'SSL');
		if (isset($this->request->get['filter_tanggal'])) {
			$filter_tanggal = $this->request->get['filter_tanggal'];
		} else {
			$filter_tanggal = '';
		}

		if (isset($this->request->get['filter_tabunghasil'])) {
			$filter_tabunghasil = $this->request->get['filter_tabunghasil'];
		} else {
			$filter_tabunghasil = '';
		}


		if (isset($this->request->get['filter_gudang_id'])) {
			$filter_gudang_id = $this->request->get['filter_gudang_id'];
		} else {
			$filter_gudang_id = null;
		}
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}


		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}


		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		
		if (isset($this->request->get['filter_tabunghasil'])) {
			$url .= '&filter_tabunghasil=' . $this->request->get['filter_tabunghasil'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->load->model('gudang/tukartabung');
		$this->load->model('gudang/product');

		$this->data['permintaans'] = array();
		$column=array('tukartabung.*','gudang.nama');
		$join=array();
    	$join[]=array(
			'tablename'	=> 'product',
			'firsttable'	=>'tukartabung.tabung_b',
			'secondtable'	=> 'product.product_id'
		);
		$leftjoin=array();
		$leftjoin[]=array(
			'tablename'	=> 'gudang',
			'firsttable'	=>'tukartabung.gudang_id',
			'secondtable'	=> 'gudang.gudang_id'
		);

		$data = array(
			'tgl_tukar'      =>$filter_tanggal,
			'tukartabung.status'			=> $filter_status,
			'tukartabung.gudang_id'			=> $filter_gudang_id,
      		'product.name'			=> array('LIKE',$filter_tabunghasil),
			'tukartabung.hapus'	=> 0,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);
		$limit=20;
		$offset=($page - 1) * $this->config->get('config_admin_limit');

		$order=array(
			'tukartabung.id'	=> 'DESC',
			'tukartabung.status'	=> 'ASC'
		);
		
		$filter = array(
			'filter_name' => $filter_name,
			'gudang_id'  => $filter_gudang_id,
			'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);
		if($this->user->getUsername()=="pawitw"){
			echo "<pre>";print_r($filter);exit;
		}
		$product_total = $this->model_perakitan_perakitan->totalperakitan($filter);
		$this->data['no']=1;
		$this->data['filter_gudang_id']=$filter_gudang_id;
		$this->data['filter_name']=$filter_name;
		// $results = $this->model_gudang_tukartabung->getTukarTabungs($column,$join,$leftjoin,$data,$order,$limit,$offset);
		$results = $this->model_perakitan_perakitan->getAll($filter);
				if($this->user->getUsername()=="pawits"){
					echo "<pre>";
					print_r($results);
					exit;
				}
		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('perakitan/perakitan/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);
				/*$action[] = array(
					'text' => 'Proses',
					'href' => $this->url->link('perakitan/perakitan', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);*/
			if($result['status']<>1){
				$action[] = array(
					'text' => 'Hapus',
					'href' => $this->url->link('perakitan/perakitan/batalkan', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);
			}
			$details = $this->model_perakitan_perakitan->getDetail($result['id']);
			$gudang = $this->model_catalog_gudang->getGudang($result['gudang_id']);
			$this->data['products'][] = array(
				'id' => $result['id'],
				'gudang_id' => $result['gudang_id'],
				'namagudang' => $gudang['nama'],
				'product_id' => $result['product_id'],
				'name' => $result['nama_product'],
				'tanggal_perakitan' => $result['tanggal_perakitan'],
				'tgl_proses' => $result['tgl_proses'],
				'quantity' => $result['qty'],
				'status'	=> ($result['status']==0)?'belum diproses':'sudah diproses',
				'stat' => $result['status'],
				'details' => $details,
				'action'	=> $action
			);
		}
		/*
		echo "<pre>";
		print_r($action);
		exit;
		*/
		$this->data['heading_title'] = 'Permintaan Pembelian';

		$this->data['token'] = $this->session->data['token'];
		$url='';
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}


		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_tabunghasil'])) {
			$url .= '&filter_tabunghasil=' . $this->request->get['filter_tabunghasil'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

	    $this->load->model('catalog/gudang');
	    $this->data['gudangs']=$this->model_catalog_gudang->getGudangs(true);

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('perakitan/perakitan', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

	//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);

			$this->data['filter_status'] = $filter_status;
			$this->data['filter_tanggal'] = $filter_tanggal;
		$this->data['insert'] = $this->url->link('perakitan/perakitan/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['view'] = $this->url->link('perakitan/perakitan/tampil', 'token=' . $this->session->data['token'] . '&popup=1&id=' . $result['id'].$url, 'SSL');
		$this->template = 'perakitan/perakitan_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	
	

	public function insert() {
		$this->load->model('perakitan/perakitan');
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Tukar Tabung');

		$this->load->model('perakitan/perakitan');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
				if($this->user->getUsername()=="pawits"){
					echo "<pre>";
					print_r($this->request->post);
					exit;
				}
			//$no_surat=$this->model_gudang_tukartabung->addTukarTabung($this->request->post);
			$no_surat=$this->model_perakitan_perakitan->addPerakitan($this->request->post);
			$this->session->data['success'] = 'Sukses: Data Perakitan Disimpan';

			$url = '';

			if (isset($this->request->get['filter_tanggal'])) {
				$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
			}


			if (isset($this->request->get['filter_gudang_id'])) {
				$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
			}
			if (isset($this->request->get['filter_tabunghasil'])) {
				$url .= '&filter_tabunghasil=' . $this->request->get['filter_tabunghasil'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}


			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->redirect($this->url->link('perakitan/perakitan/', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$url = '';
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}


		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_tabunghasil'])) {
			$url .= '&filter_tabunghasil=' . $this->request->get['filter_tabunghasil'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		//get divisi user
		$user=$this->user->getId();

		$this->data['cancel']= $this->url->link('perakitan/perakitan', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('perakitan/perakitan/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['insert'] = $this->url->link('perakitan/perakitan/insert', 'token=' . $this->session->data['token'].$url, 'SSL');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$this->load->model('catalog/gudang');
		$gudangs = $this->model_catalog_gudang->getGudangs(true);
		$this->data['gudangs']=$gudangs;
		$this->data['token'] = $this->session->data['token'];
		$this->template = 'perakitan/perakitan_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}

	private function validateForm() {
    	/*if (!$this->user->hasPermission('modify', 'pembelian/permintaanpembelian')) {
      		$this->error['warning'] = 'Permission Denied.';
    	}

    	/*if (empty($this->request->post['date_added'])) {
      		$this->error['warning'] = 'Tanggal input product cacat harus diisi';
    	}*/

			if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

	public function tampil(){
		$this->document->setTitle('Tukar Kran');
		$url = '';
    if (isset($this->request->get['filter_tanggal'])) {
      $url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
    }


    if (isset($this->request->get['filter_gudang_id'])) {
      $url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
    }
    if (isset($this->request->get['filter_tabunghasil'])) {
      $url .= '&filter_tabunghasil=' . $this->request->get['filter_tabunghasil'];
    }

    if (isset($this->request->get['filter_status'])) {
      $url .= '&filter_status=' . $this->request->get['filter_status'];
    }


    if (isset($this->request->get['page'])) {
      $url .= '&page=' . $this->request->get['page'];
    }
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('perakitan/perakitan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('perakitan/perakitan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}


    $this->load->model('perakitan/perakitan');
    $this->load->model('gudang/product');
	$this->load->model('catalog/gudang');

		$r = $this->model_perakitan_perakitan->getOne($this->request->get['id']);
		$gudang = $this->model_catalog_gudang->getGudang($r['gudang_id']);
		
		$this->data['products'] = $this->model_perakitan_perakitan->getOne($this->request->get['id']);
		$this->data['kproducts'] = $this->model_perakitan_perakitan->getDetail($this->request->get['id']);
		$this->data['namagudang'] = $gudang['nama'];

		$this->data['cancel']= $this->url->link('perakitan/perakitan', 'token=' . $this->session->data['token'] . $url, 'SSL');

		
		if (isset($this->request->get['popup'])) {
		  $this->template = 'perakitan/perakitan_infopopup.tpl';
		}else{
			$this->template = 'perakitan/perakitan_info.tpl';
		}
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

  public function setujui(){
    $this->load->model('perakitan/perakitan');
		$this->document->setTitle('Tukar Kran');
    if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
      $no_surat=$this->model_gudang_tukartabung->prosesTukarTabung($this->request->get['id'],$this->request->post['tglproses']);

			$this->session->data['success'] = 'Sukses: Data Tukar Tabung Berhasil Diproses';

			$url = '';

			if (isset($this->request->get['filter_tanggal'])) {
				$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
			}


			if (isset($this->request->get['filter_gudang_id'])) {
				$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
			}
			if (isset($this->request->get['filter_tabunghasil'])) {
				$url .= '&filter_tabunghasil=' . $this->request->get['filter_tabunghasil'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}


			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->redirect($this->url->link('perakitan/perakitan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		$url = '';
    if (isset($this->request->get['filter_tanggal'])) {
      $url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
    }


    if (isset($this->request->get['filter_gudang_id'])) {
      $url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
    }
    if (isset($this->request->get['filter_tabunghasil'])) {
      $url .= '&filter_tabunghasil=' . $this->request->get['filter_tabunghasil'];
    }

    if (isset($this->request->get['filter_status'])) {
      $url .= '&filter_status=' . $this->request->get['filter_status'];
    }


    if (isset($this->request->get['page'])) {
      $url .= '&page=' . $this->request->get['page'];
    }
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('perakitan/perakitan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('perakitan/perakitan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		/*$this->load->model('user/divisi');
		$this->load->model('catalog/gudang');

    */
    $this->data['action']= $this->url->link('perakitan/perakitan/setujui', 'token=' . $this->session->data['token'] . $url.'&id='.$id, 'SSL');


    $this->load->model('perakitan/perakitan');
    $this->load->model('gudang/product');

    $column=array('tukartabung.*','gudang.nama');
		$join=array();
	   $join[]=array(
			'tablename'	=> 'gudang',
			'firsttable'	=>'tukartabung.gudang_id',
			'secondtable'	=> 'gudang.gudang_id'
		);

		$data = array(
			'id'      =>$id,
			'hapus'			=>array('<',1),
		);


		$trans=$this->model_gudang_tukartabung->getTukarTabung($column,$join,$data);

    $tabung_a=$this->model_gudang_product->getProduct($trans['tabung_a'],$trans['gudang_id']);

    $kran_b=$this->model_gudang_product->getProduct($trans['kran_b'],$trans['gudang_id']);
    $tabung_b=$this->model_gudang_product->getProduct($trans['tabung_b'],$trans['gudang_id']);
    $kran_lepasan=$this->model_gudang_product->getProduct($trans['kran_lepasan'],$trans['gudang_id']);


		$this->data['permintaan']=$trans;
    $this->data['tabung_a']=$tabung_a;
    $this->data['tabung_b']=$tabung_b;
    $this->data['kran_b']=$kran_b;
    $this->data['kran_lepasan']=$kran_lepasan;

		$this->data['cancel']= $this->url->link('perakitan/perakitan', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->template = 'perakitan/perakitan_setujui.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function cetak(){
		$this->document->setTitle('Permintaan Pembelian');
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('pembelian/permintaanpembelian', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/permintaanpembelian', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('user/divisi');
		$this->load->model('catalog/gudang');
		$this->load->model('pembelian/permintaanpembelian');

		$column=array('permintaan_pembelian.id','permintaan_pembelian.no_surat','permintaan_pembelian.tujuan_pembelian','permintaan_pembelian.jenis_pembelian','permintaan_pembelian.jenis_barang','divisi.name','permintaan_pembelian.date_added','permintaan_pembelian.status');
		$join=array();
		$join[]=array(
			'tablename'	=> 'divisi',
			'firsttable'	=>'permintaan_pembelian.divisi_asal',
			'secondtable'	=> 'divisi.id'
		);

		$data = array(
			'permintaan_pembelian.id'      =>$id,

		);

		$trans=$this->model_pembelian_permintaanpembelian->getPermintaanPembelian($column,$join,$data);
		$prods=$this->model_pembelian_permintaanpembelian->getPermintaanPembelianProduct(array('surat_id'	=> $id));
		$gudang=$this->model_catalog_gudang->getGudang($trans['gudang_id']);
	//	print_r($prods);

		$this->data['permintaan']=$trans;
		$this->data['products']=$prods;
		$this->data['gudang']=$gudang;

		$this->template = 'pembelian/permintaanpembelian_cetak.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	public function batalkan(){
		$this->load->model('perakitan/perakitan');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
      $this->model_perakitan_perakitan->batalkan($this->request->get['id']);

			$this->session->data['success'] = 'Sukses: Data produk perakitan berhasil dihapus.';
			}
		}
			$url = '';
      if (isset($this->request->get['filter_tanggal'])) {
  			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
  		}


  		if (isset($this->request->get['filter_gudang_id'])) {
  			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
  		}
  		if (isset($this->request->get['filter_tabunghasil'])) {
  			$url .= '&filter_tabunghasil=' . $this->request->get['filter_tabunghasil'];
  		}

  		if (isset($this->request->get['filter_status'])) {
  			$url .= '&filter_status=' . $this->request->get['filter_status'];
  		}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('perakitan/perakitan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
	public function batalkanproses(){
		$this->load->model('perakitan/perakitan');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
      $this->model_gudang_tukartabung->batalkanTukarTabung($this->request->get['id']);

			$this->session->data['success'] = 'Sukses: Data Tukar Kran berhasil dibatalkan.';
			}
		}
		$url = '';
		if (isset($this->request->get['filter_tanggal'])) {
  			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
  		}


  		if (isset($this->request->get['filter_gudang_id'])) {
  			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
  		}
  		if (isset($this->request->get['filter_tabunghasil'])) {
  			$url .= '&filter_tabunghasil=' . $this->request->get['filter_tabunghasil'];
  		}

  		if (isset($this->request->get['filter_status'])) {
  			$url .= '&filter_status=' . $this->request->get['filter_status'];
  		}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('perakitan/perakitan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
	/*public function setujui(){
		$this->load->model('pembelian/permintaanpembelian');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
      $this->model_pembelian_permintaanpembelian->updatePermintaan(array('status' => 2),array('id'	=> $this->request->get['id']));

			$this->session->data['success'] = 'Sukses: Data Permintaan Pembelian berhasil disetujui.';
			}
		}
			$url = '';
			if (isset($this->request->get['filter_tanggal'])) {
				$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
			}
			if (isset($this->request->get['filter_no_surat'])) {
				$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
			}

			if (isset($this->request->get['filter_jenis_pembelian'])) {
				$url .= '&filter_jenis_pembelian=' . $this->request->get['filter_jenis_pembelian'];
			}

			if (isset($this->request->get['filter_jenis_barang'])) {
				$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
			}

			if (isset($this->request->get['filter_divisi'])) {
				$url .= '&filter_divisi=' . $this->request->get['filter_divisi'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}


			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('pembelian/permintaanpembelian', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}*/
	public function autocomplete(){
		$rests = array();

		$this->load->model('pembelian/permintaanpembelian');
		$this->load->model('pembelian/pembeliankredit');
		$this->load->model('pembelian/pembelianimport');

			if (isset($this->request->get['q'])) {
				$filter_no_surat = $this->request->get['q'];
			} else {
				$filter_no_surat = '';
			}
			if (isset($this->request->get['j'])) {
				$jenis_pembelian = $this->request->get['j'];
			} else {
				$jenis_pembelian = '';
			}


			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			if(isset($this->request->get['status'])){
				if(!empty($this->request->get['status'])){
					if($this->request->get['status'] == 5){
						$status=array('<>',3);
					}else{
						$status=$this->request->get['status'];
					}
				}else{
					$status=2;
				}
			}else{
				$status=2;
			}
			$data = array(
				'no_surat'         => array('LIKE',$filter_no_surat),
				'status'	=> $status,
				'jenis_pembelian'	=> $jenis_pembelian
				//'jenis_pembelian'	=> !mpty()$jenis_pembelian
			);
			$start=0;
			$limit=0;
			$column=array('id','no_surat');
			$join=array();

			$results = $this->model_pembelian_permintaanpembelian->getPermintaanPembelians($column,$join,array(),$data,array(),$limit,$start);
			foreach($results as $r){
				$tampil=true;
				/*if (isset($this->request->get['s'])) {
					if($jenis_pembelian == 2){
						$pem=$this->model_pembelian_pembeliankredit->getPermintaanPembelian(array(),array(),array('surat_id'=>$r['id'],'status'=>array('<>',3)));
					}
					if($jenis_pembelian == 3){
						$pem=$this->model_pembelian_pembelianimport->getPermintaanPembelian(array(),array(),array('surat_id'=>$r['id'],'status'=>array('<>',3)));
					}
					if(!empty($pem)){
						$tampil=false;
					}
				}*/
				if($tampil){
					$rests[]=array(
						'id'	=> $r['id'],
						'text'	=> $r['no_surat']
					);
				}
			}
		$this->response->setOutput(json_encode($rests));
	}

	public function detail(){
		$hasil = array();

		$this->load->model('pembelian/permintaanpembelian');
		$this->load->model('catalog/gudang');
		if(isset($this->request->get['surat_id'])){
			if(!empty($this->request->get['surat_id'])){
				$column=array();
				$surat_id=$this->request->get['surat_id'];
				$data = array(
					'id'      =>$surat_id,
					'status'	=> 2
				);

				$trans=$this->model_pembelian_permintaanpembelian->getPermintaanPembelian($column,array(),$data);
				$prods=$this->model_pembelian_permintaanpembelian->getPermintaanPembelianProduct(array('surat_id'	=> $surat_id));
			//	print_r($prods);
				$gudang=$this->model_catalog_gudang->getGudang($trans['gudang_id']);
				$hasil=array(
					'detail'	=> $trans,
					'products' => $prods,
					'gudang'	=> $gudang
				);

			}
		}
		$this->response->setOutput(json_encode($hasil));


	}


}
?>
