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
            <h3 class="box-title">Biaya Pembelian Import</h3>
            <div class="button pull-right">
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
              <div class="col-md-12">

                <table class="table table-stripped">
                    <thead>
                      <tr>
                        <th>Referensi Invoice</th>
                        <th>Vendor</th>
                        <th>No. Faktur</th>
                        <th>Status Pembayaran</th>

                        <th></th>

                      </tr>
                    </thead>
                    <tbody>
                    <tr>
                    <td>
                      <select name="filter_invoice" class="form-control nosurat">

                     </select>
                    </td>
                    <td>
                      <select style="width:200px" name="filter_vendor" class="vendor">
                        <option value="*">Semua Vendor</option>

                      </select>
                    </td>
                    <td>
                      <select name="filter_no_faktur" class="form-control nofaktur">

                     </select>
                    </td>
                    <td>
                          <select class="form-control" name="filter_status">
                            <option value="*" <?php echo $filter_status == '*'?'selected':''; ?>>Semua Status</option>
                            <option value="0" <?php echo $filter_status == 0?'selected':''; ?>>Belum Ada Tagihan</option>
                            <option value="1" <?php echo $filter_status == 1?'selected':''; ?>>Ditagih</option>
                            <option value="2" <?php echo $filter_status == 2?'selected':''; ?>>Dibayar Sebagian</option>
                            <option value="4" <?php echo $filter_status == 4?'selected':''; ?>>Lunas</option>
                            <option value="3" <?php echo $filter_status == 3?'selected':''; ?>>Dibatalkan</option>

                          </status>
                      </td>

                      <td ><a onclick="filter();" class="btn btn-info">Filter</a></td>
                    </tr>
                  </tbody>
                  </table>
              </div>
            </div>

            <div class="row" >
              <div class="col-md-12">
                <div class="callout callout-success lead">
                  <h4>Daftar Tagihan</h4>

                </div>
                <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Tanggal</th>
                        <th>Jatuh Tempo</th>
                        <th>Nomor Faktur</th>
                        <th>Invoice Pembelian</th>
                        <th>Nama Biaya</th>
                        <th>Estimasi Biaya</th>
                        <th>Total Tagihan</th>
                        <th>Status Pembayaran</th>
                        <th></th>

                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($permintaans) { ?>
                      <?php foreach ($permintaans as $product) { ?>
                      <tr>
                        <td><?php echo $product['tanggal']; ?></td>
                        <td><?php echo $product['jatuhtempo']; ?></td>
                        <td><?php echo $product['no_faktur']; ?></td>

                        <td><a href="<?php echo $product['hrefinv']; ?>" target="_blank"><?php echo $product['invoice']; ?></a></td>
                        <td><?php echo $product['namabiaya']; ?></td>
                        <td><?php echo $product['estimasibiaya']; ?></td>
                      <td><?php echo $product['totalreal']; ?></td>
                        <td><?php echo $product['statuspembayaran']; ?></td>
                        <td class="right">
                          <?php foreach ($product['actions'] as $action) {

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
              <div class="row">
                <div class="col-md-12">
                  <div class="pull-right"><?php echo $pagination; ?></div>
                </div>
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
$('.sidebar-menu').find('#menu-pembelian').addClass('active');
$('.sidebar-menu').find('#menu-pembelian-import').addClass('active');

$(function(){
  $(".rincian-tagihan").hide();
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
  $(".nosurat").select2({
    ajax: {
    url:"index.php?route=pembelian/invoicepembelianimport/autocomplete&token=<?php echo $this->request->get['token']; ?>",
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

$(".nofaktur").select2({
  ajax: {
  url:"index.php?route=pembelian/biayapembelianimport/autocomplete&token=<?php echo $this->request->get['token']; ?>",
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

</script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date-start').datepicker({dateFormat: 'yy-mm-dd'});

	$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<script type="text/javascript"><!--
function tampildetail(id){
  $(".rincian-tagihan").hide();
  $("#detail"+id).show();
}
function filter() {
	url = 'index.php?route=pembelian/biayapembelianimport&token=<?php echo $token; ?>';

	var filter_no_faktur = $('select[name=\'filter_no_faktur\']').val();

	if (filter_no_faktur != '*' & filter_no_faktur != null) {
		url += '&filter_no_faktur=' + encodeURIComponent(filter_no_faktur);
	}

  var filter_vendor = $('select[name=\'filter_vendor\']').val();

	if (filter_vendor != '*' & filter_vendor != null) {
		url += '&filter_vendor=' + encodeURIComponent(filter_vendor);
	}
  var filter_invoice = $('select[name=\'filter_invoice\']').val();

	if (filter_invoice != '*' & filter_invoice != null) {
		url += '&filter_invoice=' + encodeURIComponent(filter_invoice);
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
