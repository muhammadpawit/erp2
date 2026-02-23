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
            <h3 class="box-title">Input Surat Jalan Pembelian Produk Dagang Lokal</h3>
            <div class="button pull-right">
              <a onclick="simpan()" ><button type="button" class="btn btn-primary">Proses</button></a>
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

                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                  <div class="row">
                    <div class="col-sm-12 col-xs-12">
                  <table class="table table-responsive">
                    <tr>
                        <td>Nomor Surat Jalan</td>
                        <td>
                          <input type="text" class="form-control" name="no_suratjalan" value="" >
                        </td>
                    </tr>


                    <tr>
                       <td><span class="required">*</span>Gudang</td>
                       <td><select name="gudang_id" class="form-control"  onchange="ubahjenis()">
                         <option value="0" >Tanpa Gudang</option>
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
                      <td>Vendor</td>
                      <td>
                        <select name="vendor_id" class="vendor form-control" onchange="ubahjenis()">

                          </select>
                      </td>
                    </tr>

                  </table>
                </div>

                </div>
                <div class="row">
                  <div class="col-xs-12">
                    <div class="nav-tabs-custom">
                      <ul class="nav nav-tabs">
                        <li class="active"><a href="#detail" data-toggle="tab">Daftar Barang</a></li>
                        <li><a href="#biaya" data-toggle="tab">Biaya</a></li>


                      </ul>
                      <div class="tab-content">
                        <div class="tab-pane active " id="detail">
                          <table class="table table-responsive" id="list-product-detail" >
                            <thead>
                              <th></th>
                              <th>Nomor PO</th>
                              <th>Nama Produk</th>
                              <th>Qty PO</th>
                              <th>Quatity Telah Diterima</th>
                              <th>Quantity Datang</th>


                            </thead>
                            <tbody>
                            </tbody>
                          </table>
                        </div>
                        <div class="tab-pane " id="biaya">
                          <table class="table" id="list-biaya">
                            <thead>
                              <tr>
                                <th class="left">Nama Biaya</th>
                                <th class="right">Nominal</th>

                              </tr>
                            </thead>
                            <?php $biaya_row=0;?>

                            <tbody >
                            </tbody>
                            <tfoot>
                              <tr>
                              <tr>
                                <td colspan="2"></td>
                                <td class="left"><a onclick="addBiaya();" class="btn btn-success">Tambah Biaya</a>  </td>
                              </tr>

                      </tfoot>
                          </table>
                        </div>
                      </div>
                    </div>

                  </div>

                </div>
                </form>



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
$('.sidebar-menu').find('#menu-pembelian-kredit').addClass('active');
$('.sidebar-menu').find('#menu-invoice-pembelian-kredit').addClass('active');

</script>

