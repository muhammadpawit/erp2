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
            <h3 class="box-title">Set harga terendah untuk : <?php echo $desc['name']; ?> Gudang <?php echo $gudang['nama']; ?> </h3>
            <div class="button pull-right">
                <a onclick="$('#form').submit();" ><button type="button" class="btn btn-primary">Simpan</button></a>
									<a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>

								</div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                  <input type="hidden" name="product_id" value="<?php echo $this->request->get['product_id'] ?>">
                  <input type="hidden" name="gudang_id" value="<?php echo $this->request->get['gudang_id'] ?>">
                  <input type="hidden" name="name" value="<?php echo $desc['name']; ?> ">
                  <table id="special" class="table">
                    <thead>
                      <tr>
                        <th class="right">Tanggal</th>
                        <th class="left">Harga Terendah</th>
                        <th class="left">Poin</th>
                        <th class="left"><?php echo $type ?></th>
                        <td></td>
                      </tr>
                    </thead>
                    <?php $special_row = 0; //print_r($product_specials)?>
                    <?php foreach ($product_specials as $product_special) { ?>
                    <tbody>
                      <tr>
                        <td class="left"><input type="hidden" name="product_special[<?php echo $special_row; ?>][date]" value="<?php echo $product_special['date']; ?>" readonly/><?php echo $product_special['date']; ?></td>
                        <td class="left"><input type="hidden" name="product_special[<?php echo $special_row; ?>][harga_terendah]" value="<?php echo $product_special['harga_terendah']; ?>" readonly/><?php echo $this->currency->format($product_special['harga_terendah']); ?></td>
                        <td class="left"><input type="hidden" name="product_special[<?php echo $special_row; ?>][poin]" value="<?php echo $product_special['poin']; ?>" readonly/><?php echo $product_special['harga_terendah']; ?></td>
                      </tr>
                    </tbody>
                    <?php //$special_row++; ?>
                    <?php } ?>
                    <tfoot>
                      <tr>
                        <td colspan="5"></td>
                        <td class="left"><a onclick="addSpecial();" class="btn btn-success">Tambah</a></td>
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
$('.sidebar-menu').find('#menu-master-data').addClass('active');
$('.sidebar-menu').find('#menu-produk').addClass('active');
$('.sidebar-menu').find('#menu-daftar-produk').addClass('active');
</script>

<script type="text/javascript"><!--
var special_row =0;

function addSpecial() {
	html  = '<tbody id="special-row' + special_row + '">';
	html += '  <tr>';

    html += '    <td class="left"><input type="text" class="date" name="product_special[' + special_row + '][date]" value="" /></td>';
	html += '    <td class="left"><input type="text" name="product_special[' + special_row + '][harga_terendah]" value="" /></td>';
  html += '    <td class="left"><input type="text" name="product_special[' + special_row + '][poin]" value="" /></td>';
	html += '    <td class="left"><a onclick="$(\'#special-row' + special_row + '\').remove();" class="btn btn-warning">Hapus</a></td>';
	html += '  </tr>';
    html += '</tbody>';

	$('#special tfoot').before(html);

	$('#special-row' + special_row + ' .date').datepicker({dateFormat: 'yy-mm-dd'});

	special_row++;
}
//--></script>

<script>
function hapusspecial(special_id){
  //alert(special_id);
  var r=confirm("Anda yakin akan menghapus harga diskon? Harga diskon yang sudah dihapus tidak dapat dikembalikan lagi.");
  if(r){
    $.ajax({
			url: "index.php?route=catalog/promodiscount/hapusqtydetail&token=<?php echo $token; ?>&product_special_id="+special_id,
			success: function(json) {
        //  alert(JSON.stringify(json));
				    if(json['error']){
              alert(json['error']);
            }else{
              alert('Harga diskon berhasil dihapus');
              location.reload();
            }
      }

				});
		}


}


</script>
<script>
$(function(){
  $('.date').datepicker({
    dateFormat: 'yy-mm-dd',
  });
})
</script>
<?php echo $footer; ?>
