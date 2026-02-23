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
            <h3 class="box-title">Harga</h3>
            <div class="button pull-right">
                <a onclick="$('#form').submit();" ><button type="button" class="btn btn-primary">Simpan</button></a>
									<a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>

								</div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                  <table id="special" class="table">
                    <thead>
                      <tr>
                        <th class="left">Customer Group</th>
                        <th class="right">Harga Minimal</th>
                        <th class="right">Price List</th>

                        <td></td>
                      </tr>
                    </thead>
                    <?php $special_row = 0; ?>
                    <?php foreach ($product_specials as $product_special) { ?>
                    <tbody id="special-row<?php echo $special_row; ?>">
                      <tr>
                        <td class="left"><select class="form-control" name="product_special[<?php echo $special_row; ?>][customer_group_id]">
                            <?php foreach ($customer_groups as $customer_group) { ?>
                            <?php if ($customer_group['customer_group_id'] == $product_special['customer_group_id']) { ?>
                            <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                          </select></td>
                          <td class="right"><input class="form-control" type="text" name="product_special[<?php echo $special_row; ?>][batasbawah]" value="<?php echo number_format($product_special['batasbawah'],0,'.',''); ?>" /></td>
                          <td class="right"><input class="form-control" type="text" name="product_special[<?php echo $special_row; ?>][price]" value="<?php echo number_format($product_special['price'],0,'.',''); ?>" /></td>
                        <td class="left"><a class="btn btn-warning" onclick="hapusspecial(<?php echo $product_special['product_special_id']; ?>)" class="button">Hapus</a></td>
                      </tr>
                    </tbody>
                    <?php $special_row++; ?>
                    <?php } ?>
                    <tfoot>
                      <tr>
                        <td colspan="3"></td>
                        <td class="left"><a onclick="addSpecial();" class="btn btn-success">Tambah Harga</a></td>
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
$('.sidebar-menu').find('#menu-persediaan').addClass('active');
$('.sidebar-menu').find('#menu-persediaan-produkdagang').addClass('active');
$('.sidebar-menu').find('#menu-produk-gudang').addClass('active');
</script>

<script type="text/javascript"><!--
var special_row = <?php echo $special_row; ?>;

function addSpecial() {
	html  = '<tbody id="special-row' + special_row + '">';
	html += '  <tr>';
    html += '    <td class="left"><select class="form-control" name="product_special[' + special_row + '][customer_group_id]">';
    <?php foreach ($customer_groups as $customer_group) { ?>
    html += '      <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>';
    <?php } ?>
    html += '    </select></td>';
    html += '    <td class="right"><input class="form-control" type="text" name="product_special[' + special_row + '][batasbawah]" value="" /></td>';
    html += '    <td class="right"><input class="form-control" type="text" name="product_special[' + special_row + '][price]" value="" /></td>';
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
  var r=confirm("Anda yakin akan menghapus harga? Harga yang sudah dihapus tidak dapat dikembalikan lagi.");
  if(r){
    $.ajax({
			url: "index.php?route=catalog/product/hapusspecial&token=<?php echo $token; ?>&product_special_id="+special_id,
			success: function(json) {
        //  alert(JSON.stringify(json));
				    if(json['error']){
              alert(json['error']);
            }else{
              alert('Harga berhasil dihapus');
              location.reload();
            }
      }

				});
		}


}


</script>
<script>
$(function(){
  $('.date').datepicker({dateFormat: 'yy-mm-dd'});
})
</script>
<?php echo $footer; ?>