<script type="text/javascript"><!--
var biaya_row=<?php echo $biaya_row; ?>;
function addBiaya(){

	html = '  <tr id="biaya-row' + biaya_row + '">';
  html += '    <td class="left"><select style="width:300px" data-id="'+biaya_row+'" name="biaya[' + biaya_row + '][jenisbiaya_id]" class="biaya form-control"></select></td>';
  html += '    <td class="right"><input class="form-control"  type="text" name="biaya[' + biaya_row + '][total]" value="0"  /></td>';


  html += '    <td class="right"><a class="btn btn-warning" onclick="$(\'#biaya-row'+biaya_row+'\').remove()" class="button">Hapus</a> <span></span></td>';

	html += '  </tr>';


	$('#list-biaya tbody').append(html);

  $(function(){
    $(".biaya").select2({
        ajax: {
        url:"index.php?route=catalog/jenisbiaya/autocomplete&token=<?php echo $this->request->get['token']; ?>",
          dataType: 'json',
        data: function (params) {
          return {
            q: params.term,
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
      })
  })
	biaya_row++;

}
$(document).ready(function() {
$('.date').datepicker({dateFormat: 'yy-mm-dd'});



$(".penerima").select2({
  ajax: {
  url:"index.php?route=user/user/autocomplete&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
  data: function (params) {
    return {
      q: params.term,
      //j:21

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
  $(".vendor").select2({
    ajax: {
    url:"index.php?route=catalog/vendorlokal/autocomplete&token=<?php echo $this->request->get['token']; ?>",
      dataType: 'json',
    data: function (params) {
      //alert($(".sales").val());
      return {
        q: params.term

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
    //alert(id);
    if(id != undefined & id != null){
      gudang_id=$("select[name='gudang_id']").val();
    $.ajax({
      url: 'index.php?route=pembelian/pembeliankreditdagang/detailbelumdatang&token=<?php echo $this->request->get['token']; ?>&vendor_id=' + id+'&gudang_id='+gudang_id,
      dataType: 'json',
      success: function(json) {
        if(json){

          console.log(JSON.stringify(json));

          html='';
          net_cost=0;

          for(i in json.products){
            sisa=Number(json.products[i].quantity) - Number(json.products[i].quantityterima);
          //  totalqty += Number(json.products[i].quantity);
            html +='<tr id="product-detail-row"'+product_detail_row+'>';

            html +='<td><input class="pilih" type="checkbox" name="product[' + product_detail_row + '][pilih]" value="1" onchange="updatetotal()"></td>';

            html += '    <td class="right">'+json.products[i].no_po+'<input type="hidden" name="product[' + product_detail_row + '][po_id]" value="'+json.products[i].pembelian_id+'" /><input type="hidden" name="product[' + product_detail_row + '][po_product_id]" value="'+json.products[i].id+'" /></td>';

              html += '    <td class="left"><input type="text" class="form-control" class="product-name" onblur="updatetotal()"  name="product[' + product_detail_row + '][name]" value="'+json.products[i].product_name+'" readonly/><input type="hidden" name="product[' + product_detail_row + '][product_id]" value="'+json.products[i].product_id+'" /><input type="hidden" name="product[' + product_detail_row + '][id]" value="'+json.products[i].id+'" /></td>';

            html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][quantity]" onblur="updatetotal()"  value="'+json.products[i].quantity+'"  /></td>';


            html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][quantitytelahterima]" onblur="updatetotal()"  value="'+json.products[i].quantityterima+'" readonly  /></td>';

            if(sisa > 0){
              html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][quantityterima]" onblur="updatetotal()"  value="'+sisa+'"  /></td>';
            }else{
                html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][quantityterima]" onblur="updatetotal()"  value="0" readonly  /></td>';
            }

            html +='</tr>';
            //net_cost += Number(json.products[i].net_cost);

            product_detail_row++;

          }
          $("#list-product-detail tbody").html(html);
          updatetotal();
        //  $("#totalqty").val(totalqty);
          //$("#net_cost").val(net_cost);

        }
      }
    })
  }


  });;

});
//--></script>
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript"><!--
    var product_detail_row = 0;
    var jenis=1;
function ubahjenis(){
  product_detail_row=0

  $("#list-product-detail tbody > tr").remove();

//  $("select[name='vendor_id']").trigger('change');
id=$(".vendor").val();

//alert(id);
if(id != undefined & id != null){

  gudang_id=$("select[name='gudang_id']").val();
$.ajax({
  url: 'index.php?route=pembelian/pembeliankredit/detailbelumdatang&token=<?php echo $this->request->get['token']; ?>&vendor_id=' + id+'&gudang_id='+gudang_id,
  dataType: 'json',
  success: function(json) {
    if(json){

      console.log(JSON.stringify(json));

      html='';
      net_cost=0;

      for(i in json.products){
        sisa=Number(json.products[i].quantity) - Number(json.products[i].quantityterima);
      //  totalqty += Number(json.products[i].quantity);
        html +='<tr id="product-detail-row"'+product_detail_row+'>';

        html +='<td><input class="pilih" type="checkbox" name="product[' + product_detail_row + '][pilih]" value="1" onchange="updatetotal()"></td>';

        html += '    <td class="right">'+json.products[i].no_po+'<input type="hidden" name="product[' + product_detail_row + '][po_id]" value="'+json.products[i].pembelian_id+'" /><input type="hidden" name="product[' + product_detail_row + '][po_product_id]" value="'+json.products[i].id+'" /></td>';

          html += '    <td class="left"><input type="text" class="form-control" class="product-name" onblur="updatetotal()"  name="product[' + product_detail_row + '][name]" value="'+json.products[i].product_name+'" readonly/><input type="hidden" name="product[' + product_detail_row + '][product_id]" value="'+json.products[i].product_id+'" /><input type="hidden" name="product[' + product_detail_row + '][id]" value="'+json.products[i].id+'" /></td>';

        html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][quantity]" onblur="updatetotal()"  value="'+json.products[i].quantity+'"  /></td>';


        html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][quantitytelahterima]" onblur="updatetotal()"  value="'+json.products[i].quantityterima+'" readonly  /></td>';

        if(sisa > 0){
          html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][quantityterima]" onblur="updatetotal()"  value="'+sisa+'"  /></td>';
        }else{
            html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][quantityterima]" onblur="updatetotal()"  value="0" readonly  /></td>';
        }

        html +='</tr>';
        //net_cost += Number(json.products[i].net_cost);

        product_detail_row++;

      }
      $("#list-product-detail tbody").html(html);
      updatetotal();
    //  $("#totalqty").val(totalqty);
      //$("#net_cost").val(net_cost);

    }
  }
})
}
  updatetotal();
  //alert(jenis);
}
function hapus(coba){
  jenisj=$("select[name='orders["+coba+"][jenispenjualan]']").val();
  id=$("select[name='orders["+coba+"][order_id]']").val();
  ref='ref-'+id+'-'+jenisj;
  //ref=
  $('tr').filter('[data-ref="'+ref+'"]').remove();
  $("#product-row"+coba).remove();
  updatetotal();
}

function updatetotal(){
//  alert('ok');

}




function simpan(){
  updatetotal();
  $(".error").remove();
  error=false;
  errinv=false;
  errdup=false;
  errkur=false;
  errleb=false;
  em='';

  cek = [];
  if(product_detail_row == 0){
    error=true;
    em+="Produk Harus Dipilih<br>";
  }

  for(i=0;i<product_detail_row;i++ ){
    if($("input[name='product["+i+"][product_id]']").val() != undefined){
      qty=$("input[name='product["+i+"][quantity]']").val();
      qtytelahterima=$("input[name='product["+i+"][quantitytelahterima]']").val();
      qtyterima=$("input[name='product["+i+"][quantityterima]']").val();

      if(Number(qtyterima) > (Number(qty)-Number(qtytelahterima))){
            error=true;
            errleb=true;
      }


    }
  }

  if($('.pilih:checked').length == 0){
    error=true;
    em+="Produk Harus Dipilih<br>";
  };

  /*if($("select[name='penerima_id']").val() == null){
    error=true;
    em+="Penerima Harus Dipilih<br>";
  }

  if($("select[name='pengangkut_id']").val() == null){
    error=true;
    em+="Pengangkut Harus Dipilih<br>";
  }

  if($("input[name='tgl_surat']").val() == ""){
    error=true;
    em+="Tanggal Surat Jalan harus diisi<br>";
  }

  if($("input[name='tgl_terima']").val() == ""){
    error=true;
    em+="Tanggal Barang Datang harus diisi<br>";
  }
  */
  if($("input[name='no_suratjalan']").val() == ""){
    error=true;
    em+="Nomor Surat Jalan harus diisi<br>";
  }

  if($("select[name='vendor_id']").val() == null){
    error=true;
    em+="Vendor Harus Dipilih<br>";
  }

  /*if($("input[name='no_faktur']").val() == ""){
    error=true;
    em+="Nomor Faktur harus diisi<br>";
  }*/
  /*if(errkur){
    em+="Terdapat produk dengan Quantity Invoice kurang dari quantity yang telah diterima<br>";
  }*/
  if(errleb){
    em+="Quantity yang diterima melebihi total quantity dipesan<br>";
  }

  /*if(errinv){
    em+="Terdapat produk dengan Quantity Invoice tidak sama dengan quantity PO (untuk PO yang belum diterima quantity invoice harus sama dengan quantity PO)<br>";
  }*/


  if(error){
    /*if(errdup){
      em+= "Terdapat duplikasi data Surat Jalan/Sales Order.<br>";
    }*/
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


//--></script>


</script>

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
