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
            <h3 class="box-title">Penawaran Harga Jual</h3>
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
                    <?php
                    if($this->user->getGroupId() != 21){
                    ?>
                    <tr>
                         <td>Sales</td>
                         <td>
                           <select name="sales" onchange="removeproduct()" class="sales form-control">

                             </select>

                         </td>
                     </tr>
                     <?php
                   }else{
                     ?>
                     <input type="hidden" name="sales" class="sales" value="<?php echo $this->user->getId(); ?>">
                     <?php
                   }
                     ?>
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
                           <select name="customer_id" onchange="removeproduct()" class="customer form-control">

                             </select>
                          <input type="hidden" name="customer_group_id" id="group" value="">
                          <input type="hidden" name="piutang" value="0">
                          <input type="hidden" name="limit_piutang" value="0">
                         </td>
                     </tr>

                   </table>
                </div>
                <div class="col-md-6">
                  <table class="table">

                     <tr>
                         <td>Jatuh Tempo Penawaran (dalam hari)</td>
                         <td>
                           <input type="text" class="form-control" name="usia" value="0" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                         </td>
                     </tr>
                     <tr>
                         <td>Sub Total</td>
                         <td>
                           <input type="text" class="form-control" name="sub_total" id="sub_total" value="0" readonly>
                         </td>
                     </tr>
                     <tr style="display:none">
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
                         </td>
                     </tr>
                  </table>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <table class="table" id="list-product" >
                    <thead>
                      <tr>
                        <th></th>
                        <th class="left">Nama Produk</th>
                        <th class="right">Harga Satuan</th>
                        <th class="right">Pajak</th>
                        <th class="right">Quantity</th>
                        <th class="right">Total</th>

                      </tr>
                    </thead>
                    <?php $product_row=0;?>


                  <tfoot>
                    <tr>
                      <td colspan="6"></td>
                      <td class="left"><a onclick="addModule();" class="btn btn-success">Tambah</a></td>
                    </tr>

                  </tfoot>
                  </table>

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
$('.sidebar-menu').find('#menu-penawaran-harga').addClass('active');
</script>

<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});


});
//--></script>
<script type="text/javascript">
    var product_row = <?php echo $product_row; ?>;
function removeproduct(){
  $('#list-product tbody').remove();
  // updatepengiriman();

  product_row=0;
  updatetotal();

}

