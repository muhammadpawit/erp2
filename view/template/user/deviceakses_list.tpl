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
                    <a onclick="$('form').attr('action', '<?php echo $delete; ?>'); $('form').submit();" class="btn btn-danger">Block Akses</a>

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
                        <th width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>
                        <th class="left">Tanggal Permintaan</th>
                        <th class="left">Nama Device</th>
                        <th class="left">Permintaan Oleh</th>
                        <th class="left">Sistem Operasi</th>
                        <th class="left">Browser</th>
                        <th class="left">Status</th>
                        <th class="right"><?php echo $column_action; ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if ($devices) { ?>
                      <?php foreach ($devices as $user) { ?>
                      <tr>
                        <td style="text-align: center;"><?php
                          if($user['token'] != $devicetoken & $user['plainstatus'] !=3){
                          if ($user['selected']) { ?>
                          <input type="checkbox" name="selected[]" value="<?php echo $user['id']; ?>" checked="checked" />
                          <?php } else { ?>
                          <input type="checkbox" name="selected[]" value="<?php echo $user['id']; ?>" />
                          <?php }} ?></td>
                        <td class="left"><?php echo $user['date_added']; ?></td>
                        <td class="left"><?php echo $user['namadevice']; ?></td>
                        <td class="left"><?php echo $user['user']; ?></td>
                        <td class="left"><?php echo $user['os']; ?></td>
                        <td class="left"><?php echo $user['browser']; ?></td>
                        <td class="left"><?php echo $user['status']; ?></td>
                        <td class="right"><?php foreach ($user['action'] as $action) { ?>
                          <a href="<?php echo $action['href']; ?>" class="badge bg-yellow"><?php echo $action['text']; ?></a>
                          <?php } ?></td>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="5"><?php echo $text_no_results; ?></td>
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
$('.sidebar-menu').find('#menu-setting').addClass('active');
$('.sidebar-menu').find('#menu-deviceakses').addClass('active');

//--></script>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script>
<?php echo $footer; ?>
