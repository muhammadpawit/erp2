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
            <h3 class="box-title">Menu</h3>
            <div class="button pull-right">
                    <a onclick="location = '<?php echo $insert; ?>'" class="btn btn-info">Tambah</a>
                    <a onclick="$('form').attr('action', '<?php echo $delete; ?>'); $('form').submit();" class="btn btn-danger">Hapus</a>

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
                          <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
                          <td class="left"><?php if ($sort == 'id.name') { ?>
                            <a href="<?php echo $sort_title; ?>" class="<?php echo strtolower($order); ?>">Nama Menu</a>
                            <?php } else { ?>
                            <a href="<?php echo $sort_title; ?>">Nama Menu</a>
                            <?php } ?></td>
                            <td class="left"><?php if ($sort == 'id.url') { ?>
                            <a href="<?php echo $sort_title; ?>" class="<?php echo strtolower($order); ?>">URL</a>
                            <?php } else { ?>
                            <a href="<?php echo $sort_title; ?>">URL</a>
                            <?php } ?></td>
                          <td class="right"><?php if ($sort == 'i.sort_order') { ?>
                            <a href="<?php echo $sort_sort_order; ?>" class="<?php echo strtolower($order); ?>">Grup</a>
                            <?php } else { ?>
                            <a href="<?php echo $sort_sort_order; ?>">Grup</a>
                            <?php } ?></td>
                          <td class="right">Urutan Tampil</td>
                          <td></td>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if ($informations) { ?>
                        <?php foreach ($informations as $information) { ?>
                        <tr>
                          <td style="text-align: center;"><?php if ($information['selected']) { ?>
                            <input type="checkbox" name="selected[]" value="<?php echo $information['menu_id']; ?>" checked="checked" />
                            <?php } else { ?>
                            <input type="checkbox" name="selected[]" value="<?php echo $information['menu_id']; ?>" />
                            <?php } ?></td>
                          <td class="left"><?php echo $information['nama']; ?></td>
                          <td class="left"><?php echo $information['url']; ?></td>
                          <td class="left"><?php echo $information['grouping']; ?></td>
                          <td class="right"><?php echo $information['sort_order']; ?></td>
                          <td class="right"><?php foreach ($information['action'] as $action) { ?>
                            <a href="<?php echo $action['href']; ?>" class="badge bg-yellow"><?php echo $action['text']; ?></a>
                            <?php } ?></td>
                        </tr>
                        <?php } ?>
                        <?php } else { ?>
                        <tr>
                          <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
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
$('.sidebar-menu').find('#menu-master-data').addClass('active');
$('.sidebar-menu').find('#menu-menu').addClass('active');

//--></script>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script>
<?php echo $footer; ?>
