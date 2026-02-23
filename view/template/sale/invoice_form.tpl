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
            <h3 class="box-title">Buat Invoice</h3>
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
                        <td>Tanggal</td>
                        <td>
                          <input type="text" class="date form-control" name="date_added" value="<?php echo date('Y-m-d'); ?>" readonly>
                        </td>
                    </tr>
                    <tr>
                         <td>Gudang</td>
                         <td>
                           <select name="gudang_id" onchange="ubahjenis()" class="form-control">
                             <option value="0">Tanpa Gudang</option>
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
                        <select name="customer_id" class="customer form-control" onchange="ubahjenis()">

                          </select>
                      </td>
                    </tr>
                    <!--tr>
                      <td>Jenis Invoice</td>
                      <td>
                        <select name="jenisinvoice" class="form-control" onchange="ubahjenis()">
                          <option value="1">Proforma Invoice</option>
                          <option value="2">Invoice DP</option>
                          <option value="3">Invoice</option>
                        </select>
                      </td>
                    </tr>
                    <tr-->
                      <td>Jenis Penjualan</td>
                      <td>
                        <select name="jenispenjualan" class="form-control" onchange="ubahjenis()">
                          <option value="1">Produk Dagang & Produksi</option>
                          <option value="3">Penjualan Bahan Baku</option>
                        </select>
                      </td>
                    </tr>
                    <!--tr>
                      <td>Referensi <br>
                        <small>Untuk Proforma Invoice dan Invoice DP menggunakan Sales Order sebagai referensi.</small>
                        <br>
                          <small>Untuk Invoice menggunakan surat jalan sebagai referensi.</small>
                      </td>
                      <td>
                        <select name="referensi" class="order form-control"></select>
                      </td>
                    </tr-->

                    <tr>
                      <td>Metode Pembayaran</td>
                      <td>
                        <select name="metode_pembayaran" class="form-control" >

                        </select>
                        <input type="hidden" name="piutang" value="0">
                        <input type="hidden" name="limit_piutang" value="0">
                      </td>
                    </tr>
                    <tr>
                      <td>Jatuh Tempo</td>
                      <td>
                        <select name="jatuh_tempo" class="form-control" >

                        </select>
                        <!--input type="text" class="form-control" readonly name="usia" value="0" id="usia" -->

                      </td>
                    </tr>
                  </table>
                </div>
                <div class="col-sm-6 col-xs-12">
                  <table class="table table-responsive" >

                    <tr>
                      <td>Sub Total</td>
                      <td>
                        <input type="text" class="form-control" name="sub_total" id="sub_total" value="" readonly>
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
                      <td>Uang Muka Telah Diterima</td>
                      <td>
                        <input type="text" class="form-control" name="dp" id="dp" value="0" readonly>
                        <!-- <input type="text" class="form-control" name="dp" id="dp" onblur="updatetagihan()"> -->
                      </td>
                    </tr>
                    <tr>
                      <td>Pajak</td>
                      <td>
                        <input type="text" class="form-control" name="pajak" id="pajak" value="" readonly>
                        <input type="hidden" class="form-control" name="pembulatan" id="pembulatan" value="" readonly>
                      </td>
                    </tr>
                    <!--tr>
                      <td>Pembulatan</td>
                      <td>
                        <input type="text" class="form-control" name="pembulatan" id="pembulatan" value="" readonly>
                      </td>
                    </tr-->
                    <tr>
                      <td>Total</td>
                      <td>
                        <input type="text" class="form-control" name="total" id="totalreal" value="" readonly>
                      </td>
                    </tr>
                    <tr>
                      <td>Total Ditagihkan</td>
                      <td>
                        <input type="text" class="form-control" id="totaltagihan" name="totaltagihan" value="" >
                      </td>
                    </tr>

                  </table>
                  </div>
                </div>
                <div class="row">
                  <div class="col-xs-12">
                    <table class="table table-responsive" id="list-product-detail" >
                      <thead>
                        <th></th>
                        <th>No. SJ</th>
                        <th>No. SO</th>
                        <th>Nama Produk</th>
                        <th>Qty</th>
                        <th>Satuan</th>
                        <th>Harga Satuan</th>
                        <th>Pajak</th>
                        <th>Diskon</th>
                        <!--th>Pembulatan</th-->

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
$('.sidebar-menu').find('#menu-penjualan').addClass('active');
$('.sidebar-menu').find('#menu-penjualan-website').addClass('active');
$('.sidebar-menu').find('#menu-penjualan-detailorder').addClass('active');

