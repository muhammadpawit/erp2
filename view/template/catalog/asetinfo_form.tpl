<?php echo $header; ?>
<div class="content-wrapper" id="content">
  <section class="content-header">
    <h1>

    </h1>

  </section>

  <section class="content" >
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Aset</h3>
            <div class="button pull-right">
                <a onclick="$('#form').submit();" ><button type="button" class="btn btn-primary">Simpan</button></a>
									<a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>

								</div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <?php if ($error_warning) { ?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                  <?php
                  foreach($error_warning as $e){
                    echo $e.' <br>';
                  }
                  ?>
                </div>
                <?php
                }
                ?>

              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                  <table class="table">
                    <tr>
                      <td><span class="required">*</span> Nama Aset</td>
                      <td><?php echo $name; ?>
                      </td>
                    </tr>
                    <tr >
                      <td>Tanggal Pembeliaan</td>
                      <td><input type="text" class="date form-control" name="tglpembelian" readonly value="<?php echo $tglpembelian; ?>" /></td>
                    </tr>
                    <tr >
                      <td>Harga Beli</td>
                      <td><input type="text" name="hargabeli" class="form-control" value="<?php echo $hargabeli; ?>" /></td>
                    </tr>
                    <tr >
                      <td>Tanggal Penyusutan</td>
                      <td><input type="text" class="date form-control" name="tglbuku" readonly value="<?php echo date('Y-m-d'); ?>" /></td>
                    </tr>

                    <tr >
                      <td>Nilai Buku</td>
                      <td><input type="text" name="nilaibuku" class="form-control" value="<?php echo $nilaibuku; ?>" /></td>
                    </tr>

                  </table>
                </form>
              </div>
            </div>

          </div>
          <div class="box-footer">
            <div class="row">
              <div class="col-md-12">

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
$('.sidebar-menu').find('#menu-persediaan-aset').addClass('active');
</script>
<script>
$(function(){
  $('.date').datepicker({dateFormat: 'yy-mm-dd'});
})
</script>
<?php echo $footer; ?>
