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
            <h3 class="box-title">Refund Deposit Pembayaran Customer</h3>
            <div class="button pull-right">
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
                        <th>Tanggal Awal</th>
                        <th>Tanggal Akhir</th>
                        <th>Customer</th>

                      </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td><input class="date form-control" type="text" name="filter_date_start" value="<?php echo $filter_tgl_awal; ?>" readonly></td>
                      <td><input class="date form-control" type="text" name="filter_date_end" value="<?php echo $filter_tgl_akhir; ?>" readonly></td>
                      <td>
                        <select style="width:200px;" name="filter_customer_id" class="form-control lokasi-pameran">

                        </select>
                      </td>

                    </tr>
                  </tbody>
                  </table>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <table class="table table-stripped">
                    <thead>
                      <tr>
                        <th>Metode Pembayaran</th>
                        <th>Bank/Kas</th>
                        <th>No. Giro</th>
                        <th>Status</th>
                        <th></th>

                      </tr>
                    </thead>
                    <tbody>
                    <tr>

                      <td><select name="filter_metode" class="form-control">
                        <option value="0">Semua Metode Pembayaran</option>
                        <option value="1">Tunai</option>
                        <option value="2">Transfer Bank</option>
                        <option value="3">Giro</option>
                        <option value="4">Cheque</option>
                     	</select></td>
                    <td>
                      <select style="width:200px;" name="filter_bank_id" class="form-control bank">

                      </select>
                    </td>
                    <td><input class="form-control" type="text" name="filter_no_giro" value="" ></td>
                    <td><select name="filter_status" class="form-control">
                      <option value="0">Semua Status</option>
                      <option value="1">Disimpan</option>
                      <option value="2">Diterima</option>
                      <option value="3">Dibatalkan</option>

                    </select></td>
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
                        <th>Tanggal Refund</th>
                        <th>Tanggal Diterima</th>
                        <th>Customer</th>
                        <th>Jenis</th>
                        <th>No. Giro</th>
                        <th>Keterangan</th>
                        <th>Bank/Kas</th>
                        <th>Metode Pembayaran</th>
                        <th>Jumlah Pembayaran</th>
                        <th>Status</th>
                        <th></th>

                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($permintaans) { ?>
                      <?php foreach ($permintaans as $product) { ?>
                      <tr>
                        <td><?php echo $product['tanggal']; ?></td>
                        <td><?php echo $product['tanggalditerima']; ?></td>
                        <td><?php echo $product['customer']; ?></td>
                        <td><?php echo $product['jenis'] == 1?'Deposit Customer':'Pembayaran Tunai/COD';

                          ?>
                        </td>
                        <td><?php echo $product['no_giro']; ?></td>
                        <td><?php echo $product['keterangan']; ?></td>
                        <td><?php echo $product['nama_bank']; ?></td>
                        <td><?php
                          if($product['metode_pembayaran'] == 1){
                            echo 'Tunai';
                          }
                          if($product['metode_pembayaran'] == 2){
                            echo 'Transfer Bank';
                          }
                          if($product['metode_pembayaran'] == 3){
                            echo 'Giro';
                          }
                          if($product['metode_pembayaran'] == 4){
                            echo 'Cheque';
                          }
                          ?></td>
                          <td><?php echo $product['jumlah']; ?></td>
                          <td>
                            <?php
                              if($product['status'] == 1){
                                echo 'Disimpan';
                              }
                              if($product['status'] == 2){
                                echo 'Diterima';
                              }
                              if($product['status'] == 3){
                                echo 'Dibatalkan';
                              }

                              ?>
                          </td>
                      <td class="right"><?php foreach ($product['actions'] as $action) {

                          ?>

                           <a href="<?php echo $action['href']; ?>"  <?php echo $action['text']=='Cetak PO'?'target="_blank"':''; ?> class="badge bg-yellow"><?php echo $action['text']; ?></a>
                          <?php } ?></td>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="6">Data tidak ditemukan</td>
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
$('.sidebar-menu').find('#menu-keuangan').addClass('active');
</script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});

	$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=keuangan/refunddeposit&token=<?php echo $token; ?>';

	var filter_date_start = $('input[name=\'filter_date_start\']').val();

	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

  var filter_date_end = $('input[name=\'filter_date_end\']').val();

	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}

  var filter_no_giro = $('input[name=\'filter_no_giro\']').val();

	if (filter_no_giro) {
		url += '&filter_no_giro=' + encodeURIComponent(filter_no_giro);
	}

  var filter_customer_id = $('select[name=\'filter_customer_id\']').val();

	if (filter_customer_id) {
		url += '&filter_customer_id=' + encodeURIComponent(filter_customer_id);
	}

  var filter_metode = $('select[name=\'filter_metode\']').val();

	if (filter_metode) {
		url += '&filter_metode=' + encodeURIComponent(filter_metode);
	}

  var filter_bank_id = $('select[name=\'filter_bank_id\']').val();

	if (filter_bank_id) {
		url += '&filter_bank_id=' + encodeURIComponent(filter_bank_id);
	}

  var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status) {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

	location = url;
}
//--></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
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
  })
  $(".bank").select2({
    ajax: {
    url:"index.php?route=keuangan/bank/autocomplete&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        q: params.term,
        c:1

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
  })
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
