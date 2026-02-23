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
            <h3 class="box-title">Buat Proforma Invoice</h3>
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
                      <td>Customer</td>
                      <td>
                        <select name="customer_id" class="customer form-control" onchange="ubahjenis()">

                          </select>
                      </td>
                    </tr>

                    <tr>
                      <td>Jenis Penjualan</td>
                      <td>
                        <select name="jenispenjualan" class="form-control" onchange="ubahjenis()">
                          <option value="1">Produk Dagang & Produksi</option>
                          <!--option value="2">Penjualan MR</option-->
                          <option value="3">Penjualan Bahan Baku</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td>Referensi <br>

                      </td>
                      <td>
                        <select name="referensi" class="order form-control"></select>
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
                          <input type="hidden" class="form-control" readonly name="gudang_id" value="0" id="gudang_id" >

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
                        <input type="hidden" class="form-control" name="pembulatan" id="pembulatan" value="" readonly>
                      </td>
                    </tr>
                    <tr>
                      <td>Total</td>
                      <td>
                        <input type="text" class="form-control" name="total" id="totalreal" value="" readonly>
                      </td>
                    </tr>


                  </table>
                  </div>
                </div>
                <div class="row">
                  <div class="col-xs-12">
                    <table class="table table-responsive" id="list-product-detail" >
                      <thead>
                        <th>Nama Produk</th>
                        <th>Qty</th>
                        <th>Satuan</th>
                        <th>Harga Satuan</th>
                        <th>Pajak</th>
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
      /*  mp ='<option value="1">Tunai</option>';
        mp +='<option value="2">COD</option>';
		mp +='<option value="4">CBD</option>';
        if(Number(json.piutang) < Number(json.limit_piutang)){
          mp +='<option value="3">Kredit</option>';
        }
        $('select[name=\'metode_pembayaran\']').html(mp);
        $("input[name='piutang']").val(json.piutang);
        $("input[name='limit_piutang']").val(json.limit_piutang);*/
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
  jenis=$("select[name='jenisinvoice']").val();
  $("select[name='referensi']").val(0);
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
  dp=Number($("#dp").val());
  pajak=0;
  grandtotal=0;
  totalpembulatan=0;
  net=0;

  error=false;
  i = 0;
  //alert(product_detail_row);
  while(i < product_detail_row){
    //alert(i);
    harga=0;
  // alert($("input[name='product["+i+"][quantity]']").val());
    if($("input[name='product["+i+"][product_id]']").val() != undefined){
  		quantity=$("input[name='product["+i+"][quantity]']").val();
      harga=$("input[name='product["+i+"][price]']").val();
      pjk=$("input[name='product["+i+"][pajak]']").val();
      net_cost=$("input[name='product["+i+"][net_cost]']").val();
      pembulatan=$("input[name='product["+i+"][pembulatan]']").val();
      sub=$("input[name='product["+i+"][total]']").val();

      error=false;
      if($.isNumeric( Number(quantity) ) & $.isNumeric( Number(harga) )){
          net += Number(quantity) * (Number(net_cost));
          tot=(Number(quantity) * Number(harga)) + ((Number(pjk)*Number(quantity))) + (Number(pembulatan)*Number(quantity));
          totalpembulatan +=Number(pembulatan)*Number(quantity);
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
    i++;
  }

  if(!error){
  //  pajak=(sub_total-diskon)*0.1;
    pajak=pajak;
    //grandtotal=sub_total-diskon-dp+pajak+Number(totalpembulatan);

    $("#sub_total").val(sub_total);
    $("#pajak").val(pajak);
  //  $("#totalqty").val(totalqty);
    $("#net_cost").val(net);
    $("#pembulatan").val(totalpembulatan);
    $("#diskon").val(diskon);
    $("#total").val(grandtotal);
    $("#totalreal").val(grandtotal);
  }
}




function simpan(){
  updatetotal();
  $(".error").remove();
  error=false;
  errdup=false;
  em='';

  cek = [];
  if(product_detail_row == 0){
    error=true;
    em+="Data order sukses tidak boleh kosong";
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

$(document).ready(function() {
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
  jinv=1

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
  }

});
});

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
