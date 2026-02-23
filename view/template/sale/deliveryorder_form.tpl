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
            <h3 class="box-title">Delivery Order</h3>
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
                     <!--tr>
                       <td>Customer</td>
                       <td>
                         <select name="customer_id" class="customer form-control">

                           </select>
                       </td>
                     </tr-->
                     <tr>
                         <td>Tanggal Kirim</td>
                         <td>
                           <input type="text" class="date form-control" name="date_added" value="<?php echo date('Y-m-d'); ?>" readonly>
                         </td>
                     </tr>
                     <tr>
                         <td>Total Tabung Dikirim</td>
                         <td>
                           <input type="text" class="form-control" name="totaltabung" value="0" readonly>
                         </td>
                     </tr>
                     <tr>
                         <td>Total SJ Dikirim</td>
                         <td>
                           <input type="text" class="form-control" name="totalsj" value="0" readonly>
                         </td>
                     </tr>
                     <tr>
                         <td>Metode Pengiriman</td>
                         <td>
                           <select class="form-control pengiriman" name="pengiriman">
                             <option value="2">Diantar</option>
                             <option value="1">Diambil</option>
                           </select>
                           </td>
                     </tr>

                     
                      
                   </table>
                </div>
                <div class="col-md-6">

                    <table class="table alamat">
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
                              <select name="kernet1" class="kernet form-control">

                                </select>

                            </td>
                        </tr>
                        <tr>
                             <td>Kernet 2</td>
                             <td>
                               <select name="kernet2" class="kernet form-control">

                                 </select>

                             </td>
                         </tr>
                         <tr>
                              <td>Kernet 3</td>
                              <td>
                                <select name="kernet3" class="kernet form-control">

                                  </select>
                              </td>
                          </tr>

                    </table>

                 
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">

                  <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#detail" data-toggle="tab">Daftar SJ</a></li>
                        <li><a href="#tabung-list" data-toggle="tab">No. Tabung</a></li>


                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active " id="detail">
                            <table class="table" id="list-product" >
                                <thead>
                                <tr>
                                    <!--th class="left">No. SO</th-->
                                    <th></th>
                                    <th class="left">No. SJ</th>
                                    <th class="left">Nama Customer</th>
                                    <!--th class="right">Quantity Kirim</th-->

                                </tr>
                                </thead>
                                <?php $product_row=0;?>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                    <td ></td>
                                    <td ></td>
                                    <td class="left"><a onclick="addSuratjalan();" class="btn btn-success">Tambah</a></td>
                                    </tr>

                                </tfoot>

                            </table>
                        </div>
                        <div class="tab-pane" id="tabung-list">
                           
                                <table class="table" id="list-tabung">
                                    <thead>
                                      <tr>
                                        <th></th>
                                        <th class="left">No. Tabung</th>
                                        <th class="right">Jenis Gas</th>
                                        <th class="right">Tutup</th>
                                        <th class="right">Keterangan</th>

                                      </tr>
                                    </thead>
                                <?php $tabung_row=0;?>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr>
                                    <td ></td>
                                    <td ></td>
                                    <td ></td>
                                    <td ></td>
                                    <td class="left"><a onclick="addModule();" class="btn btn-success">Tambah</a></td>
                                    </tr>

                                </tfoot>
                                </table>
                        </div>
                    </div>
                </div>

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

<script>
var tabung_row='<?php echo $tabung_row;?>';
var product_row='<?php echo $product_row;?>';

function updatetotaltabung(){
  totaltabung=0;
  for(i=0;i<tabung_row;i++){
    tid=$("select[name='tabung["+i+"][tabung_id]']").val();
    if(tid != undefined){
      totaltabung +=1;
    }


  }
  $("input[name='totaltabung']").val(totaltabung);
}
function updatetotalsj(){
  totalsj=0;
  for(i=0;i<product_row;i++){
    tid=$("select[name='product["+i+"][sj_id]']").val();
    if(tid != undefined){
      totalsj +=1;
    }


  }
  $("input[name='totalsj']").val(totalsj);
}
function hapus(row){
  $('#tabung-row'+row).remove();
  tabung=[];
  updatetotaltabung();
  
}
function hapussj(row){
  $('#product-row'+row).remove();
  updatetotalsj();
  
}
function updatetabung(pid){
  $('.producttabung'+pid).remove();
  updatetotaltabung();
}

