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
            <h3 class="box-title">History Follow up Penagihan</h3>
            <div class="button pull-right">
              <a href="<?php echo $cancel?>" class="btn btn-danger">Kembali</a>
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
                <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Tanggal</th>
                        <th>Customer</th>
                        <th>Media Follow Up</th>
                        <th>Hasil Pembicaraan</th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php if (isset($permintaans)) { ?>
                      <?php foreach ($permintaans as $product) { ?>
                      <tr>
                        <td><?php echo $product['tanggal']; ?></td>
                        <td><?php echo $product['namacustomer']; ?></td>
                        <td><?php
                          $color='';
                          if($product['media'] == 1){
                            echo '<i class="fa fa-whatsapp"></i>&nbsp;Whatsapp';
                            $color='blue';
                          }
                          if($product['media'] == 2){
                            echo '<i class="fa fa-phone"></i>&nbsp;Telephone';
                            $color='gray';
                          }
                          if($product['media'] == 3){
                            echo '<i class="fa fa-envelope"></i>&nbsp;E-mail';
                            $color='green';
                          }
                          if($product['media'] == 4){
                            echo '<i class="fa fa-user"></i>&nbsp;Sales';
                            $color='yellow';
                          }
                          if($product['media'] == 5){
                            echo '<i class="fa fa-envelope"></i>&nbsp;Surat';
                            $color='purple';
                          }
                          if($product['media'] == 6){
                            echo '<i class="fa fa-user"></i>&nbsp;Kurir';
                            $color='purple';
                          }
                          ?></td>
                          <td><div class="alert bg-<?php echo $color ?>"><?php echo $product['hasil_pembicaraan']; ?></div></td>
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
$('.sidebar-menu').find('#menu-laporan').addClass('active');
$('.sidebar-menu').find('#menu-laporan-followup-penagihan').addClass('active');
//$('.sidebar-menu').find('#penerimaan-dana-hutang-lain').addClass('active');
</script>
<script type="text/javascript">
$(document).ready(function() {
  $('.date').datepicker({dateFormat: 'yy-mm-dd'});
  $('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
});
</script>
<script type="text/javascript">
function filter() {
  url = 'index.php?route=laporan/followup&token=<?php echo $token; ?>';

  var filter_date_start = $('input[name=\'filter_date_start\']').val();

  if (filter_date_start) {
    url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
  }

  var filter_date_end = $('input[name=\'filter_date_end\']').val();

  if (filter_date_end) {
    url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
  }

  var filter_customer_id = $('select[name=\'filter_customer_id\']').val();

  if (filter_customer_id) {
    url += '&filter_customer_id=' + encodeURIComponent(filter_customer_id);
  }

  var filter_jenis = $('select[name=\'filter_jenis\']').val();

  if (filter_jenis!='*') {
    url += '&filter_jenis=' + encodeURIComponent(filter_jenis);
  }


  location = url;
}
//--></script>
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
  $(".bank").select2({
    ajax: {
    url:"index.php?route=keuangan/bank/autocomplete&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        q: params.term,
        c:1

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
