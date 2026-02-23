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
            <h3 class="box-title"><i class="fa fa-plus"></i> Tambah Return Penjualan</h3>
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
                    <!--tr>
                         <td>Nomor SO</td>
                         <td>
                           <select name="no_so" class="no_so form-control">

                             </select>

                         </td>
                     </tr-->
                     <tr>
                          <td>Gudang</td>
                          <td>
                            <select name="gudang_id" onchange="removeproduct()" class="form-control">
                              <?php
                              foreach($gudangs as $g){
                                ?>
                                  <option value="<?php echo $g['gudang_id']; ?>"><?php echo $g['nama']; ?></option>
                                <?php
                              }
                              ?>
                              </select>

                          </td>
                      </tr>
                     <tr>
                       <td>Customer</td>
                       <td>
                         <select name="customer_id" class="customer form-control">

                           </select>
                       </td>
                     </tr>
                     <tr>
                       <td>No. Sales Order</td>
                       <td>
                         <select name="no_so" class="no_so form-control">

                           </select>
                       </td>
                     </tr>
                     <tr>
                         <td>Tanggal Retur</td>
                         <td>
                           <input type="text" class="date form-control" name="date_added" value="<?php echo date('Y-m-d'); ?>" readonly>
                         </td>
                     </tr>
                     
                     <tr>
                       <td>Keterangan</td>
                       <td>
                         <input type="text" name="keterangan" class="form-control">
                       </td>
                     </tr>
                      <tr>
                          <td>Sub Total</td>
                          <td>
                            <input type="text" class="form-control" name="sub_total" id="sub_total" value="0" readonly>
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
                          </td>
                      </tr>
                      <tr>
                          <td>Total Refund</td>
                          <td>
                            <input type="text" class="form-control" name="totalrefund" id="totalrefund" value="0">
                            <input type="hidden" class="form-control" name="pajakrefund" id="pajakrefund" value="0">
                            <input type="hidden" class="form-control" name="net_cost" id="net_cost" value="0" readonly>
                          </td>
                      </tr>
                     <tr>
                        <td><span class="required">*</span>Bank/Kas Di Debet</td>
                        <td >
                        <select name="bank_id" class="form-control bank">
                          <option value="0" id="bank">Tidak Ada Refund</option>
                    		</select>
                        </td>
                      </tr>

                   </table>
                </div>
                <div class="col-md-6">

                    <table class="table alamat">
                      <

                    </table>

                  <table class="table" id="list-customer">

                  </table>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <table class="table" id="list-product" >
                    <thead>
                      <tr>
                        <th width="3"></th>
                        <th class="left" width="100">No. SO</th>
                        <th class="left" width="100">No. SJ</th>
                        <th class="left" width="100">Nama Produk</th>
                        <th class="left" width="100">Tabung</th>
                        <th class="right" width="70">Qty Kirim</th>
                        <th class="right" width="70">Qty Retur</th>
                        <th class="right" width="70">Harga</th>
                        <th class="right" width="70">Pajak</th>
                        <th class="right" width="70">Total</th>
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
var product_row = <?php echo $product_row; ?>;
$(document).ready(function() {
 
  $('.date').datepicker({dateFormat: 'yy-mm-dd',minDate:'<?php echo $locktanggal; ?>',maxDate:new Date()});

  $(".no_so").select2({
    ajax: {
    url:"index.php?route=sale/returnpenjualan/sosudahdikirim&token=<?php echo $this->request->get['token']; ?>",
      dataType: 'json',
    data: function (params) {
      //alert($(".sales").val());
      return {
        q: params.term,
        customer_id:$(".customer").val(),
        gudang_id:$("select[name='gudang_id']").val()

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
      url: 'index.php?route=sale/salesorder/detailsudahdikirim&token=<?php echo $this->request->get['token']; ?>&customer_id=' + $(".customer").val()+'&gudang_id='+gudang_id+'&no_so='+id,
      dataType: 'json',
      success: function(json) {
        console.log(JSON.stringify(json));
        html='';
        net_cost=0;
        for(i in json.products){
          if(Number(json.products[i].tabung_id) == 0){
            json.products[i].no_tabung="";
          }
          totalqty += Number(json.products[i].quantity);
          pajak=Number(json.products[i].totalpajakso)/totalqty;
          totalpajak=Number(json.products[i].totalpajakso);
          if(!totalpajak){
          //  totalpajak=Number(json.products[i].quantity)*Number(json.products[i].pajakso)
            pajak=Number(json.products[i].pajakso);
            totalpajak==Number(json.products[i].pajakso)*totalqty;
          }
          pemb=Number(json.products[i].pembulatan);
          if(!pemb){
            pemb=0;
          }
          pembulatan=pemb/totalqty;
          harga=Number(json.products[i].price);

          
          quantitymax = Number(json.products[i].quantity) - Number(json.products[i].quantityreturn);
        //  harga=harga.toFixed(2);
          //totalpajak=totalpajak.toFixed(2);
          html +='<tr id="product-row"'+product_row+'>';
          html +='<td><input class="pilih" type="checkbox" name="product[' + product_row + '][pilih]" value="1" onchange="updatetotal()"></td>';
          html += '    <td class="left">'+json.products[i].no_salesorder+'<input type="hidden" name="product[' + product_row + '][nomor_so]" value="'+json.products[i].no_salesorder+'" /><input type="hidden" name="product[' + product_row + '][statuspembayaran]" value="'+json.products[i].statuspembayaran+'" /><input type="hidden" name="product[' + product_row + '][no_so]" value="'+json.products[i].no_so+'" /></td>';
           html += '    <td class="left">'+json.products[i].no_sj+'<input type="hidden" name="product[' + product_row + '][nomor_sj]" value="'+json.products[i].no_sj+'" /><input type="hidden" name="product[' + product_row + '][no_sj]" value="'+json.products[i].sales_order_id+'" /><input type="hidden" name="product[' + product_row + '][penjualan_product_id]" value="'+json.products[i].id+'" /></td>';
          
          html += '    <td class="left"><input type="hidden" class="form-control" class="product-name" onblur="updatetotal"  name="product[' + product_row + '][name]" value="'+json.products[i].namaproduct+'" readonly/>'+json.products[i].namaproduct+'<input type="hidden" name="product[' + product_row + '][product_id]" value="'+json.products[i].product_id+'" /><input type="hidden" name="product[' + product_row + '][id]" value="'+json.products[i].id+'" /></td>';
          html += '    <td class="left"><input type="text" class="form-control" class="product-name" onblur="updatetotal()"  name="product[' + product_row + '][no_tabung]" value="'+json.products[i].no_tabung+'" readonly/><input type="hidden" name="product[' + product_row + '][tabung_id]" value="'+json.products[i].tabung_id+'" /><input type="hidden" name="product[' + product_row + '][quantityterima]" value="'+json.products[i].quantityterima+'" /><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][pajak]" value="'+totalpajak+'"  readonly/><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][pembulatan]" value="'+pembulatan+'"  readonly/><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][net_cost]" value="'+json.products[i].net_cost+'"  /></td>';

          //html += '    <td class="right"></td>';
          html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_row + '][quantitykirim]" value="'+json.products[i].quantity+'"  readonly/></td>';
          html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_row + '][quantity]" value="'+quantitymax+'"  /><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][totalpajak]" value="'+totalpajak+'"  /><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][quantitymax]" value="'+quantitymax+'"  /></td>';
          html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_row + '][price]" value="'+json.products[i].price+'" readonly /><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][net_cost]" value="'+json.products[i].net_cost+'"  /></td>';
          html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_row + '][pajak]" value="'+json.products[i].pajakso+'"  readonly /></td>';
          html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_row + '][total]" value="'+(Number(json.products[i].pajak)+Number(json.products[i].price))*quantitymax+'" readonly /><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][net_cost]" value="'+json.products[i].net_cost+'"  /></td>';
          
          
          //html += '    <td class="right"></td>';
        //  html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_row + '][total]" value="'+json.products[i].total+'"  readonly/></td>';

          html +='</tr>';
          net_cost += 0;
          product_row++;
        }
        $("#list-product tbody").html(html);
        $("#net_cost").val(net_cost);
        updatetotal();

      }
    })
  }


  });
  $(".bank").select2({
  ajax: {
  url:"index.php?route=keuangan/bank/autocomplete&token=<?php echo $this->request->get['token']; ?>",
  dataType: 'json',
  data: function (params) {
    return {
      q: params.term,
      c:1

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
  $(".customer").select2({
    ajax: {
    url:"index.php?route=sale/customer/autocomplete&token=<?php echo $this->request->get['token']; ?>",
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
  removeproduct();
});


});
//--></script>
<script type="text/javascript"><!--

function removeproduct(){
  $('#list-product tbody').empty();
  // updatepengiriman();
  product_row=0;
  updatetotal();

  /*id=$(".customer").val();
  
  if(id != undefined & id != null){
      gudang_id=$("select[name='gudang_id']").val();
    $.ajax({
      url: 'index.php?route=sale/salesorder/detailsudahdikirim&token=<?php echo $this->request->get['token']; ?>&customer_id=' + id+'&gudang_id='+gudang_id,
      dataType: 'json',
      success: function(json) {
        console.log(JSON.stringify(json));
        html='';
        net_cost=0;
        for(i in json.products){
          if(Number(json.products[i].tabung_id) == 0){
            json.products[i].no_tabung="";
          }
          totalqty += Number(json.products[i].quantity);
          pajak=Number(json.products[i].totalpajakso)/totalqty;
          totalpajak=Number(json.products[i].totalpajakso);
          if(!totalpajak){
          //  totalpajak=Number(json.products[i].quantity)*Number(json.products[i].pajakso)
            pajak=Number(json.products[i].pajakso);
            totalpajak==Number(json.products[i].pajakso)*totalqty;
          }
          pemb=Number(json.products[i].pembulatan);
          if(!pemb){
            pemb=0;
          }
          pembulatan=pemb/totalqty;
          harga=Number(json.products[i].price);

          
          quantitymax = Number(json.products[i].quantity) - Number(json.products[i].quantityreturn);
        //  harga=harga.toFixed(2);
          //totalpajak=totalpajak.toFixed(2);
          html +='<tr id="product-row"'+product_row+'>';
          html +='<td><input class="pilih" type="checkbox" name="product[' + product_row + '][pilih]" value="1" onchange="updatetotal()"></td>';
          html += '    <td class="left">'+json.products[i].no_salesorder+'<input type="hidden" name="product[' + product_row + '][nomor_so]" value="'+json.products[i].no_salesorder+'" /><input type="hidden" name="product[' + product_row + '][statuspembayaran]" value="'+json.products[i].statuspembayaran+'" /><input type="hidden" name="product[' + product_row + '][no_so]" value="'+json.products[i].no_so+'" /></td>';
           html += '    <td class="left">'+json.products[i].no_sj+'<input type="hidden" name="product[' + product_row + '][nomor_sj]" value="'+json.products[i].no_sj+'" /><input type="hidden" name="product[' + product_row + '][no_sj]" value="'+json.products[i].sales_order_id+'" /><input type="hidden" name="product[' + product_row + '][penjualan_product_id]" value="'+json.products[i].id+'" /></td>';
          
          html += '    <td class="left"><input type="hidden" class="form-control" class="product-name" onblur="updatetotal"  name="product[' + product_row + '][name]" value="'+json.products[i].namaproduct+'" readonly/>'+json.products[i].namaproduct+'<input type="hidden" name="product[' + product_row + '][product_id]" value="'+json.products[i].product_id+'" /><input type="hidden" name="product[' + product_row + '][id]" value="'+json.products[i].id+'" /></td>';
          html += '    <td class="left"><input type="text" class="form-control" class="product-name" onblur="updatetotal()"  name="product[' + product_row + '][no_tabung]" value="'+json.products[i].no_tabung+'" readonly/><input type="hidden" name="product[' + product_row + '][tabung_id]" value="'+json.products[i].tabung_id+'" /><input type="hidden" name="product[' + product_row + '][quantityterima]" value="'+json.products[i].quantityterima+'" /><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][pajak]" value="'+totalpajak+'"  readonly/><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][pembulatan]" value="'+pembulatan+'"  readonly/><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][net_cost]" value="'+json.products[i].net_cost+'"  /></td>';

          //html += '    <td class="right"></td>';
          html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_row + '][quantitykirim]" value="'+json.products[i].quantity+'"  readonly/></td>';
          html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_row + '][quantity]" value="'+quantitymax+'"  /><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][totalpajak]" value="'+totalpajak+'"  /><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][quantitymax]" value="'+quantitymax+'"  /></td>';
          html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_row + '][price]" value="'+json.products[i].price+'" readonly /><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][net_cost]" value="'+json.products[i].net_cost+'"  /></td>';
          html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_row + '][pajak]" value="'+json.products[i].pajakso+'"  readonly /></td>';
          html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_row + '][total]" value="'+(Number(json.products[i].pajak)+Number(json.products[i].price))*quantitymax+'" readonly /><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][net_cost]" value="'+json.products[i].net_cost+'"  /></td>';
          
          
          //html += '    <td class="right"></td>';
        //  html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_row + '][total]" value="'+json.products[i].total+'"  readonly/></td>';

          html +='</tr>';
          net_cost += 0;
          product_row++;
        }
        $("#list-product tbody").html(html);
        $("#net_cost").val(net_cost);
        updatetotal();

      }
    })
  }*/



}


function updatetotal(){
  //alert('test');
  total=0;
  totalrefund=0;
  pajakrefund=0;
  diskonp=0;
  grandtotal=0;
  totalqty=0;
  net=0;
  totalpembulatan=0;
  error=false;

  i = 0;
  while(i < product_row){
    //alert(i);
    harga=0;
  // alert($("input[name='product["+i+"][quantity]']").val());
    if($("input[name='product["+i+"][product_id]']").val() != undefined){
      if($("input[name='product["+i+"][pilih]']").is(":checked")){
      quantity=$("input[name='product["+i+"][quantity]']").val();
      quantitypesan=$("input[name='product["+i+"][quantitypesan]']").val();
      harga=$("input[name='product["+i+"][price]']").val();
     // totalpajak=$("input[name='product["+i+"][totalpajak]']").val();
      net_cost=$("input[name='product["+i+"][net_cost]']").val();
      diskonproduk=$("input[name='product["+i+"][pajak]']").val();
      pembulatan=$("input[name='product["+i+"][pembulatan]']").val();
      //alert($("input[name='product["+i+"][weight]']").val());
      error=false;

    statuspembayaran=$("input[name='product["+i+"][statuspembayaran]']").val();

      if($.isNumeric( Number(quantity) ) & $.isNumeric( Number(harga) )){

        totalpjk=0;
        //total +=Math.floor(Number(quantity) * (Number(harga)));
        net += Number(quantity) * (Number(net_cost));
       // diskonp +=Number(diskonproduk)*Number(quantity);
        totalqty += Number(quantity);
        subtanpapajak=Math.floor(Number(quantity) * (Number(harga)));
        if(diskonproduk > 0){
          subpajak=Math.floor(subtanpapajak*0.1);
        }else{
          subpajak=0;
        }
        diskonp +=subpajak;
        sub=subtanpapajak+subpajak;
        $("input[name='product["+i+"][total]']").val(sub);
        total +=subtanpapajak;

        if(Number(statuspembayaran) == 3){
          totalrefund +=sub; 
          pajakrefund +=subpajak;
        }

      //  sub=Math.floor((Number(quantity) * (Number(harga))) +  ((Number(totalpajak)/Number(quantitypesan))*Number(quantity)))

      }else{
          error=true;
          alert("Nilai quantity dan harga harus berupa angka.");
      }
    }
    }
    i++;
  }

  if(!error){
    pajak=Math.floor(diskonp);
    grandtotal=Math.floor(Number(total)+Number(pajak));

    $("#sub_total").val(Math.round(total));
    $("#pajak").val(pajak);
    $("#totalqty").val(totalqty);
    $("#net_cost").val(net);
    
    //$("#diskon").val(diskon);
    //$("#diskon").val(diskonp);
    $("#total").val(grandtotal);
    $("#totalrefund").val(totalrefund);
     $("#pajakrefund").val(pajakrefund);
  }
}

//--></script>




<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript"><!--
$('.date').datepicker({dateFormat: 'yy-mm-dd',minDate:'<?php echo $locktanggal; ?>',maxDate:new Date()});

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
  updatetotal();
  $(".error").remove();
  error=false;
  errdup=false;
  errmax=false;
  errqty=false;
  em='';
  if($("select[name='customer_id']").val() == null){
    error=true;
    em +="Customer harus dipilih <br>";
  }
  //cek error data
  //alert($("select[name='sales']").val() );
  if(Number($("input[name='totalrefund']").val()) > 0){
    if($("select[name='bank_id']").val() == 0){
      error=true;
      em +="Bank yang didebet harus dipilih <br>";
    }
  }
  

  if($('.pilih:checked').length == 0){
    error=true;
    em+="Produk Harus Dipilih<br>";
  };
  cek = [];
  for(i=0;i<product_row;i++){
    if($("input[name='product["+i+"][pilih]']").is(":checked")){
    //pid=$("select[name='product["+i+"][product_id]']").val();
    qtykirim=$("input[name='product["+i+"][quantitykirim]']").val();
      qty=$("input[name='product["+i+"][quantity]']").val();
      qtymax=$("input[name='product["+i+"][quantitymax]']").val();
       
      totalqty=Number(qty);

    
    if(totalqty > Number(qtymax)){
      error=true;
      errdup=true;
    }

    


  }


  }

  if(error){
    if(errdup){
      em+= "Quantity Retur melebihi quantity dikirim.<br>";
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

<script>
<?php echo $footer; ?>