function hapus(row){
  $('#product-row'+row).remove();
  updatetotal();
}
function updatetotal(row){
  //console.log(row);
  if(row != undefined){

    batasbawah=$("input[name='product["+row+"][batasbawah]']").val();
    harga=$("input[name='product["+row+"][price]']").val();
    console.log(harga);

    if(Number(batasbawah) > Number(harga)){
      $("input[name='product["+row+"][price]']").after('<br><small style="color:red">Harga kurang dari batasbawah</small>');
    }
  }
  //alert('test');
  total=0;
  diskonp=0;
  grandtotal=0;
  totalsub=0;
  error=false;
  totalpajak=0;
  //totalpembulatan=0;
  i = 0;
  while(i < product_row){
    //alert(i);
    harga=0;
  // alert($("input[name='product["+i+"][quantity]']").val());
    nilaipajak=$("select[name='product["+i+"][nilaipajak]']").val();
    pajak=0;
    pembulatan=0;
    hargasatuan=0;
    subtotal=0;

    if($("select[name='product["+i+"][product_id]']").val() != undefined){
  		quantity=$("input[name='product["+i+"][quantity]']").val();
      harga=$("input[name='product["+i+"][price]']").val();

    //  diskonproduk=$("input[name='product["+i+"][diskon]']").val();
      //alert($("input[name='product["+i+"][weight]']").val());
      error=false;
      if($.isNumeric( Number(quantity) ) & $.isNumeric( Number(harga) )){
          if(nilaipajak == 1){
            //subtotal=(110/100)*(Number(quantity) * (Number(harga)));

            pajak=Math.round(0.1 * (Number(quantity) * (Number(harga))));
            subtotal=Number(harga)+Number(pajak);
            totalpajak +=pajak;
            hargasatuan=harga;
            total += subtotal;
            totalsub +=(Number(quantity) * (Number(harga)));

          }
          if(nilaipajak == 2){
            hargasatuan=Math.round((100/110)*harga);
            pajak=Math.round((10/110)*harga);

          //  pembulatan=harga - (hargasatuan+pajak);
            //totalpembulatan += pembulatan*Number(quantity);
            totalpajak += (pajak*Number(quantity));

            subtotal=Number(quantity) * (Number(harga));
            total += subtotal;
            totalsub +=(Number(quantity) * (Number(hargasatuan)));
          }
          if(nilaipajak == 3){
            subtotal=Number(quantity) * (Number(harga));
            total += subtotal;
            hargasatuan=harga;
            totalsub+=(Number(quantity) * (Number(harga)));
            pajak=0;
            totalpajak +=pajak;
          }
          //diskonp += Number(quantity) * (Number(diskonproduk));
          //(Number(quantity) * (Number(diskonproduk)))

        $("input[name='product["+i+"][total]']").val(subtotal);
        //$("input[name='product["+i+"][pajak]']").val(pajak);
        //$("input[name='product["+i+"][pembulatan]']").val(pembulatan);
        //$("input[name='product["+i+"][price]']").val(hargasatuan);
      }else{
          error=true;
          alert("Nilai quantity dan harga harus berupa angka.");
      }
    }
    i++;
  }
  //alert(totalpajak);
  if(!error){
    //pajak=(total)*0.1;
    grandtotal=total;

    $("#sub_total").val(totalsub);
    $("#pajak").val(totalpajak);
    //$("#pembulatan").val(totalpembulatan);
    //$("#diskon").val(diskon);
    //$("#diskon").val(diskonp);
    $("#total").val(grandtotal);
  }
}
function addModule() {
  html  = '<tbody id="product-row' + product_row + '">';
	html += '  <tr>';
  html +='<td class="center" style="width: 3px;"><img src="view/image/delete.png" onclick="hapus('+product_row+')" style="cursor: pointer;" /></td>';

	html += '    <td class="left"><select data-id="'+product_row+'" name="product[' + product_row + '][product_id]" class="product form-control"></select></td>';
  html += '    <td class="right"><input type="text" class="form-control" name="product[' + product_row + '][price]" onblur="updatetotal('+product_row+')" value="" /><input type="hidden" name="product[' + product_row + '][net_cost]" value="" readonly/><input type="hidden" name="product[' + product_row + '][pricelist]" value="" readonly/><input type="hidden" name="product[' + product_row + '][batasbawah]" value="" readonly/>';
  html +='<input type="hidden" class="form-control" name="product[' + product_row + '][pajak]"  value="" /></td>';
  html += '<td><select class="form-control" name="product[' + product_row + '][nilaipajak]" onchange="updatetotal('+product_row+')">';
  html += '<option value="1" >Belum Termasuk</option>';
  html += '<option value="2" >Sudah Termasuk</option>';
  html += '<option value="3" >Tanpa Pajak</option>';
  html += '</select></td>';
//  htm\ += '    <td class="right"><input type="text" class="form-control" name="product[' + product_row + '][diskon]" onblur="updatetotal()" value="" /></td>';
  html += '    <td class="right"><input type="text" class="form-control" name="product[' + product_row + '][quantity]" onblur="updatetotal()" value="" /></td>';
  html += '    <td class="right"><input type="text" class="form-control" name="product[' + product_row + '][total]" value="" readonly/></td>';
	html += '  </tr>';
	html += '</tbody>';

	$('#list-product tfoot').before(html);

	product_row++;

  $(function(){
    $(".product").select2({
        ajax: {
        url:"index.php?route=catalog/product/autocompleteprod&token=<?php echo $this->request->get['token']; ?>",
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
      }).on("select2:select",function(e){
          //console.log(e);
          //console.log($(this).val());
          id=$(this).val();
          coba=$(this).data('id');
          if(id != undefined & id != null){
            customer_group_id=$("#group").val();
            customer_id=$('select[name=\'customer_id\']').val()
            gudang_id=$('select[name=\'gudang_id\']').val();
          $.ajax({
            url: 'index.php?route=catalog/productgudang/detail&token=<?php echo $token; ?>&product_id=' + id+'&customer_group_id='+customer_group_id+'&gudang_id='+gudang_id+'&customer_id='+customer_id,
            dataType: 'json',
            success: function(json) {
            console.log(JSON.stringify(json));
            $("input[name='product["+coba+"][price]']").val(json.lastprice);
            $("input[name='product["+coba+"][pricelist]']").val(json.pricelist);
            $("input[name='product["+coba+"][batasbawah]']").val(json.batasbawah);
            $("input[name='product["+coba+"][net_cost]']").val(json.detail.net_cost);
            //$("input[name='product["+coba+"][diskon]']").val(json.diskon);
            $("input[name='product["+coba+"][quantity]']").val(1);

            total=Number($("input[name='product["+coba+"][quantity]']").val() * $("input[name='product["+coba+"][price]']").val());
            $("input[name='product["+coba+"][total]']").val(total);
            $("input[name='product["+coba+"][pajak]']").val(total*0.1);
            /*if(json.detail.jenistabung > 0){
              $("input[name='product["+coba+"][quantity]']").prop('readonly',true);
              $("input[name='product["+coba+"][jenistabung]']").val(json.detail.jenistabung);
            }*/
            updatetotal();

            }
          })
        }
    })
    /*$(".tabung").select2({
        ajax: {
        url:"index.php?route=catalog/tabungmp/autocomplete&token=<?php echo $this->request->get['token']; ?>",
          dataType: 'json',
        data: function (params) {
          row=$(this).data('row');
          jenisgas=$("select[name='product["+row+"][product_id]']").val();
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
    })*/

})
}
</script>




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
  updatetotal();
  $(".error").remove();
  error=false;
  errdup=false;
  errduptb=false;
  errreqtb=false;
  errqty=false;
  em='';

  //cek error data
  //alert($("select[name='sales']").val() );
  if($(".sales").val() == null | $(".sales").val() == undefined){
    error=true;
    em +="Sales harus diisi <br>";
  }

  /*if($("select[name='address_id']").val() == null | $("select[name='address_id']").val() == undefined){
    error=true;
    em +="Alamat harus dipilih <br>";
  }

  if($("select[name='metode_pembayaran']").val() == null | $("select[name='metode_pembayaran']").val() == undefined){
    error=true;
    em +="Metode Pembayaran harus dipilih <br>";
  }*/

  if($("select[name='customer_id']").val() == null){
    error=true;
    em +="Customer harus diisi <br>";
  }
  if($("input[name='usia']").val() == 0){
    error=true;
    em +="Jatuh Tempo harus diisi <br>";
  }

  /*if($("select[name='jenisorder']").val() == null){
    error=true;
    em +="Jenis Order harus diisi <br>";
  }*/

  if(product_row == 0){
    error=true;
    em +="Produk harus dipilih <br>";
  }
  cek = [];
  cektb=[];
  for(i=0;i<product_row;i++){
  //  pid=$("select[name='product["+i+"][product_id]']").val();
  //  tabung_id=$("select[name='product["+i+"][tabung_id]']").val();
    qty=$("input[name='product["+i+"][quantity]']").val();
  //  jenistabung=$("input[name='product["+i+"][jenistabung]']").val();

    if(qty <= 0){
      error=true;
      errqty=true;
    }


  }

  if(error){
    if(errduptb){
      em+= "Terdapat duplikasi data tabung.<br>";
    }
    if(errdup){
      em+= "Terdapat duplikasi data produk.<br>";
    }
    if(errqty){
      em +=" Quantity produk harus lebih dari 0";
    }
    /*if(errreqtb){
      em +=" Tabung Gas harus dipilih";
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
$(function(){
  updatetotal();
  $(".sales").select2({
    ajax: {
    url:"index.php?route=user/user/autocomplete&token=<?php echo $this->request->get['token']; ?>",
      dataType: 'json',
    data: function (params) {
      return {
        q: params.term,
        j:21

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

  $(".customer").select2({
    ajax: {
    url:"index.php?route=sale/customer/autocomplete&token=<?php echo $this->request->get['token']; ?>",
      dataType: 'json',
    data: function (params) {
      //alert($(".sales").val());
      return {
        q: params.term,
        s:$(".sales").val()

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
      $.ajax({
        url: 'index.php?route=sale/customer/detail&token=<?php echo $token; ?>&customer_id=' + id,
        dataType: 'json',
        success: function(json) {
          //alert(JSON.stringify(json));
          $('input[name=\'customer_group_id\']').val(json.customer_group_id);

          /*mp ='<option value="1">Tunai</option>';
          mp +='<option value="2">COD</option>';
          if(Number(json.piutang) < Number(json.limit_piutang)){
            mp +='<option value="3">Kredit</option>';
          }
          $('select[name=\'metode_pembayaran\']').html(mp);
          $("input[name='piutang']").val(json.piutang);
          $("input[name='limit_piutang']").val(json.limit_piutang);*/
        }
      })
    }


    });


  $(".address").select2({
    ajax: {
    url:"index.php?route=sale/customer/autocompleteaddress&token=<?php echo $this->request->get['token']; ?>",
      dataType: 'json',
    data: function (params) {
      return {
        q: params.term,
        customer_id:$('.customer').val()

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




})
</script>
<?php echo $footer; ?>
