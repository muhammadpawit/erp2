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
            <h3 class="box-title">Transfer Items</h3>
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
                       <td><span class="required">*</span>Gudang Asal</td>
                       <td><select name="gudang_asal" class="form-control">
                   			<option value="*">Pilih</option>
                        <?php
                   			foreach($gudangs as $g){
                   			?>
                   				<option value="<?php echo $g['gudang_id']; ?>" <?php echo ($g['gudang_id'] == $gudang_asal)?'selected':''; ?>><?php echo $g['nama']; ?></option>
                   			<?php
                   			}
                   			?>
                   		</select>
                       </td>
                     </tr>
                     <tr>
                        <td><span class="required">*</span>Gudang Tujuan</td>
                        <td><select name="tujuan" id="tujuan" class="form-control">
                          <option value="*">Pilih</option>
                    			<?php
                    			foreach($tujuans as $g){
                    			?>
                    				<option value="<?php echo $g['gudang_id']; ?>" <?php echo ($g['gudang_id'] == $tujuan)?'selected':''; ?>><?php echo $g['nama']; ?></option>
                    			<?php
                    			}
                    			?>
                    		</select>
                        </td>
                      </tr>
                      <?php //if($this->user->getUsername()=="pawit") { ?>
                        <tr style="display: none;" id="nopo">
                          <td><span class="required">*</span>Nomor PO</td>
                          <td>
                             <select name="filter_no_po" class="form-control nosurat">

                              </select>
                              <input type="hidden" name="no_po" id="no_po">
                              <span id="textpo"></span>
                          </td>
                        </tr>
                      <?php //}?>
                      <tr>
                        <!-- <td>Keterangan</td>
                        <td><input type="text" name="keterangan" class="form-control" value="<?php echo $keterangan;?>" ></td> -->
                        <td>SJ Supplier</td>
                        <td><input type="text" name="keterangan" class="form-control" value="<?php echo $keterangan;?>" ></td>
                      </tr>
                      <?php //if($this->user->getUsername()=="pawit"){?>
                      <tr>
                        <td>Alamat Expedisi</td>
                        <td>
                          <textarea class="form-control" rows="5" cols="10" name="alamatexpedisi"></textarea>
                        </td>
                      </tr>
                      <tr>
                        <td>No.Polisi</td>
                        <td>
                          <input type="text" name="nopol" class="form-control">
                        </td>
                      </tr>
                      <?php //} ?>
                  </table>
                  <table class="table" id="list-product" >
                    <thead>
                      <tr>
                        <td></td>
                        <td class="left">Nama Produk</td>
                        <td class="right">Quantity</td>

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
$('.sidebar-menu').find('#menu-persediaan').addClass('active');
$('.sidebar-menu').find('#menu-persediaan-produkdagang').addClass('active');
$('.sidebar-menu').find('#menu-transfer-item').addClass('active');
</script>

<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
});

$('#tujuan').on('change', function() {
  //alert( this.value );
  url='index.php?route=gudang/transferitem/cekgudang&token=<?php echo $token; ?>&gudang_id=';
  var gudang_id=this.value;
  $.ajax({
      url: url + gudang_id,
      dataType: 'json',
      success: function(json) {
        //alert(json);
        if(json>0){
          $("#nopo").fadeIn();
        }else if(json<0){
          $("#nopo").fadeOut();
          $("#nopo").load();
          $("#no_po").attr("value", "-");
        }else{
          $("#nopo").fadeOut();
          $("#nopo").load();
          $("#no_po").attr("value", "-");
        }
        console.log(json);
      }
    });
});
$('.nosurat').on('change', function() {
  //alert( this.value );
  $("#no_po").val(this.value);
  $("#textpo").html(this.value);
});  
$(function(){
  $(".nosurat").select2({
    ajax: {
    url:"index.php?route=pembelian/pembeliankreditdagang/autocomplete&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        q: params.term,


      };
    },
    delay: 250,
    processResults: function (data) {
      return {
        results: data
      };
    },
    //cache: true
  },
  theme:"bootstrap"
})
$(".suratpermintaan").select2({
  ajax: {
  url:"index.php?route=pembelian/permintaanpembelian/autocomplete&token=<?php echo $this->request->get['token']; ?>",
  dataType: 'json',
  data: function (params) {
    return {
      q: params.term,
      j: 2,
      status:5,
      s:1// search term

    };
  },
  delay: 250,
  processResults: function (data) {
    return {
      results: data
    };
  },
  //cache: true
},
theme:"bootstrap"
})
  $(".vendor").select2({
    ajax: {
    url:"index.php?route=catalog/vendorlokal/autocomplete&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        filter_name: params.term
      };
    },
    delay: 250,
    processResults: function (data) {
      return {
        results: data
      };
    },
    //cache: true
  },
  theme:"bootstrap"
  });

});

