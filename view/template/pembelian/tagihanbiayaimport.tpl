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
            <h3 class="box-title">Tagihan Biaya Pembelian Import</h3>
            <div class="button pull-right">
                  <a href="<?php echo $insert; ?>"><button type="button" class="btn btn-primary">Tambah</button></a>
                  <a href="<?php echo $insertpembayaran; ?>"><button type="button" class="btn btn-success">Input Pembayaran</button></a>
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
              <div class="col-md-3">
                <table class="table table-stripped">

                  <tr>
                    <td>Tanggal Awal</td>
                    <td><input class="date form-control" type="text" name="filter_date_start" value="<?php echo $filter_tgl_awal; ?>"></td>
                  </tr>
                  <tr>
                    <td>Tanggal Akhir</td>
                    <td><input class="date form-control" type="text" name="filter_date_end" value="<?php echo $filter_tgl_akhir; ?>"></td>
                  </tr>
                  <tr>
                    <td>Tanggal Bayar</td>
                    <td>
                      <input class="date form-control" type="text" name="filter_date_bayarawal" value="<?php echo $filter_date_bayarawal; ?>"><br>
                      <input class="date form-control" type="text" name="filter_date_bayarakhir" value="<?php echo $filter_date_bayarakhir; ?>">
                    </td>
                  </tr>
                  <tr>
                    <td>Nomor Faktur</td>
                    <td>
                      <select class="form-control nosurat" name="filter_no_faktur"></select>
                    </td>
                  </tr>
                  <tr>
                    <td><span class="required">*</span>Vendor</td>
                    <td>
                      <select name="vendor_id" class="form-control vendor">
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <td>Status</td>
                    <td><select name="filter_jenis" class="form-control">
                      <option value="0">Semua Status</option>
                      <option value="1">Belum Dibayar</option>
                      <option value="2">Dibayar Sebagian</option>
                      <option value="3">Lunas</option>
                   		</select></td>
                  </tr>
                  <tr>
                    <td colspan="2"><a onclick="filter();" class="btn btn-info">Filter</a></td>
                  </tr>
                  </table>
              </div>

              <div class="col-md-9">
                <div class="callout callout-success lead">
                  <h4>Daftar Tagihan</h4>

                </div>
                <p>*Klik tampil untuk melihat detail tagihan</p>
                <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Tanggal Tagihan</th>
                        <th>Jatuh Tempo</th>
                        <th>Tanggal Bayar</th>
                        <th>Vendor</th>
                        <th>Nomor Faktur</th>
                        <th>Keterangan</th>
                        <th>Total</th>
                        <th>Total Dibayar</th>
                        <th>Status</th>
                        <th></th>

                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($permintaans) { ?>
                      <?php foreach ($permintaans as $product) { ?>
                      <tr>
                        <td><?php echo $product['tanggal']; ?></td>
                        <td><?php echo $product['jatuhtempo']; ?></td>
                        <td><?php echo $product['tgl_bayar']; ?></td>
                        <td><?php echo $product['vendor']; ?></td>
                        <td><?php echo $product['no_faktur']; ?>

                        </td>
                        <td>

                          <?php echo $product['keterangan']; ?></td>
                        <td><?php echo $product['total']; ?></td>
                        <td><?php echo $product['totalbayar']; ?></td>
                        <!--td>
                          <?php echo $product['pajakdimuka']; ?>
                          <br>
                          <small><?php echo $product['ppn']; ?></small></td>
                        <td>
                          <?php echo $product['pajak']; ?>
                          <br>
                          <small>
                          <?php echo $product['nilaipajak']; ?></small></td>
                        <td><?php echo $product['total']; ?></td-->

                        <td><?php
                            if($product['status'] == 1){
                              echo 'Belum Dibayar';
                            }
                            if($product['status'] == 2){
                              echo 'Dibayar Sebagian';
                            }
                            if($product['status'] == 3){
                              echo 'Lunas';
                            }
                           ?></td>
                      <td class="right">
                        <a onclick=tampildetail(<?php echo $product['id']; ?>) class="badge bg-blue">Rincian Tagihan</a>
                        <?php foreach ($product['actions'] as $action) {

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
            <div class="row">
              <div class="col-md-12">
                <div class="pull-right"><?php echo empty($filter_date_bayarawal)?$pagination:''; ?></div>
              </div>
            </div>
            <div class="row" style="height:500px;overflow:scroll">
              <div class="col-xs-12">
                <div class="callout callout-success lead">
                  <h4>Rincian Tagihan</h4>

                </div>
                <?php if ($permintaans) { ?>
                <?php foreach ($permintaans as $p) { ?>
                <div class="rincian-tagihan" id="detail<?php echo $p['id']; ?>">
                <div class="row " >
                <div class="col-xs-12 col-md-6">
                <table class="table table-stripped">
                  <tr>
                    <td>Tanggal Tagihan</td>
                    <td><?php echo $p['tanggal']; ?></td>
                  </tr>
                  <tr>
                    <td>Tanggal Jatuh Tempo</td>
                    <td><?php echo $p['jatuhtempo']; ?></td>
                  </tr>
                  <tr>
                    <td>Nomor Faktur</td>
                    <td><?php echo $p['no_faktur']; ?></td>
                  </tr>
                  <tr>
                    <td>Keterangan</td>
                    <td><?php echo $p['keterangan']; ?></td>
                  </tr>

                </table>
              </div>
              <div class="col-xs-12 col-md-6">
                <table class="table table-stripped">

                <tr>
                    <td>Hutang Pajak</td>
                    <td><?php echo $p['pajak']; ?><br>
                      <?php
                      if($p['statuspajak'] == 1){
                        echo '(Dipotong dari tagihan)';
                      }
                      if($p['statuspajak'] == 2){
                        echo '(Tidak dipotong dari tagihan)';
                      }
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td>Pajak Dibayar Dimuka</td>
                    <td><?php echo $p['pajakdimuka']; ?><br>
                      <?php
                      if($p['statuspajakdimuka'] == 1){
                        echo '(Termasuk Total Tagihan)';
                      }
                      if($p['statuspajakdimuka'] == 2){
                        echo '(Belum Termasuk Total Tagihan)';
                      }
                      ?>
                    </td>
                  </tr>
                </table>
              </div>
              </div>
              <div class="row">
                <div class="col-xs-12">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Jumlah Tagihan</th>
                        <th>Hutang Pajak</th>
                        <th>Pajak Dibayar Dimuka</th>
                        <th>Total</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><?php echo $p['jumlah']; ?></td>
                        <td><?php echo $p['nilaipajak']; ?></td>
                        <td><?php echo $p['ppn']; ?></td>
                        <td><?php echo $p['total']; ?></td>
                      </tr>
                    </tbody>
                  </table>

                </div>
              </div>
              <div class="row">
                <div class="col-xs-12">
                  <div class="callout callout-warning lead">
                    <h4>Rincian Biaya</h4>

                  </div>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Nama Biaya</th>
                        <th>Total</th>

                      <tr>
                    </thead>
                    <tbody>
                      <?php
                      foreach($p['biayas'] as $pem){
                      ?>
                      <tr>
                        <td><?php echo $pem['name']; ?></td>
                        <td><?php echo $this->currency->format($pem['totalreal']); ?></td>
                      </tr>
                      <?php
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12">
                  <div class="callout callout-warning lead">
                    <h4>Rincian Pembayaran</h4>

                  </div>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Tanggal Bayar</th>
                        <th>No. Dokumen</th>
                        <th>Keterangan</th>
                        <th>Nominal</th>
                        <th></th>
                      <tr>
                    </thead>
                    <tbody>
                      <?php
                      foreach($p['pembayaran'] as $pem){
                      ?>
                      <tr>
                        <td><?php echo date('d/m/Y',strtotime($pem['tgl_bayar'])); ?></td>
                        <td><?php echo $pem['no_dokumen']; ?></td>
                        <td><?php echo $pem['keterangan']; ?></td>
                        <td><?php echo $this->currency->format($pem['nominal']); ?></td>
                        <td>
                          <?php 
                          if(!empty($pem['no_dokumen'])){
                          ?>
                          <a href="<?php echo $this->url->link('laporan/jurnalumum', 'token=' . $this->session->data['token'] .'&filter_nodokumen='.$pem['no_dokumen'], 'SSL'); ?>"  class="badge bg-blue" target="_blank">Lihat Jurnal</a>
                          <?php
                          }
                          ?>
                          <a href="<?php echo $pem['href']; ?>"  class="badge bg-yellow">Batalkan</a>
                        </td>
                      </tr>
                      <?php
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <div>
              </div>
            </div>
              <?php
            }}
              ?>
              </div>
            </div>
          </div>

          <div class="box-footer">

          </div>
        </div>
      </div>
    </div>
  </section>
</div>
</div>
<script>
$('.sidebar-menu').find('#menu-keuangan').addClass('active');
$('.sidebar-menu').find('#menu-pencatatan-tagihan').addClass('active');
</script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
  $(".rincian-tagihan").hide();

	$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
  $(".nosurat").select2({
    ajax: {
    url:"index.php?route=pembelian/tagihanbiayaimport/autocomplete&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        q: params.term,

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
function tampildetail(id){
  $(".rincian-tagihan").hide();
  $("#detail"+id).show();
}
function filter() {
	url = 'index.php?route=pembelian/tagihanbiayaimport&token=<?php echo $token; ?>';

	var filter_date_start = $('input[name=\'filter_date_start\']').val();

	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

  var filter_date_end = $('input[name=\'filter_date_end\']').val();

	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}

  var filter_date_bayarawal = $('input[name=\'filter_date_bayarawal\']').val();

	if (filter_date_bayarawal) {
		url += '&filter_date_bayarawal=' + encodeURIComponent(filter_date_bayarawal);
	}

  var filter_date_bayarakhir = $('input[name=\'filter_date_bayarakhir\']').val();

	if (filter_date_bayarakhir) {
		url += '&filter_date_bayarakhir=' + encodeURIComponent(filter_date_bayarakhir);
	}

  if(filter_date_bayarawal){
    if(filter_date_bayarakhir==''){
        alert("Tgl akhir bayar harus diisi");
        $('input[name=\'filter_date_bayarakhir\']').focus();
        return false;
    }
  }

  var filter_jenis = $('select[name=\'filter_jenis\']').val();

	if (filter_jenis) {
		url += '&filter_jenis=' + encodeURIComponent(filter_jenis);
	}

  var vendor_id = $('select[name=\'vendor_id\']').val();

	if (vendor_id) {
		url += '&vendor_id=' + encodeURIComponent(vendor_id);
	}


  var filter_no_faktur = $('select[name=\'filter_no_faktur\']').val();

	if (filter_no_faktur) {
		url += '&filter_no_faktur=' + encodeURIComponent(filter_no_faktur);
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
//--></script>
<?php echo $footer; ?>
