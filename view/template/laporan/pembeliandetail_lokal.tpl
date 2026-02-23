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
            <h3 class="box-title">Laporan Pembelian Detail Lokal</h3>
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
              <div class="col-sm-3 col-xs-12">
                <table class="table table-stripped">
                  <tr>
                    <td>Tanggal Awal(SJ)</td>
                    <td> <input type="text" class="form-control date" name="filter_date_start" value="<?php echo $filter_date_start; ?>" /></td>
                  </tr>
                  <tr>
                    <td>Tanggal Akhir(SJ)</td>
                    <td> <input type="text" class="form-control date" name="filter_date_end" value="<?php echo $filter_date_end; ?>" /></td>
                  </tr>
                  <?php //if($this->user->getUsername()=="pawit"){?>
                  <tr>
                    <td>Tanggal Awal(Invoice)</td>
                    <td> <input type="text" class="form-control date" name="filter_date_startsj" value="<?php echo $filter_date_startsj; ?>" /></td>
                  </tr>
                  <tr>
                    <td>Tanggal Akhir(Invoice)</td>
                    <td> <input type="text" class="form-control date" name="filter_date_endsj" value="<?php echo $filter_date_endsj; ?>" /></td>
                  </tr>
                  <?php //} ?>
                  <tr>
                    <td>Supplier</td>
                    <td>
                       <select style="width:200px" name="filter_vendor" class="vendor">
                          <option value="*">Semua Vendor</option>

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
                    <td>Jenis Pembelian</td>
                    <td>
                      <select name="jenis" class="form-control">
                        <option value="1">Produk Dagang</option>
                        <!--<option value="2">Non-Produk Dagang</option>-->
                      </select>
                    </td>
                  </tr>
                  <!--
                  <tr>
                    <td>Status Pembayaran</td>
                    <td id="status">
                      <input type="checkbox" name="filter_status[]" <?php echo in_array(1,$filter_status)?'checked':''; ?> value="1"> Ditagih<br>
                      <input type="checkbox" name="filter_status[]" <?php echo in_array(2,$filter_status)?'checked':''; ?> value="2"> Belum Lunas<br>
                      <input type="checkbox" name="filter_status[]" <?php echo in_array(4,$filter_status)?'checked':''; ?> value="4"> Lunas<br>
                    </td>
                  </tr>-->
                  <tr>
                    <td><a onclick="filter();" class="btn btn-info">Filter</a></td>
                    <td>
                      <div class="btn-group">
                        <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                          Download to Excel&nbsp;<span class="caret"></span>
                          <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                          <li><a href="<?php echo $cetak?>" target="_blank"><i class="fa fa-angle-right"></i>&nbsp;Tanpa Detail Invoice</a></li>
                          <?php //if($this->user->getUsername()=="pawit"){?>
                          <li><a href="<?php echo $cetakproduk ?>" target="_blank"><i class="fa fa-angle-right"></i>&nbsp;Dengan Detail Invoice</a></li>
                          <?php //} ?>
                        </ul>
                      </div>
                    </td>
                    <!-- <td></td> -->
                  </tr>
                  <!--
                  <tr>
                    <td><b>Jumlah: <?php echo $this->currency->format(0); ?></b><br>
                      Jumlah Tanpa Pajak: <?php echo $this->currency->format(0); ?>
                    </td>
                    <td></td>
                  </tr>-->

                </table>

              </div>
              <div class="col-sm-9 col-xs-12 table-responsive" style="max-height:400px;overflow-y:scroll">
                <div class="callout callout-success lead">
                  <h4>Rincian Invoice <span id="display-faktur"></span></h4>
                </div>
                <table class="table table-bordered">
                  <thead>
                    <th>Tgl.Invoice</th>
                    <th>No.Dokumen</th>
                    <th>No.Invoice</th>
                    <th>No.PO</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                    <th>Pajak</th>
                    <th>Total</th>
                    <!--<th>Total Tagihan</th>-->
                    <th>Status</th>
                  </thead>
                  <?php if ($penjualans) { ?>
                  <?php foreach ($penjualans as $p) {   ?>
                  <tbody class="list-product" id="list<?php echo $p['id']; ?>">
                      <!--<tr>
                        <td colspan="11">Rincian Invoice</td>
                      </tr>-->
                      <?php
                        foreach($p['products'] as $product){
                          $po=$this->model_pembelian_pembeliankreditdagang->getPermintaanPembelian(array(),array(),array('id'=>$product['po_id']));
                          $sjp=$this->model_pembelian_pembeliankreditdagang->getsjpembelian($product['po_product_id']);
                      ?>
                      <tr >
                        <td><small><?php echo date('d/m/Y',strtotime($product['tglfaktur']));?></small></td>
                        <td><small><?php echo $product['no_dokumen'];?></small></td>
                        <td><small><?php echo $product['no_faktur'];?></small></td>
                        <td><small><?php echo $product['no_po'];?></small></td>
                        <td><small><?php echo $product['product_name'];?></small></td>
                        <td><small><?php echo $product['quantity'];?></small></td>
                        <td><?php echo $this->currency->format($product['price']); ?></td>
                        <td><?php echo $this->currency->format($product['price']*$product['quantity']); ?></td>
                        <td><?php echo $this->currency->format($product['pajak']); ?></td>
                        <td><?php echo $this->currency->format($product['total']); ?></td>
                        <!--<td><?php echo $this->currency->format($product['totalivs']); ?></td>-->
                        <td>
                          <?php 
                            if($product['status']==1){
                              echo 'Ditagih';
                            }else if($product['status']==2){
                              echo "Dibayar sebagian";
                            }else if($product['status']==4){
                              echo "Lunas";
                            }
                          ?>
                        </td>
                      </tr>
                      <tr>
                        <?php if(!empty($product['no_dokumen'])){ ?>
                        <td colspan="11">
                          <a href="index.php?route=laporan/jurnalumum&token=<?php echo $this->request->get['token']; ?>&filter_nodokumen=<?php echo $product['no_dokumen']?>" class="badge bg-orange" target="_blank">Lihat Jurnal</a>
                          <a href="index.php?route=pembelian/invoicepembeliandagang/tampil&token=<?php echo $this->request->get['token']; ?>&filter_nodokumen=<?php echo $product['invoice_id']?>" class="badge bg-orange" target="_blank">Lihat Detail Invoice</a>
                        </td>
                        <?php }else{ ?>
                        <td colspan="11">
                          <a href="index.php?route=laporan/jurnalumum&token=<?php echo $this->request->get['token']; ?>&filter_keterangan=Invoice Pembelian Produk Dagang Dengan No Faktur <?php echo $product['no_faktur']?>" class="badge bg-orange" target="_blank">Lihat Jurnal</a>
                          <a href="index.php?route=pembelian/invoicepembeliandagang/tampil&token=<?php echo $this->request->get['token']; ?>&id=<?php echo $product['invoice_id']?>" class="badge bg-orange" target="_blank">Lihat Detail Invoice</a>
                        </td>
                        <?php } ?>
                      </tr>
                    <?php
                    }
                    ?>
                      
                      <!--<tr>
                        <td colspan="11">Rincian Terima Barang</td>
                      </tr>-->
                    <?php
                        foreach($p['products'] as $product){
                          $po=$this->model_pembelian_pembeliankreditdagang->getPermintaanPembelian(array(),array(),array('id'=>$product['po_id']));
                          $sjp=$this->model_pembelian_pembeliankreditdagang->getsjpembelian($product['po_product_id']);
                      ?>
                      <!--
                      <tr >
                        <?php if(!empty($filter_date_startsj)){?>
                        <td><small><?php echo date('d-m-Y',strtotime($sjp['tgl_terima']));?></small></td>
                        <?php }else{ ?>
                        <td><small><?php echo date('d-m-Y',strtotime($sjp['tgl_terima']));?></small></td>
                        <?php } ?>
                        <td><small><?php echo $sjp['no_suratjalan'];?></small></td>
                        <td><small><?php echo $sjp['no_dokumen'];?></small></td>
                        <td><small><?php echo date('d-m-Y',strtotime($po['date_added']));?></small></td>
                        <td><small><?php echo $po['no_po'];?></small></td>
                        <td><?php echo $sjp['quantity'].' '.$product['namasatuan']; ?></td>
                        <td><?php echo $product['product_name']; ?></td>
                        <td><?php echo $this->currency->format($product['price']); ?></td>
                        <td><?php //echo $this->currency->format(round(($product['price']/10))); ?></td>
                        <td><?php //echo $this->currency->format($product['pajak']); ?></td>
                        <td><?php echo $this->currency->format( ($product['price']*$sjp['quantity']) ); ?></td>
                      </tr>-->
                    <?php
                    }
                    ?>
                    <!--<tr>
                      <td colspan="11"><a href="index.php?route=laporan/jurnalumum&token=<?php echo $this->request->get['token']; ?>&filter_keterangan=Peneriman Pembelian Produk Dagang Dengan Surat Jalan <?php echo $sjp['no_suratjalan'];?>" class="badge bg-orange" target="_blank">Lihat Jurnal</a></td>
                    </tr>-->
                  </tbody>
                    <?php
                    }
                    }
                    ?>
                   
                </table>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
                  <table class="table table-bordered table-hover" id="myTable">
                    <thead>
                      <tr>
                        <th class="left">Tanggal</th>
                        <th class="left">No.Dokumen</th>
                        <th class="left">No.Surat Jalan</th>
                        <th class="left">No.PO</th>
                        <!--<th class="left">No.Invoice</th>-->
                        <th class="left">Nama Supplier</th>
                        <th class="left">Nama Barang</th>
                        <th class="left">Harga</th>
                        <th class="left">Quantity</th>
                        <th class="left">Total Persediaan</th>
                        <th class="left"></th>
                        <!--<th class="left">Status</th>
                        <th class="left">Jumlah</th>
                        <th class="left">Total Bayar</th>
                        <th class="left">Tgl Invoice</th>
                        <th class="left">Invoice</th>
                        <th class="left">Metode Pembayaran</th>
                        <th width="1" class="left">Lama Kredit (Hari)</th>
                        <th width="1" class="left">Tgl Jatuh Tempo</th>
                        <th width="3" class="left">Status</th>
                        <th width="1"style="text-align: center;">Tgl Lunas</th>-->
                      </tr>
                    </thead>
                    <tbody>                      
                      <?php if ($penjualans) { ?>
                      <?php foreach ($penjualans as $product) { ?>
                      <tr class="invoice" data-id="<?php echo $product['id']; ?>" data-faktur="<?php echo $product['no_faktur']; ?>" id="list-invoice-<?php echo $product['id']; ?>" >
                        <td><?php echo $product['tgl_terima']; ?></td>
                        <td><?php echo $product['no_dokumen']; ?></td>
                        <td><?php echo $product['no_suratjalan']; ?></td>
                        <td><?php echo $product['no_po']; ?></td>
                        <!--<td><?php echo $product['no_invoice']; ?></td>-->
                        <td><?php echo $product['supplier']; ?></td>
                        <td><?php echo $product['product_name']; ?></td>
                        <td><?php echo $product['harga']; ?></td>
                        <td><?php echo $product['quantity']; ?></td>
                        <td><?php echo $product['total']; ?></td>
                        <td class="right"><?php foreach ($product['actions'] as $action) { ?>
                           <a href="<?php echo $action['href']; ?>" target="_blank" class="label label-warning"><?php echo $action['text']; ?></a>&nbsp;
                          <?php } ?>
                        </td>
                        <!--<td><?php echo $product['status']; ?></td>-->
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td colspan="12">Data tidak ditemukan</td>
                      </tr>
                      <?php } ?>
                      <tr>
                          <td colspan="7"><b>Total</b></td>
                          <td><b><?php echo $totaljumlah ?></b></td>
                          <td><b><?php echo $this->currency->format($total) ?></b></td>
                          <td></td>
                      </tr>
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
<?php if (count($penjualans)>0) { ?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
<script>
$(document).ready( function () {
    /*
    $('#myTable').DataTable( {
        "lengthChange": false,
        "bPaginate": false,
        "bFilter": false,
    });
    */
} );
</script>
<?php } ?>
<script>
$('.sidebar-menu').find('#menu-laporan').addClass('active');
$('.sidebar-menu').find('#laporan-pembelian-detail').addClass('active');
$('.sidebar-menu').find('#laporan-pembelian-detail-lokal').addClass('active');
$(function(){
  $('.date').datepicker({dateFormat: 'yy-mm-dd'});
  $('.list-product').hide();
  $('.total-transaksi').hide();

  $(".invoice").on('click',function(){
    id=$(this).data('id');
    faktur=$(this).data('faktur');
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
	url = "index.php?route=laporan/pembeliandetaillokal&token=<?php echo $token; ?>";

	var filter_customer_id = $('select[name=\'filter_vendor\']').val();

	if (filter_customer_id != '*') {
		url += '&filter_vendor=' + encodeURIComponent(filter_customer_id);
	}
 
  var filter_gudang_id = $('select[name=\'filter_gudang_id\']').val();

	if (filter_gudang_id != '*') {
		url += '&filter_gudang_id=' + encodeURIComponent(filter_gudang_id);
	}


  var filter_date_start = $('input[name=\'filter_date_start\']').val();

	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

  var filter_date_end = $('input[name=\'filter_date_end\']').val();

	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}

  var filter_date_startsj = $('input[name=\'filter_date_startsj\']').val();
  
  if (filter_date_startsj) {
		url += '&filter_date_startsj=' + encodeURIComponent(filter_date_startsj);
	}

  var filter_date_endsj = $('input[name=\'filter_date_endsj\']').val();

	if (filter_date_endsj) {
		url += '&filter_date_endsj=' + encodeURIComponent(filter_date_endsj);
	}

  /*
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
    $(".vendor").select2({
    ajax: {
    url:"index.php?route=catalog/vendorlokal/autocomplete&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        filter_name: params.term
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