</script>
<script type="text/javascript"><!--
    var product_row = <?php echo $product_row; ?>;

function addModule() {
  if(product_row <= 100){
  	html  = '<tbody id="product-row' + product_row + '">';
  	html += '  <tr>';
    html +='<td class="center" style="width: 3px;"><img src="view/image/delete.png" onclick="$(\'#product-row'+product_row+'\').remove()" style="cursor: pointer;" /></td>';

  	html += '    <td class="left"><input class="form-control" type="text"  data-id="'+product_row+'" onfocus="komplit('+product_row+')" class="form-control product-name" name="product[' + product_row + '][name]" /><input type="hidden" name="product[' + product_row + '][product_id]" /><input type="hidden" name="product[' + product_row + '][price]" /><input type="hidden" name="product[' + product_row + '][totalqty]" /></td>';
  	html += '    <td class="right"><input class="form-control" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" name="product[' + product_row + '][qty]" /></td>';
    html += '  </tr>';
  	html += '</tbody>';

  	$('#list-product tfoot').before(html);

  	product_row++;
  }else{
    alert("Anda melewati batas maksimal produk. Mohon buat form pengiriman barang baru.");
  }
}


//--></script>
<script type="text/javascript"><!--
function komplit(coba){
  url='index.php?route=catalog/productgudang/autocompletegudang&token=<?php echo $token; ?>&filter_name=';

//alert($("select[name='gudang_id']").val());
$("input[name='product["+coba+"][name]']").autocomplete({
	delay: 500,
  minLength:3,
	source: function(request, response) {
		$.ajax({
			url: url + encodeURIComponent(request.term)+'&filter_gudang_id=' + $("select[name='gudang_asal']").val(),
			dataType: 'json',
			success: function(json) {
				//alert(json);
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.product_id,
						qty:item.qty,
            option: item.option,
            price:item.price,
            net_cost:item.net_cost
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
			$("input[name='product["+coba+"][product_id]']").val(ui.item['value']);
      $("input[name='product["+coba+"][price]']").val(ui.item['net_cost']);
      $("input[name='product["+coba+"][totalqty]']").val(ui.item['qty']);
			/*kuans='';
			for(z=1;z<=ui.item['qty'];z++){
				kuans+='<option value="'+z+'">'+z+'</option>';
			}
			$("select[name='product["+coba+"][qty]']").html(kuans);*/

			//$("input[name='product["+coba+"][harga_jual]']").val(ui.item['price']);

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
  if($("select[name='gudang_asal']").val() == $("select[name='tujuan']").val()){
    error = true;
    em += "Gudang Asal dan gudang tujuan tidak boleh sama.<br>";
  }
  cek = [];
  for(i=0;i<product_row;i++){
    pid=$("input[name='product["+i+"][product_id]']").val();
  //  potion=$("input[name='product["+i+"][product_otion]']").val();
    qty=$("input[name='product["+i+"][qty]']").val();
    totalqty=$("input[name='product["+i+"][totalqty]']").val();
    //alert(pid);

    if(Number(qty) > Number(totalqty)){
      error=true;
      errqty=true;

    }
    if(pid != undefined){
      if(cek[pid] == undefined){
        cek[pid] = i;
      }else{
        errdup = true;
        error=true;
      }

		}
  }

  if(error){
    if(errdup){
      em+= "Terdapat duplikasi data produk.<br>";
    }
    if(errqty){
      em+= "Quantity melebihi total quantity tersedia.<br>";
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
