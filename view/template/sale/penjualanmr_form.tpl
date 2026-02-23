<?php echo $header; ?>
<div class="content-wrapper" id="content">
  <section class="content-header">
    <h1>

    </h1>

  </section>

  <section class="content" >
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Penjualan MR</h3>
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


                <div class="col-md-6">
                  <table class="table">
                    <tr>
                         <td>Nomor SO</td>
                         <td>
                           <select name="no_so" class="no_so form-control">

                             </select>

                         </td>
                     </tr>
                     <tr>
                         <td>Tanggal Kirim</td>
                         <td>
                           <input type="text" class="date form-control" name="date_added" value="<?php echo date('Y-m-d'); ?>" readonly>
                         </td>
                     </tr>
                    <!-- <tr>
                         <td>Sales</td>
                         <td>
                           <input type="hidden" class="form-control" name="sales" id="sales" value="" readonly>
                         </td>
                     </tr>
                     <tr>
                         <td>Customer</td>
                         <td>
                           <input type="hidden" class="customer form-control" name="customer_id" id="customer_id" value="" readonly>
                         </td>
                     </tr>
                     <tr>
                         <td>Alamat Pengiriman</td>
                         <td>
                           <input type="hidden" class="form-control" name="address_id" id="address_id" value="" readonly>
                           <input type="hidden" class="form-control" name="jenisorder" id="jenisorder" value="" readonly>
                         </td>
                     </tr>


                     <tr>
                         <td>Metode Pengiriman</td>
                         <td>
                           <input type="hidden" class="form-control" name="pengiriman" id="pengiriman" value="" readonly>

                         </td>
                     </tr>-->
                     <tr>
                          <td>Nomor Polisi</td>
                          <td>
                            <input type="text" class="form-control" name="no_pol" id="" value="" >
                          </td>
                      </tr>
                     <tr>
                          <td>Sopir</td>
                          <td>
                            <select name="sopir" class="sales form-control">

                              </select>

                          </td>
                      </tr>
                      <tr>
                            <td>Kernet 1</td>
                            <td>
                              <select name="kernet[1]" class="kernet form-control">

                                </select>

                            </td>
                        </tr>
                        <tr>
                             <td>Kernet 2</td>
                             <td>
                               <select name="kernet[2]" class="kernet form-control">

                                 </select>

                             </td>
                         </tr>
                         <tr>
                              <td>Kernet 3</td>
                              <td>
                                <select name="kernet[3]" class="kernet form-control">

                                  </select>

                              </td>
                          </tr>
                       <tr>
                           <td>Sub Total</td>
                           <td>
                             <input type="text" class="form-control" name="sub_total" id="sub_total" value="0" readonly>
                           </td>
                       </tr>
                       <tr>
                           <td>Diskon</td>
                           <td>
                             <input type="text" class="form-control" name="diskon" id="diskon" value="0" readonly>
                           </td>
                       </tr>
                       <tr>
                           <td>Pajak</td>
                           <td>
                             <input type="text" class="form-control" name="pajak" id="pajak" value="0" readonly>
                           </td>
                       </tr>
                       <tr>
                           <td>Total</td>
                           <td>
                             <input type="text" class="form-control" name="total" id="total" value="0" readonly>
                             <input type="hidden" class="form-control" name="net_cost" id="net_cost" value="0" readonly>
                           </td>
                       </tr>

                   </table>
                </div>
                <div class="col-md-6">
                  <table class="table" id="list-customer">

                  </table>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <table class="table" id="list-product" >
                    <thead>
                      <tr>
                        <th class="left">Nama Produk</th>
                        <th class="right">Harga Satuan</th>
                        <th class="right">Pajak</th>
                        <th class="right">Quantity Beli</th>
                        <th class="right">Quantity Kirim</th>
                        <th class="right">Total</th>

                      </tr>
                    </thead>
                    <?php $product_row=0;?>
                    <tbody>
                    </tbody>


                  </table>

              </div>
            </div>


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
      </form>
  </section>
</div>
</div>
<script>
$('.sidebar-menu').find('#menu-penjualan').addClass('active');
$('.sidebar-menu').find('#menu-penjualan-website').addClass('active');
$('.sidebar-menu').find('#menu-penjualan-detailorder').addClass('active');

</script>