function updatetagihan(){
  var total = $("#totalreal").val();
  var dp = $("#dp").val();
  $("#totaltagihan").val(Number(total-dp));
}
</script>

<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd',minDate:'<?php echo $locktanggal; ?>',maxDate:new Date()});

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
  console.log($(this).val());
  id=$(this).val();
  jenisj=$("select[name='jenispenjualan']").val();
  gudang_id=$("select[name='gudang_id']").val();
  if(jenisj == 1){
    url='index.php?route=sale/penjualan/detailtanpainvoice&token=<?php echo $this->request->get['token']; ?>&id=' + id+'&gudang_id='+gudang_id;
  }
  if(jenisj == 2){
    url='index.php?route=sale/penjualanmr/detail&token=<?php echo $this->request->get['token']; ?>&id=' + id;
  }
  if(jenisj == 3){
    url ='index.php?route=sale/penjualanbahanbaku/detail&token=<?php echo $this->request->get['token']; ?>&id=' + id;
  }
  $.ajax({
    url: url,
    dataType: 'json',
    success: function(json) {
    if(json){

      console.log(JSON.stringify(json));

      /*$("input[name='usia']").val(json.order.usia);
      $("input[name='jenisstok']").val(json.order.jenisstok);
      $("input[name='gudang_id']").val(json.order.gudang_id);
      */
      html='';
      net_cost=0;
      sisa=0;
      jumlah=0;
      for(i in json.products){
        sisa=Number(json.products[i].quantity)-Number(json.products[i].quantityreturn);
        if(Number(json.products[i].pajak) > 0){
        pajak= Math.floor((10/100)*(Number(json.products[i].price)*Number(sisa)));
        json.products[i].pajak=pajak;
      }
      jumlah=Math.floor((Number(json.products[i].quantity)-Number(json.products[i].quantityreturn))*Number(json.products[i].price)+Number(json.products[i].pajak));
      //jumlah=json.products[i].total;
      console.log(jumlah);
      
      //  totalqty += Number(json.products[i].quantity);
        html +='<tr data-ref="ref-'+id+'-'+jenisj+'"  id="product-detail-row"'+product_detail_row+'>';
        html +='<td><input class="pilih" type="checkbox" name="product[' + product_detail_row + '][pilih]" value="1" onchange="updatetotal()"></td>';
        html += '    <td class="right"><input class="form-control" type="hidden" name="product[' + product_detail_row + '][sales_order_id]" value="'+json.products[i].sales_order_id+'"  readonly />'+json.products[i].no_sj+'<input class="form-control" type="hidden" name="product[' + product_detail_row + '][jatuhtempo]" value="'+json.products[i].jatuhtempo+'"  readonly/><input class="form-control" type="hidden" name="product[' + product_detail_row + '][metode_pembayaran]" value="'+json.products[i].metode_pembayaran+'"  readonly/></td>';
        html += '    <td class="right"><input class="form-control" type="hidden" name="product[' + product_detail_row + '][no_so]" value="'+json.products[i].no_so+'"  readonly /><input class="form-control" type="hidden" name="product[' + product_detail_row + '][jenisstok]" value="'+json.products[i].jenisstok+'"  readonly /><input class="form-control" type="hidden" name="product[' + product_detail_row + '][jenispenjualan]" value="'+json.products[i].jenispenjualan+'"  readonly />'+json.products[i].no_salesorder+'</td>';
          html += '    <td class="left"><input type="text" class="form-control" class="product-name" onblur="updatetotal()"  name="product[' + product_detail_row + '][name]" value="'+json.products[i].namaproduct+'" readonly/><input type="hidden" name="product[' + product_detail_row + '][product_id]" value="'+json.products[i].product_id+'" /><input type="hidden" name="product[' + product_detail_row + '][id]" value="'+json.products[i].id+'" /></td>';
        html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][quantity]" value="'+sisa+'" readonly /></td>';
        html += '    <td class="right"><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_detail_row + '][net_cost]" value="'+json.products[i].net_cost+'"  />'+json.products[i].namasatuan+'</td>';
        html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][price]" value="'+json.products[i].price+'"  readonly/></td>';
        html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][pajak]" value="'+json.products[i].pajak+'"  readonly/><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_detail_row + '][pembulatan]" value="0"  readonly/></td>';
        //html+='<td><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][diskonp]" value="'+json.products[i].diskon+'"  readonly/></td>';
        html+='<td><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][diskonp]" value="0"  readonly/></td>';
        //html += '<td><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][pembulatan]" value="'+json.products[i].pembulatan+'"  readonly/></td>';
        html += '<td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][total]" value="'+jumlah+'" readonly /><input class="form-control" type="hidden" name="product[' + product_detail_row + '][harga_terendah]" value="'+json.products[i].harga_terendah+'"/></td>';

        html +='</tr>';
        //net_cost += Number(json.products[i].net_cost);

        product_detail_row++;

      }
      $("#list-product-detail tbody").html(html);
      //$("#dp").val(json.order.dp);
      updatetotal();
    //  $("#totalqty").val(totalqty);
      //$("#net_cost").val(net_cost);

    }
    }
  })


  /*coba=$(this).data('row');
  jenisj=$("select[name='jenispenjualan']").val();
  jinv=$("select[name='jenisinvoice']").val();

  if(id != undefined & id != null){
    if(jenisj == 1){
      url='index.php?route=sale/penjualan/detail&token=<?php echo $this->request->get['token']; ?>&id=' + id+'&j='+jinv;
    }
    if(jenisj == 2){
      url='index.php?route=sale/penjualanmr/detail&token=<?php echo $this->request->get['token']; ?>&id=' + id+'&j='+jinv;
    }
    if(jenisj == 3){
      url ='index.php?route=sale/penjualanbahanbaku/detail&token=<?php echo $this->request->get['token']; ?>&id=' + id+'&j='+jinv;
    }
    $.ajax({
      url: url,
      dataType: 'json',
      success: function(json) {
      if(json){

        console.log(JSON.stringify(json));

        $("input[name='usia']").val(json.order.usia);
        $("input[name='jenisstok']").val(json.order.jenisstok);
        $("input[name='gudang_id']").val(json.order.gudang_id);

        html='';
        net_cost=0;

        for(i in json.products){

        //  totalqty += Number(json.products[i].quantity);
          html +='<tr data-ref="ref-'+id+'-'+jenisj+'"  id="product-detail-row"'+product_detail_row+'>';
            html += '    <td class="left"><input type="text" class="form-control" class="product-name" onblur="updatetotal()"  name="product[' + product_detail_row + '][name]" value="'+json.products[i].name+'" readonly/><input type="hidden" name="product[' + product_detail_row + '][product_id]" value="'+json.products[i].product_id+'" /><input type="hidden" name="product[' + product_detail_row + '][id]" value="'+json.products[i].id+'" /></td>';
          html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][quantity]" value="'+json.products[i].quantity+'"  readonly /></td>';
          html += '    <td class="right"><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_detail_row + '][net_cost]" value="'+json.products[i].net_cost+'"  />'+json.products[i].namasatuan+'</td>';
          html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][price]" value="'+json.products[i].price+'"  readonly/></td>';
            html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][pajak]" value="'+json.products[i].pajak+'"  readonly/><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_detail_row + '][pembulatan]" value="'+json.products[i].pembulatan+'"  readonly/></td>';
            //html += '<td><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][pembulatan]" value="'+json.products[i].pembulatan+'"  readonly/></td>';
          html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][total]" value="'+json.products[i].total+'" readonly /></td>';

          html +='</tr>';
          //net_cost += Number(json.products[i].net_cost);

          product_detail_row++;

        }
        $("#list-product-detail tbody").html(html);
        $("#dp").val(json.order.dp);
        updatetotal();
      //  $("#totalqty").val(totalqty);
        //$("#net_cost").val(net_cost);

      }
      }
    })
  }*/

});
/*.on("select2:select",function(e){
    //console.log(e);
    //console.log($(this).val());
    id=$(this).val();
    //alert(id);
    if(id != undefined & id != null){
    $.ajax({
      url: 'index.php?route=sale/customer/detail&token=<?php echo $this->request->get['token']; ?>&customer_id=' + id,
      dataType: 'json',
      success: function(json) {
        //alert(JSON.stringify(json));
        mp ='<option value="1">Tunai</option>';
        mp +='<option value="2">COD</option>';
		mp +='<option value="4">CBD</option>';
        if(Number(json.piutang) < Number(json.limit_piutang)){
          mp +='<option value="3">Kredit</option>';
        }
        $('select[name=\'metode_pembayaran\']').html(mp);
        $("input[name='piutang']").val(json.piutang);
        $("input[name='limit_piutang']").val(json.limit_piutang);
      }
    })
  }


});*/

});
//--></script>
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript"><!--
    var product_detail_row = 0;
    var jenis=1;
    var mp=[];
    var jt=[];
