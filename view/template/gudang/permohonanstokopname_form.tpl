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
            <h3 class="box-title">Permohonan Stok Opname</h3>
            <div class="button pull-right">
              <a onclick="simpan()" ><button type="button" class="btn btn-primary">Simpan</button></a>
                <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>

								</div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <div class="errordisplay"></div>
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
                        <td>Tanggal Stok Opname</td>
                        <td>
                          <input type="text" class="date form-control" name="tanggal" value="<?php echo date('Y-m-d'); ?>" readonly>
                        </td>
                    </tr>
                    <tr>
                       <td><span class="required">*</span>Gudang</td>
                       <td><select name="gudang_id" class="form-control">
                        <?php
                   			foreach($gudangs as $g){
                   			?>
                   				<option value="<?php echo $g['gudang_id']; ?>" ><?php echo $g['nama']; ?></option>
                   			<?php
                   			}
                   			?>
                   		</select>
                       </td>
                     </tr>

                     <tr>
                         <td>Keterangan</td>
                         <td><input type="text" name="keterangan" class="form-control" value="" ></td>
                     </tr>

                  </table>
                  <table class="table table-bordered" id="list-product" >
                    <thead>
                      <tr>
                        <th></th>
                        <th class="left">Nama Produk</th>
                         <th class="right">Qty Tercatat</th>
                         <th class="right">Rusak</th>
                         <th class="right">Hilang</th>
                         <th class="right">Qty Tersedia</th>
                        <th class="right">Keterangan</th>

                      </tr>
                    </thead>
                    <?php $product_row=0;?>
                    <?php $option_row = 0; ?>
                    <?php $download_row = 0; ?>

                  <tfoot>
                    <tr>
                      <td colspan="6"></td>
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
$('.sidebar-menu').find('#menu-persediaan-produkdagang').addClass('active');
$('.sidebar-menu').find('#menu-stok-opname').addClass('active');

</script>

<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd',minDate:'<?php echo $locktanggal; ?>',maxDate:new Date()});
 

});
//--></script>
<script type="text/javascript"><!--
    var product_row = <?php echo $product_row; ?>;

function addModule() {
	html  = '<tbody id="product-row' + product_row + '">';
	html += '  <tr>';
        html +='<td class="center" style="width: 3px;"><img src="view/image/delete.png" onclick="$(\'#product-row'+product_row+'\').remove()" title="Hapus" alt="Hapus" style="cursor: pointer;" /></td>';

	html += '    <td class="left"><input class="form-control" type="text" data-id="'+product_row+'" onkeyup="komplit('+product_row+')" class="product-name" name="product[' + product_row + '][name]" /><input type="hidden" name="product[' + product_row + '][product_id]" /><input type="hidden" name="product[' + product_row + '][price]" /></td>';
	html += '    <td class="right"><input type="hidden" name="product[' + product_row + '][qtytercatat]" /></td>';
  html += '    <td class="right"><input class="form-control" type="text" name="product[' + product_row + '][qtyrusak]" /></td>';
  html += '    <td class="right"><input class="form-control" type="text" name="product[' + product_row + '][qtyhilang]" /></td>';
  html += '    <td class="right"><input class="form-control" type="text" name="product[' + product_row + '][qtytersedia]" /></td>';
        html += '    <td class="right"><input class="form-control" type="text" name="product[' + product_row + '][keterangan]" /></td>';

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
            price:item.net_cost
					}
				}));
			}


		});
	},
	select: function(event, ui) {
    //alert(JSON.stringify(ui));
      var column=$(this).data("id");

			$("input[name='product["+coba+"][name]']").remove();
			$("input[name='product["+coba+"][product_id]']").before(ui.item['label']);
      $("input[name='product["+coba+"][price]']").val(ui.item['net_cost']);
			$("input[name='product["+coba+"][product_id]']").val(ui.item['value']);
      $("input[name='product["+coba+"][qtytercatat]']").before(ui.item['qty']);
      $("input[name='product["+coba+"][qtytercatat]']").val(ui.item['qty']);


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
$('.datetime').datetimepicker({
	dateFormat: 'yy-mm-dd',
	timeFormat: 'h:m'
});
$('.time').timepicker({timeFormat: 'h:m'});
//--></script>
<script type="text/javascript"><!--
$('.vtabs a').tabs();
//--></script>
<script>
function simpan(){
  $(".error").remove();
  error=false;
  errdup=false;
  errqty=false;
  em='';

  cek = [];
  gudang_id=$("select[name='gudang_id']").val();
  
  if(gudang_id < 1){
    error=true;
    em +="Gudang harus dipilih untuk pembelian produk dagang<br>";
  }
  tanggal=$("input[name='tanggal']").val();
  if(tanggal < '<?php echo $locktanggal;?>'){
    error=true;
    em+="Tanggal tidak   boleh kurang dari <?php echo $locktanggal;?> <br>";
  }

  for(i=0;i<product_row;i++){
    pid=$("input[name='product["+i+"][product_id]']").val();
    qty=$("input[name='product["+i+"][qty]']").val();

    if(qty <= 0){
      error=true;
      errqty=true;
    }

    if(pid == 0){
      error=true;
      em+="Nama Barang tidak ditemukan";
    }

    if(pid != undefined){
  		if(cek[pid] == undefined){
        if(pid > 0){
  			     cek[pid] = i;
        }
  		}
  		else{
  			errdup = true;
  			error=true;
  			//alert(product_id+' '+p);
  		}
		}
  }

  if(error){
    if(errdup){
      em+= "Terdapat duplikasi data produk.<br>";
    }
    if(errqty){
      em +=" Quantity produk harus lebih dari 0";
    }
    html='<div class="alert alert-danger alert-dismissible">';
    html +='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
    html +='<h4><i class="icon fa fa-ban"></i> Alert!</h4>';
    html +=em;
    html +='</div>';

    $('.errordisplay').html(html);;
  }else{
    $('#form').submit();
  }
}
</script>
<?php echo $footer; ?>
