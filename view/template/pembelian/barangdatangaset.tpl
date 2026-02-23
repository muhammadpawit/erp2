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
            <h3 class="box-title">Barang Datang Pembelian Aset</h3>
            <div class="button pull-right">

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
                        <th>Nomor PO</th>
                        <th>Vendor</th>
                        <th>Status Penerimaan</th>

                        <th></th>

                      </tr>
                    </thead>
                    <tbody>
                    <tr>
                    <td>
                      <select name="filter_no_po" class="form-control nosurat">

                     </select>
                    </td>
                      <td>
                        <select style="width:200px" name="filter_vendor" class="vendor">
                          <option value="*">Semua Vendor</option>

                        </select>
                      </td>
                      <td>
                            <select class="form-control" name="filter_status">
                              <option value="*" >Semua Status</option>
                              <option value="0" <?php echo $filter_status == 0?'selected':''; ?>>Belum Diterima</option>
                              <option value="1" <?php echo $filter_status == 1?'selected':''; ?>>Sudah Diterima</option>
                              <option value="2" <?php echo $filter_status == 2?'selected':''; ?>>Diterima Sebagian</option>
                              <option value="5" <?php echo $filter_status == 5?'selected':''; ?>>Sudah Diterima(PO Ditutup)</option>

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
                        <th>Nomor PO</th>
                        <th>Metode Pembayaran</th>
                        <th>Vendor</th>
                        <th>Status Penerimaan</th>
                        <th>Nama Barang</th>
                        <th>Quantity PO</th>
                        <th>Quantity Terima</th>
                        <th></th>

                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($permintaans) { ?>
                      <?php foreach ($permintaans as $product) { ?>
                      <tr>
                        <td><?php echo $product['tanggal']; ?></td>
                        <td><?php echo $product['no_po']; ?></td>
                        <td><?php echo $product['metode_pembayaran']; ?></td>
                        <td><?php echo $product['name']; ?></td>

                          <td><?php echo $product['status']; ?></td>
                        <td><?php echo $product['product_name']; ?></td>
                        <td><?php echo $product['quantity']; ?></td>
                        <td><?php echo $product['quantityterima']; ?></td>

                        <td class="right"><?php foreach ($product['actions'] as $action) {

                          ?>

                           <a href="<?php echo $action['href']; ?>"  <?php echo $action['text']=='Cetak PO'?'target="_blank"':''; ?> class="badge bg-yellow"><?php echo $action['text']; ?></a><br>
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
$('.sidebar-menu').find('#menu-pembelian-kredit').addClass('active');

$(function(){
  $(".nosurat").select2({
    ajax: {
    url:"index.php?route=pembelian/pembeliankredit/autocomplete&token=<?php echo $this->request->get['token']; ?>",
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
	url = 'index.php?route=pembelian/barangdatangaset&token=<?php echo $token; ?>';

	var filter_no_po = $('select[name=\'filter_no_po\']').val();

	if (filter_no_po != '*' & filter_no_po != null) {
		url += '&filter_no_po=' + encodeURIComponent(filter_no_po);
	}

  var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

	var filter_vendor = $('select[name=\'filter_vendor\']').val();

	if (filter_vendor != '*' & filter_vendor != null) {
		url += '&filter_vendor=' + encodeURIComponent(filter_vendor);
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
