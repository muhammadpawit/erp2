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
            <h3 class="box-title">Tambah Kode Premi</h3>
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
                      <td>Tabung 0 - 500</td>
                      <td><input type="text" name="kelompok" class="form-control" value="<?php echo $kelompok; ?>" />
                        </td>
                    </tr>
                    <tr>
                      <td>Tabung 501 - 1000</td>
                      <td><input type="text" name="kelompok2" class="form-control" value="<?php echo $kelompok2; ?>" />
                        </td>
                    </tr>
                    <tr>
                      <td>Tabung 1001 - 1500</td>
                      <td><input type="text" name="kelompok3" class="form-control" value="<?php echo $kelompok3; ?>" />
                        </td>
                    </tr>
                    <tr>
                      <td>Tabung > 1501</td>
                      <td><input type="text" name="kelompok4" class="form-control" value="<?php echo $kelompok4; ?>" />
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
$('.sidebar-menu').find('#menu-title').addClass('active');
</script>


<?php echo $footer; ?>
