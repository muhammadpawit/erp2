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
            <h3 class="box-title">Perangkat Pengakses</h3>
            <div class="button pull-right">
                    <a onclick="$('#form').submit();" class="btn btn-info">Simpan</a>
                    <a onclick="location = '<?php echo $cancel; ?>';" class="btn btn-danger">Kembali</a>
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

                <?php if ($error_warning) { ?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-check"></i> Alert!</h4>
                  <?php echo $error_warning; ?>
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
                      <td>Permintaan Oleh</td>
                      <td><?php echo $user_id; ?></td>
                    </tr>
                    <tr>
                      <td>OS</td>
                      <td><?php echo $os; ?></td>
                    </tr>
                    <tr>
                      <td>Browser</td>
                      <td><?php echo $browser; ?></td>
                    </tr>
                    <tr>
                      <td>Tanggal Permintaan</td>
                      <td><?php echo $date_added; ?></td>
                    </tr>
                    <tr>
                      <td>Nama Device</td>
                      <td><input type="text" name="namadevice" class="form-control" value="<?php echo $namadevice; ?>" />
                        <?php if ($error_namadevice) { ?>
                        <span class="error"><?php echo $error_namadevice; ?></span>
                        <?php } ?>
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
$('.sidebar-menu').find('#menu-system').addClass('active');
$('.sidebar-menu').find('#menu-user').addClass('active');
$('.sidebar-menu').find('#menu-daftar-user').addClass('active');

//--></script>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script>

<?php echo $footer; ?>