function ubahjenis(){
  product_detail_row=0

  /*$("#list-product-detail tbody > tr").remove();
  jenis=$("select[name='jenisinvoice']").val();
  $("select[name='referensi']").val(0);
  updatetotal();*/
  //alert(jenis);

  id=$(".customer").val();
  jenisj=$("select[name='jenispenjualan']").val();
  gudang_id=$("select[name='gudang_id']").val();
  if(jenisj == 1){
    url='index.php?route=sale/penjualan/detailtanpainvoice&token=<?php echo $this->request->get['token']; ?>&id=' + id+'&gudang_id='+gudang_id;
  }
  if(jenisj == 2){
    url='index.php?route=sale/penjualanmr/detail&token=<?php echo $this->request->get['token']; ?>&id=' + id;
  }
  if(jenisj == 3){
    url ='index.php?route=sale/penjualanbahanbaku/detail&token=<?php echo $this->request->get['token']; ?>&id=' + id;
  }
  $.ajax({
    url: url,
    dataType: 'json',
    success: function(json) {
    if(json){

      console.log(JSON.stringify(json));

      /*$("input[name='usia']").val(json.order.usia);
      $("input[name='jenisstok']").val(json.order.jenisstok);
      $("input[name='gudang_id']").val(json.order.gudang_id);
      */
      html='';
      net_cost=0;

      for(i in json.products){

      //  totalqty += Number(json.products[i].quantity);

      if(Number(json.products[i].pajak) > 0){
        pajak= Math.floor((10/100)*(Number(json.products[i].price)*Number(json.products[i].quantity)));
        json.products[i].pajak=pajak;
      }
        html +='<tr data-ref="ref-'+id+'-'+jenisj+'"  id="product-detail-row"'+product_detail_row+'>';
        html +='<td><input class="pilih" type="checkbox" name="product[' + product_detail_row + '][pilih]" value="1" onchange="updatetotal()"></td>';
        html += '    <td class="right"><input class="form-control" type="hidden" name="product[' + product_detail_row + '][sales_order_id]" value="'+json.products[i].sales_order_id+'"  readonly />'+json.products[i].no_sj+'<input class="form-control" type="hidden" name="product[' + product_detail_row + '][jatuhtempo]" value="'+json.products[i].jatuhtempo+'"  readonly/><input class="form-control" type="hidden" name="product[' + product_detail_row + '][metode_pembayaran]" value="'+json.products[i].metode_pembayaran+'"  readonly/></td>';
        html += '    <td class="right"><input class="form-control" type="hidden" name="product[' + product_detail_row + '][no_so]" value="'+json.products[i].no_so+'"  readonly /><input class="form-control" type="hidden" name="product[' + product_detail_row + '][jenisstok]" value="'+json.products[i].jenisstok+'"  readonly /><input class="form-control" type="hidden" name="product[' + product_detail_row + '][jenispenjualan]" value="'+json.products[i].jenispenjualan+'"  readonly />'+json.products[i].no_salesorder+'</td>';
          html += '    <td class="left"><input type="text" class="form-control" class="product-name" onblur="updatetotal()"  name="product[' + product_detail_row + '][name]" value="'+json.products[i].namaproduct+'" readonly/><input type="hidden" name="product[' + product_detail_row + '][product_id]" value="'+json.products[i].product_id+'" /><input type="hidden" name="product[' + product_detail_row + '][id]" value="'+json.products[i].id+'" /></td>';
        html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][quantity]" value="'+json.products[i].quantity+'"  readonly /></td>';
        html += '    <td class="right"><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_detail_row + '][net_cost]" value="'+json.products[i].net_cost+'"  />'+json.products[i].namasatuan+'</td>';
        html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][price]" value="'+json.products[i].price+'"  readonly/></td>';
          html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][pajak]" value="'+json.products[i].pajak+'"  readonly/><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_detail_row + '][pembulatan]" value="0"  readonly/></td>';
          //html += '<td><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][pembulatan]" value="'+json.products[i].pembulatan+'"  readonly/></td>';
        html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][total]" value="'+json.products[i].total+'" readonly /></td>';

        html +='</tr>';
        //net_cost += Number(json.products[i].net_cost);

        product_detail_row++;

      }
      $("#list-product-detail tbody").html(html);
      //$("#dp").val(json.order.dp);
      updatetotal();
    //  $("#totalqty").val(totalqty);
      //$("#net_cost").val(net_cost);

    }
    }
  })
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
  //diskon=Number($("#diskon").val());
  diskon=0;
  dp=Number($("#dp").val());
  pajak=0;
  grandtotal=0;
  totalpembulatan=0;
  net=0;
  diskonp=0;

  error=false;
  i = 0;
  //alert(product_detail_row);
  while(i < product_detail_row){
    //alert(i);
    harga=0;
  // alert($("input[name='product["+i+"][quantity]']").val());
    if($("input[name='product["+i+"][product_id]']").val() != undefined){
      if($("input[name='product["+i+"][pilih]']").is(":checked")){

        //metode pembayaran
        metodepembayaran=$("input[name='product["+i+"][metode_pembayaran]']").val();
        //alert(metodepembayaran);
        if(metodepembayaran != null){
          if(mp[metodepembayaran] == undefined){
            mp[metodepembayaran]=metodepembayaran;
          }
        }
        console.log(mp[metodepembayaran]);
        //alert(mp[metodepembayaran]);
        //jatuh tempo
        jatuhtempo=$("input[name='product["+i+"][jatuhtempo]']").val();
        if(jt[jatuhtempo] == undefined){
          jt[jatuhtempo]=jatuhtempo;
        }
        console.log(jt[jatuhtempo]);
        //alert(jt[jatuhtempo]);

    		quantity=$("input[name='product["+i+"][quantity]']").val();
        harga=$("input[name='product["+i+"][price]']").val();
        pjk=$("input[name='product["+i+"][pajak]']").val();
        net_cost=$("input[name='product["+i+"][net_cost]']").val();
        pembulatan=$("input[name='product["+i+"][pembulatan]']").val();
        sub=$("input[name='product["+i+"][total]']").val();
        diskonprods=$("input[name='product["+i+"][diskonp]']").val();
        error=false;
        if($.isNumeric( Number(quantity) ) & $.isNumeric( Number(harga) )){
            net += Number(quantity) * (Number(net_cost));
            tot=(Number(quantity) * Number(harga)) + ((Number(pjk)*Number(quantity))) + (Number(pembulatan)*Number(quantity));
            totalpembulatan +=Number(pembulatan)*Number(quantity);
            diskonp+=(Number(quantity)*Number(diskonprods));
          if(Number(pjk) > 0){
              /*hargasatuan=Math.floor(((100/110)*Number(sub))/Number(quantity));
              pajaksatuan=Math.floor((10/100)*(Number(hargasatuan)*Number(quantity)));

              $("input[name='product["+i+"][price]']").val(hargasatuan);
              $("input[name='product["+i+"][pajak]']").val(pajaksatuan/Number(quantity));
              */
              harga=$("input[name='product["+i+"][price]']").val();
              pjk=$("input[name='product["+i+"][pajak]']").val();
              pajaksatuan=Math.floor((10/100)*(Number(harga)*Number(quantity)));
              sub_total += Math.floor(Number(harga)*Number(quantity));
              pajak+= Math.floor(Number(pajaksatuan));

              $("input[name='product["+i+"][pajak]']").val(Number(pajaksatuan));

            }else{
              sub_total += Math.floor(Number(quantity) * (Number(harga)));
              //pajak+= Math.floor((Number(harga)*Number(quantity))*0.1);
            }
            grandtotal +=Number(sub);


        }else{
            error=true;
            alert("Nilai quantity dan harga harus berupa angka.");
        }
      }
    }
    i++;
  }

  //alert(JSON.stringify(mp));

  optjatuhtempo='';
  for(i in jt){
    optjatuhtempo += '<option value="'+jt[i]+'">'+jt[i]+'</option>';
  }

  console.log(JSON.stringify(jt));

  $("select[name='jatuh_tempo']").html(optjatuhtempo);

  optmetodepembayaran='';
  for(y in mp){
    if(mp[y] ==1){
      omp='Tunai';
    }else if(mp[y] == 2){
      omp = 'COD';
    }else if(mp[y] == 3){
      omp = 'Kredit';
    }
    else if(mp[y] == 4){
      omp = 'CBD';
    }else{
      omp = 'Kredit';
    }
    optmetodepembayaran += '<option value="'+mp[y]+'">'+omp+'</option>';
  }

  $("select[name='metode_pembayaran']").html(optmetodepembayaran);

  /*
  mp ='<option value="1">Tunai</option>';
  mp +='<option value="2">COD</option>';
mp +='<option value="4">CBD</option>';
  if(Number(json.piutang) < Number(json.limit_piutang)){
    mp +='<option value="3">Kredit</option>';
  */

  if(!error){
  //  pajak=(sub_total-diskon)*0.1;
    pajak=pajak;
    //grandtotal=sub_total-diskon-dp+pajak+Number(totalpembulatan);

    $("#sub_total").val(sub_total);
    $("#pajak").val(pajak);
  //  $("#totalqty").val(totalqty);
    $("#net_cost").val(net);
    $("#pembulatan").val(totalpembulatan);
    $("#diskon").val(diskonp);
    $("#total").val(Number(grandtotal)-Number(diskon));
    $("#totalreal").val(Number(grandtotal)-Number(diskon));
  }
}