function addModule() {
   html  = '<tbody id="tabung-row' + tabung_row + '">';
	html += '  <tr>';
  html +='<td class="center" style="width: 3px;"><img src="view/image/delete.png" onclick="hapus('+tabung_row+')" style="cursor: pointer;" /></td>';
	html += '    <td class="left"><select data-id="'+tabung_row+'" name="tabung[' + tabung_row + '][tabung_id]" class="tabung form-control"></select><input type="hidden" name="pemilik" value="1"></td>';
	html += '    <td class="right" id="ukuran'+tabung_row+'"></td>';
  html += '    <td class="right"><select class="form-control" name="tabung[' + tabung_row + '][tutup]"><option value="1">Dengan Tutup</option><option value="2">Tanpa Tutup</option></select></td>';
  html += '    <td class="right"><input type="text" class="form-control" name="tabung[' + tabung_row + '][keterangan]" value="" /></td>';
	html += '  </tr>';
	html += '</tbody>';

	$('#list-tabung tfoot').before(html);

	tabung_row++;

  $(function(){

    $(".tabung").select2({
        ajax: {
        url:"index.php?route=catalog/tabungmp/autocomplete&token=<?php echo $this->request->get['token']; ?>",
          dataType: 'json',
        data: function (params) {
          row=$(this).data('row');
          //jenisgas=$("input[name='product_id']").val();
          //alert(jenisgas);
          return {
            q: params.term,
            statustabung:1,
            //jenisgas:jenisgas,
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
      }).on("select2:select",function(e){
        //console.log(e);
        //console.log($(this).val());

        id=$(this).val();
        coba=$(this).data('id');
        if(id != undefined & id != null){
          $.ajax({
            url: 'index.php?route=catalog/tabungmp/detail&token=<?php echo $token; ?>&product_id=' + id,
            dataType: 'json',
            success: function(json) {
            console.log(JSON.stringify(json));
            found=false;
           
            /*for(i=0;i<product_row;i++){
              if($("input[name='product["+i+"][pilih]']").is(":checked")){
                pid=$("input[name='product["+i+"][product_id]']").val();
                if(json.product_id == pid){
                  found=true;
                }
      

              


              }


            }
            if(!found){
              alert("Jenis gas tidak ditemukan pada surat jalan yang dipilih");
              hapus(coba);
            }else{
              $("#tabung-row"+coba).addClass("producttabung"+json.product_id);
              $("#ukuran"+coba).text(json.namaproduct);
              updatetotaltabung();
            }*/
            tabung=[];
            for(i=0;i<tabung_row;i++){
              tid=$("select[name='tabung["+i+"][tabung_id]']").val();
              //alert(tid);
              if(tid != undefined){
                if(tabung[tid] != undefined){
                  found=true;
                
                }else{
                  tabung[tid]=1;
                }
              }


            }
            //alert(found);

            if(found){
              alert("Terdapat Duplikasi Nomor Tabung");
              hapus(coba);
              tabung=[];
            }else{
               $("#tabung-row"+coba).addClass("producttabung"+json.product_id);
                $("#ukuran"+coba).text(json.namaproduct);
                updatetotaltabung();
            }
           


            }
          })
      }
  })


})
}

function addSuratjalan() {
   html  = '<tbody id="product-row' + product_row + '">';
	html += '  <tr>';
  html +='<td class="center" style="width: 3px;"><img src="view/image/delete.png" onclick="hapussj('+product_row+')" style="cursor: pointer;" /></td>';
	html += '    <td class="left"><select style="width:100%" data-id="'+product_row+'" name="product[' + product_row + '][sj_id]" class="suratjalan form-control"></select></td>';
	html += '    <td class="right"><span id="customer'+product_row+'"></span><input type="hidden" class="form-control" name="product[' + product_row + '][customer_id]" value="" /></td>';
	html += '  </tr>';
	html += '</tbody>';

	$('#list-product tfoot').before(html);

	product_row++;

  $(function(){

    $(".suratjalan").select2({
        ajax: {
        url:"index.php?route=sale/penjualan/autocompletebelumdo&token=<?php echo $this->request->get['token']; ?>",
          dataType: 'json',
        data: function (params) {
          row=$(this).data('row');
          return {
            q: params.term,
            gudang:$("select[name='gudang_id']").val()
            


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
        coba=$(this).data('id');
        if(id != undefined & id != null){
          $.ajax({
            url: 'index.php?route=sale/penjualan/detail&token=<?php echo $token; ?>&id=' + id+'&j=3',
            dataType: 'json',
            success: function(json) {
            console.log(JSON.stringify(json));
            foundsj=false;
            sj=[];
            for(i=0;i<product_row;i++){
              pid=$("select[name='product["+i+"][sj_id]']").val();
              if(pid != undefined){
                if(sj[pid] != undefined){
                  foundsj=true;
                
                }else{
                  sj[pid]=1;
                }
              }
      

            }
            /*for(i=0;i<product_row;i++){
              if($("input[name='product["+i+"][pilih]']").is(":checked")){
                pid=$("input[name='product["+i+"][product_id]']").val();
                if(json.product_id == pid){
                  found=true;
                }
      

              


              }


            }
            if(!found){
              alert("Jenis gas tidak ditemukan pada surat jalan yang dipilih");
              hapus(coba);
            }else{
              $("#tabung-row"+coba).addClass("producttabung"+json.product_id);
              $("#ukuran"+coba).text(json.namaproduct);
              updatetotaltabung();
            }
            */
            if(foundsj){
              alert("Terdapat duplikasi nomor surat jalan");
              hapussj(coba);
            }else{
               $("input[name='product["+coba+"][customer_id]']").val(json.order.customer_id);
              $("#customer"+coba).text(json.order.name);
              updatetotalsj();
            }

           
            }
          })
      }
  })


})
}
</script>
<script type="text/javascript"><!--

$(document).ready(function() {
  
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
  
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
     id=$(this).val();
   if(id != undefined & id != null){
      gudang_id=$("select[name='gudang_id']").val();
    $.ajax({
      url: 'index.php?route=sale/penjualan/detailBelumDo&token=<?php echo $this->request->get['token']; ?>&customer_id=' + id+'&gudang_id='+gudang_id,
      dataType: 'json',
      success: function(json) {
        console.log(JSON.stringify(json));
        html='';
        net_cost=0;
        for(i in json.products){
          if(json.products[i].no_tabung == null){
            json.products[i].no_tabung="";
          }
       
          html +='<tr id="product-row"'+product_row+'>';
          html +='<td><input class="pilih" type="checkbox" name="product[' + product_row + '][pilih]" value="1" onchange="updatetabung('+json.products[i].product_id+')"></td>';
          html += '    <td class="left">'+json.products[i].no_salesorder+'<input type="hidden" name="product[' + product_row + '][sj_id]" value="'+json.products[i].sales_order_id+'" /><input type="hidden" name="product[' + product_row + '][sjproduct_id]" value="'+json.products[i].id+'" /><input type="hidden" name="product[' + product_row + '][quantityterima]" value="'+json.products[i].quantityterima+'" /></td>';
          html += '    <td class="left">'+json.products[i].no_sj+'<input type="hidden" name="product[' + product_row + '][no_so]" value="'+json.products[i].no_so+'" /></td>';
          
          html += '    <td class="left"><input type="text" class="form-control" class="product-name" onblur="updatetotal"  name="product[' + product_row + '][name]" value="'+json.products[i].namaproduct+'" readonly/><input type="hidden" name="product[' + product_row + '][product_id]" value="'+json.products[i].product_id+'" /><input type="hidden" name="product[' + product_row + '][id]" value="'+json.products[i].id+'" /></td>';
          html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()" readonly  name="product[' + product_row + '][quantity]" value="'+json.products[i].quantity+'"  /></td>';
         
          html +='</tr>';
         
          product_row++;
        }
        $("#list-product tbody").html(html);
       
        //updatetotal();

      }
    })
  }


  });


});
//--></script>
<script type="text/javascript"><!--


function removeproduct(){
  $('#list-product tbody').empty();
  $('#list-tabung tbody').empty();
 product_row=0;
 tabung_row=0;
 

  id=$(".customer").val();
  if(id != undefined & id != null){
    gudang_id=$("select[name='gudang_id']").val();
    $.ajax({
      url: 'index.php?route=sale/penjualan/detailBelumDo&token=<?php echo $this->request->get['token']; ?>&customer_id=' + id+'&gudang_id='+gudang_id,
      dataType: 'json',
      success: function(json) {
        console.log(JSON.stringify(json));
        html='';
        net_cost=0;
        for(i in json.products){
          if(json.products[i].no_tabung == null){
            json.products[i].no_tabung="";
          }
       
          html +='<tr id="product-row"'+product_row+'>';
          html +='<td><input class="pilih" type="checkbox" name="product[' + product_row + '][pilih]" value="1" onchange="updatetabung('+json.products[i].product_id+')"></td>';
          html += '    <td class="left">'+json.products[i].no_salesorder+'<input type="hidden" name="product[' + product_row + '][sj_id]" value="'+json.products[i].sales_order_id+'" /><input type="hidden" name="product[' + product_row + '][sjproduct_id]" value="'+json.products[i].id+'" /><input type="hidden" name="product[' + product_row + '][quantityterima]" value="'+json.products[i].quantityterima+'" /></td>';
          html += '    <td class="left">'+json.products[i].no_sj+'<input type="hidden" name="product[' + product_row + '][no_so]" value="'+json.products[i].no_so+'" /></td>';
          
          html += '    <td class="left"><input type="text" class="form-control" class="product-name" onblur="updatetotal"  name="product[' + product_row + '][name]" value="'+json.products[i].namaproduct+'" readonly/><input type="hidden" name="product[' + product_row + '][product_id]" value="'+json.products[i].product_id+'" /><input type="hidden" name="product[' + product_row + '][id]" value="'+json.products[i].id+'" /></td>';
          html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()" readonly  name="product[' + product_row + '][quantity]" value="'+json.products[i].quantity+'"  /></td>';
         
          html +='</tr>';
         
          product_row++;
        }
        $("#list-product tbody").html(html);
       
       

      }
    })
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
    url:"index.php?route=sale/salesorder/autocomplete&token=<?php echo $token; ?>",
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
      url: 'index.php?route=sale/salesorder/detail&token=<?php echo $token; ?>&id=' + id,
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
     
        html='';
        net_cost=0;
        for(i in json.products){
          if(json.products[i].no_tabung == null){
            json.products[i].no_tabung="";
          }
          totalqty += Number(json.products[i].quantity);
          pajak=Number(json.products[i].totalpajak)/totalqty;
          totalpajak=Number(json.products[i].totalpajak);
          if(!totalpajak){
          //  totalpajak=Number(json.products[i].quantity)*Number(json.products[i].pajak)
            pajak=Number(json.products[i].pajak);
            totalpajak==Number(json.products[i].pajak)*totalqty;
          }
          pemb=Number(json.products[i].pembulatan);
          if(!pemb){
            pemb=0;
          }
          pembulatan=pemb/totalqty;
          harga=Number(json.products[i].price);

          if(Number(json.products[i].quantitymax) > Number(json.products[i].quantity)){
            quantitymax=Number(json.products[i].quantity);
          }else{
            quantitymax=Number(json.products[i].quantitymax);
          }
        //  harga=harga.toFixed(2);
          //totalpajak=totalpajak.toFixed(2);
          html +='<tr id="product-row"'+product_row+'>';

          html += '    <td class="left"><input type="text" class="form-control" class="product-name" name="product[' + product_row + '][name]" value="'+json.products[i].name+'" readonly/><input type="hidden" name="product[' + product_row + '][product_id]" value="'+json.products[i].product_id+'" /><input type="hidden" name="product[' + product_row + '][id]" value="'+json.products[i].id+'" /></td>';
          html += '    <td class="left"><input type="text" class="form-control" class="product-name"   name="product[' + product_row + '][no_tabung]" value="'+json.products[i].no_tabung+'" readonly/><input type="hidden" name="product[' + product_row + '][tabung_id]" value="'+json.products[i].tabung_id+'" /><input type="hidden" name="product[' + product_row + '][quantityterima]" value="'+json.products[i].quantityterima+'" /><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][pajak]" value="'+totalpajak+'"  readonly/><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][pembulatan]" value="'+pembulatan+'"  readonly/><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][net_cost]" value="'+json.products[i].net_cost+'"  /></td>';

          //html += '    <td class="right"></td>';
          html += '    <td class="right"><input class="form-control" type="text"  name="product[' + product_row + '][quantitypesan]" value="'+json.products[i].quantity+'"  readonly/><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][price]" value="'+harga+'"  readonly /></td>';
          html += '    <td class="right"><input class="form-control" type="text"  name="product[' + product_row + '][quantity]" value="'+quantitymax+'"  /><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][totalpajak]" value="'+totalpajak+'"  /><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][total]" value="'+json.products[i].total+'"  readonly/><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][quantitymax]" value="'+json.products[i].quantitymax+'"  /></td>';
          //html += '    <td class="right"></td>';
        //  html += '    <td class="right"><input class="form-control" type="text"   name="product[' + product_row + '][total]" value="'+json.products[i].total+'"  readonly/></td>';

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
     
      }
    })
  }
});
})
function simpan(){
  $(".error").remove();
  error=false;
  errdup=false;
  errmax=false;
  errqty=false;
  em='';
  
  /*totalqty=0;
  for(i=0;i<product_row;i++){
    if($("input[name='product["+i+"][pilih]']").is(":checked")){
      qty=$("input[name='product["+i+"][quantity]']").val();
      
      totalqty +=Number(qty);

    


    }


  }

  totaltabung=0;
  cek=[];
  for(i=0;i<tabung_row;i++){
    tid=$("select[name='tabung["+i+"][tabung_id]']").val();
    if(tid != undefined){
      totaltabung +=1;
   
      if(cek[tid] == undefined){
        cek[tid]=1;
      }else{
        errdup=true;
      }
    }


  }
  
  if(totalqty != totaltabung){
    error=true;
    em +="Jumlah tabung yang dikirim tidak sama dengan jumlah produk";
  }*/
  
  totaltabung=0;
  cek=[];
  for(i=0;i<tabung_row;i++){
    tid=$("select[name='tabung["+i+"][tabung_id]']").val();
    if(tid != undefined){
      if(cek[tid] == undefined){
        cek[tid]=1;
      }else{
      error=true;
        errdup=true;
      }
    }


  }

  totaltabung=$("input[name='totaltabung']").val();
  if(Number(totaltabung) < 1){
    error=true;
    em += "Jumlah tabung yang dikirim harus lebih dari 0<br>";
  }

  totalsj=$("input[name='totalsj']").val();

  if(Number(totalsj) < 1){
      error=true;
      em += "Jumlah surat jalan yang dikirim harus lebih dari 0<br>";
    }


  if(error){
    if(errdup){
      em+= "Terdapat nomor tabung yang duplikat.<br>";
    }
    if(errqty){
      em +=" Quantity produk harus lebih dari 0";
    }
    if(errmax){
      em +=" Quantity produk melebihi stok yang tersedia";
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
