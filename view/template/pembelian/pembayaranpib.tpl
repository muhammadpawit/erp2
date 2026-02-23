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
            <h3 class="box-title">Pembayaran PIB</h3>
            <div class="button pull-right">
                  <a href="<?php echo $insert; ?>"><button type="button" class="btn btn-primary">Input Pembayaran</button></a>
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
                        <th>Status Pembayaran PIB</th>

                        <th></th>

                      </tr>
                    </thead>
                    <tbody>
                    <tr>
                    <td>
                      <select name="filter_no_faktur" class="form-control nosurat">

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
                            <option value="1" <?php echo $filter_status == 1?'selected':''; ?>>Lunas</option>
                            <option value="2" <?php echo $filter_status == 2?'selected':''; ?>>Dibayar Sebagian</option>
                            <option value="0" <?php echo $filter_status == 0?'selected':''; ?>>Belum Dibayar</option>

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
                        <th>Gudang</th>
                        <th>Vendor</th>
                        <th>No. PIB</th>
                        <th>PPn</th>
                        <th>PPh</th>
                        <th>BM</th>
                        <th>Total</th>
                        <th>Status Pembayaran</th>

                        <th></th>

                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($permintaans) { ?>
                      <?php foreach ($permintaans as $product) { ?>
                      <tr>
                        <td><?php echo $product['tanggal']; ?></td>
                        <td><?php echo $product['no_faktur']; ?></td>

                        <td><?php echo $product['gudang']; ?></td>
                        <td><?php echo $product['name']; ?></td>


                        <td><?php echo $product['no_pib']; ?></td>
                          <td><?php echo $product['ppnpib']; ?></td>
                        <td><?php echo $product['pphpib']; ?></td>
                        <td><?php echo $product['bmpib']; ?></td>
                        <td><?php echo $product['total']; ?></td>
                        <td><?php echo $product['statuspembayaranpib']; ?></td>
                        <td class="right">
                          <a onclick=tampildetail(<?php echo $product['id']; ?>) class="badge bg-blue">Rincian Pembayaran</a>
                        </td>
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
            <div class="row">
              <div class="col-md-12">
                <div class="pull-right"><?php echo $pagination; ?></div>
              </div>
            </div>
            <div class="row" style="height:500px;overflow:scroll">
              <div class="col-xs-12">
                <div class="callout callout-success lead">
                  <h4>Rincian Pembayaran</h4>

                </div>
                <?php if ($permintaans) { ?>
                <?php foreach ($permintaans as $p) { ?>
                <div class="rincian-tagihan" id="detail<?php echo $p['id']; ?>">

              <div class="row">
                <div class="col-xs-12">

                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Tanggal</th>
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
                        <td><?php echo date('d/m/y',strtotime($pem['tgl_bayar'])); ?></td>
                        <td><?php echo $pem['keterangan']; ?></td>
                        <td><?php echo $this->currency->format($pem['nominal']); ?></td>
                        <td>
                          <?php
                          //if($p['statuspembayar'] == 2 | $p['status'] == 3){
                          if($pem['status'] == 1){
                          ?>
                          <a href="<?php echo $this->url->link('pembelian/pembayaranpib/batalkan', 'token=' . $this->session->data['token'] . '&id=' . $pem['pembayaran_id'].$url, 'SSL'); ?>"   class="badge bg-yellow">Batalkan Pembayaran</a>
                          <?php
                        }else{
                          echo 'Dibatalkan';
                        }
                      /*}else{
                        if($pem['status'] == 2){
                          echo 'Dibatalkan';
                        }
                      }*/
                          ?>
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
$('.sidebar-menu').find('#menu-pembelian').addClass('active');
$('.sidebar-menu').find('#menu-pembelian-import').addClass('active');
function tampildetail(id){
  $(".rincian-tagihan").hide();
  $("#detail"+id).show();
}
$(function(){
  $(".rincian-tagihan").hide();
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
	url = 'index.php?route=pembelian/pembayaranpib&token=<?php echo $token; ?>';

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
