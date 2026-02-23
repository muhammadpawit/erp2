<?php echo $header; ?>

<div class="content-wrapper" >
  <section class="content-header">
    <h1>

    </h1>

  </section>
  <section class="content" id="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Lock Tanggal</h3>
            <div class="button pull-right">
                    <a onclick="$('#form').submit();" class="btn btn-info">Simpan</a>

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
                  <div id="tab-general">
                    <table class="table table-bordered">
                      <tr>
                        <td style="width:400px"><span class="required">*</span> Lock Tanggal</td>
                        <td><input type="text" class="date form-control" name="config_locktanggal" value="<?php echo $config_locktanggal; ?>" size="40" />
                          </td>
                      </tr>
                     
                    </table>
                  </div>

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
$('.sidebar-menu').find('#menu-system').addClass('active');
$('.sidebar-menu').find('#menu-setting').addClass('active');

//--></script>
<script type="text/javascript">
$('.date').datepicker({dateFormat: 'yy-mm-dd'});
</script>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script>
<script type="text/javascript"><!--
$('#tabs a').tabs();
$('#timepicker1').timepicker();
//--></script>
<?php echo $footer; ?>
