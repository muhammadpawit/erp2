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
            <h3 class="box-title">Pembelian Kredit: Barang Datang
            </h3>
            <div class="button pull-right">
                	</div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <div >
                  <h4>
                  >> Untuk menginput penerimaan barang, pilih nomor PO kemudian klik "Tampil".<br>
                  >> Jika PO belum diterima/diterima sebagian akan muncul tombol "Tambah" <br>
                  >> Klik tombol "Tambah" </h4>
                </div>
                <?php if ($success) { ?>
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-check"></i> Success!</h4>
                  <?php echo $success; ?>
                </div>
                <?php
                }
                ?>

                <?php if ($warning) { ?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>

                  <?php echo $warning; ?>
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
                        <th>Nomor PO</th>
                        <th></th>

                      </tr>
                    </thead>
                    <tbody>
                    <tr>
                    <td>
                      <select name="filter_no_po" class="form-control nosurat">

                     </select></td>
                      <td ><a onclick="filter();" class="btn btn-info">Tampil</a></td>
                    </tr>
                  </tbody>
                  </table>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <h3>
                  <b>
                    <?php
                    if($tambah){
                    echo 'Daftar Penerimaan Barang PO Nomor '.$pembelian['no_po'];
                    }
                    ?>
                  </b>
                </h3>
                <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Tanggal</th>
                        <th>Nomor PO</th>
                        <th>Nomor Surat Jalan</th>
                        <th>Nomor Polisi</th>
                        <th>Penerima</th>
                        <th>Pengangkut</th>
                        <th>Total Quantity</th>
                        <!--th>Total</th-->
                        <th></th>

                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($permintaans) { ?>
                      <?php foreach ($permintaans as $product) { ?>
                      <tr>
                        <td><?php echo $product['tanggal']; ?></td>
                        <td><?php echo $product['no_po']; ?></td>
                        <td><?php echo $product['no_suratjalan']; ?></td>
                        <td><?php echo $product['no_pol']; ?></td>
                        <td><?php echo $product['penerima']; ?></td>
                        <td><?php echo $product['pengangkut']; ?></td>
                        <td><?php echo $product['totalquantity']; ?></td>
                        <!--td><?php echo $product['total']; ?></td-->
                        <td class="right"><?php foreach ($product['actions'] as $action) {

                          ?>

                           <a href="<?php echo $action['href']; ?>"  <?php echo $action['text']=='Cetak PO'?'target="_blank"':''; ?> class="badge bg-yellow"><?php echo $action['text']; ?></a>
                          <?php } ?></td>
                      </tr>


                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="9">Data tidak ditemukan</td>
                      </tr>
                      <?php } ?>
                      <?php
                      if($tambah){
                      ?>
                      <tr>
                        <td colspan="9"><a href="<?php echo $tambahurl; ?>" class="btn btn-primary">Terima Barang</a></td>
                      </tr>
                      <?php
                      }
                      ?>
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
$('.sidebar-menu').find('#menu-pembelian-kredit').addClass('active');

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
	url = 'index.php?route=pembelian/barangdatang&token=<?php echo $token; ?>';

	var filter_no_po = $('select[name=\'filter_no_po\']').val();

	if (filter_no_po) {
		url += '&filter_no_po=' + encodeURIComponent(filter_no_po);
	}


	location = url;
}
//--></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
  $(".nosurat").select2({
    ajax: {
    url:"index.php?route=pembelian/pembeliankredit/autocomplete&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        q: params.term,
        j: 2// search term

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
