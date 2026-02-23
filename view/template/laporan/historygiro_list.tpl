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
            <h3 class="box-title"><?php echo $halaman ?></h3>
            <div class="button pull-right">
                      <a onclick="location = '<?php echo $cancel; ?>';" class="btn btn-warning">Kembali</a>
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
                <form action="" method="post" enctype="multipart/form-data" id="form">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th class="left">Tanggal Terima Giro</th>
                        <th class="left">Tanggal Jatuh Tempo</th>
                        <th class="left">Tanggal Cair</th>
                        <th class="left">No.Giro</th>
                        <th class="left">Keterangan</th>
                        <th class="left">Nominal</th>

                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($addresses) { ?>
                      <?php foreach ($addresses as $customer) { ?>
                      <tr>
                        <td class="left"><?php echo $customer['tglterima_giro']; ?></td>
                        <td class="left"><?php echo $customer['tgl_jatuhtempo']; ?></td>
                        <td class="left"><?php echo $customer['tgl_cair']; ?></td>
                        <td class="left"><?php echo $customer['no_giro']; ?></td>
                        <td class="left"><?php echo $customer['keterangan']; ?></td>
                        <td class="left"><?php echo $customer['nominal']; ?></td>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="5">Tidak terdapat data </td>
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
                <div class="pull-right"><?php //echo $pagination; ?></div>
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
$('.sidebar-menu').find('#menu-laporan-deposit').addClass('active');

//--></script>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script>
<?php echo $footer; ?>
