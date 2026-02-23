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
            <h3 class="box-title">Pembelian Tunai</h3>
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
                        <th>Nomor Faktur</th>
                        <th>Jenis Barang</th>
                        <th>Vendor</th>
                        <th></th>

                      </tr>
                    </thead>
                    <tbody>
                    <tr>
                    <td><input type="text" class="form-control" name="filter_no_faktur" value="<?php echo $filter_no_faktur; ?>"></td>
                      <td>
                          <select class="form-control" name="filter_jenis_barang">
                            <option value="*" >Semua Jenis Barang</option>
                          <option value="1" <?php echo $filter_jenis_barang == 1?'selected':''; ?>>Bahan Baku</option>
                            <option value="2" <?php echo $filter_jenis_barang == 2?'selected':''; ?>>Produk Dagang</option>
                            <option value="3" <?php echo $filter_jenis_barang == 3?'selected':''; ?>>ATK</option>
                            <option value="4" <?php echo $filter_jenis_barang == 4?'selected':''; ?>>Aset</option>
                            <option value="5" <?php echo $filter_jenis_barang == 5?'selected':''; ?>>Tabung MP</option>
                          </status>
                      </td>
                      <td>
                        <select style="width:200px" name="filter_vendor" class="vendor">
                          <option value="*">Semua Vendor</option>

                        </select>
                      </td>
                      <td>
                          <select class="form-control" name="filter_status">
                            <option value="*" >Semua Status</option>
                            <option value="3" <?php echo $filter_status == 3?'selected':''; ?>>Ditolak/Dibatalkan</option>
                            <option value="1" <?php echo $filter_status == 1?'selected':''; ?>>Disimpan</option>
                            <option value="2" <?php echo $filter_status == 2?'selected':''; ?>>Disetujui</option>

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
                        <th>Nomor Faktur</th>
                        <th>Vendor</th>
                        <th>Jenis Barang</th>
                        <th>Sub Total</th>
                        <th>Diskon</th>
                        <th>Pajak</th>
                        <th>Total</th>
                        <th></th>

                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($permintaans) { ?>
                      <?php foreach ($permintaans as $product) { ?>
                      <tr>
                        <td><?php echo $product['tanggal']; ?></td>
                        <td><?php echo $product['no_faktur']; ?><br><small>No. Nota: <?php echo $product['no_nota']; ?></small></td>
                        <td><?php echo $product['name']; ?></td>

                        <td>
                          <?php
                            if($product['jenis_barang'] == 1){
                              echo 'Bahan Baku';
                            }
                            if($product['jenis_barang'] == 2){
                              echo 'Produk Dagang';
                            }
                            if($product['jenis_barang'] == 3){
                              echo 'ATK';
                            }
                            if($product['jenis_barang'] == 4){
                              echo 'Aset';
                            }
                            if($product['jenis_barang'] == 5){
                              echo 'Tabung MP';
                            }

                          ?><br>
                          <small>No. SPPb: <a href="<?php echo $this->url->link('pembelian/permintaanpembelian/tampil', 'token=' . $this->session->data['token'] . '&id=' . $product['surat_id'], 'SSL'); ?>" target="_blank"><?php echo $product['no_surat']; ?></a></small>
                        </td>
                        <td><?php echo $product['sub_total']; ?></td>
                        <td><?php echo $product['diskon']; ?></td>
                        <td><?php echo $product['pajak']; ?></td>
                        <td><?php echo $product['total_pembelian']; ?></td>

                        <td class="right"><?php foreach ($product['actions'] as $action) {

                          ?>

                           <a href="<?php echo $action['href']; ?>"  <?php echo $action['text']=='Cetak'?'target="_blank"':''; ?> class="badge bg-yellow"><?php echo $action['text']; ?></a>
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
$('.sidebar-menu').find('#menu-pembelian').addClass('active');
$('.sidebar-menu').find('#menu-pembelian-produk').addClass('active');

$(function(){
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

});

</script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date-start').datepicker({dateFormat: 'yy-mm-dd'});

	$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=pembelian/pembeliantunai&token=<?php echo $token; ?>';

	var filter_no_faktur = $('input[name=\'filter_no_faktur\']').val();

	if (filter_no_faktur) {
		url += '&filter_no_faktur=' + encodeURIComponent(filter_no_faktur);
	}

	var filter_vendor = $('select[name=\'filter_vendor\']').val();

	if (filter_vendor != '*' & filter_vendor != null) {
		url += '&filter_vendor=' + encodeURIComponent(filter_vendor);
	}


  var filter_jenis_barang = $('select[name=\'filter_jenis_barang\']').val();

	if (filter_jenis_barang != '*') {
		url += '&filter_jenis_barang=' + encodeURIComponent(filter_jenis_barang);
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
