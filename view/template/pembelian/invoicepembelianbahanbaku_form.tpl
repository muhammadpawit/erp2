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
            <h3 class="box-title">Buat Invoice Pembelian Bahan Baku</h3>
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
                    <div class="col-sm-6 col-xs-12">
                  <table class="table table-responsive">
                    <tr>
                        <td>Tanggal Faktur</td>
                        <td>
                          <input type="text" class="date form-control" name="tglfaktur" value="<?php echo date('Y-m-d'); ?>" readonly>
                        </td>
                    </tr>
                    <tr>
                      <td>Jatuh Tempo</td>
                      <td>
                        <input type="text" class="date form-control" name="jatuhtempo" value="<?php echo date('Y-m-d'); ?>" readonly>

                      </td>
                    </tr>
                    <tr>
                        <td>Nomor Faktur</td>
                        <td>
                          <input type="text" class="form-control" name="no_faktur" value="" >
                        </td>
                    </tr>


                    <tr>
                      <td>Vendor</td>
                      <td>
                        <select name="vendor_id" class="vendor form-control" onchange="ubahjenis()">

                          </select>
                      </td>
                    </tr>


                    <tr>
                      <td>Metode Pembayaran</td>
                      <td>
                        <select name="metode_pembayaran" class="form-control" >
                          <option value="1" >CBD</option>
                          <option value="2" >COD</option>
                          <option value="3" >Kredit</option>
                        </select>

                      </td>
                    </tr>

                  </table>
                </div>
                <div class="col-sm-6 col-xs-12">
                  <table class="table table-responsive" >
                    <tr>
                      <td>Biaya Kirim</td>
                      <td>
                        <input type="text" class="form-control" onblur="updatetotal()" name="biayakirim" id="biayakirim" value="" >
                        <input type="hidden" class="form-control"  name="jenisstok" value="0" >
                      </td>
                    </tr>
                    <tr>
                      <td>Sub Total</td>
                      <td>
                        <input type="text" class="form-control" name="sub_total" id="sub_total" value="" readonly>
                        <input type="hidden" class="form-control" name="sub_totalpo" id="sub_totalpo" value="" readonly>
                        <input type="hidden" class="form-control" name="net_cost" id="net_cost" value="" readonly>

                      </td>
                    </tr>

                    <tr>
                      <td>Diskon</td>
                      <td>
                        <input type="text" class="form-control" onblur="updatetotal()" name="diskon" id="diskon" value="" >
                        <input type="hidden" class="form-control"  name="jenisstok" value="0" >
                      </td>
                    </tr>

                    <tr>
                      <td>Pajak</td>
                      <td>
                        <input type="text" class="form-control" name="pajak" id="pajak" value="" readonly>
                        <input type="hidden" class="form-control" name="pajakpo" id="pajakpo" value="" readonly>
                        <input type="hidden" class="form-control" name="pembulatan" id="pembulatan" value="" readonly>
                      </td>
                    </tr>

                    <tr>
                      <td>Total</td>
                      <td>
                        <input type="text" class="form-control" name="total" id="totalreal" value="" readonly>
                        <input type="hidden" class="form-control" name="totalpo" id="totalpo" value="" readonly>
                          <input type="hidden" class="form-control" name="totaltagihan" value="" >
                      </td>
                    </tr>
                    <!--tr>
                      <td>Total Ditagihkan</td>
                      <td>

                      </td>
                    </tr-->

                  </table>
                  </div>
                </div>
                <div class="row">
                  <div class="col-xs-12">
                    <table class="table table-responsive" id="list-product-detail" >
                      <thead>
                        <th></th>
                        <th>Nomor PO</th>
                        <th>Nama Produk</th>
                        <th>Qty PO <br><small>(Belum Ditagih)</small></th>
                        <th>Harga Satuan</th>
                        <th>Pajak</th>

                        <th>Jumlah</th>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>




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
$(document).ready(function() {
  $('.date').datepicker({dateFormat: 'yy-mm-dd'});
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

    $.ajax({
      url: 'index.php?route=pembelian/pembeliankreditbahanbaku/detail&token=<?php echo $this->request->get['token']; ?>&vendor_id=' + id,
      dataType: 'json',
      success: function(json) {
        if(json){

          console.log(JSON.stringify(json));

          html='';
          net_cost=0;

          for(i in json.products){

          //  totalqty += Number(json.products[i].quantity);
            html +='<tr data-ref="ref-'+id+'-'+jenisproduk+'"  id="product-detail-row"'+product_detail_row+'>';
            html +='<td><input class="pilih" type="checkbox" name="product[' + product_detail_row + '][pilih]" value="1" onchange="updatetotal()"></td>';
            html += '    <td class="right">'+json.products[i].no_po+'<input type="hidden" name="product[' + product_detail_row + '][po_id]" value="'+json.products[i].pembelian_id+'" /><input type="hidden" name="product[' + product_detail_row + '][po_product_id]" value="'+json.products[i].id+'" /></td>';
              html += '    <td class="left"><input type="text" class="form-control" class="product-name" onblur="updatetotal()"  name="product[' + product_detail_row + '][name]" value="'+json.products[i].product_name+'" readonly/><input class="form-control" type="hidden" name="product[' + product_detail_row + '][statuspenerimaan]"  value="'+json.products[i].statuspenerimaan+'" /><input type="hidden" name="product[' + product_detail_row + '][product_id]" value="'+json.products[i].product_id+'" /><input type="hidden" name="product[' + product_detail_row + '][id]" value="'+json.products[i].id+'" /></td>';
            html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][quantity]" onblur="updatetotal()"  value="'+json.products[i].quantity+'"  /><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_detail_row + '][quantityterima]" onblur="updatetotal()"  value="'+json.products[i].quantityterima+'"  /><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_detail_row + '][quantitypo]" onblur="updatetotal()"  value="'+json.products[i].quantity+'"  /></td>';
          html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][price]"  value="'+json.products[i].harga+'" /><input class="form-control" type="hidden" name="product[' + product_detail_row + '][hargapo]"  value="'+json.products[i].harga+'" /></td>';
              html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][pajak]" readonly onblur="updatetotal()"  value="'+json.products[i].ppn+'" /><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_detail_row + '][ppnpo]" readonly  value="'+json.products[i].ppn+'" /><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_detail_row + '][pembulatan]" value="'+json.products[i].pembulatan+'"  readonly/></td>';
              //html += '<td><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][pembulatan]" value="'+json.products[i].pembulatan+'"  readonly/></td>';
            html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][total]" value="'+(json.products[i].harga*json.products[i].quantity)+'" readonly /></td>';

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
jenisproduk=$("select[name='jenisproduk']").val();
if(jenisproduk != 2){
  $("#biaya-kirim").show();
}else{
  $("#biaya-kirim").hide();
}
//alert(id);
if(id != undefined & id != null){


$.ajax({
  url: 'index.php?route=pembelian/pembeliankreditbahanbaku/detail&token=<?php echo $this->request->get['token']; ?>&vendor_id=' + id,
  dataType: 'json',
  success: function(json) {
    if(json){

      console.log(JSON.stringify(json));

      html='';
      net_cost=0;

      for(i in json.products){

      //  totalqty += Number(json.products[i].quantity);
        html +='<tr data-ref="ref-'+id+'-'+jenisproduk+'"  id="product-detail-row"'+product_detail_row+'>';
        html +='<td><input class="pilih" type="checkbox" name="product[' + product_detail_row + '][pilih]" value="1" onchange="updatetotal()"></td>';
        html += '    <td class="right">'+json.products[i].no_po+'<input type="hidden" name="product[' + product_detail_row + '][po_id]" value="'+json.products[i].pembelian_id+'" /><input type="hidden" name="product[' + product_detail_row + '][po_product_id]" value="'+json.products[i].id+'" /></td>';
          html += '    <td class="left"><input type="text" class="form-control" class="product-name" onblur="updatetotal()"  name="product[' + product_detail_row + '][name]" value="'+json.products[i].product_name+'" readonly/><input type="hidden" name="product[' + product_detail_row + '][product_id]" value="'+json.products[i].product_id+'" /><input type="hidden" name="product[' + product_detail_row + '][id]" value="'+json.products[i].id+'" /></td>';
        html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][quantity]" onblur="updatetotal()"  value="'+json.products[i].quantity+'"  /><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_detail_row + '][quantityterima]" onblur="updatetotal()"  value="'+json.products[i].quantityterima+'"  /><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_detail_row + '][quantitypo]" onblur="updatetotal()"  value="'+json.products[i].quantity+'"  /></td>';
      html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][price]"  value="'+json.products[i].harga+'" /><input class="form-control" type="hidden" name="product[' + product_detail_row + '][hargapo]"  value="'+json.products[i].harga+'" /><input class="form-control" type="hidden" name="product[' + product_detail_row + '][statuspenerimaan]"  value="'+json.products[i].statuspenerimaan+'" /></td>';
          html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][pajak]" readonly onblur="updatetotal()"  value="'+json.products[i].ppn+'" /><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_detail_row + '][ppnpo]" readonly  value="'+json.products[i].ppn+'" /><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_detail_row + '][pembulatan]" value="'+json.products[i].pembulatan+'"  readonly/></td>';
          //html += '<td><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][pembulatan]" value="'+json.products[i].pembulatan+'"  readonly/></td>';
        html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][total]" value="'+(json.products[i].harga*json.products[i].quantity)+'" readonly /></td>';

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
  sub_total=0;
  diskon=Number($("#diskon").val());
  biayakirim=Number($("#biayakirim").val());
dp=Number($("#dp").val());
  pajak=0;
  grandtotal=0;
  totalpembulatan=0;

  pajakpo=0;
  sub_totalpo=0;
  totalpo=0;
  net=0;

  error=false;
  i = 0;
  //alert(product_detail_row);
  while(i < product_detail_row){
    if($("input[name='product["+i+"][pilih]']").is(":checked")){
    //alert(i);
    harga=0;
  // alert($("input[name='product["+i+"][quantity]']").val());
    if($("input[name='product["+i+"][product_id]']").val() != undefined){
  		quantity=$("input[name='product["+i+"][quantity]']").val();
      //quantity=$("input[name='product["+i+"][quantity]']").val();
      harga=$("input[name='product["+i+"][price]']").val();
      pjk=$("input[name='product["+i+"][pajak]']").val();
      net_cost=$("input[name='product["+i+"][net_cost]']").val();
      pembulatan=$("input[name='product["+i+"][pembulatan]']").val();
      sub=$("input[name='product["+i+"][total]']").val();

      //po
      hargapo=$("input[name='product["+i+"][hargapo]']").val();
      pjkpo=$("input[name='product["+i+"][ppnpo]']").val();
    //  subpo=$("input[name='product["+i+"][totalpo]']").val();

      error=false;
      if($.isNumeric( Number(quantity) ) & $.isNumeric( Number(harga) ) & $.isNumeric( Number(pajak) )){

          net += Number(quantity) * (Number(net_cost));
          tot=(Number(quantity) * Number(harga)) + ((Number(pjk)*Number(quantity))) + (Number(pembulatan)*Number(quantity));
          totalpembulatan +=Number(pembulatan)*Number(quantity);

        if(Number(pjk) > 0){
          harga=$("input[name='product["+i+"][price]']").val();
            pjk=Number(harga)*0.1;
            $("input[name='product["+i+"][pajak]']").val(pjk);
            sub_total += Number(harga)*Number(quantity);

          }else{
            sub_total += Number(harga)*Number(quantity);
          }
          sub_totalpo += Number(hargapo)*Number(quantity);
          pajak+= Number(pjk)*Number(quantity);

          pajakpo += Number(pjkpo) * Number(quantity);

          subs=(Number(harga)*Number(quantity)) + (Number(pjk)*Number(quantity));
          grandtotal +=Number(subs);
          totalpo += (Number(hargapo)*Number(quantity));
          $("input[name='product["+i+"][total]']").val(subs);

      }else{
          error=true;
          alert("Nilai quantity, pajak dan harga harus berupa angka.");
      }
    }
    }
    i++;
  }

  if(!error){
    pajak=pajak;
    $("#sub_total").val(sub_total);
    $("#sub_totalpo").val(sub_totalpo);

    $("#pajak").val(pajak);
    $("#pajakpo").val(pajakpo);

  $("#diskon").val(diskon);
    $("#total").val(grandtotal-diskon+biayakirim);
    $("#totalpo").val(totalpo);

    $("#totalreal").val(grandtotal-diskon+biayakirim);
  }
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
      qtypo=$("input[name='product["+i+"][quantitypo]']").val();
      qtyterima=$("input[name='product["+i+"][quantityterima]']").val();

      /*if(statuspenerimaan != 1){
        if(Number(qty) != Number(qtypo)){
          error=true;
          errinv=true;
        }
      }else{*/
      if(Number(qty) > Number(qtypo)){
        //if(qtyterima > 0){
          if(Number(qty) > Number(qtyterima)){
            error=true;
            errleb=true;
          //  em+="Quantity Invoice melebihi quantity PO<br>";
          }
        //}
      }
      /*if(Number(qty) < Number(qtyterima)){
        error=true;
        errkur=true;
      //  em+="Quantity Invoice kurang dari quantity yang telah diterima<br>";
    }*/
    //}
    }
  }

  if($('.pilih:checked').length == 0){
    error=true;
    em+="Produk Harus Dipilih<br>";
  };

  if($("select[name='vendor_id']").val() == null){
    error=true;
    em+="Vendor Harus Dipilih<br>";
  }

  if($("input[name='no_faktur']").val() == ""){
    error=true;
    em+="Nomor Faktur harus diisi<br>";
  }
  if(errkur){
    em+="Terdapat produk dengan Quantity Invoice kurang dari quantity yang telah diterima<br>";
  }
  if(errleb){
    em+="Terdapat produk dengan Quantity Invoice lebih dari quantity PO<br>";
  }

  if(errinv){
    em+="Terdapat produk dengan Quantity Invoice tidak sama dengan quantity PO (untuk PO yang belum diterima quantity invoice harus sama dengan quantity PO)<br>";
  }


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
