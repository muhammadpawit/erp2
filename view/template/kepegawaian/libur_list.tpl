<?php
echo $header;
?>
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
            <h3 class="box-title">Hari Libur</h3>
            <div class="button pull-right">
									<a href="<?php echo $insert; ?>"><button type="button" class="btn btn-primary">Tambah</button></a>
                  <a onclick="$('#form').submit();" ><button type="button" class="btn btn-warning">Hapus</button></a>
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
                  <?php echo $error_warning; ?>
                </div>
                <?php
                }
                ?>
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
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
                        <td class="left">Keterangan</td>
                        <td class="left">Tanggal</td>


                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($liburs) { ?>
                      <?php foreach ($liburs as $product) { ?>
                      <tr>
                        <td style="text-align: center;">

          		<?php if ($product['selected']) { ?>
                          <input type="checkbox" name="selected[]" value="<?php echo $product['libur_id']; ?>" checked="checked" />
                          <?php } else { ?>
                          <input type="checkbox" name="selected[]" value="<?php echo $product['libur_id']; ?>" />
                          <?php } ?>
          		</td>

                        <td class="left"><?php echo $product['keterangan']; ?></td>
                        <td class="left"><?php echo date('d F Y',strtotime($product['tanggal'])); ?></td>


                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="4">No Results.</td>
                      </tr>
                      <?php } ?>
                    </tbody>
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
$('.sidebar-menu').find('#menu-kepegawaian').addClass('active');
$('.sidebar-menu').find('#menu-pengaturan-periode').addClass('active');
</script>
<?php
echo $footer;
?>
