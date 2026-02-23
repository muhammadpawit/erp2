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
            <h3 class="box-title">Surat Jalan Penjualan belum Difakturkan</h3>
            <div class="button pull-right">
                <a href="<?php echo $exporttoexcel; ?>" target="_blank"><button type="button" class="btn btn-primary">Export to Excel</button></a>
                <a href="<?php echo $printbiasa; ?>" target="_blank"><button type="button" class="btn btn-primary">Cetak</button></a>
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
            <div class="row">
              <div class="col-md-12">
                <table class="table table-bordered">
                  <tr>
                    <td>Tanggal Awal</td>
                    <td>Tanggal Akhir</td>
                    <td>Gudang</td>
                    <td>No SJ</td>
                  </tr>
                  <tr>
                    <td><input type="text" class="form-control date" name="filter_tanggal_awal" value="<?php echo $filter_tanggal_awal; ?>" /></td>
                    <td><input type="text" class="form-control date" name="filter_tanggal_akhir" value="<?php echo $filter_tanggal_akhir; ?>" /></td>
                    <td>
                      <select class="form-control" name="filter_gudang_id">
                            <option value="*">Semua Lokasi</option>
                            <?php

                            foreach($gudangs as $g){

              ?>
                              <option value="<?php echo $g['gudang_id']; ?>" <?php echo ($g['gudang_id']==$filter_gudang_id)?'selected':''; ?>><?php echo $g['nama']; ?></option>
                            <?php
                            }
                            ?>
                          </select>
                    </td>
                    <td>
                      <select name="filter_order_id" class="salesorder form-control">
                              <option value="*">Semua Surat Jalan</option>


                            </select>
                    </td>
                  </tr>
                  <tr>
                    <td>No SO</td>
                    <td>Nama</td>
                    <td></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td>
                      <select name="filter_sales_order" class="nosalesorder form-control">
                              <option value="*">Semua Sales Order</option>

                            </select>
                    </td>
                    <td>
                       <select name="filter_customer_id" class="form-control lokasi-pameran">
                            <option value="*" <?php echo empty($filter_customer_id)?'selected':'';?>>Semua Customer</option>


                          </select>
                    </td>
                    <td>
                       <select name="filter_status" class="form-control">
                              <option value="*">Semua Status</option>
                              <option value="1" >Proses Kirim</option>
                              <option value="2" >Diterima</option>
                              <!--<option value="3" >Dibatalkan</option>-->

                          </select>
                    </td>
                    <td>
                      <a onclick="filter();" class="btn btn-info">Filter</a>
                    </td>
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
                        <th width="1" style="text-align: center;">Tanggal Awal</th>
                        <th >Gudang</th>
                        <th >No. SJ</th>
                        <th >No. SO</th>
                        <th class="left">No. Invoice</th>
                        <th class="left">Nama</th>
                        <th class="left">Nama Produk</th>
                        <th class="left">Quantity Kirim</th>
                        <th class="left">Status</th>
                        <th class="left"></th>

                      </tr>
                    </thead>
                    <tbody>
                      

                      <?php if ($penjualans) { ?>
                      <?php foreach ($penjualans as $product) { ?>
                      <tr>
                        <td><?php echo $product['tanggal']; ?></td>
                        <td><?php echo $product['nama']; ?></td>
                        <td class="left"><?php echo $product['no_sj']; ?></td>
                        <td class="left"><?php echo $product['no_salesorder']; ?></td>
                        <td class="left"><?php echo $product['no_faktur']; ?>
                        </td>
                        <td class="left"><?php echo $product['name']; ?></td>
                        <td><?php echo $product['namaproduct']; ?></td>
                        <td><?php echo $product['quantity']; ?> <?php echo $product['satuan']; ?></td>

                        <td><?php
                                if($product['status'] == 1){
                                  echo 'Proses Kirim';
                                }
                                if($product['status'] == 2){
                                  echo 'Diterima';
                                }
                                if($product['status'] == 3){
                                  echo 'Dibatalkan';
                                }

                        ?></td>

                         <td class="right"><?php foreach ($product['action'] as $action) { ?>
                          <a class="badge bg-green" href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a>
                          <?php } ?></td>
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
<script>
$('.sidebar-menu').find('#menu-penjualan').addClass('active');
$('.sidebar-menu').find('#menu-daftar-penjualan').addClass('active');
</script>
<script>
$(function(){
  $('.date').datepicker({dateFormat: 'yy-mm-dd'});
})
</script>
<script type="text/javascript"><!--
function filter() {
	url = "index.php?route=sale/sjbelumdifaktur&token=<?php echo $token; ?>";

	var filter_customer_id = $('select[name=\'filter_customer_id\']').val();

	if (filter_customer_id != '*') {
		url += '&filter_customer_id=' + encodeURIComponent(filter_customer_id);
	}

  var filter_gudang_id= $('select[name=\'filter_gudang_id\']').val();

if (filter_gudang_id != '*') {
  url += '&filter_gudang_id=' + encodeURIComponent(filter_gudang_id);
}

    var filter_status= $('select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

  var filter_tanggal_awal = $('input[name=\'filter_tanggal_awal\']').val();

	if (filter_tanggal_awal) {
		url += '&filter_tanggal_awal=' + encodeURIComponent(filter_tanggal_awal);
	}

var filter_tanggal_akhir = $('input[name=\'filter_tanggal_akhir\']').val();

	if (filter_tanggal_akhir) {
		url += '&filter_tanggal_akhir=' + encodeURIComponent(filter_tanggal_akhir);
	}

  var filter_order_id = $('select[name=\'filter_order_id\']').val();

	if (filter_order_id != '*') {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}

 //  var filter_invoice = $('select[name=\'filter_invoice\']').val();

	// if (filter_invoice != '*') {
	// 	url += '&filter_invoice=' + encodeURIComponent(filter_invoice);
	// }
  var filter_sales_order = $('select[name=\'filter_sales_order\']').val();

	if (filter_sales_order != '*') {
		url += '&filter_sales_order=' + encodeURIComponent(filter_sales_order);
	}


/*  var filter_shipping_method = $('select[name=\'filter_shipping_method\']').val();

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
//start

  $(".invoice").select2({
    ajax: {
    url:"index.php?route=sale/invoice/autocomplete&token=<?php echo $token; ?>",
    //url: "index.php?route=pamerantoko/toko/autocomplete&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        q: params.term, // search term

      };
    },
    delay: 250,
    processResults: function (data) {
      console.log(JSON.stringify(data));
      belum={id:'na',text:'Belum Ada Invoice'};
      data.unshift(belum);
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
    url:"index.php?route=sale/penjualan/autocomplete&token=<?php echo $token; ?>",
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
  $(".nosalesorder").select2({
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
    url:"index.php?route=catalog/category/autocompletecat&token=<?php echo $this->request->get['token']; ?>",
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


  //end
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
