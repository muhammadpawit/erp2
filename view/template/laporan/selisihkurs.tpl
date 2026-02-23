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
            <h3 class="box-title">Laporan Selisih Kurs</h3>
              <div class="button pull-right">
                  <a href="<?php echo $excel?>" target="_blank" class="btn btn-success">Export to Excel</a>
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
                        <th colspan="2">Filter Tanggal</th>
                        <th colspan="2">Nomor Faktur</th>
                      </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td><input type="text" class="form-control" placeholder="Tanggal Awal" name="filter_date_start" value="<?php echo $filter_date_start; ?>" id="date-start" size="12"  /></td>
                      <td><input type="text" class="form-control" placeholder="Tanggal Akhir" name="filter_date_end" value="<?php echo $filter_date_end; ?>" id="date-end" size="12"  /></td>
                      <td>
                        <select name="filter_no_faktur" class="form-control nosurat">

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
                        <!--<th>Vendor</th>-->
                        <th>Status Pembayaran</th>
                        <th>Status Penerimaan</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <!--
                      <td>
                        <select style="width:400px" name="filter_vendor" class="vendor">
                          <option value="*">Semua Vendor</option>
                        </select>
                      </td>-->
                      <td>
                          <select class="form-control" name="filter_status">
                            <option value="*" >Semua Status</option>
                            <option value="1" <?php echo $filter_jenis_barang == 1?'selected':''; ?>>Ditagih</option>
                            <option value="2" <?php echo $filter_jenis_barang == 2?'selected':''; ?>>Dibayar Sebagian</option>
                            <option value="4" <?php echo $filter_jenis_barang == 4?'selected':''; ?>>Lunas</option>
                            <!--<option value="3" <?php echo $filter_jenis_barang == 3?'selected':''; ?>>Dibatalkan</option>-->
                          </select>
                      </td>
                      <td>
                          <select class="form-control" name="filter_status_penerimaan">
                            <option value="*" >Semua Status</option>
                            <option value="0" <?php echo $filter_jenis_barang == 1?'selected':''; ?>>Belum Diterima</option>
                            <option value="1" <?php echo $filter_jenis_barang == 1?'selected':''; ?>>Sudah Diterima</option>
                            <option value="2" <?php echo $filter_jenis_barang == 3?'selected':''; ?>>Diterima Sebagian</option>

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
                        <!--<th>Jatuh Tempo</th>-->
                        <th>Nomor Faktur</th>
                        <th>Gudang</th>
                        <!--<th>Vendor</th>-->
                        <th>Total Tagihan</th>
                        <th>Total Bayar</th>
                        <th>Kurs PIB</th>
                        <th>Total PIB</th>
                        <th>Selisih Kurs</th>
                        
                        <th>Status Pembayaran</th>
                        <!--<th>Status Penerimaan</th>-->
                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($permintaans) { ?>
                      <?php foreach ($permintaans as $product) { ?>
                      <tr>
                        <td><?php echo $product['tanggal']; ?></td>
                        <!--<td><?php echo $product['jatuhtempo']; ?></td>-->
                        <td><?php echo $product['no_faktur']; ?></td>
                        <td><?php echo $product['gudang']; ?></td>
                        <!--<td><?php echo $product['name']; ?></td>-->
                        <td><?php echo $product['total']; ?></td>
                        <td><?php echo $product['totalbayar']; ?></td>
                        <td><?php echo $product['kurspib'] ?></td>
                        <td><?php echo $product['totalpib']?></td>
                        <td><?php echo $product['selisihkurs']?></td>
                        
                        <td><?php echo $product['status']; ?></td>
                        <!--<td><?php echo $product['statuspenerimaan']; ?></td>-->
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="9">Data tidak ditemukan</td>
                      </tr>
                      <?php } ?>
                      <tr>
                        <td colspan="3"><b>Total</b></td>
                        <td><b><?php echo $alltagihan ?></b></td>
                        <td><b><?php echo $allbayar ?></b></td>
                        <td><b><?php echo $allkurs ?></b></td>
                        <td><b><?php echo $allpib ?></b></td>
                        <td><b><?php echo $allselisih ?></b></td>
                      </tr>
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
$('.sidebar-menu').find('#menu-pembelian-import').addClass('active');

$(function(){
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
$(".suratpermintaan").select2({
  ajax: {
  url:"index.php?route=pembelian/permintaanpembelian/autocomplete&token=<?php echo $this->request->get['token']; ?>",
  dataType: 'json',
  data: function (params) {
    return {
      q: params.term,
      j: 3,
      status:5,
      s:1// search term

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
    url:"index.php?route=catalog/vendorimport/autocomplete&token=<?php echo $this->request->get['token']; ?>",
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
	url = 'index.php?route=laporan/selisihkurs&token=<?php echo $token; ?>';

  var filter_date_start = $('input[name=\'filter_date_start\']').val();

	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

  var filter_date_end = $('input[name=\'filter_date_end\']').val();

	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	var filter_no_faktur = $('select[name=\'filter_no_faktur\']').val();

	if (filter_no_faktur != '*' & filter_no_faktur != null) {
		url += '&filter_no_faktur=' + encodeURIComponent(filter_no_faktur);
	}
  var filter_vendor = $('select[name=\'filter_vendor\']').val();

	if (filter_vendor != '*' & filter_vendor != null) {
		url += '&filter_vendor=' + encodeURIComponent(filter_vendor);
	}


  var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status != '*' & filter_status != null) {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

  var filter_status_penerimaan = $('select[name=\'filter_status_penerimaan\']').val();

	if (filter_status_penerimaan != '*' & filter_status_penerimaan != null) {
		url += '&filter_status_penerimaan=' + encodeURIComponent(filter_status_penerimaan);
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
