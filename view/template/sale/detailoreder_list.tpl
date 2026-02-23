<?php echo $header; ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>

    </h1>

  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Sales Order</h3>
            <div class="button pull-right">
              <a href="<?php echo $insert; ?>"><button type="button" class="btn btn-primary">Tambah SO</button></a>
            </div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <?php if ($error_warning) { ?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                  <?php echo $error_warning; ?>
                </div>
                <?php
                }
                ?>
                <?php if ($success) { ?>
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-check"></i> Success!</h4>
                  <?php echo $success; ?>
                </div>
                <?php
                }
                ?>
              </div>
            </div>
              <div class="row table-responsive">
                <div class="col-md-12">
                  <table class="table">
                      <tr>
                        <th>Tanggal Awal</th>
                        <th>Tanggal Akhir</th>
                        <th>Gudang</th>
                        <th>Nomor So</th>
                      </tr>
                      <tr>
                        <td>
                          <input type="text" class="form-control date" name="filter_tanggal" value="<?php echo $filter_tanggal; ?>" placeholder="awal"/>
                          <!--<input type="text" class="form-control daterange" name="filter_tanggalrange" value="<?php echo $filter_tanggal; ?>" placeholder="awal"/>-->
                        </td>
                        <td>
                          <input type="text" class="form-control date" name="filter_tanggalakhir" value="<?php echo $filter_tanggalakhir; ?>" placeholder="akhir"/>
                        </td>
                        <td>
                          <select class="form-control select-ads" name="filter_gudang_id">
                            <option value="*">Semua Lokasi</option>
                            <?php
                            foreach($gudangs as $g){
                            ?>
                              <option value="<?php echo $g['gudang_id']; ?>" ><?php echo $g['nama']; ?></option>
                            <?php
                            }
                            ?>
                          </select>
                        </td>
                        <td>
                          <select name="filter_order_id" class="salesorder form-control" style="width:300px">>
                            <option value="*">Semua Order ID</option>

                          </select>
                        </td>
                      </tr>
                      <tr>
                        <th>Nama Customer</th>
                        <th>Nama Barang</th>
                        <th>Status Pengiriman</th>
                        <th></th>
                      </tr>
                      <tr>
                        <td>
                          <select name="filter_customer_id" class="form-control lokasi-pameran" style="width:300px">
                      			<option value="*" <?php echo empty($filter_customer_id)?'selected':'';?>>Semua Customer</option>


                      		</select>
                        </td>
                        <td>
                           <select name="filter_jenisorder" class="jenisorder form-control" style="width:350px">
                              <option value="*">Semua Produk</option>


                            </select>
                        </td>
                        <td>
                           <select name="filter_status" class="form-control">
                              <option value="*">Semua Status</option>
                              <option value="1" >Belum Dikirim</option>
                              <option value="2" >Dikirim Sebagian</option>
                              <option value="3" >Sudah Dikirim</option>
                                <option value="4" >Dibatalkan</option>
                          </select>
                        </td>
                        <td><a onclick="filter();" class="btn btn-info"><i class="fa fa-search"></i></a></td>
                      </tr>
                  </table>
                </div>
              </div>
            <div class="row">
              <div class="col-md-12">
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th width="1" style="text-align: center;">Tanggal</th>
                        <th class="left">Gudang</th>
                        <th class="left">No. SO</th>
                        <th class="left">Nama Customer</th>
                        <th class="left">Nama Barang</th>
                        <th class="left">Qty Kirim</th>
                        <th class="left">Qty Terima</th>
                        <th class="left">Status Pengiriman</th>
                         <th class="right"></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--
                      <tr class="filter">
                        <td> 
                          <input type="text" class="date" name="filter_tanggal" value="<?php echo $filter_tanggal; ?>" placeholder="awal"/><br>
                          <input type="text" class="date" name="filter_tanggalakhir" value="<?php echo $filter_tanggalakhir; ?>" placeholder="akhir"/>
                        </td>
                        <td>
                          <select class="form-control" name="filter_gudang_id">
                            <option value="*">Semua Lokasi</option>
                            <?php
                            foreach($gudangs as $g){
                            ?>
                              <option value="<?php echo $g['gudang_id']; ?>" ><?php echo $g['nama']; ?></option>
                            <?php
                            }
                            ?>
                          </select>
                        </td>
                        <td>
                          <select name="filter_order_id" class="salesorder form-control">
                            <option value="*">Semua Order ID</option>

                          </select>
                        </td>

                        <td>
                          <select name="filter_customer_id" class="form-control lokasi-pameran">
                      			<option value="*" <?php echo empty($filter_customer_id)?'selected':'';?>>Semua Customer</option>


                      		</select>
                        </td>

                        <td>
                          <select name="filter_jenisorder" class="jenisorder form-control">
                              <option value="*">Semua Produk</option>


                            </select>
                        </td>
                      <td>

                        </td>
                        <td>

                        </td>
                        <td>
                          <select name="filter_status" class="form-control">
                              <option value="*">Semua Status</option>
                              <option value="1" >Belum Dikirim</option>
                              <option value="2" >Dikirim Sebagian</option>
                              <option value="3" >Sudah Dikirim</option>
                                <option value="4" >Dibatalkan</option>
                          </select>
                        </td>
          		          <td align="right"><a onclick="filter();" class="btn btn-info">Filter</a></td>
                      </tr>
                      -->
                        <?php if ($perlupersetujuan) { ?>
                          <?php foreach ($perlupersetujuan as $product) { ?>
                            <tr>
                              <td>
                                <?php 
                                  if($product['status']==5){
                                    echo "tgl SO ".$product['tanggal']."<br>";
                                    echo "tgl Input ".$product['tglinput'];
                                  }else{
                                    echo $product['tanggal'];
                                  }                                
                                ?>
                              </td>
                              <td><?php echo $product['namagudang']; ?></td>
                              <td><?php echo $product['no_so']; ?></td>
                              <td><?php echo $product['name']; ?></td>
                              <td><?php echo $product['nameproduct']; ?></td>
                              <td><?php echo $product['quantity']; ?></td>
                              <td><?php echo $product['quantityterima']; ?></td>
                              <td><?php
                                  if($product['status_pengiriman'] == 1 & $product['status']<>5){
                                    echo 'Belum Dikirm';
                                  }
                                  if($product['status_pengiriman'] == 2){
                                    echo 'Dikirim Sebagian';
                                  }
                                
                                  if($product['status_pengiriman'] == 4){
                                    echo 'Dibatalkan';
                                  }
                                  if($product['status_pengiriman'] == 3){
                                    if($product['quantityterima']==0){
                                      echo 'Close SO';
                                    }else{
                                      echo 'Sudah Dikirim';
                                    }
                                  }
                                  if($product['status_pengiriman'] == 1 & $product['status']==5){
                                    echo 'Menunggu persetujuan perubahan tanggal';
                                  }
                                  if($product['status_pengiriman']==6){
                                    echo 'perubahan harga ditolak';
                                  }
                                ?>
                              </td>
                              <td class="right"><?php foreach ($product['action'] as $action) { ?>
                                <a class="<?php echo $action['badge'] ?>" href="<?php echo $action['href']; ?>" style="margin-bottom:8px;"><?php echo $action['text']; ?></a><br>
                                <?php }  ?>
                                <?php if($product['status_pengiriman']==2 & $tso==1) { ?>
                                  <a href="index.php?route=sale/salesorder/tutupso&token=<?php echo $token; ?>&tutup=<?php echo $product['idso']?>" class="badge bg-red">tutup SO</a>
                                <?php } ?>
                                <?php if($product['status_pengiriman']<>4 & $this->user->getUsername()=="pawitz") { ?>
                                    <?php if($tso==1 && $product['status_pengiriman']<>3) { ?>
                                        <a href="index.php?route=sale/salesorder/tutupsobelumdikirim&token=<?php echo $token; ?>&tutup=<?php echo $product['idso']?>&filter_status=<?php echo $filter_status ?>&page=<?php echo $_REQUEST['page']?>" class="badge bg-red">tutup SO belum dikirim</a>
                                    <?php } ?>
                                <?php } ?>
                                <?php
                                if($product['status'] == 4){
                                  echo 'Alasan Batal: <br>'.$product['alasan_batal'];
                                }
                                ?>
                              </td>
                            </tr>
                          <?php } ?>
                        <?php } ?>
                      <?php if ($penjualans) { ?>
                      <?php foreach ($penjualans as $product) { ?>
                      <tr>
                        <td <?php echo $product['status'] == 4?'style="background:#f3bfbf;"':'';?>>
                          <?php 
                            if($product['status']==5){
                              echo "tgl SO ".$product['tanggal']."<br>";
                              echo "tgl Input ".$product['tglinput'];
                            }else{
                               echo $product['tanggal'];
                            }
                          
                          ?>
                        </td>
                        <td <?php echo $product['status'] == 4?'style="background:#f3bfbf;"':'';?> class="left"><?php echo $product['namagudang']; ?></td>

                        <td <?php echo $product['status'] == 4?'style="background:#f3bfbf;"':'';?> class="left"><?php echo $product['no_so']; ?> </td>

                        <td <?php echo $product['status'] == 4?'style="background:#f3bfbf;"':'';?> class="left"><?php echo $product['name']; ?></td>
                        <td <?php echo $product['status'] == 4?'style="background:#f3bfbf;"':'';?>><?php echo $product['nameproduct']; ?></td>
                        <td <?php echo $product['status'] == 4?'style="background:#f3bfbf;"':'';?>><?php echo $product['quantity']; ?></td>
                        <td <?php echo $product['status'] == 4?'style="background:#f3bfbf;"':'';?>><?php echo $product['quantityterima']; ?></td>
                        <td <?php echo $product['status'] == 4?'style="background:#f3bfbf;"':'';?>><?php
                                if($product['status_pengiriman'] == 1 & $product['status']<>5){
                                  echo 'Belum Dikirm';
                                }
                                if($product['status_pengiriman'] == 2){
                                  echo 'Dikirim Sebagian';
                                }
                               
                                if($product['status_pengiriman'] == 4){
                                  echo 'Dibatalkan';
                                }
                                if($product['status_pengiriman'] == 3){
                                  if($product['quantityterima']==0){
                                    echo 'Close SO';
                                  }else{
                                    echo 'Sudah Dikirim';
                                  }
                                }
                                if($product['status_pengiriman'] == 1 & $product['status']==5){
                                  echo 'Menunggu persetujuan perubahan tanggal';
                                }
                                if($product['status_pengiriman']==6){
                                  echo 'perubahan harga ditolak';
                                }
                        ?></td>

                         <td <?php echo $product['status'] == 4?'style="background:#f3bfbf;"':'';?> class="right"><?php foreach ($product['action'] as $action) { ?>
                          <a class="<?php echo $action['badge'] ?>" href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a><br>
                          <?php }  ?>
                          <?php if($product['status_pengiriman']==2 & $tso==1) { ?>
                            <a href="index.php?route=sale/salesorder/tutupso&token=<?php echo $token; ?>&tutup=<?php echo $product['idso']?>" class="badge bg-red">tutup SO</a>
                          <?php } ?>
                          <?php if($product['status_pengiriman']<>4 & $this->user->getUsername()=="pawitz") { ?>
                              <?php if($tso==1 && $product['status_pengiriman']<>3) { ?>
                                  <a href="index.php?route=sale/salesorder/tutupsobelumdikirim&token=<?php echo $token; ?>&tutup=<?php echo $product['idso']?>&filter_status=<?php echo $filter_status ?>&page=<?php echo $_REQUEST['page']?>" class="badge bg-red">tutup SO belum dikirim</a>
                              <?php } ?>
                          <?php } ?>
                          <?php
                          if($product['status'] == 4){
                            echo 'Alasan Batal: <br>'.$product['alasan_batal'];
                          }
                          ?>
                        </td>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="8">Data tidak ditemukan</td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </form>
              </div>
            </div>

          </div>
          <div class="box-footer">
            <div class="row">
              <div class="col-md-12">
                <div class="pull-right"><?php echo $pagination; ?></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
</div>
<!-- daterange picker -->
  <!--<link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <script src="https://adminlte.io/themes/AdminLTE/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>-->
<script>
$('.sidebar-menu').find('#menu-penjualan').addClass('active');
$('.sidebar-menu').find('#menu-salesorder').addClass('active');
</script>
<script>
$(function(){
  $('.date').datepicker({dateFormat: 'yy-mm-dd'});
  /*
  $('.daterange').daterangepicker({
    locale: {
      format: 'YYYY-MM-DD'
    }
  });
  */
})
</script>
<script type="text/javascript"><!--
function filter() {
	url = "index.php?route=sale/salesorder&token=<?php echo $token; ?>";

	var filter_customer_id = $('select[name=\'filter_customer_id\']').val();

	if (filter_customer_id != '*') {
		url += '&filter_customer_id=' + encodeURIComponent(filter_customer_id);
	}

    var filter_status= $('select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

  var filter_tanggal = $('input[name=\'filter_tanggal\']').val();

	if (filter_tanggal) {
		url += '&filter_tanggal=' + encodeURIComponent(filter_tanggal);
	}

  var filter_gudang_id = $('select[name=\'filter_gudang_id\']').val();

	if (filter_gudang_id != '*') {
		url += '&filter_gudang_id=' + encodeURIComponent(filter_gudang_id);
	}

  var filter_order_id = $('select[name=\'filter_order_id\']').val();

	if (filter_order_id != '*') {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}
  var filter_jenisorder = $('select[name=\'filter_jenisorder\']').val();

	if (filter_jenisorder != '*') {
		url += '&filter_jenisorder=' + encodeURIComponent(filter_jenisorder);
	}

   var filter_tanggalakhir = $('input[name=\'filter_tanggalakhir\']').val();

	if (filter_tanggalakhir) {
		url += '&filter_tanggalakhir=' + encodeURIComponent(filter_tanggalakhir);
	}

  var filter_tanggalrange = $('input[name=\'filter_tanggalrange\']').val();

	if (filter_tanggalrange) {
		url += '&filter_tanggalrange=' + encodeURIComponent(filter_tanggalrange);
	}

if(filter_tanggal){
    if(filter_tanggalakhir==''){
      alert("tanggal akhir harus diisi");
      return false;
    }
  }


  /*var filter_shipping_method = $('select[name=\'filter_shipping_method\']').val();

	if (filter_shipping_method != '*') {
		url += '&filter_shipping_method=' + encodeURIComponent(filter_shipping_method);
	}
*/


	location = url;
}
$(function(){
  $(".select-ads").select2({


      theme:"bootstrap"
    });
  $(".lokasi-pameran").select2({
    ajax: {
    url:"index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>",
    //url: "index.php?route=pamerantoko/toko/autocomplete&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        q: params.term, // search term

      };
    },
    delay: 250,
    processResults: function (data) {
      return {
        results: data
      };
    },
    //cache: true
  },
  theme:"bootstrap"
  });

  $(".salesorder").select2({
    ajax: {
    url:"index.php?route=sale/salesorder/autocomplete&token=<?php echo $token; ?>",
    //url: "index.php?route=pamerantoko/toko/autocomplete&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        q: params.term, // search term

      };
    },
    delay: 250,
    processResults: function (data) {
      return {
        results: data
      };
    },
    //cache: true
  },
  theme:"bootstrap"
  });

  $(".jenisorder").select2({
    ajax: {
    url:"index.php?route=catalog/product/autocompleteprod&token=<?php echo $this->request->get['token']; ?>",
      dataType: 'json',
    data: function (params) {
      return {
        q: params.term

      };
    },
    delay: 250,
    processResults: function (data) {
      return {
        results: data
      };
    },
    //cache: true
  },
  theme:"bootstrap"
  });
})
//--></script>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script>
<script type="text/javascript"><!--
$('input[name=\'filter_name\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/atk/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item.nama,
						value: item.atk_id
					}
				}));
			}
		});
	},
	select: function(event, ui) {
		$('input[name=\'filter_name\']').val(ui.item.label);

		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});


//--></script>
<?php echo $footer; ?>
