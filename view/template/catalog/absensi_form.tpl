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
            <h3 class="box-title">Tambah Vendor</h3>
            <div class="button pull-right">
									<a onclick="$('#form').submit();" class="button"><button type="button" class="btn btn-primary">Simpan</button></a>
                  <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-warning">Kembali</button></a>
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
                      <td><span class="required">*</span> Nama Vendor</td>
                      <td><input class="form-control"  type="text" name="name" size="100" value="<?php echo $name; ?>" />
                        <?php if ($error_name) { ?>
                        <span class="help-block"><?php echo $error_name; ?></span>
                        <?php } ?></td>
                    </tr>
                    <tr>
                      <td>Alamat</td>
                      <td><input class="form-control"  type="text" name="alamat" size="100" value="<?php echo $alamat; ?>" />
                        </td>
                    </tr>
                    <tr>
                      <td>Email</td>
                      <td><input class="form-control"  type="text" name="email" size="100" value="<?php echo $email; ?>" />
                        </td>
                    </tr>
                    <tr>
                      <td>Telephone</td>
                      <td><input class="form-control"  type="text" name="telephone" size="100" value="<?php echo $telephone; ?>" />
                        </td>
                    </tr>
                    <tr>
                      <td>NPWP</td>
                      <td><input class="form-control"  type="text" name="npwp" size="100" value="<?php echo $npwp; ?>" />
                        </td>
                    </tr>
                    <tr>
                      <td>SIUP</td>
                      <td><input class="form-control"  type="text" name="siup" size="100" value="<?php echo $siup; ?>" />
                        </td>
                    </tr>
                    <tr>
                      <td>TDP</td>
                      <td><input class="form-control"  type="text" name="tdp" size="100" value="<?php echo $tdp; ?>" />
                        </td>
                    </tr>
                    <tr>
                      <td>HO</td>
                      <td><input class="form-control"  type="text" name="ho" size="100" value="<?php echo $ho; ?>" />
                        </td>
                    </tr>
                    <tr>
                      <td>SPPKP</td>
                      <td><input class="form-control"  type="text" name="sppkp" size="100" value="<?php echo $sppkp; ?>" />
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
$('.sidebar-menu').find('#menu-vendor').addClass('active');
$('.sidebar-menu').find('#menu-vendor-lokal').addClass('active');
</script>


<?php echo $footer; ?>
