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
            <h3 class="box-title">Dokumen Customer</h3>
            <div class="button pull-right">
                <a onclick="$('#form').submit();" ><button type="button" class="btn btn-primary">Simpan</button></a>
									<a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>

								</div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                  <table id="images" class="table">
                    <thead>
                      <tr>
                        <td class="right">Nama Dokumen</td>
                        <td class="left">File Dokumen</td>

                        <td></td>
                      </tr>
                    </thead>
                    <?php $image_row = 0; ?>
                    <?php foreach ($product_images as $product_image) { ?>
                    <tbody id="image-row<?php echo $image_row; ?>">
                      <tr>
                        <td class="right"><input type="text" name="product_image[<?php echo $image_row; ?>][name]" value="<?php echo $product_image['name']; ?>" class="form-control" /></td>

                        <td class="left"><div class="image"><img onclick="tampilimage('<?php echo $product_image['imageorigin']; ?>')" src="<?php echo $product_image['thumb']; ?>" alt="" id="thumb<?php echo $image_row; ?>" />
                            <input type="hidden" name="product_image[<?php echo $image_row; ?>][image]" value="<?php echo $product_image['image']; ?>" id="image<?php echo $image_row; ?>" />
                            <br />
                            <a onclick="image_upload('image<?php echo $image_row; ?>', 'thumb<?php echo $image_row; ?>');">Cari</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb<?php echo $image_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php echo $image_row; ?>').attr('value', '');">Bersihkan</a></div></td>
                        <td class="left"><a onclick="hapusimage(<?php echo $product_image['product_image_id']; ?>)" class="btn btn-warning">Hapus</a></td>
                      </tr>
                    </tbody>
                    <?php $image_row++; ?>
                    <?php } ?>
                    <tfoot>
                      <tr>
                        <td colspan="2"></td>
                        <td class="left"><a onclick="addImage();" class="btn btn-success">Tambah Gambar</a></td>
                      </tr>
                    </tfoot>
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
$('.sidebar-menu').find('#menu-customer').addClass('active');
/*$('.sidebar-menu').find('#menu-produk').addClass('active');
$('.sidebar-menu').find('#menu-daftar-produk').addClass('active');*/
</script>
<script type="text/javascript"><!--
function tampilimage(src){
  $('#tampil').remove();
  $('#content').prepend('<div id="tampil" style="padding: 3px 0px 0px 0px;"><img src="'+src+'"></div>');
  $('#tampil').dialog({
		title: 'Tampil Dokumen',
    bgiframe: false,
		width: 800,
		height: 400,
		resizable: false,
		modal: false

	});
}
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
<script type="text/javascript"><!--
var image_row = <?php echo $image_row; ?>;

function addImage() {
    html  = '<tbody id="image-row' + image_row + '">';
	html += '  <tr>';
  html += '    <td class="right"><input type="text" name="product_image[' + image_row + '][name]" value="" class="form-control"/></td>';
	html += '    <td class="left"><div class="image"><img src="<?php echo $no_image; ?>" alt="" id="thumb' + image_row + '" /><input type="hidden" name="product_image[' + image_row + '][image]" value="" id="image' + image_row + '" /><br /><a onclick="image_upload(\'image' + image_row + '\', \'thumb' + image_row + '\');">Cari</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$(\'#thumb' + image_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image' + image_row + '\').attr(\'value\', \'\');">Bersihkan</a></div></td>';

	html += '    <td class="left"><a onclick="$(\'#image-row' + image_row  + '\').remove();" class="btn btn-warning">Hapus</a></td>';
	html += '  </tr>';
	html += '</tbody>';

	$('#images tfoot').before(html);

	image_row++;
}

function hapusimage(product_image_id){
  var r=confirm("Anda yakin akan menghapus dokumen? Dokumen yang sudah dihapus tidak dapat dikembalikan lagi.");
  if(r){
    $.ajax({
			url: "index.php?route=sale/customer/hapusdocument&token=<?php echo $token; ?>&product_image_id="+product_image_id,
			success: function(json) {
        //  alert(JSON.stringify(json));
				    if(json['error']){
              alert(json['error']);
            }else{
              alert('Dokumen berhasil dihapus');
              location.reload();
            }
      }

				});
		}


}
//--></script>
<?php echo $footer; ?>
