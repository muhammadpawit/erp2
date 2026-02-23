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

                <div class="callout callout-success lead">
                  <h4>Tabung Gas</h4>

                </div>
                <?php
                $product_row=0;
                ?>
                <table class="table" id="list-product">
                    <thead>
                      <th></th>
                      <th>Tabung</th>
                      <th>Quantity</th>
                      <th></th>
                  </thead>
                  <?php $product_row=0;?>
                  <tbody>

                  </tbody>
                  <tfoot>
                    <tr>
                      <td ></td>
                      <td ></td>
                      <td ></td>
                      <td class="left"><a onclick="addModuleMs();" class="btn btn-success">Tambah</a></td>
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
$('.sidebar-menu').find('#menu-produksi').addClass('active');


</script>

<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});


});
//--></script>
<script>
var product_row='<?php echo $product_row;?>';


function addModuleMs() {
  pid=$("input[name='product[0][product_id]']").val();
  //alert(pid);
  if(pid != 0){
  jenis=$("select[name='jenis_produksi']").val();
  if(jenis == 2){
  html  = '<tbody id="product-row' + product_row + '">';
	html += '  <tr>';
  html +='<td class="center" ><img src="view/image/delete.png" onclick="hapus('+product_row+')" style="cursor: pointer;" /></td>';
  html += '    <td class="left"><select data-row="'+product_row+'" name="tabungms[' + product_row + '][product_id]" class="productms form-control"></select><input type="hidden" name="tabungms[' + product_row + '][net_cost]" value="0"></td>';
	html += '  <td><select class="form-control" name="tabungms[' + product_row + '][quantity]"></select></td><td></td></tr>';
	html += '</tbody>';

	$('#list-product tfoot').before(html);

	product_row++;

  $(function(){


            $(".productms").select2({
                ajax: {
                url:"index.php?route=catalog/product/autocompleteprod&token=<?php echo $this->request->get['token']; ?>",
                  dataType: 'json',
                data: function (params) {
                  return {
                    q: params.term,
                    kategori:195
                  //  statustabung:$(".statustabung").val(),
                  //  kategori:$(".jenisorder").val()
                    //customer_group_id:$('input[name=\'customer_group_id\']').val()

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
              }).on("select2:select",function(e){
                  //console.log(e);
                  //console.log($(this).val());
                  id=$(this).val();
                  coba=$(this).data('row');
                  $.ajax({
                    url: 'index.php?route=catalog/productgudang/detail&token=<?php echo $this->request->get['token']; ?>&product_id=' + pid+'&gudang_id='+$("select[name='gudang_id']").val(),
                    dataType: 'json',
                    success: function(json) {
                    console.log(JSON.stringify(json));
                    //alert(json.detail.quantity);
                    html='';
                    for(i=0;i<=json.detail.quantity;i++){
                      html +='<option value='+i+'>'+i+'</option>';
                    }
                    $("select[name='tabungms["+coba+"][quantity]']").html(html);
                    $("input[name='tabungms["+coba+"][net_cost]']").val(json.detail.net_cost);


                    }
                  })
            })

        })
    }
    if(jenis == 3){
      html  = '<tbody id="product-row' + product_row + '">';
    	html += '  <tr>';
      html +='<td class="center" ><img src="view/image/delete.png" onclick="hapus('+product_row+')" style="cursor: pointer;" /></td>';
      html += '    <td class="left"><select data-row="'+product_row+'" name="tabung[' + product_row + '][tabung_id]" class="tabung form-control"></select></td>';
    	html += '  <td><select class="form-control" name="tabung[' + product_row + '][quantity]"><option value="1">1</option></select></td><td></td></tr>';
    	html += '</tbody>';

    	$('#list-product tfoot').before(html);

    	product_row++;

      $(function(){

        $(".tabung").select2({
            ajax: {
            url:"index.php?route=catalog/tabungmp/autocomplete&token=<?php echo $this->request->get['token']; ?>",
              dataType: 'json',
            data: function (params) {
              row=$(this).data('row');
              jenisgas=pid;
              //alert(jenisgas);
              return {
                q: params.term,
                statustabung:1,
                jenisgas:jenisgas,
                status:1


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


    })
    }
  }else{
    alert('Mohon pilih nama produk terlebih dahulu.');
  }
}
function hapus(row){
  $('#product-row'+row).remove();

}
</script>



<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript"><!--
$('.date').datepicker({dateFormat: 'yy-mm-dd'});
$('.datetime').datetimepicker({
	dateFormat: 'yy-mm-dd',
	timeFormat: 'h:m'
});
$("select[name='jenis_produksi']").on('change',function(){
  $('#list-product tbody').remove();
  $("input[name='product[0][product_id]']").val(0);
  $("input[name='product[0][product_name]']").val("");
  product_row=0;
})
$('.time').timepicker({timeFormat: 'h:m'});
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
  total=0;

  cek = [];
  cektbg=[];
  gudang_id=$("select[name='gudang_id']").val();
  jenis=$("select[name='jenis_produksi']").val();

  pid=$("input[name='product[0][product_id]']").val();
  qty=$("input[name='product[0][qty]']").val();
  if(qty = undefined){
      error=true;
      em+="Quantity permintaan harus diisi<br>";
  }

  if(qty <= 0){
    error=true;
    errqty=true;
  }

  if(pid == 0){
    error=true;
    em+="Nama Barang tidak ditemukan<br>";
  }

  for(i=0;i<product_row;i++){

    if(jenis ==2){
      tabung_id=$("select[name='tabungms["+i+"][product_id]']").val();
      tbgqty=$("select[name='tabungms["+i+"][quantity]']").val();
      //alert(tbgqty);
    }
    if(jenis ==3){
      tabung_id=$("select[name='tabung["+i+"][tabung_id]']").val();
      tbgqty=$("select[name='tabung["+i+"][quantity]']").val();
      //alert(tbgqty);
    }
    if(pid != 0){
      if(tabung_id != undefined){
        total += Number(tbgqty);
        if(cek[tabung_id] == undefined){
          cek[tabung_id]=i;
        }else{
          errdup = true;
          error=true;
          //alert(product_id+' '+p);
        }
      }
    }




  }
  if(total > qty){
    error=true;
    em +="Jumlah tabung melebihi jumlah quantity permintaan penggembosan<br>";
  }
  if(total < qty){
    error=true;
    em +="Jumlah tabung kurang dari quantity permintaan penggembosan <br>";
  }
  if(total == 0){
    error=true;
    em +="Tabung Gas harus dipilih <br>";
  }

  if(error){

    if(errdup){
      em+= "Terdapat duplikasi data tabung.<br>";
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
