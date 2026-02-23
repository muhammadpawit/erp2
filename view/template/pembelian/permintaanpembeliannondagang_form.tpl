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
            <h3 class="box-title">Permintaan Pembelian Non Produk Dagang</h3>
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
                  <input type="hidden" name="vendor_id" value="0">
                  <table class="table">

                    <tr>
                       <td><span class="required">*</span>Divisi Asal</td>
                       <td>
                         <input type="hidden" name ="jenis_pembelian" value="2">
                         <input type="hidden" name ="gudang_id" value="0">
                        <select name="divisi_asal" class="form-control">
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
                         <td>Jenis Barang</td>
                         <td>
                           <select class="form-control" name="jenis_barang">
                             <option value="3" >ATK</option>
                             <option value="4" >Aset</option>
                             <option value="5" >Tabung Gas</option>
                           </status>
                         </td>
                     </tr>
                     <tr>
                         <td>Jenis Aktiva</td>
                         <td>
                           <select class="form-control" name="jenis_aktiva">
                             <option value="0" >Persediaan</option>
                             <?php
                             foreach($aktivas as $a){
                              ?>
                              <option value="<?php echo $a['no_akun']?>" ><?php echo $a['nama']; ?></option>
                              <?php
                             }
                             ?>
                           </status>
                         </td>
                     </tr>

                     <tr>
                         <td>Tujuan Pembelian</td>
                         <td><input type="text" name="tujuan_pembelian" class="form-control" value="" ></td>
                     </tr>

                  </table>
                  <table class="table" id="list-product" >
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
$('.sidebar-menu').find('#menu-pembelian').addClass('active');
$('.sidebar-menu').find('#menu-pembelian-produk').addClass('active');

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

	html += '    <td class="left"><input type="text" data-id="'+product_row+'" onkeyup="komplit('+product_row+')" class="form-control product-name" name="product[' + product_row + '][name]" value=""/><input type="hidden" name="product[' + product_row + '][product_id]" value="0" /></td>';
  html += '    <td class="right"><input type="text" class="form-control" name="product[' + product_row + '][spesifikasi]" value=""/></td>';
  html += '    <td class="right"><input type="text" class="form-control" name="product[' + product_row + '][quantity]" value="1" /></td>';
        html += '    <td class="right"><input type="text" class="form-control" name="product[' + product_row + '][keterangan]" value="" /></td>';

	html += '  </tr>';
	html += '</tbody>';

	$('#list-product tfoot').before(html);

	product_row++;
}


//--></script>
<script type="text/javascript"><!--
function komplit(coba){
//alert($("select[name='gudang_id']").val());
//jenis barang
jb=$("select[name='jenis_barang']").val();
if(jb == 1){
  $("input[name='product["+coba+"][name]']").autocomplete({
    delay: 0,
    source: function(request, response) {
      $.ajax({
        url: 'index.php?route=catalog/bahanbaku/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term),
        dataType: 'json',
        success: function(json) {
          //alert(json);
          response($.map(json, function(item) {
            return {
              label: item.name,
              value: item.id,


            }
          }));
        }


      });
    },
    select: function(event, ui) {
      //alert(JSON.stringify(ui));
    //  $("input[name='product["+coba+"][name]']").remove();
      $("input[name='product["+coba+"][name]']").val(ui.item['label']);
      $("input[name='product["+coba+"][product_id]']").val(ui.item['value']);

      return false;
    },
    focus: function(event, ui) {
          return false;
      }
  });
}
if(jb == 3){
  $("input[name='product["+coba+"][name]']").autocomplete({
    delay: 0,
    source: function(request, response) {
      $.ajax({
        url: 'index.php?route=catalog/atk/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term),
        dataType: 'json',
        success: function(json) {
          //alert(JSON.stringify(json));
          response($.map(json, function(item) {
            return {
              label: item.text,
              value: item.id,


            }
          }));
        }


      });
    },
    select: function(event, ui) {
      //alert(JSON.stringify(ui));
    //  $("input[name='product["+coba+"][name]']").remove();
      $("input[name='product["+coba+"][name]']").val(ui.item['label']);
      $("input[name='product["+coba+"][product_id]']").val(ui.item['value']);

      return false;
    },
    focus: function(event, ui) {
          return false;
      }
  });
}
if(jb == 4){
  $("input[name='product["+coba+"][name]']").autocomplete({
    delay: 0,
    source: function(request, response) {
      $.ajax({
        url: 'index.php?route=catalog/aset/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term),
        dataType: 'json',
        success: function(json) {
          //alert(JSON.stringify(json));
          response($.map(json, function(item) {
            return {
              label: item.text,
              value: item.id,


            }
          }));
        }


      });
    },
    select: function(event, ui) {
      //alert(JSON.stringify(ui));
    //  $("input[name='product["+coba+"][name]']").remove();
      $("input[name='product["+coba+"][name]']").val(ui.item['label']);
      $("input[name='product["+coba+"][product_id]']").val(ui.item['value']);
      $("input[name='product["+coba+"][quantity]']").val(1);
      $("input[name='product["+coba+"][quantity]']").prop('readonly');

      return false;
    },
    focus: function(event, ui) {
          return false;
      }
  });
}
if(jb == 5){
  $("input[name='product["+coba+"][name]']").autocomplete({
    delay: 0,
    source: function(request, response) {
      $.ajax({
        url: 'index.php?route=catalog/tabungmp/autocomplete&token=<?php echo $token; ?>&filter_no_tabung=' + encodeURIComponent(request.term),
        dataType: 'json',
        success: function(json) {
          //alert(json);
          response($.map(json, function(item) {
            return {
              label: item.text,
              value: item.id,


            }
          }));
        }


      });
    },
    select: function(event, ui) {
      //alert(JSON.stringify(ui));
    //  $("input[name='product["+coba+"][name]']").remove();
      $("input[name='product["+coba+"][name]']").val(ui.item['label']);
      $("input[name='product["+coba+"][product_id]']").val(ui.item['value']);

      return false;
    },
    focus: function(event, ui) {
          return false;
      }
  });
}
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
  gudang_id=$("select[name='gudang_id']").val();

  if(gudang_id < 1){
    error=true;
    em +="Gudang harus dipilih untuk pembelian produk dagang";
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