<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});


});
//--></script>
<script type="text/javascript"><!--
    var product_row = <?php echo $product_row; ?>;

function removeproduct(){
  $('#list-product tbody').remove();
  // updatepengiriman();

  product_row=0;
  updatetotal();

}



function updatetotal(){
  //alert('test');
  total=0;
  diskonp=0;
  grandtotal=0;
  totalqty=0;
  net=0;
  error=false;

  i = 0;
  while(i < product_row){
    //alert(i);
    harga=0;
  // alert($("input[name='product["+i+"][quantity]']").val());
    if($("input[name='product["+i+"][product_id]']").val() != undefined){
  		quantity=$("input[name='product["+i+"][quantity]']").val();
      harga=$("input[name='product["+i+"][price]']").val();
      net_cost=$("input[name='product["+i+"][net_cost]']").val();
      diskonproduk=$("input[name='product["+i+"][pajak]']").val();
      //alert($("input[name='product["+i+"][weight]']").val());
      error=false;
      if($.isNumeric( Number(quantity) ) & $.isNumeric( Number(harga) )){
          total += Number(quantity) * (Number(harga));
          net += Number(quantity) * (Number(net_cost));
          diskonp += Number(quantity) * (Number(diskonproduk));
          totalqty += Number(quantity);

        $("input[name='product["+i+"][total]']").val((Number(quantity) * (Number(harga)))+(Number(quantity) * (Number(diskonproduk))));
      }else{
          error=true;
          alert("Nilai quantity dan harga harus berupa angka.");
      }
    }
    i++;
  }

  if(!error){
    pajak=Math.round(diskonp);
    grandtotal=Number(total)+Number(pajak);

    $("#sub_total").val(total);
    $("#pajak").val(pajak);
    $("#totalqty").val(totalqty);
    $("#net_cost").val(net);
    //$("#diskon").val(diskon);
    $("#diskon").val(diskonp);
    $("#total").val(grandtotal);
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
$(function(){
  $(".sales").select2({
    ajax: {
    url:"index.php?route=user/user/autocomplete&token=<?php echo $token; ?>",
    //url: "index.php?route=pamerantoko/toko/autocomplete&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        q: params.term,
        j:22 // search term

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
$(".kernet").select2({
  ajax: {
  url:"index.php?route=user/user/autocomplete&token=<?php echo $token; ?>",
  //url: "index.php?route=pamerantoko/toko/autocomplete&token=<?php echo $this->request->get['token']; ?>",
  dataType: 'json',
  data: function (params) {
    return {
      q: params.term,
      j:23 // search term

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

  $(".no_so").select2({
    ajax: {
    url:"index.php?route=sale/salesordermr/autocomplete&token=<?php echo $token; ?>",
    //url: "index.php?route=pamerantoko/toko/autocomplete&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        q: params.term, // search term

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
    //coba=$(this).data('id');
    console.log(id);
    totalqty=0;
    if(id != undefined & id != null){

    $.ajax({
      url: 'index.php?route=sale/salesordermr/detail&token=<?php echo $token; ?>&id=' + id,
      dataType: 'json',
      success: function(json) {
      if(json){

        console.log(JSON.stringify(json));
        if(json.address != null){
          detailadd=json.address.firstname+'. '+json.address.address_1+' '+json.address.city+', '+json.address.zone+', '+json.address.country+' '+json.address.postcode;
        }else{
          detailadd="";
        }
        if(json.order.pengiriman == 1){
          peng='Diambil';
        }else{
          peng='Diantar';
        }
      //  $("#sales").before("");
      html='';
      html+='<tr>';
      html +='<td>Sales: </td><td>'+json.order.namasales+'<input type="hidden" name="sales" value="'+json.order.sales+'"></td>'
      html+='</tr>';
      html+='<tr>';
      html +='<td>Gudang: </td><td>'+json.order.namagudang+'<input type="hidden" name="gudang_id" value="'+json.order.gudang_id+'"></td>'
      html+='</tr>';
      html+='<tr>';
      html +='<td>Customer: </td><td>'+json.order.name+'<input type="hidden" name="customer_id" value="'+json.order.customer_id+'"></td>'
      html+='</tr>';
      html+='<tr>';
      html +='<td>Alamat Kirim: </td><td>'+detailadd+'<input type="hidden" name="address_id" value="'+json.order.address_id+'"></td>'
      html+='</tr>';
      html+='<tr>';
      html +='<td>Metode Pengiriman: </td><td>'+peng+'<input type="hidden" name="pengiriman" value="'+json.order.pengiriman+'"></td>'
      html+='</tr>';
      $("#list-customer").html(html);
      //  $("#sub_total").val(json.order.sub_total);
      //  $("#diskon").val(json.order.diskon);
      //  $("#pajak").val(json.order.pajak);
      //  $("#total").val(json.order.total);

        html='';
        net_cost=0;
        for(i in json.products){
          if(json.products[i].no_tabung == null){
            json.products[i].no_tabung="";
          }
          totalqty += Number(json.products[i].quantity);
          html +='<tr id="product-row"'+product_row+'>';

          html += '    <td class="left"><input type="text" class="form-control" class="product-name" onblur="hitungtotal()"  name="product[' + product_row + '][name]" value="'+json.products[i].name+'" readonly/><input type="hidden" name="product[' + product_row + '][product_id]" value="'+json.products[i].product_id+'" /><input type="hidden" name="product[' + product_row + '][id]" value="'+json.products[i].id+'" /></td>';

          html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_row + '][price]" value="'+json.products[i].price+'"  readonly /></td>';
          html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_row + '][pajak]" value="'+json.products[i].pajak+'"  readonly/><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][net_cost]" value="'+json.products[i].net_cost+'"  /></td>';
          html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_row + '][quantitypesan]" value="'+json.products[i].quantity+'"  readonly/></td>';
          html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_row + '][quantity]" value="0"  /></td>';
          html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_row + '][total]" value="0"  /></td>';

          html +='</tr>';
          net_cost += 0;
          product_row++;
        }
        $("#list-product tbody").html(html);
        $("#totalqty").val(totalqty);
        $("#net_cost").val(net_cost);
      }
      else{
        alert('Data sales order tidak ditemukan');
      }
      /*
      $("input[name='product["+coba+"][price]']").val(json.price);
      $("input[name='product["+coba+"][net_cost]']").val(json.net_cost);
      $("input[name='product["+coba+"][diskon]']").val(json.diskon);
      $("input[name='product["+coba+"][quantity]']").val(1);

      total=Number($("input[name='product["+coba+"][quantity]']").val() * $("input[name='product["+coba+"][price]']").val());
      $("input[name='product["+coba+"][total]']").val(total);
      $("input[name='product["+coba+"][pajak]']").val(total*0.1);
      updatetotal();
      */
      }
    })
  }
});
})
function simpan(){
  updatetotal();
  $(".error").remove();
  error=false;
  errdup=false;
  errqty=false;
  em='';
  if($("select[name='no_so']").val() == null){
    error=true;
    em +="Nomor SO harus dipilih <br>";
  }
  //cek error data
  //alert($("select[name='sales']").val() );
  /*if($(".sales").val() == null | $(".sales").val() == undefined){
    error=true;
    em +="Sales harus diisi <br>";
  }

  if($("input[name='customer_id']").val() == null){
    error=true;
    em +="Customer harus diisi <br>";
  }

  if($("input[name='jenisorder']").val() == null){
    error=true;
    em +="Jenis Order harus diisi <br>";
  }
  */
  if(product_row == 0){
    error=true;
    em +="Produk harus dipilih <br>";
  }
  cek = [];
  for(i=0;i<product_row;i++){
    //pid=$("select[name='product["+i+"][product_id]']").val();
    qtypesan=$("input[name='product["+i+"][quantitypesan]']").val();
      qty=$("input[name='product["+i+"][quantity]']").val();
      qtyterima=$("input[name='product["+i+"][quantityterima]']").val();

      totalqty=Number(qty)+Number(qtyterima);

    /*if(qty <= 0){
      error=true;
      errqty=true;
    }*/
    if(totalqty > qtypesan){
      error=true;
      errdup=true;
    }



    /*if(pid != undefined){
  		if(cek[pid] == undefined){

  			cek[pid] = i;

  		}
  		else{
  			errdup = true;
  			error=true;
  			//alert(product_id+' '+p);
  		}
		}*/
  }

  if(error){
    if(errdup){
      em+= "Quantity Kirim melebihi quantity dibeli.<br>";
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
$(function(){

})
</script>
<?php echo $footer; ?>
