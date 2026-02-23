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
            <h3 class="box-title">Laporan Penjualan Detail to Excel</h3>
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
                    <td>
                      <!--<a href="<?php echo $exporttoexcel ?>" class="btn btn-success" target="_blank">Export to Excel</a>-->
                      <div class="btn-group">
                        <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                          Download to Excel&nbsp;<span class="caret"></span>
                          <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                          <li><a href="<?php echo $exporttoexcel ?>" target="_blank"><i class="fa fa-angle-right"></i>&nbsp;Tanpa Detail Produk</a></li>
                          <li><a href="<?php echo $exporttoexcelproduk ?>" target="_blank"><i class="fa fa-angle-right"></i>&nbsp;Dengan Detail Produk</a></li>
                        </ul>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td><b>Jumlah: <?php echo $jumlah; ?></b><br>
                      Jumlah Tanpa Pajak: <?php echo $jumlahtanpapajak; ?>
                    </td>
                    <td><b>No. Transaksi: <?php echo $total; ?></b> </td>
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
                
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th width="1" style="text-align: center;">
                          <?php if ($sort == 'invoice.date_added') { ?>
                            <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>">Tanggal</a>
                            <?php } else { ?>
                            <a href="<?php echo $sort_date_added; ?>">Tanggal</a>
                            <?php } ?>
                        </th>
                        <th width="1" style="text-align: center;">
                          <?php if ($sort == 'customer.date_added') { ?>
                            <a href="<?php echo $sort_register; ?>" class="<?php echo strtolower($order); ?>">Tanggal Input Customer</a>
                            <?php } else { ?>
                            <a href="<?php echo $sort_register; ?>">Tanggal Input Customer</a>
                            <?php } ?>
                        </th>
                        <th class="left">
                        	<a href="index.php?route=laporan/penjualandetail&token=<?php echo $token; ?>">Nama Sales</a>
                        </th>
                        <th class="left">
                        	<a href="index.php?route=laporan/penjualandetail&token=<?php echo $token; ?>">Customer ID</a>
                        </th>                        
                        <th class="left">
                          <?php if ($sort == 'customer.name') { ?>
                            <a href="<?php echo $sort_customer; ?>" class="<?php echo strtolower($order); ?>">Nama Customer</a>
                            <?php } else { ?>
                            <a href="<?php echo $sort_customer; ?>">Nama Customer</a>
                            <?php } ?>
                        </th>
                        <th>
                            Kategori
                        </th>
                        <th>
                            Telephone
                        </th>
                        <th>
                            Alamat NPWP
                        </th>
                        <th>
                            Alamat KTP
                        </th>
                        <th>
                            Provinsi
                        </th>
                        <th class="left">
                          <?php if ($sort == 'invoice.totaltagihan') { ?>
                            <a href="<?php echo $sort_tagihan; ?>" class="<?php echo strtolower($order); ?>">Jumlah</a>
                            <?php } else { ?>
                            <a href="<?php echo $sort_tagihan; ?>">Jumlah</a>
                            <?php } ?>
                        </th>
                        <th class="left">
                          <?php if ($sort == 'invoice.totalbayar') { ?>
                            <a href="<?php echo $sort_bayar; ?>" class="<?php echo strtolower($order); ?>">Total Bayar</a>
                            <?php } else { ?>
                            <a href="<?php echo $sort_bayar; ?>">Total Bayar</a>
                            <?php } ?>
                        </th>
                        <!--th class="left">No. SO</th>
                        <th class="left">No. SJ</th-->
                        <th class="left">
                          <?php if ($sort == 'invoice.id') { ?>
                            <a href="<?php echo $sort_invoice; ?>" class="<?php echo strtolower($order); ?>">Invoice</a>
                            <?php } else { ?>
                            <a href="<?php echo $sort_invoice; ?>">Invoice</a>
                            <?php } ?>
                        </th>
                        <th class="left">
                          <?php if ($sort == 'invoice.metode_pembayaran') { ?>
                            <a href="<?php echo $sort_metode_pembayaran; ?>" class="<?php echo strtolower($order); ?>">Metode Pembayaran</a>
                            <?php } else { ?>
                            <a href="<?php echo $sort_metode_pembayaran; ?>">Metode Pembayaran</a>
                            <?php } ?>
                        </th>
                        <th class="left"><a href="">Lama Kredit(Hari)</a></th>
                        <th class="left">
                          <?php if ($sort == 'invoice.status') { ?>
                            <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>">Status</a>
                            <?php } else { ?>
                            <a href="<?php echo $sort_status; ?>">Status</a>
                            <?php } ?>
                        </th>
                        <th width="1" style="text-align: center;">
                          <?php if ($sort == 'invoice.tgllunas') { ?>
                            <a href="<?php echo $sort_tgl_lunas; ?>" class="<?php echo strtolower($order); ?>">Tgl Lunas</a>
                            <?php } else { ?>
                            <a href="<?php echo $sort_tgl_lunas; ?>">Tgl Lunas</a>
                            <?php } ?>
                        </th>

                      </tr>
                    </thead>
                    <tbody>


                      <?php if ($penjualans) { ?>
                      <?php foreach ($penjualans as $product) { ?>
                      <tr class="invoice" data-id="<?php echo $product['id']; ?>" data-faktur="<?php echo $product['no_faktur']; ?>" id="list-invoice-<?php echo $product['id']; ?>" >
                        <td><?php echo $product['tanggal']; ?></td>
                        <td><?php echo $product['register']; ?></td>
                        <td class="left"><?php echo $product['namasales'] ?></td>
                        <td class="left"><?php echo $product['customer_id'] ?></td>
                        <td class="left"><?php echo $product['name']; ?></td>
                        <td class="left"><?php echo $product['kategori']; ?></td>
                        <td class="left"><?php echo $product['telephone']; ?></td>
                        <td class="left"><?php echo $product['alamatnpwp']; ?></td>
                        <td class="left"><?php echo $product['alamatktp']; ?></td>
                        <td class="left"><?php echo $product['provinsi']; ?></td>

                        <td><?php echo $product['total']; ?></td>
                        <td><?php echo $product['totalbayar']; ?></td>
                        <td class="left"><?php echo $product['no_faktur']; ?></td>
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
$('.sidebar-menu').find('#menu-laporan').addClass('active');

</script>
<script>
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
})
</script>
<script type="text/javascript"><!--
function filter() {
	url = "index.php?route=laporan/penjualandetailexportexcel&token=<?php echo $token; ?>";

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
	url = "index.php?route=laporan/penjualandetailexportexcel&print=1&token=<?php echo $token; ?>";

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
