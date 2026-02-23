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
            <h3 class="box-title">Permintaan Produksi</h3>
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
                       <td><span class="required">*</span>Divisi Asal</td>
                       <td><select name="divisi_asal" class="form-control">
                   			<?php
                   			foreach($divisis as $g){
                   			?>
                   				<option value="<?php echo $g['id']; ?>" ><?php echo $g['name']; ?></option>
                   			<?php
                   			}
                   			?>
                   		</select>
                       </td>
                     </tr>
                     <tr>
                         <td>Jenis Produksi</td>
                         <td>
                           <select class="form-control" name="jenis_produksi">
                             <!--option value="1" >MR</option-->
                             <option value="2" >Stok</option>
                             <option value="3" >MP</option>
                           </status>
                         </td>
                     </tr>
                     <!--tr>
                         <td>Referensi Sales Order</td>
                         <td>
                           <select class="form-control" name="no_so">
                             <option value="0" >Tanpa Sales Order</option>

                           </status>
                         </td>
                     </tr-->
                     <tr>
                         <td>Nama Produk</td>
                         <td>
                           <input type="text"  onkeyup="komplit()" class="form-control product-name" name="product[0][product_name]" value=""/><input type="hidden" name="product[0][product_id]" value="0" />
                        </td>
                     </tr>
                     <tr>
                         <td>Quantity</td>
                         <td>
                            <input type="text" name="product[0][quantity]" class="form-control" value="" >
                        </td>
                     </tr>
                     <tr>
                         <td>Keterangan</td>
                         <td><input type="text" name="product[0][keterangan]" class="form-control" value="" ></td>
                     </tr>

                  </table>
                  <!--table class="table" id="list-product" >
                    <thead>
                      <tr>
                        <th></th>
                        <th class="left">Nama Produk</th>
                        <th class="left">Spesifikasi</th>
                         <th class="right">Quantity</th>
                        <th class="right">Keterangan</td>

                      </tr>
                    </thead>
                    <?php $product_row=0;?>
                    <?php $option_row = 0; ?>
                    <?php $download_row = 0; ?>

                  <tfoot>
                    <tr>
                      <td colspan="4"></td>
                      <td class="left"><a onclick="addModule();" class="btn btn-success">Tambah</a></td>
                    </tr>
                  </tfoot>
                </table-->
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
$('.sidebar-menu').find('#menu-produksi').addClass('active');


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
  html +='<td class="center" style="width: 3px;"><img src="view/image/delete.png" onclick="$(\'#product-row'+product_row+'\').remove()" style="cursor: pointer;" /></td>';

	html += '    <td class="left"><input type="text" data-id="'+product_row+'" onkeyup="komplit('+product_row+')" class="product-name" name="product[' + product_row + '][name]" value=""/><input type="hidden" name="product[' + product_row + '][product_id]" value="0" /></td>';
  html += '    <td class="right"><input type="text" name="product[' + product_row + '][spesifikasi]" value=""/></td>';
  html += '    <td class="right"><input type="text" name="product[' + product_row + '][quantity]" value="1" /></td>';
        html += '    <td class="right"><input type="text" name="product[' + product_row + '][keterangan]" value="" /></td>';

	html += '  </tr>';
	html += '</tbody>';

	$('#list-product tfoot').before(html);

	product_row++;
}


//--></script>
<script type="text/javascript"><!--
function komplit(){
//alert($("select[name='gudang_id']").val());
//jenis barang

    $("input[name='product[0][product_name]']").autocomplete({
    	delay: 0,
    	source: function(request, response) {
    		$.ajax({
    			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term)+'&filter_gudang_id=' + $("select[name='gudang_id']").val(),
    			dataType: 'json',
    			success: function(json) {
    				//alert(json);
    				response($.map(json, function(item) {
    					return {
    						label: item.name,
    						value: item.product_id,


    					}
    				}));
    			}


    		});
    	},
    	select: function(event, ui) {
        //alert(JSON.stringify(ui));
      //  $("input[name='product["+coba+"][name]']").remove();
        $("input[name='product[0][product_name]']").val(ui.item['label']);
        $("input[name='product[0][product_id]']").val(ui.item['value']);

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
<script>
function simpan(){
  $(".error").remove();
  error=false;
  errdup=false;
  errqty=false;
  em='';

  cek = [];
  jenisbarang=$("select[name='jenis_barang']").val();
  gudang_id=$("select[name='gudang_id']").val();

  if(jenisbarang == 2){
    if(gudang_id < 1){
      error=true;
      em +="Gudang harus dipilih untuk pembelian produk dagang";
    }
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
