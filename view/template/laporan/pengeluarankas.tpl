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
            <h3 class="box-title">Jurnal Pengeluaran Kas</h3>
            <div class="button pull-right">
                  </div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <table class="table table-stripped">
                    <thead>
                      <tr>
                        <th>Filter Tanggal Awal</th>
                        <th>Filter Tanggal Akhir</th>
                        <th></th>

                      </tr>
                    </thead>
                    <tbody>
                    <tr>
                    <td><input type="text" class="form-control" id="date-start" name="filter_date_start" value="<?php echo $filter_date_start; ?>"></td>
                      <td><input type="text" class="form-control" id="date-end" name="filter_date_end" value="<?php echo $filter_date_end; ?>"></td>
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
                        <th>Keterangan</th>
                        <th>Ref</th>
                        <th colspan="7" style="text-align:center">Debet</th>
                        <th colspan="2" style="text-align:center">Kredit</th>

                      </tr>
                      <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>Pembelian</th>
                        <th>Hutang Dagang</th>
                        <th colspan="3" style="text-align:center">Serba Serbi</th>
                        <th>DP Pembelian</th>
                        <th>PPN Masukan</th>
                        <th>Kas</th>
                        <th>Diskon </th>

                      </tr>
                      <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>Jenis Akun</th>
                        <th>Ref</th>
                        <th>Jumlah</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>

                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($orders) { ?>
                      <?php foreach ($orders as $product) { ?>
                      <tr>
                        <td><?php echo $product['tanggal']; ?></td>
                        <td><?php echo $product['no_faktur']; ?></td>
                        <td><?php echo $product['keterangan']; ?></td>
                        <td>
                          <?php
                          if($product['type'] == 1){
                          ?>
                          <a target="_blank" href="<?php echo $this->url->link('pembelian/pembeliantunai/tampil', 'token=' . $this->session->data['token'].'&id='.$product['ref'], 'SSL'); ?>">
                          <?php
                          }
                          ?>
                          <?php
                          if($product['type'] == 3){
                          ?>
                          <a target="_blank" href="<?php echo $this->url->link('pembelian/pembayarandp', 'token=' . $this->session->data['token'].'&filter_no_po='.$product['ref'], 'SSL'); ?>">
                          <?php
                          }
                          ?>
                          <?php echo $product['ref']; ?></a>
                        </td>
                        <td><?php echo $this->currency->format($product['pembelian']); ?></td>
                        <td><?php echo $this->currency->format($product['hutangdagang']); ?></td>
                        <td><?php echo $product['serbaserbi']['akun']; ?></td>
                        <td><?php echo $product['serbaserbi']['ref']; ?></td>
                        <td><?php echo $this->currency->format($product['serbaserbi']['jumlah']); ?></td>
                          <td><?php echo $this->currency->format($product['dp']); ?></td>
                          <td><?php echo $this->currency->format($product['ppn']); ?></td>
                          <td><?php echo $this->currency->format($product['kas']); ?></td>
                        <td><?php echo $this->currency->format($product['diskon']); ?></td>

                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="11">Data tidak ditemukan</td>
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
	url = 'index.php?route=laporan/pengeluarankas&token=<?php echo $token; ?>';

	var filter_date_start = $('input[name=\'filter_date_start\']').val();

	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

  var filter_date_end = $('input[name=\'filter_date_end\']').val();

	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
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
