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
            <h3 class="box-title">Laporan Penjualan Detail</h3>
            <div class="button pull-right">

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
              <div class="col-xs-5">
                <table class="table table-stripped">
                    <tr>
                      <td>Tanggal Awal</td>
                      <td> <input type="text" class="form-control date" readonly name="filter_date_start" value="<?php echo $filter_date_start; ?>" /></td>
                    </tr>
                    <tr>
                      <td>Tanggal Akhir</td>
                      <td> <input type="text" class="form-control date" readonly name="filter_date_end" value="<?php echo $filter_date_end; ?>" /></td>
                    </tr>
                    <?php //if($this->user->getUsername()=="pawit"){?>
                    <tr>
                      <td>Berdasarkan</td>
                      <td>
                        <select name="jenistgl" class="form-control">
                          <option value="1" <?php echo $jenistgl==1?'selected':''?>>Tanggal Invoice</option>
                          <option value="2" <?php echo $jenistgl==2?'selected':''?>>Tanggal Surat Jalan</option>
                        </select>
                      </td>
                    </tr>
                    <?php //} ?>
                    <tr>
                      <td>Tanggal Input Customer Awal</td>
                      <td> <input type="text" class="form-control date" readonly name="filter_register_start" value="<?php echo $filter_register_start; ?>" /></td>
                    </tr>
                    <tr>
                      <td>Tanggal Input Customer Akhir</td>
                      <td> <input type="text" class="form-control date" readonly name="filter_register_end" value="<?php echo $filter_register_end; ?>" /></td>
                    </tr>

                  <tr>
                    <td>Customer</td>
                    <td>
                    <select name="filter_customer_id" class="form-control lokasi-pameran">
                      <option value="*" <?php echo empty($filter_customer_id)?'selected':'';?>>Semua Customer</option>


                    </select>
                  </td>
                  </tr>
                  <tr>
                    <td>Provinsi</td>
                    <td>
                      <!--
                      <div id="filter-prop" style="height:100px;overflow:scroll;">
                      <?php
                      foreach($countries as $c){
                      ?>
                        <input type="checkbox" name="filter_provinsi[]" <?php echo ($c['country_id']==$filter_provinsi)?'checked':''; ?><?php //echo in_array($c['country_id'],$filter_provinsi)?'checked':''; ?> value="<?php echo $c['country_id']; ?>" /> <?php echo $c['name']; ?><br>
                      <?php
                      }
                      ?>
                      </div>
                      -->
                      <select name="filter_provinsi" multiple="multiple" class="form-control select" id="filter_provinsi">
                        <option value="*" <?php echo $filter_provinsi=='*'?'':'selected'?>>Semua</option>
                        <?php foreach($countries as $c){ ?>
                        <option value="<?php echo $c['country_id'] ?>" <?php echo $c['country_id']==$filter_provinsi?'selected':'' ?>><?php echo $c['name'] ?></option>
                        <?php } ?>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <td>Gudang</td>
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
                  </tr>
                  <tr>
                    <td>Nama Sales</td>
                    <td>
                      <select name="sales" class="sales form-control">
                        <option value="*">Semua</option>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <td>Status Pembayaran</td>
                    <td id="status">
                      <input type="checkbox" name="filter_status[]" <?php echo in_array(1,$filter_status)?'checked':''; ?> value="1"> Ditagih<br>
                      <input type="checkbox" name="filter_status[]" <?php echo in_array(2,$filter_status)?'checked':''; ?> value="2"> Belum Lunas<br>
                      <input type="checkbox" name="filter_status[]" <?php echo in_array(3,$filter_status)?'checked':''; ?> value="3"> Lunas<br>
                      <input type="checkbox" name="filter_status[]" <?php echo in_array(4,$filter_status)?'checked':''; ?> value="4"> Dibatalkan<br>
                      <!--select name="filter_status" class="form-control">
                          <option value="*">Semua Status</option>
                          <option value="1" >Ditagih</option>
                          <option value="2" >Belum Lunas</option>
                          <option value="3" >Lunas</option>
                          <option value="4" >Dibatalkan</option>
                      </select-->
                    </td>
                  </tr>
                  <tr>
                    <td><a onclick="filter();" class="btn btn-info">Filter</a></td>
                    <td><!--<a onclick="cetak();" class="btn btn-default">Cetak</a>-->
                      <a href="<?php echo $cetak ?>" class="btn btn-default" target="_blank">Cetak</a>
                    </td>
                    <!-- <td></td> -->
                  </tr>
                  <tr>
                    <td><b>Jumlah: <?php echo $this->currency->format($jumlah); ?></b><br>
                      Jumlah Tanpa Pajak: <?php echo $this->currency->format($jumlahtanpapajak); ?>
                    </td>
                    <td><b>No. Transaksi: <?php echo  count($penjualans) //$total; ?> <?php //echo $jt?></b> </td>
                  </tr>

                </table>

              </div>
              <div class="col-xs-7 table-responsive" style="max-height:300px;overflow-y:scroll">
                <div class="callout callout-success lead">
                  <h4>Rincian Barang <span id="display-faktur"></span></h4>

                </div>
                <table class="table table-bordered">
                  <thead>
                    <th>Jumlah</th>
                    <th>Nama Barang</th>
                    <th>Harga Satuan</th>
                    <th>PPN</th>
                    <th>Total Pajak</th>
                    <th>Total</th>
                  </thead>
                  <?php if ($penjualans) { ?>
                  <?php foreach ($penjualans as $p) {
                    ?>
                  <tbody class="list-product" id="list<?php echo $p['id']; ?>">
                      <?php
                        foreach($p['products'] as $product){
                      ?>
                      <tr >
                        <td><?php echo $product['quantity'].' '.$product['namasatuan']; ?></td>
                        <td><?php echo $product['name']; ?></td>
                        <td><?php echo $this->currency->format($product['price']); ?></td>
                        <td><?php echo $this->currency->format(round(($product['price']/10))); ?></td>
                        <td><?php echo $this->currency->format($product['pajak']); ?></td>
                        <td><?php echo $this->currency->format($product['total']); ?></td>
                      </tr>
                    <?php
                    }
                    ?>
                  </tbody>
                    <tfoot class="total-transaksi" id="total<?php echo $p['id']; ?>">
                      <tr >
                        <td class="text-right" colspan="5">Harga Jual/Penggantian/Uang Muka</td>
                        <td><?php echo $p['sub_total']; ?></td>
                      </tr>
                      <tr >
                        <td class="text-right" colspan="5">Potongan Harga</td>
                        <td><?php echo $this->currency->format($p['diskon']); ?></td>
                      </tr>

                      <tr >
                        <td class="text-right" colspan="5">Dasar Pengenaan Pajak</td>
                        <td><?php echo $p['dasar']; ?></td>
                      </tr>
                      <tr >
                        <td class="text-right" colspan="5">PPN 10%</td>
                        <td><?php echo $p['pajak']; ?></td>
                      </tr>
                      <tr >
                        <td class="text-right" colspan="5">Uang Muka yang Telah Diterima</td>
                        <td><?php echo $p['dp']; ?></td>
                      </tr>
                      <?php
                      if($p['jenisinvoice'] == 1 | $p['jenisinvoice'] == 3){
                      ?>
                      <tr >
                        <td class="text-right" colspan="5">Jumlah yang Harus Dibayar</td>
                        <td><?php echo $p['totaltagihan']; ?></td>
                      </tr>
                      <?php
                      }
                      ?>
                      <?php
                      if($p['jenisinvoice'] == 2){
                      ?>
                      <tr >
                        <td class="text-right" colspan="5">Total Tagihan</td>
                        <td><?php echo $p['totaltagihan']; ?></td>
                      </tr>
                      <tr >
                        <td class="text-right" colspan="5">Uang Muka yang Harus Dibayar</td>
                        <td><?php echo $p['total']; ?></td>
                      </tr>
                      <?php
                      }
                      ?>
                    </tfoot>
                    <?php
                    }
                    }
                    ?>

                </table>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
               <!--  <a href="<?php echo $exporttoexcel ?>" class="btn btn-success" target="_blank">Export to Excel</a> -->
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
                  <table class="table table-hover" id="myTable">
                    <thead>
                      <tr>
                        <th width="1" style="text-align: center;">
                          <!--
                          <?php if ($sort == 'invoice.date_added') { ?>
                            <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>">Tanggal</a>
                            <?php } else { ?>
                            <a href="<?php echo $sort_date_added; ?>">Tanggal</a>
                            <?php } ?>
                          -->
                          Tanggal
                        </th>
                        <th width="1" style="text-align: center;">
                          <!--
                          <?php if ($sort == 'customer.date_added') { ?>
                            <a href="<?php echo $sort_register; ?>" class="<?php echo strtolower($order); ?>">Tanggal Input Customer</a>
                            <?php } else { ?>
                            <a href="<?php echo $sort_register; ?>">Tgl Input Cust</a>
                            <?php } ?>
                          -->
                          Tgl SJ
                        </th>
                        <th>No SJ</th>
                        <th class="left">
                        	<!--<a href="index.php?route=laporan/penjualandetail&token=<?php echo $token; ?>">Nama Sales</a>-->
                          Nama Sales
                        </th>                        
                        <th class="left">
                          <!--
                          <?php if ($sort == 'customer.name') { ?>
                            <a href="<?php echo $sort_customer; ?>" class="<?php echo strtolower($order); ?>">Nama Customer</a>
                            <?php } else { ?>
                            <a href="<?php echo $sort_customer; ?>">Nama Customer</a>
                          <?php } ?>
                          -->
                          Nama Customer
                        </th>
                        <th class="left">
                          <!--
                          <?php if ($sort == 'invoice.totaltagihan') { ?>
                            <a href="<?php echo $sort_tagihan; ?>" class="<?php echo strtolower($order); ?>">Jumlah</a>
                            <?php } else { ?>
                            <a href="<?php echo $sort_tagihan; ?>">Jumlah</a>
                          <?php } ?>
                          -->
                          Jumlah
                        </th>
                        <th class="left">
                          <!--
                          <?php if ($sort == 'invoice.totalbayar') { ?>
                            <a href="<?php echo $sort_bayar; ?>" class="<?php echo strtolower($order); ?>">Total Bayar</a>
                            <?php } else { ?>
                            <a href="<?php echo $sort_bayar; ?>">Total Bayar</a>
                          <?php } ?>
                          -->
                          Total Bayar
                        </th>
                        <!--th class="left">No. SO</th>
                        <t class="left">No. SJ</th-->
                        <th class="left">
                          <!--
                          <?php if ($sort == 'invoice.id') { ?>
                            <a href="<?php echo $sort_invoice; ?>" class="<?php echo strtolower($order); ?>">Invoice</a>
                            <?php } else { ?>
                            <a href="<?php echo $sort_invoice; ?>">Invoice</a>
                          <?php } ?>-->
                          Invoice
                        </th>
                        <th class="left">
                          Proforma Invoice
                        </th>
                        <th class="left">
                          <!--
                          <?php if ($sort == 'invoice.metode_pembayaran') { ?>
                            <a href="<?php echo $sort_metode_pembayaran; ?>" class="<?php echo strtolower($order); ?>">Metode Pembayaran</a>
                            <?php } else { ?>
                            <a href="<?php echo $sort_metode_pembayaran; ?>">Metode Pembayaran</a>
                          <?php } ?>
                          -->
                          Metode Pembayaran
                        </th>
                        <th class="left">
                        	Lama Kredit (Hari)
                        </th>
                        <th class="left">
                          <!--
                          <?php if ($sort == 'invoice.status') { ?>
                            <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>">Status</a>
                            <?php } else { ?>
                            <a href="<?php echo $sort_status; ?>">Status</a>
                          <?php } ?>
                          -->
                          Status
                        </th>
                        <th width="1" style="text-align: center;">
                          <!--
                          <?php if ($sort == 'invoice.tgllunas') { ?>
                            <a href="<?php echo $sort_tgl_lunas; ?>" class="<?php echo strtolower($order); ?>">Tgl Lunas</a>
                            <?php } else { ?>
                            <a href="<?php echo $sort_tgl_lunas; ?>">Tgl Lunas</a>
                          <?php } ?>
                          -->
                          Tgl Lunas
                        </th>
                      </tr>
                    </thead>
                    <tbody>

                      
                      <?php if ($penjualans) { ?>
                      <?php foreach ($penjualans as $product) { ?>
                      <tr class="invoice" data-id="<?php echo $product['id']; ?>" data-faktur="<?php echo $product['no_faktur']; ?>" id="list-invoice-<?php echo $product['id']; ?>" >
                        <td><?php echo $product['tanggal']; ?></td>
                        <td><?php echo $product['tglsj']; ?></td>
                        <td><?php echo $product['nosj']; ?></td>
                        <td class="left"><?php echo $product['namasales'] ?></td>
                        <td class="left"><?php echo $product['name']; ?>
                        </td>
                        <td><?php echo $product['total']; ?></td>
                        <td><?php echo $product['totalbayar']; ?></td>
                        <td class="left"><?php echo $product['no_faktur']; ?></td>
                        <td class="left">
                          <?php echo ($product['noso']['pi1']==null)?$product['noso']['pi2']:$product['noso']['pi1'] ?>
                        </td>
						<td>
							<?php 
								// 1 tunai, 2 cod, 3 kredit, 4 CBD
								$metode = $product['metode_pembayaran'];
								if($metode==1){
									echo "Tunai";
								}else if($metode==2){
									echo "COD";
								}else if($metode==3){
									echo "Kredit";
								}else{
									echo "CBD";
								}
							?>
							
						</td>
						<td>
							<?php echo $product['usia']?>
						</td>
                        <td><?php
                                if($product['status'] == 1){
                                  echo 'Ditagih';
                                }
                                if($product['status'] == 2){
                                  echo 'Belum Lunas';
                                }
                                if($product['status'] == 3){
                                  echo 'Lunas';
                                }
                                if($product['status'] == 4){
                                  echo 'Dibatalkan';
                                }

                        ?></td>
                        <td><?php echo $product['tgllunas']; ?></td>


                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td colspan="12">Data tidak ditemukan</td>
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
              <div class="pull-right"><?php //echo $pagination; ?></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
</div>
<?php if (count($penjualans)>0) { ?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
<script>
$(document).ready( function () {
    $('#myTable').DataTable( {
        "lengthChange": false,
        "bPaginate": false,
        "bFilter": false,
    } );
} );
</script>
<?php } ?>
<script>
$('.sidebar-menu').find('#menu-laporan').addClass('active');
$(function(){
  $('.date').datepicker({dateFormat: 'yy-mm-dd'});
  $('.list-product').hide();
  $('.total-transaksi').hide();

  $(".invoice").on('click',function(){
    id=$(this).data('id');
    faktur=$(this).data('faktur');

    $("#display-faktur").html(faktur);
    $(".invoice td").css('background-color','#fff');
    $(".invoice td").css('font-weight','normal');
    $("#list-invoice-"+id+" td").css('background-color','#ccc');
    $("#list-invoice-"+id+" td").css('font-weight','bold');

    $('.list-product').hide();
    $('.total-transaksi').hide();

    $("#list"+id).show();
    $("#total"+id).show();
  });
});
$(".select").select2({
    //width: 'resolve' // need to override the changed default
    theme:"bootstrap"
});

$(".sales").select2({
    ajax: {
    url:"index.php?route=user/user/autocomplete&token=<?php echo $this->request->get['token']; ?>",
      dataType: 'json',
    data: function (params) {
      return {
        q: params.term,
        j:21

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
</script>
<script type="text/javascript"><!--
function filter() {
	url = "index.php?route=laporan/penjualandetail&token=<?php echo $token; ?>";

	var filter_customer_id = $('select[name=\'filter_customer_id\']').val();

	if (filter_customer_id != '*') {
		url += '&filter_customer_id=' + encodeURIComponent(filter_customer_id);
	}
  /*
  var filter_provinsi = $("#filter-prop input:checkbox:checked").map(function(){
      return $(this).val();
    }).get(); // <----

    //alert(JSON.stringify(filter_statuss));
  if(filter_provinsi!= null){
    url+='&filter_provinsi=' +filter_provinsi;
  }
  */
  var filter_gudang_id = $('select[name=\'filter_gudang_id\']').val();

	if (filter_gudang_id != '*') {
		url += '&filter_gudang_id=' + encodeURIComponent(filter_gudang_id);
	}

  var jenistgl = $('select[name=\'jenistgl\']').val();

	if (jenistgl != '*') {
		url += '&jenistgl=' + encodeURIComponent(jenistgl);
	}

  var filter_provinsi = $('select[name=\'filter_provinsi\']').val();

	if (filter_provinsi != '*') {
		url += '&filter_provinsi=' + encodeURIComponent(filter_provinsi);
	}
  //alert(filter_provinsi=='null');

  var filter_sales= $('select[name=\'sales\']').val();

	if (filter_sales != '*') {
		url += '&filter_sales=' + encodeURIComponent(filter_sales);
	}

  var filter_date_start = $('input[name=\'filter_date_start\']').val();

	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

  var filter_date_end = $('input[name=\'filter_date_end\']').val();

	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}

  var filter_register_start = $('input[name=\'filter_register_start\']').val();

	if (filter_register_start) {
		url += '&filter_register_start=' + encodeURIComponent(filter_register_start);
	}

  var filter_register_end = $('input[name=\'filter_register_end\']').val();

	if (filter_register_end) {
		url += '&filter_register_end=' + encodeURIComponent(filter_register_end);
	}

  var filter_statuss = $("#status input:checkbox:checked").map(function(){
      return $(this).val();
    }).get(); // <----

    //alert(JSON.stringify(filter_statuss));
    url+='&filter_status=' +filter_statuss;

  /*var filter_shipping_method = $('select[name=\'filter_shipping_method\']').val();

	if (filter_shipping_method != '*') {
		url += '&filter_shipping_method=' + encodeURIComponent(filter_shipping_method);
	}
*/


	location = url;
}
function cetak() {
	//url = "index.php?route=laporan/penjualandetail&print=1&token=<?php echo $token; ?>";
  url ="<?php echo $cetak ?>";

	var filter_customer_id = $('select[name=\'filter_customer_id\']').val();

	if (filter_customer_id != '*') {
		url += '&filter_customer_id=' + encodeURIComponent(filter_customer_id);
	}

  var filter_gudang_id = $('select[name=\'filter_gudang_id\']').val();

	if (filter_gudang_id != '*') {
		url += '&filter_gudang_id=' + encodeURIComponent(filter_gudang_id);
	}

  /*  var filter_status= $('select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}*/

  var filter_date_start = $('input[name=\'filter_date_start\']').val();

	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

  var filter_date_end = $('input[name=\'filter_date_end\']').val();

	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}

  var filter_statuss = $("#status input:checkbox:checked").map(function(){
      return $(this).val();
    }).get(); // <----

    //alert(JSON.stringify(filter_statuss));
    url+='&filter_status=' +filter_statuss;

  /*var filter_shipping_method = $('select[name=\'filter_shipping_method\']').val();

	if (filter_shipping_method != '*') {
		url += '&filter_shipping_method=' + encodeURIComponent(filter_shipping_method);
	}
*/


  window.open(
  url,
  '_blank' // <- This is what makes it open in a new window.
  );
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
<script>
function detail(id,faktur){
  //alert(id);
  $("#display-faktur").html(faktur);
  $(".invoice td").css('background-color','#fff');
  $(".invoice td").css('font-weight','normal');
  $("#list-invoice-"+id+" td").css('background-color','#ccc');
  $("#list-invoice-"+id+" td").css('font-weight','bold');

  $('.list-product').hide();
  $('.total-transaksi').hide();

  $("#list"+id).show();
  $("#total"+id).show();
}
</script>
<?php echo $footer; ?>
