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
            <h3 class="box-title">Jenis Biaya</h3>
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
                        <td width="1" style="text-align: center;">
                          <input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" />
                        </td>
                        <th class="left">Nama Biaya</th>
                        <th class="right"></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td></td>

                        <td><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" /></td>
                        <td align="right"><a onclick="filter();" class="btn btn-info">Filter</a></td>
                      </tr>
                      <?php if ($options) { ?>
                      <?php foreach ($options as $category) { ?>
                      <tr>
                        <td style="text-align: center;">
                          <?php
                          //if(empty($category['cek'])){
                          ?>
                          <?php if ($category['selected']) { ?>
                          <input type="checkbox" name="selected[]" value="<?php echo $category['id']; ?>" checked="checked" />
                          <?php } else { ?>
                          <input type="checkbox" name="selected[]" value="<?php echo $category['id']; ?>" />
                          <?php }
                        //}
                        ?></td>
                        <td class="left"><?php echo $category['name']; ?></td>
                      <td class="right"><?php foreach ($category['action'] as $action) { ?>
                          <a href="<?php echo $action['href']; ?>" class="badge bg-blue"><?php echo $action['text']; ?></a>
                          <?php } ?></td>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="4">Data area tidak ditemukan</td>
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
$('.sidebar-menu').find('#menu-jenisbiaya').addClass('active');
</script>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=catalog/jenisbiaya&token=<?php echo $token; ?>';

	var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}



	location = url;
}
//--></script>
<?php echo $footer; ?>
