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
            <h3 class="box-title">History HPP</h3>
            <div class="button pull-right">
								<a href="<?php echo $cancel; ?>" ><button type="button" class="btn btn-danger">Kembali</button></a>
								</div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th class="left">Tanggal</th>
                        <th class="left">HPP</th>
          				      </tr>
                    </thead>
                    <tbody>

                      <?php if ($hpps) { ?>
                      <?php foreach ($hpps as $product) { ?>
                      <tr>
                        <td class="left"><?php echo $product['tanggal'];
                          ?></td>
                        <td class="left"><?php echo $product['net_cost']; ?></td>

                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="2">Data tidak ditemukan</td>
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
$('.sidebar-menu').find('#menu-persediaan').addClass('active');
$('.sidebar-menu').find('#menu-persediaan-produkdagang').addClass('active');
$('.sidebar-menu').find('#menu-daftar-persediaan').addClass('active');
$(function(){
  $(".select-ads").select2({


      theme:"bootstrap"
    });
})
</script>
<script>
$(function(){
  $('#tanggal').datepicker({dateFormat: 'yy-mm-dd'});
})
</script>
<?php echo $footer; ?>