function simpan(){
  //updatetotal();
  $(".error").remove();
  error=false;
  errdup=false;
  em='';

  cek = [];
  if(product_detail_row == 0){
    error=true;
    em+="Data produk tidak boleh kosong<br>";
  }
  tanggal=$("input[name='date_added']").val();
  if(tanggal < '<?php echo $locktanggal;?>'){
    error=true;
    em+="Tanggal tidak   boleh kurang dari <?php echo $locktanggal;?> <br>";
  }
  if($('.pilih:checked').length == 0){
    error=true;
    em+="Produk Harus Dipilih<br>";
  };
  if($("select[name='customer_id']").val() == null){
    error=true;
    em +="Customer harus dipilih <br>";
  }
  if($("select[name='metode_pembayaran']").val() == null){
    error=true;
    em +="Metode Pembayaran harus dipilih <br>";
  }
  if($("select[name='jatuh_tempo']").val() == null){
    error=true;
    em +="Jatuh Tempo harus dipilih <br>";
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

/*$(document).ready(function() {
  $(".order").select2({
    ajax: {
    url:"index.php?route=sale/penjualan/autocompletedetail&token=<?php echo $this->request->get['token']; ?>",
      dataType: 'json',
    data: function (params) {
      //alert($(this).data('row')) ;
      row=$(this).data('row');
      jenispenjualan=$("select[name='jenispenjualan']").val();
      customer_id=$("select[name='customer_id']").val();
      //alert(customer_id);
      return {
        q: params.term,
        p:1,
        jenis:jenis,
        jenispenjualan:jenispenjualan,
        customer_id:customer_id


      };
    },
    delay: 250,
    processResults: function (data) {
      console.log(JSON.stringify(data));

      return {
        results: $.map(data, function (item) {
              return {
                  text: item.text,
                  name: item.total,
                  id: item.id
              }
          })
      };
    },
    //cache: true
  },
  theme:"bootstrap"
}).on("select2:select",function(e){
  console.log($(this).val());
  id=$(this).val();
  coba=$(this).data('row');
  jenisj=$("select[name='jenispenjualan']").val();
  jinv=$("select[name='jenisinvoice']").val();

  if(id != undefined & id != null){
    if(jenisj == 1){
      url='index.php?route=sale/penjualan/detail&token=<?php echo $this->request->get['token']; ?>&id=' + id;
    }
    if(jenisj == 2){
      url='index.php?route=sale/penjualanmr/detail&token=<?php echo $this->request->get['token']; ?>&id=' + id;
    }
    if(jenisj == 3){
      url ='index.php?route=sale/penjualanbahanbaku/detail&token=<?php echo $this->request->get['token']; ?>&id=' + id;
    }
    $.ajax({
      url: url,
      dataType: 'json',
      success: function(json) {
      if(json){

        console.log(JSON.stringify(json));

        $("input[name='usia']").val(json.order.usia);
        $("input[name='jenisstok']").val(json.order.jenisstok);
        $("input[name='gudang_id']").val(json.order.gudang_id);

        html='';
        net_cost=0;

        for(i in json.products){

        //  totalqty += Number(json.products[i].quantity);
          html +='<tr data-ref="ref-'+id+'-'+jenisj+'"  id="product-detail-row"'+product_detail_row+'>';
            html += '    <td class="left"><input type="text" class="form-control" class="product-name" onblur="updatetotal()"  name="product[' + product_detail_row + '][name]" value="'+json.products[i].name+'" readonly/><input type="hidden" name="product[' + product_detail_row + '][product_id]" value="'+json.products[i].product_id+'" /><input type="hidden" name="product[' + product_detail_row + '][id]" value="'+json.products[i].id+'" /></td>';
          html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][quantity]" value="'+json.products[i].quantity+'"  readonly /></td>';
          html += '    <td class="right"><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_detail_row + '][net_cost]" value="'+json.products[i].net_cost+'"  />'+json.products[i].namasatuan+'</td>';
          html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][price]" value="'+json.products[i].price+'"  readonly/></td>';
            html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][pajak]" value="'+json.products[i].pajak+'"  readonly/><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_detail_row + '][pembulatan]" value="'+json.products[i].pembulatan+'"  readonly/></td>';
            //html += '<td><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][pembulatan]" value="'+json.products[i].pembulatan+'"  readonly/></td>';
          html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_detail_row + '][total]" value="'+json.products[i].total+'" readonly /></td>';

          html +='</tr>';
          //net_cost += Number(json.products[i].net_cost);

          product_detail_row++;

        }
        $("#list-product-detail tbody").html(html);
        $("#dp").val(json.order.dp);
        updatetotal();
      //  $("#totalqty").val(totalqty);
        //$("#net_cost").val(net_cost);

      }
      }
    })
  }

});
});*/

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
