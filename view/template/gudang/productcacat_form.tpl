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
            <h3 class="box-title">Produk Cacat</h3>
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
                       <td><span class="required">*</span>Gudang</td>
                       <td><select name="gudang_id" class="form-control">
                   			<?php
                   			foreach($gudangs as $g){
                   			?>
                   				<option value="<?php echo $g['gudang_id']; ?>" <?php echo ($g['gudang_id'] == $gudang_id)?'selected':''; ?>><?php echo $g['nama']; ?></option>
                   			<?php
                   			}
                   			?>
                   		</select>
                       </td>
                     </tr>

                  </table>
                  <table class="table" id="list-product" >
                    <thead>
                      <tr>
                        <td></td>
                        <td class="left">Nama Produk</td>
                        <td class="left">Ukuran</td>
                         <td class="right">Quantity</td>
                        <td class="right">Harga</td>

                      </tr>
                    </thead>
                    <?php $product_row=0;?>
                    <?php $option_row = 0; ?>
                    <?php $download_row = 0; ?>
                    <?php
                    if($products){
                    foreach ($products as $product) { ?>
                  <tbody id="product-row<?php echo $product_row; ?>">
                    <tr>
                        <td class="center" style="width: 3px;"><img src="view/image/delete.png" onclick="$('product-row<?php echo $product_row; ?>').remove()" title="Hapus" alt="Hapus" style="cursor: pointer;" onclick="$('#product-row<?php echo $product_row; ?>').remove(); $('#button-update').trigger('click');" /></td>

                        <td class="left"><input type="text" data-id="<?php echo $product_row; ?>" class="product-name" name="product[<?php echo $product_row ?>][name]" value="<?php echo $product['name'] ?>" />
                            <input type="hidden" class="product-id" name="product[<?php echo $product_row ?>][product_id]" value="<?php echo $product['product_id'] ?>" />

                      </td>
                      <td id="option<?php echo $product_row; ?>"></td>
                      <td class="left"><input type="text" name="product[<?php echo $product_row ?>][qty]" /></td>
                      <td class="left"><input type="text" name="product[<?php echo $product_row ?>][price]" /></td>

                    </tr>
                  </tbody>
                  <?php $product_row++; ?>
                   <?php $option_row++; ?>
                  <?php } } ?>
                  <tfoot>
                    <tr>
                      <td colspan="4"></td>
                      <td class="left"><a onclick="addModule();" class="btn btn-success">Tambah</a></td>
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
$('.sidebar-menu').find('#menu-produk-cacat').addClass('active');
$('.sidebar-menu').find('#menu-daftar-persediaan').addClass('active');

</script>

<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});


});
//--></script>
<script type="text/javascript"><!--
    var product_row = <?php echo $product_row; ?>;

function addModule() {
	html  = '<tbody id="product-row' + product_row + '">';
	html += '  <tr>';
        html +='<td class="center" style="width: 3px;"><img src="view/image/delete.png" onclick="$(\'#product-row'+product_row+'\').remove()" title="Hapus" alt="Hapus" style="cursor: pointer;" /></td>';

	html += '    <td class="left"><input type="text" data-id="'+product_row+'" onkeyup="komplit('+product_row+')" class="product-name" name="product[' + product_row + '][name]" /><input type="hidden" name="product[' + product_row + '][product_id]" /></td>';
	html += '    <td class="left" id="option'+product_row+'"></td>';
	html += '    <td class="right"><select name="product[' + product_row + '][qty]" /></select></td>';
        html += '    <td class="right"><input type="text" name="product[' + product_row + '][price]" /></td>';

	html += '  </tr>';
	html += '</tbody>';

	$('#list-product tfoot').before(html);

	product_row++;
}


