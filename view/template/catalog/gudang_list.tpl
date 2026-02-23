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
            <h3 class="box-title">Gudang</h3>
            <div class="button pull-right">
									<a href="<?php echo $insert; ?>"><button type="button" class="btn btn-primary">Tambah</button></a>
                  <a onclick="$('#form').submit();" ><button type="button" class="btn btn-danger">Hapus</button></a>
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
                        <th width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>
                        <th class="left">Nama Gudang</th>
                        <th class="left">Suplier</th>
                        <th class="left">Printer</th>
                        <th class="right"></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if ($gudangs) { ?>
                      <?php foreach ($gudangs as $gudang) { ?>
                      <tr>
                        <td style="text-align: center;"><?php if ($gudang['selected']) { ?>
                          <input type="checkbox" name="selected[]" value="<?php echo $gudang['gudang_id']; ?>" checked="checked" />
                          <?php } else { ?>
                          <input type="checkbox" name="selected[]" value="<?php echo $gudang['gudang_id']; ?>" />
                          <?php } ?></td>
                        <td class="left"><?php echo $gudang['name']; ?></td>
                        <td class="left">
                          <?php if($gudang['supplier']==1){ ?>
                            <i class="fa fa-check"></i>
                          <?php } ?>
                        </td>
                        <td class="left"><?php echo $gudang['printer']; ?></td>
                        <td class="right"><?php foreach ($gudang['action'] as $action) { ?>
                           <a href="<?php echo $action['href']; ?>" class="badge bg-blue"><?php echo $action['text']; ?></a>
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
$('.sidebar-menu').find('#menu-gudang').addClass('active');
</script>
<?php
echo $footer;
?>
