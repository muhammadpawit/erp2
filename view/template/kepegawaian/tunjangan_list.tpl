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
            <h3 class="box-title">Tunjangan Pegawai</h3>
            <div class="button pull-right">
              <a onclick="location = '<?php echo $insert; ?>'" class="btn btn-info">Insert</a>
              <a onclick="$('#form').submit();" class="btn btn-warning">Delete</a>
								<a class="btn btn-danger" onclick="location = '<?php echo $cancel; ?>'" class="button">Kembali</a>
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
                        <td class="left">Nama Tunjangan</td>
                        <td class="right">Nilai Tunjangan</td>
                        <td class="left">Satuan</td>


                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($tunjangans) { ?>
                      <?php foreach ($tunjangans as $product) { ?>
                      <tr>
                        <td style="text-align: center;">

          		<?php if ($product['selected']) { ?>
                          <input type="checkbox" name="selected[]" value="<?php echo $product['tunjangan_id']; ?>" checked="checked" />
                          <?php } else { ?>
                          <input type="checkbox" name="selected[]" value="<?php echo $product['tunjangan_id']; ?>" />
                          <?php } ?>
          		</td>

                        <td class="left"><?php echo $product['nama']; ?></td>
                        <td class="right"><?php echo $product['nilai']; ?></td>
                        <td class="left"><?php echo $product['satuan']; ?></td>

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
$('.sidebar-menu').find('#menu-spg-toko').addClass('active');

</script>
<?php
echo $footer;
?>
