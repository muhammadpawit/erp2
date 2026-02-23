<?php echo $header; ?>
<div class="content-wrapper" id="content">
  <section class="content-header">
    <h1>

    </h1>

  </section>

  <section class="content" >
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Produk Dagang</h3>
            <div class="button pull-right">
                <a onclick="$('#form').submit();" ><button type="button" class="btn btn-primary">Simpan</button></a>
									<a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>

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

              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                  <table class="table">
                    <tr>
                      <td><span class="required">*</span> Nama Produk</td>
                      <td><input class="form-control" type="text" name="name" size="100" value="<?php echo $name; ?>" />
                        <?php if (isset($error_name)) { ?>
                        <span class="error"><?php echo $error_name; ?></span>
                        <?php } ?></td>
                    </tr>
                    <tr >
                      <td>Barcode</td>
                      <td><input class="form-control" type="text" name="barcode" value="<?php echo $barcode; ?>" /></td>
                    </tr>
                    <tr>
                      <td>Quantity</td>
                      <td><?php echo $quantity; ?></td>
                    </tr>
                    <tr>
                      <td>Satuan</td>
                      <td>
                        <select class="form-control" name="satuan">
                            <?php
                            foreach($satuans as $s){
                            ?>
                              <option value="<?php echo $s['id']; ?>" <?php echo $satuan == $s['id']?'selected':''; ?>><?php echo $s['name']; ?></option>
                            <?php
                            }
                            ?>
                          </select>
                      </td>
                    </tr>
                    <tr>
                      <td>Status Tabung</td>
                      <td><select class="form-control" name="jenistabung">
                            <option value="0" <?php echo $jenistabung == 0?'selected':''; ?>>Tanpa Tabung</option>
                            <option value="1" <?php echo $jenistabung == 1?'selected':''; ?>>Milik Perusahaan</option>
                            <option value="2" <?php echo $jenistabung == 2?'selected':''; ?>>Milik Relasi</option>
                            <option value="3" <?php echo $jenistabung == 3?'selected':''; ?>>Milik Stok</option>

                        </select></td>
                    </tr>
                    <tr>
                      <td>Ukuran Tabung</td>
                      <td>
                          <select class="form-control" name="ukuran_tabung">
                            <option value="0" <?php echo $jenistabung == 0?'selected':''; ?>>Tanpa Tabung</option>
                          <?php
                          foreach($ukurans as $c){
                          ?>
                            <option value="<?php echo $c['product_options_id']; ?>" <?php echo $c['product_options_id']==$ukuran_tabung?'selected':''; ?>><?php echo $c['name']; ?></option>
                          <?php
                          }
                          ?>
                          </select>
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
$('.sidebar-menu').find('#menu-persediaan').addClass('active');
$('.sidebar-menu').find('#menu-persediaan-produkdagang').addClass('active');
$('.sidebar-menu').find('#menu-daftar-produk').addClass('active');
</script>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--
CKEDITOR.replace('description', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});

//--></script>
<script type="text/javascript"><!--
function image_upload(field, thumb) {
	$('#dialog').remove();

	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');

	$('#dialog').dialog({
		title: 'Image Manager',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).attr('value')),
					dataType: 'text',
					success: function(text) {
						$('#' + thumb).replaceWith('<img src="' + text + '" alt="" id="' + thumb + '" />');
					}
				});
			}
		},
		bgiframe: false,
		width: 800,
		height: 400,
		resizable: false,
		modal: false
	});
};
//--></script>
<?php echo $footer; ?>