//--></script>
<script type="text/javascript"><!--
function komplit(coba){
//alert($("select[name='gudang_id']").val());
$("input[name='product["+coba+"][name]']").autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/productgudang/autocompletegudang&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term)+'&filter_gudang_id=' + $("select[name='gudang_id']").val(),
			dataType: 'json',
			success: function(json) {
				//alert(json);
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.product_id,
						qty:item.qty,
            option: item.option,
            price:item.price
					}
				}));
			}


		});
	},
	select: function(event, ui) {
    //alert(JSON.stringify(ui));
      var column=$(this).data("id");
			if (ui.item['option'] != '') {
			$('#product-row'+coba).remove();
			for (i = 0; i < ui.item['option'].length; i++) {
				option_value = ui.item['option'][i];
        if(option_value['qty'] >= 1){
          html  = '<tbody id="product-row' + coba + '">';
          html += '  <tr>';
          html +='<td class="center" style="width: 3px;"><img src="view/image/delete.png" onclick="$(\'#product-row'+coba+'\').remove()" title="Hapus" alt="Hapus" style="cursor: pointer;" /></td>';

          html += '    <td class="left">'+ui.item['label']+'<input type="hidden" name="product[' + coba+ '][product_id]" value="'+ui.item['value']+'" /></td>';
          html += '    <td class="left" id="option'+coba+'">'+option_value['name']+'<input type="hidden" name="product[' + coba+ '][product_otion]" value="'+option_value['product_option_id']+'" /></td>';
          html += '    <td class="right"><select name="product[' + coba + '][qty]" >';

          for(z=1;z<=option_value['qty'];z++){
            html+='<option value="'+z+'">'+z+'</option>';
          }
          html+='</select></td>';
          html += '    <td class="right"><input type="text" name="product[' + coba + '][price]" value="'+ui.item['price']+'" /></td>';

          html += '  </tr>';
          html += '</tbody>';
          $('#list-product tfoot').before(html);
          coba++;
          product_row=coba;
        }
			}


			for (i = 0; i < ui.item.option.length; i++) {
				option = ui.item.option[i];

				if (option['type'] == 'file') {
					new AjaxUpload('#button-option-' + option['product_option_id'], {
						action: 'index.php?route=sale/order/upload&token=<?php echo $token; ?>',
						name: 'file',
						autoSubmit: true,
						responseType: 'json',
						data: option,
						onSubmit: function(file, extension) {
							$('#button-option-' + (this._settings.data['product_option_id'] + '-' + this._settings.data['product_option_id'])).after('<img src="view/image/loading.gif" class="loading" />');
						},
						onComplete: function(file, json) {

							$('.error').remove();

							if (json['success']) {
								alert(json['success']);

								$('input[name=\'option[' + this._settings.data['product_option_id'] + ']\']').attr('value', json['file']);
							}

							if (json.error) {
								$('#option-' + this._settings.data['product_option_id']).after('<span class="error">' + json['error'] + '</span>');
							}

							$('.loading').remove();
						}
					});
				}
			}

			$('.date').datepicker({dateFormat: 'yy-mm-dd'});
			$('.datetime').datetimepicker({
				dateFormat: 'yy-mm-dd',
				timeFormat: 'h:m'
			});
			$('.time').timepicker({timeFormat: 'h:m'});
		} else {
			$('#option'+column).empty();
			//$("input[name='product["+coba+"][name]']").val( ui.item['label']);
			$("input[name='product["+coba+"][name]']").remove();
			$("input[name='product["+coba+"][product_id]']").before(ui.item['label']);
			$("input[name='product["+coba+"][product_id]']").val(ui.item['value']);
			kuans='';
			for(z=1;z<=ui.item['qty'];z++){
				kuans+='<option value="'+z+'">'+z+'</option>';
			}
			$("select[name='product["+coba+"][qty]']").html(kuans);

			$("input[name='product["+coba+"][price]']").val(ui.item['price']);
		}

		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});
}
//--></script>


<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript"><!--
$('.date').datepicker({dateFormat: 'yy-mm-dd'});
$('.datetime').datetimepicker({
	dateFormat: 'yy-mm-dd',
	timeFormat: 'h:m'
});
$('.time').timepicker({timeFormat: 'h:m'});
//--></script>
<script type="text/javascript"><!--
$('.vtabs a').tabs();
//--></script>
<?php echo $footer; ?>
