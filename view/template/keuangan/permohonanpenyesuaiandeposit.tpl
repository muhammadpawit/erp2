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
            <h3 class="box-title">Permohonan Penyesuaian Deposit </h3>
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
                        <th>Tanggal</th>
                        <th>Nomor Surat</th>
                        

                      </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td><input type="text" class="form-control date" name="filter_tanggal" value=""></td>
                      <td><input type="text" class="form-control" name="filter_no_surat" value="<?php echo $filter_no_surat; ?>"></td>
                      
                    </tr>
                  </tbody>
                  </table>
                  <table class="table table-stripped">
                    <thead>
                      <tr>
                        <th>Customer</th>
                        <th>Status</th>
                        <th></th>

                      </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td >
                        <select style="width:100%;" name="filter_customer_id" class="form-control lokasi-pameran">

                        </select>
                      </td>
                      <td>
                          <select class="form-control" name="filter_status">
                            <option value="*" >Semua Status</option>
                            <option value="3" <?php echo $filter_status == 3?'selected':''; ?>>Ditolak/Dibatalkan</option>
                            <option value="1" <?php echo $filter_status == 1?'selected':''; ?>>Menunggu Persetujuan</option>
                            <option value="2" <?php echo $filter_status == 2?'selected':''; ?>>Selesai Diproses</option>

                          </status>
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
                        <th>Tanggal</th>
                        <th>Tanggal Diproses</th>
                        <th>Nomor Surat</th>
                        <th>Nama Customer</th>
                        <th>Keterangan</th>
                        <th>Tersimpan</th>
                        <th>Tersedia</th>
                         <th>Selisih</th>
                        <th>Status</th>
                        <th></th>

                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($permintaans) { ?>
                      <?php foreach ($permintaans as $product) { ?>
                      <tr>
                        <td><?php echo $product['tanggal']; ?></td>
                        <td><?php echo $product['tgl_diproses']; ?></td>
                        <td><?php echo $product['no_surat']; ?></td>
                        <td><?php echo $product['name']; ?></td>
                        <td><?php echo $product['keterangan']; ?></td>
                        <td><?php echo $product['nominal_tersimpan']; ?></td>
                        <td><?php echo $product['nominal_tersedia']; ?></td>
                        <td><?php echo $product['selisih']; ?></td>
                        <td>
                          <?php
                            if($product['status'] == 3){
                              echo 'Ditolak/Dibatalkan';
                            }
                            if($product['status'] == 1){
                              echo 'Disimpan';
                            }
                            if($product['status'] == 2){
                              echo 'Selesai Diproses';
                            }


                          ?>
                        </td>
                        <td class="right"><?php foreach ($product['actions'] as $action) {

                          ?>

                           <a href="<?php echo $action['href']; ?>" <?php echo $action['text'] == 'Lihat Jurnal'?'target="_blank"':'';?> <?php echo $action['text']=='Cetak'?'target="_blank"':''; ?> class="badge bg-yellow"><?php echo $action['text']; ?></a>
                          <?php } ?></td>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="9">Data tidak ditemukan</td>
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
/*$('.sidebar-menu').find('#menu-persediaan').addClass('active');
$('.sidebar-menu').find('#menu-persediaan-produkdagang').addClass('active');
$('.sidebar-menu').find('#menu-stok-opname').addClass('active');
*/


</script>
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
  
});
//--></script>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=keuangan/permohonanpenyesuaiandeposit&token=<?php echo $token; ?>';

  var filter_tanggal = $('input[name=\'filter_tanggal\']').val();

	if (filter_tanggal) {
		url += '&filter_tanggal=' + encodeURIComponent(filter_tanggal);
	}

  var filter_no_surat = $('input[name=\'filter_no_surat\']').val();

	if (filter_no_surat) {
		url += '&filter_no_surat=' + encodeURIComponent(filter_no_surat);
	}

	var filter_customer_id = $('select[name=\'filter_customer_id\']').val();

	if (filter_customer_id !=null) {
		url += '&filter_customer_id=' + encodeURIComponent(filter_customer_id);
	}
  var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
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
