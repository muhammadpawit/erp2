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
            <h3 class="box-title">Pembayaran Penjualan Kredit</h3>
                <div class="button pull-right">
                  <a href="<?php echo $cetakexcel; ?>" target="_blank"><button type="button" class="btn btn-success">Export to Excel</button></a>
                  <a href="<?php echo $insert; ?>"><button type="button" class="btn btn-primary">Tambah</button></a>
								</div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">

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
                <table class="table table-stripped">
                    <thead>
                      <tr>
                        <th>Tanggal Awal<br><small>Alokasi</small></th>
                        <th>Tanggal Akhir<br><small>Alokasi)</small></th>
                        <th>Tanggal Awal<br><small>(Penerimaan Dana)</small></th>
                        <th>Tanggal Akhir<br><small>(Penerimaan Dana)</small></th>
                        <th>Nomor Invoice</th>
                        <th>Nama Customer</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                    <tr>
                    <td><input type="text" name="tgl_input_awal" class="form-control filter_tanggal_awal" value="<?php echo $tgl_input_awal ?>"/></td>
                    <td><input type="text" name="tgl_input_akhir" class="form-control filter_tanggal_akhir" value="<?php echo $tgl_input_akhir ?>"/></td>
                    <td><input type="text" name="filter_tanggal_awal" class="form-control filter_tanggal_awal" value="<?php echo $filter_tanggal_awal ?>"/></td>
                    <td><input type="text" name="filter_tanggal_akhir" class="form-control filter_tanggal_akhir" value="<?php echo $filter_tanggal_akhir ?>"/></td>
                     <td>
                      <select name="filter_no_invoice" class="salesorder form-control">
                        <option value="*">Semua Invoice Tunai</option>
                      </select>
                    </td>
                    <td>
                      <select name="filter_customer_id" class="form-control lokasi-pameran">
                        <option value="*" >Semua Customer</option>
                      </select>
                    </td>
                    <td ><a onclick="filter();" class="btn btn-info">Filter</a></td>
                    </tr>
                  </tbody>
                  </table>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Tanggal <br><small>(Alokasi)</small></th>
                        <th>Tanggal <br><small>(Pen.Dana)</small></th>
                        <th>Lama Alokasi (hari)</small></th>
                        <th>No. Dokumen</th>
                        <th>Nama Customer</th>
                        <th>Total Alokasi Pembayaran</th>
                        <th>Invoice</th>
                        <th>Status</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($permintaans) { ?>
                      <?php foreach ($permintaans as $product) { ?>
                      <tr>
                        <td><?php echo $product['tanggalinput']; ?></td>
                        <td><?php echo $product['tanggal']; ?></td>
                        <td><?php echo $product['lamaalokasi']; ?></td>
                        <td><?php echo $product['no_dokumen']; ?></td>
                        <td><?php echo $product['name']; ?></td>
                          <td><?php echo $product['total']; ?></td>
                            <td><?php
                              foreach($product['invoice'] as $i){
                                echo $i['invoice'].' '.$i['totalbayar'].'<br>';
                              }
                               ?></td>
                        <td><?php echo ($product['status']==1)?'Dialokasikan':'Dibatalkan' ?></td>
                      <td class="right"><?php foreach ($product['actions'] as $action) {

                          ?>

                           <a href="<?php echo $action['href']; ?>"  <?php echo $action['text']=='Cetak PO'?'target="_blank"':''; ?> class="badge bg-yellow"><?php echo $action['text']; ?></a>
                          <?php } ?></td>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="5">Data tidak ditemukan</td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>

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
$('.sidebar-menu').find('#menu-pembayaran').addClass('active');

</script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.filter_tanggal_awal').datepicker({dateFormat: 'yy-mm-dd'});
  $('.filter_tanggal_akhir').datepicker({dateFormat: 'yy-mm-dd'});

	$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
  $(".lokasi-pameran").select2({
    ajax: {
    url:"index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>",
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
        q: params.term,
        //p: 4 // search term

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
});
//--></script>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=sale/pembayarankredit&token=<?php echo $token; ?>';

	var filter_customer_id = $('select[name=\'filter_customer_id\']').val();
	if (filter_customer_id !='*') {
		url += '&filter_customer_id=' + encodeURIComponent(filter_customer_id);
	}

  var filter_no_invoice = $('select[name=\'filter_no_invoice\']').val();
	if (filter_no_invoice !='*') {
		url += '&filter_no_invoice=' + encodeURIComponent(filter_no_invoice);
	}

  var tgl_input_awal = $('input[name=\'tgl_input_awal\']').val();
	if (tgl_input_awal) {
		url += '&tgl_input_awal=' + encodeURIComponent(tgl_input_awal);
	}

   var tgl_input_akhir = $('input[name=\'tgl_input_akhir\']').val();
	if (tgl_input_akhir) {
		url += '&tgl_input_akhir=' + encodeURIComponent(tgl_input_akhir);
	}

  var filter_tanggal_awal = $('input[name=\'filter_tanggal_awal\']').val();
	if (filter_tanggal_awal) {
		url += '&filter_tanggal_awal=' + encodeURIComponent(filter_tanggal_awal);
	}

  var filter_tanggal_akhir = $('input[name=\'filter_tanggal_akhir\']').val();
	if (filter_tanggal_akhir) {
		url += '&filter_tanggal_akhir=' + encodeURIComponent(filter_tanggal_akhir);
	}


	location = url;
}
//--></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script>
<?php echo $footer; ?>
