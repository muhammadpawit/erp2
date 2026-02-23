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
            <h3 class="box-title">Barang Datang Pembelian Produk Dagang</h3>
            <div class="button pull-right">
              <a href="<?php echo $exporttoexcel ?>" target="_blank" class="btn btn-danger">Export to Excel</a>
            </div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <div class="alert alert-warning alert-dismissible">
                  Quantity dan barang yang diterima harus sama dengan yang diinput. Jika berbeda mohon diinput ulang.
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
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <table class="table table-stripped">
                    <thead>
                      <tr>
                        <th colspan="2">Filter Tanggal</th>
                        <th>Nomor PO</th>
                        <th>Vendor</th>
                        <th>Status Penerimaan</th>

                        <th></th>

                      </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td><input type="text" class="form-control" placeholder="Tanggal Awal" name="filter_date_start" value="<?php echo $filter_date_start; ?>" id="date-start" size="12"  /></td>
                      <td><input type="text" class="form-control" placeholder="Tanggal Akhir" name="filter_date_end" value="<?php echo $filter_date_end; ?>" id="date-end" size="12"  /></td>

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
                              <option value="1" <?php echo $filter_status == '0'?'selected':''; ?>>Belum Diterima</option>
                              <option value="2" <?php echo $filter_status == 1?'selected':''; ?>>Sudah Diterima</option>

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
                        <th>No.dokumen</th>
                        <th>Tgl Dibuat</th>
                        <th>Tgl Datang</th>
                        <th>No. Surat Jalan</th>
                        <th>Nomor PO</th>
                        <th>Gudang</th>
                        <th>Vendor</th>

                        <th>Nama Barang</th>
                        <th>Harga</th>
                        <th>Quantity PO</th>
                        <th>Status Penerimaan</th>
                        <th></th>

                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($permintaans) { ?>
                      <?php foreach ($permintaans as $product) { ?>
                      <tr>
                        <td><?php echo $product['no_dokumen']; ?></td>
                        <td><?php echo $product['tanggal']; ?></td>
                        <td><?php echo $product['tgl_terima']; ?></td>
                        <td><!--<a href=""><?php echo $product['no_suratjalan']; ?></a>--><a onclick="detail('<?php echo $product['id'] ?>')" data-toggle="modal" data-target="#jurnal"><?php echo $product['no_suratjalan']; ?></a></td>
                        <td><?php echo $product['no_po']; ?></td>
                        <td><?php echo $product['nama']; ?></td>
                        <td><?php echo $product['vendor']; ?></td>
                        <td><?php echo $product['product_name']; ?></td>
                        <td><?php echo $product['harga']; ?></td>
                        <td><?php echo $product['quantity']; ?></td>
                        <td><?php echo $product['status']; ?></td>

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
$('.sidebar-menu').find('#menu-pembelian-lokal').addClass('active');
$('.sidebar-menu').find('#menu-pembelian-produkdagang-lokal').addClass('active');
function detail(id){
    $.ajax({
      url: 'index.php?route=pembelian/terimabarangdagang/jurnal&token=<?php echo $token; ?>&id=' +  encodeURIComponent(id),
      //dataType: 'json',
      success: function(json) {
        $('.modal-body').html(json);
      }
    });/**/
}
$(function(){
  $(".nosurat").select2({
    ajax: {
    url:"index.php?route=pembelian/pembeliankreditdagang/autocomplete&token=<?php echo $this->request->get['token']; ?>",
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
	url = 'index.php?route=pembelian/terimabarangdagang&token=<?php echo $token; ?>';
  var filter_date_start = $('input[name=\'filter_date_start\']').val();

	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

  var filter_date_end = $('input[name=\'filter_date_end\']').val();

	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}

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
