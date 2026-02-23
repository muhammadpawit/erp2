<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=FileImportTest.xls");
?>
<?php //echo $header; ?>
<style> .str{ mso-number-format:\@; } </style>
<div class="content-wrapper">
  <section class="content-header">
    <h1>

    </h1>

  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
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
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
                  <table class="table table-bordered" border="1">
                    <thead>
                      <tr>
                        <th>FK</th>
                        <th>KD_JENIS_TRANSAKSI</th>
                        <th>FG_PENGGANTI</th>
                        <th>NOMOR_FAKTUR</th>
                        <th>MASA_PAJAK</th>
                        <th>TAHUN_PAJAK</th>
                        <th>TANGGAL_FAKTUR</th>
                        <th>NPWP</th>
                        <th>NAMA</th>
                        <th>ALAMAT_LENGKAP</th>
                        <th>JUMLAH_DPP</th>
                        <th>JUMLAH_PPN</th>
                        <th>JUMLAH_PPNBM</th>
                        <th>ID_KETERANGAN_TAMBAHAN</th>
                        <th>FG_UANG_MUKA</th>
                        <th>UANG_MUKA_DPP</th>
                        <th>UANG_MUKA_PPN</th>
                        <th>UANG_MUKA_PPNBM</th>
                        <th>REFERENSI</th>
                      </tr>
                      <tr>
                        <th>LT</th>
                        <th>NPWP</th>
                        <th>NAMA</th>
                        <th>JALAN</th>
                        <th>BLOK</th>
                        <th>NOMOR</th>
                        <th>RT</th>
                        <th>RW</th>
                        <th>KECAMATAN</th>
                        <th>KELURAHAN</th>
                        <th>KABUPATEN</th>
                        <th>PROVINSI</th>
                        <th>KODE_POS</th>
                        <th>NOMOR_TELEPHONE</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                      </tr>
                      <tr>
                        <th>OF</th>
                        <th>KODE_OBJEK</th>
                        <th>NAMA</th>
                        <th>HARGA_SATUAN</th>
                        <th>JUMLAH_BARANG</th>
                        <th>HARGA_TOTAL</th>
                        <th>DISKON</th>
                        <th>DPP</th>
                        <th>PPN</th>
                        <th>TARIF_PPNBM</th>
                        <th>PPNBM</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $i=4;?>
                      <?php if ($penjualans) { ?>
                      <?php foreach ($penjualans as $product) { ?>
                      <tr>
                        <td>FK</td>
                        <td class='str'>01</td>
                        <td class='str'>0</td>
                        <td class='str'>00<?php echo $product['no_faktur'] ?></td>
                        <td><?php echo $product['masapajak'] ?></td>
                        <td><?php echo $product['tahunpajak'] ?></td>
                        <td><?php echo  $product['tanggal']?></td>
                        <td class='str'><?php echo str_replace('-', "", $product['npwp']) ; ?></td>
                        <td><?php echo $product['name']; ?></td>
                        <td><?php echo $product['alamat']; ?></td>
                        <td class="left"><?php echo $product['sub_total']; ?></td>
                        <td class="left"><?php echo $product['pajak']; ?></td>
                        <td class="str">0</td>
                        <td class="str">0</td>
                        <td class="str">0</td>
                        <td class="str">0</td>
                        <td class="str">0</td>
                        <td class="str">0</td>
                        <td class="str">0</td>
                      </tr>
                        <?php foreach($product['products'] as $p ){?>
                        <tr>
                          <td>OF</td>
                          <td></td>
                          <td><?php echo $p['name']?></td>
                          <td><?php echo $p['price']?></td>
                          <td><?php echo $p['quantity']?></td>
                          <td><?php echo $p['price'] * $p['quantity']?></td>
                          <td>0</td>
                          <td><?php echo $p['price'] * $p['quantity'] //echo $product['sub_total']; ?></td>
                          <td><?php $ppn=0; $ppn=($p['price']*$p['quantity'])/10; echo round($ppn,0,PHP_ROUND_HALF_EVEN); ?></td>
                          <td class="str">0</td>
                          <td class="str">0</td>
                          <td></td>
                          <td class="str"></td>
                          <td class="str"></td>
                          <td class="str"></td>
                          <td class="str"></td>
                          <td class="str"></td>
                          <td class="str"></td>
                          <td class="str"></td>
                        </tr>
                        <?php } ?>
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
<script>
$('.sidebar-menu').find('#menu-penjualan').addClass('active');
$('.sidebar-menu').find('#menu-salesorder').addClass('active');
</script>
<script>
$(function(){
  $('.date').datepicker({dateFormat: 'yy-mm-dd'});
})
</script>
<script type="text/javascript"><!--
function filter() {
	url = "index.php?route=sale/invoice&token=<?php echo $token; ?>";

	var filter_customer_id = $('select[name=\'filter_customer_id\']').val();

	if (filter_customer_id != '*') {
		url += '&filter_customer_id=' + encodeURIComponent(filter_customer_id);
	}

  var filter_gudang_id = $('select[name=\'filter_gudang_id\']').val();

	if (filter_gudang_id != '*') {
		url += '&filter_gudang_id=' + encodeURIComponent(filter_gudang_id);
	}

    var filter_status= $('select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

  var filter_tanggal = $('input[name=\'filter_tanggal\']').val();

	if (filter_tanggal) {
		url += '&filter_tanggal=' + encodeURIComponent(filter_tanggal);
	}
  var filter_order_id = $('select[name=\'filter_order_id\']').val();

	if (filter_order_id != '*') {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
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
<?php // echo $footer; ?>
