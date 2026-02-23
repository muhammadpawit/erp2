<?php echo $header; ?>
<div id="content" class="content-wrapper">
  <section class="content-header">
    <h1>

    </h1>

  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Tambah Kelompok Aset</h3>
            <div class="button pull-right">
									<a onclick="$('#form').submit();" class="button"><button type="button" class="btn btn-primary">Simpan</button></a>
                  <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-warning">Kembali</button></a>
								</div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <?php if ($errors) { ?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                  <?php
                  foreach($errors as $e){
                    echo $e.'<br>';
                  }
                   ?>
                </div>
                <?php
                }
                ?>
                <?php if (isset($success)) { ?>
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
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                  <table class="table table-stripped">
                    <tr>
                      <td><span class="required">*</span> Nama Kelompok Aset</td>
                      <td><input type="text" class="form-control" name="name" size="100" value="<?php echo $name; ?>" />
                        </td>
                    </tr>
                    <tr>
                      <td><span class="required">*</span> Jenis Aset</td>
                      <td>
                        <select class="form-control" name="jenis_aset">
                          <option value="1" <?php echo $jenis_aset==1?'selected':''; ?>>Bukan Bangunan</option>
                          <option value="2" <?php echo $jenis_aset==2?'selected':''; ?>>Bangunan</option>
                        </select>
                        </td>
                    </tr>
                    <tr>
                      <td><span class="required">*</span> Masa Manfat (dalam tahun)</td>
                      <td><input type="text" class="form-control" name="masa_manfaat" size="100" value="<?php echo $masa_manfaat; ?>" />
                        </td>
                    </tr>
                    <tr>
                      <td><span class="required">*</span> Metode Depresiasi</td>
                      <td>
                        <select class="form-control" name="jenis_depresiasi">
                          <option value="1" <?php echo $jenis_aset==1?'selected':''; ?>>Garis Lurus</option>
                          <option value="2" <?php echo $jenis_aset==2?'selected':''; ?>>Saldo Menurun</option>
                        </select>
                        </td>
                    </tr>
                    <tr>
                      <td><span class="required">*</span> Tarif Depresiasi (dalam persen)</td>
                      <td><input type="text" class="form-control" name="nilai_depresiasi" size="100" value="<?php echo $nilai_depresiasi; ?>" />
                        </td>
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
$('.sidebar-menu').find('#menu-master-data').addClass('active');
$('.sidebar-menu').find('#menu-kelompok_aset').addClass('active');

</script>


<?php echo $footer; ?>
