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
            <h3 class="box-title">Tambah Perakitan</h3>
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
                            <!-- <select name="gudang_id" onchange="removeproduct()" class="form-control"> -->
                            <select name="gudang_id" class="form-control" required="required">
                              <option value="*">Pilih Gudang</option>
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
                         <td style="display: none;">Jenis Penjualan</td>
                         <td style="display: none;">
                           <select class="form-control penjualan" name="jenispenjualan">
                             <option value="1">Penjualan MP</option>
                             <option value="2">Penjualan MR</option>
                             <!--option value="3">Penjualan Bahan Baku</option-->
                           </select>
                           </td>
                     </tr>
                      <tr>
                          <td>Tanggal</td>
                          <td>
                            <input type="text" class="date form-control" name="date_added" value="<?php echo date('Y-m-d'); ?>" readonly>
                          </td>
                      </tr>
                      <tr>
                        <td>Nama Produk Hasil</td>
                        <td>
                          <input type="hidden" class="form-control" name="nama_product" value="<?php echo $filter_name; ?>" />
                          <input type="hidden" class="form-control" name="id_product" value="" />
                          <select name="product_id" class="product form-control"></select>
                          <!-- <input type="text" name="nama_product" class="form-control"> -->
                        </td>
                      </tr>
                      <tr>
                        <td>Qty</td>
                        <td><input type="text" id="qty" onblur="updatesq()" name="qty" class="form-control"></td>
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
                      </tr>
                    </thead>
                    <?php $product_row=0;?>
                  <tfoot>
                    <tr>
                      <td colspan="7"></td>
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
<script type="text/javascript"><!--
$(document).ready(function() {
  	$('.date').datepicker({dateFormat: 'yy-mm-dd',minDate:'<?php echo $locktanggal; ?>',maxDate:new Date()});

  $(".product").select2({
      ajax: {
      url:"index.php?route=catalog/product/autocompleteprod&token=<?php echo $this->request->get['token']; ?>",
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
  }).on("select2:select",function(e){
      id=$(this).val();
      //alert(id);
      if(id != undefined & id != null){
        gudang_id=$('select[name=\'gudang_id\']').val();
        if(gudang_id!="*"){
          $.ajax({
            //url: 'index.php?route=catalog/productgudang/detail&token=<?php echo $token; ?>&product_id=' + id+'&gudang_id='+gudang_id,
            url: 'index.php?route=perakitan/perakitan/cek&token=<?php echo $token; ?>&product_id=' + id,
            dataType: 'json',
            success: function(json) {
            console.log(JSON.stringify(json));
              $('input[name=\'nama_product\']').val(json.detail.name);
              $('input[name=\'id_product\']').val(json.detail.product_id);
              if(json.detail.name ==undefined){
                $(this).val(null);
                swal("Produk tidak ditemukan");
                setTimeout(function(){location.reload()}, 1500);
              }
            }
          })
        }else{
          swal("Mohon Pilih Gudang terlebih dahulu");
          setTimeout(function(){location.reload()}, 1500);
        }
      }else{
        swal("Mohon Pilih gudang");
        return false;
      }
  })

});
//--></script>
<script>
$('.sidebar-menu').find('#menu-penjualan').addClass('active');
$('.sidebar-menu').find('#menu-penjualan-website').addClass('active');
$('.sidebar-menu').find('#menu-penjualan-detailorder').addClass('active');
$(function(){
  $("#tttk").hide();
  $(".penjualan").on('change',function(){
      p=$(".penjualan").val();
      $("#list-product tbody").remove();
      product_row=0;
      if(p == 2){
        $("#tttk").show();

      }else{
        $("#tttk").hide();
      }
  })
  $(".pengiriman").on('change',function(){
    p=$(".pengiriman").val();
    if(p==1){
      $('.alamat').hide();
      $('.alamat input').attr('readonly', 'readonly');
      $('.alamat select').attr('readonly', 'readonly');
    }else{
      $('.alamat input').removeAttr('readonly');
      $('.alamat select').removeAttr('readonly');
      $('.alamat').show();
    }
  })
  $(".no_so").select2({
    ajax: {
    url:"index.php?route=sale/tttkmr/autocomplete&token=<?php echo $token; ?>",
    //url: "index.php?route=pamerantoko/toko/autocomplete&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        q: params.term,
        customer_id:$(".customer").val() // search term

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
</script>

<script type="text/javascript"><!--
$(document).ready(function() {
  $('.date').datepicker({dateFormat: 'yy-mm-dd'});


});
//--></script>
<script type="text/javascript">
var product_row = <?php echo $product_row; ?>;

function hapus(row){
  console.log(row);
  //return false;
  $('#product-row'+row).remove();
  $('#list-product tbody').each(function(){
    parent=$(this).data('parent');
    if(parent == row){
      $(this).remove();
    }
  });
  //updatetotal();
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

    child=$("select[name='product["+row+"][tabung_id]']").data('child');
    if(child > 0){
      $("input[name='product["+child+"][quantity]']").val($("input[name='product["+row+"][quantity]']").val());
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

    if($("input[name='product["+i+"][product_id]']").val() != undefined){
      quantity=$("input[name='product["+i+"][quantity]']").val();
      harga=$("input[name='product["+i+"][price]']").val();

    //  diskonproduk=$("input[name='product["+i+"][diskon]']").val();
      //alert($("input[name='product["+i+"][weight]']").val());
      error=false;
      if($.isNumeric( Number(quantity) ) & $.isNumeric( Number(harga) )){
          if(nilaipajak == 1){
            //subtotal=(110/100)*(Number(quantity) * (Number(harga)));

            //  pajak=Math.round((0.1 * Number(harga)))*Number(quantity);
            pajak = Math.floor(0.1*(Number(harga)*Number(quantity)));
            subtotal=Math.floor((Number(harga)*Number(quantity))+Number(pajak));
            totalpajak +=pajak;
            hargasatuan=harga;
            total += subtotal;
            totalsub +=(Number(quantity) * (Number(harga)));

          }
          if(nilaipajak == 2){
            //hargasatuan=((100/110)*(harga*quantity))/quantity;
            hargasatuan=Math.floor((100/110)*harga);
            pajak=Math.floor((10/100)*(hargasatuan*Number(quantity)));
            totalpajak +=pajak;

            subtotal=Math.floor((Number(quantity) * (Number(hargasatuan))) + ((10/100)*(hargasatuan*Number(quantity))));
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
    grandtotal=totalsub+totalpajak;

    $("#sub_total").val(totalsub);
    $("#pajak").val(totalpajak);
    //$("#pembulatan").val(totalpembulatan);
    //$("#diskon").val(diskon);
    //$("#diskon").val(diskonp);
    $("#total").val(total);
  }
}
function updatesq(){
  console.log(product_row);
  i=0;
  var qty=$("#qty").val();
  if(product_row>0){
    while(i < product_row){
      $("input[name='product["+i+"][quantity]']").val(qty);
      i++;
    }
  }
  qty=$("#qty").val();

}
function addModule() {
  if(verify()==false){
    return false;
  }
  var qty=$("#qty").val();
  html  = '<tbody data-parent="0" id="product-row' + product_row + '">';
  html += '  <tr>';
  html +='<td class="center" style="width: 3px;"><img src="view/image/delete.png" onclick="hapus('+product_row+')" style="cursor: pointer;" /></td>';

  html += '    <td class="left" id="productname-'+product_row+'"><select style="width:300px" data-id="'+product_row+'" name="product[' + product_row + '][product_id]" class="product form-control"></select></td>';
  html += '    <td class="right"><input type="hidden" class="form-control" name="product[' + product_row + '][product_name]" /><input type="hidden" class="form-control" name="product[' + product_row + '][net_cost]" /><input type="hidden" class="form-control" name="product[' + product_row + '][quantity]" onblur="updatetotal('+product_row+')" value="'+ qty +'" /></td>';
  html += '</tbody>';

  $('#list-product tfoot').before(html);

  product_row++;
  penjualan=$(".penjualan").val();
  //alert(penjualan);

  $(function(){
    if(penjualan == 1){
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
            var pid=$('input[name=\'id_product\']').val();
            coba=$(this).data('id');
            //alert(coba);
            if(id != undefined & id != null){
              customer_group_id=$("#group").val();
              customer_id=$('select[name=\'customer_id\']').val()
              gudang_id=$('select[name=\'gudang_id\']').val();
            $.ajax({
              url: 'index.php?route=catalog/productgudang/details&token=<?php echo $token; ?>&product_id=' + id+'&gudang_id='+gudang_id,
              dataType: 'json',
              success: function(json) {
              console.log(JSON.stringify(json));
              if(json.detail.name !=undefined){
              child=$("select[name='product["+coba+"][tabung_id]']").data('child');
              if(child > 0){
                hapus(child);
              }

              if(json.detail.product_id==pid){
                swal("komponen produk tidak boleh sama dengan produk hasil");
                hapus(coba);
              }else{
                $("input[name='product["+coba+"][product_name]']").val(json.detail.name);
                $("input[name='product["+coba+"][net_cost]']").val(json.detail.net_cost);
              }
              
            }else{
       // alert(gudang_id);
              hapus(coba);
              //alert("Produk tidak ditemukan");
        //swal("Oops!", "Produk tidak ditemukan");
        swal({
        title: "Oops!",
        text: "Produk tidak ditemukan !",
        icon: "warning",
        button: "Ok",
        });
            }

              }
            })
          }
      })
      $(".tabung").select2({
          ajax: {
          url:"index.php?route=catalog/tabungmp/autocomplete&token=<?php echo $this->request->get['token']; ?>",
            dataType: 'json',
          data: function (params) {
            row=$(this).data('row');
            jenisgas=$("select[name='product["+row+"][product_id]']").val();
            gudang=$("select[name='gudang_id']").val();
            jenistabung=$("input[name='product["+row+"][jenistabung]']").val();
          //  alert(jenistabung);
            return {
              q: params.term,
              statustabung:1,
              jenisgas:jenisgas,
              jenistabung:jenistabung,
              gudang_id:gudang,
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
        child=$(this).data('child');
        if(child > 0){
          hapus(child);
        }
        id=$(this).val();
        coba=product_row;
        row=$(this).data('row');

        $(this).data('child',coba);
        jenistabung=$("input[name='product["+row+"][jenistabung]']").val();
        if(jenistabung == 3){
          customer_group_id=$("#group").val();
          customer_id=$('select[name=\'customer_id\']').val()
          gudang_id=$('select[name=\'gudang_id\']').val();
          //addModule();

        $.ajax({
          url: 'index.php?route=catalog/productgudang/detail&token=<?php echo $token; ?>&product_id=' + id+'&customer_group_id='+customer_group_id+'&gudang_id='+gudang_id+'&customer_id='+customer_id,
          dataType: 'json',
          success: function(prodtab) {
          console.log(JSON.stringify(prodtab));
          html  = '<tbody data-parent='+row+' id="product-row' + product_row + '">';
          html += '  <tr>';
          html +='<td class="center" style="width: 3px;"></td>';

          html += '    <td class="left"><input type="hidden" name="product[' + product_row + '][product_id]" value="'+id+'"><input type="text" class="form-control" name="product[' + product_row + '][name]" value="'+prodtab.detail.name+'" readonly ></td>';
          html += '    <td class="left"><select data-id="'+product_row+'"  data-row="'+product_row+'" name="product[' + product_row + '][tabung_id]" class="tabung form-control"><option value="0">Tanpa Tabung</option></select><input type="hidden" name="product[' + product_row + '][jenistabung]" value="0" readonly/></td>';
          html += '    <td class="right"><input type="text" class="form-control" name="product[' + product_row + '][price]" onblur="updatetotal('+product_row+')" value="" /><input type="hidden" name="product[' + product_row + '][net_cost]" value="" readonly/><input type="hidden" name="product[' + product_row + '][pricelist]" value="" readonly/><input type="hidden" name="product[' + product_row + '][batasbawah]" value="" readonly/>';
          html +='<input type="hidden" class="form-control" name="product[' + product_row + '][pajak]"  value="" /></td>';
          html += '<td><select class="form-control" name="product[' + product_row + '][nilaipajak]" onchange="updatetotal('+product_row+')">';
          html += '<option value="1" >Belum Termasuk</option>';
          html += '<option value="2" >Sudah Termasuk</option>';
          html += '<option value="3" >Tanpa Pajak</option>';
          html += '</select></td>';
        //  htm\ += '    <td class="right"><input type="text" class="form-control" name="product[' + product_row + '][diskon]" onblur="updatetotal()" value="" /></td>';
          html += '    <td class="right"><input type="text" class="form-control" name="product[' + product_row + '][quantity]" readonly value="'+$("input[name='product["+row+"][quantity]']")+'" /></td>';
          html += '    <td class="right"><input type="text" class="form-control" name="product[' + product_row + '][total]" value="" readonly/></td>';
          html += '  </tr>';
          html += '</tbody>';

          $('#list-product tfoot').before(html);

          product_row++;

          $("input[name='product["+coba+"][price]']").val(prodtab.lastprice);
          $("input[name='product["+coba+"][pricelist]']").val(prodtab.pricelist);
          $("input[name='product["+coba+"][batasbawah]']").val(prodtab.batasbawah);
          $("input[name='product["+coba+"][net_cost]']").val(prodtab.detail.net_cost);
          //$("input[name='product["+coba+"][diskon]']").val(json.diskon);
          $("input[name='product["+coba+"][quantity]']").val(1);

          total=Number($("input[name='product["+coba+"][quantity]']").val() * $("input[name='product["+coba+"][price]']").val());
          $("input[name='product["+coba+"][total]']").val(total);
          $("input[name='product["+coba+"][pajak]']").val(total*0.1);

          updatetotal();
          product_row++;


          }
        })

        }
      })
    }
    if(penjualan == 2){
      $(".product").select2({
          ajax: {
          url:"index.php?route=catalog/tabungmr/autocompleteprod&token=<?php echo $this->request->get['token']; ?>",
            dataType: 'json',
          data: function (params) {
            return {
              q: params.term,
              filter_customer_id:$('select[name=\'customer_id\']').val()
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
              if(json.detail.net_cost !=undefined){
              console.log(JSON.stringify(json));

              $("select[name='product["+coba+"][product_id]']").remove();

              addhtml='<input type="hidden" name="product['+coba+'][product_id]" value="'+id+'"><input type="text" class="form-control" readonly name="product['+coba+'][name]" value="'+json.detail.name+'">';
              $("#productname-"+coba).html(addhtml);

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
              else{
                hapus(coba);
                alert("Produk tidak ditemukan");
              }
              }
            })
          }
      })
    }
    if(penjualan == 3){
      $(".product").select2({
          ajax: {
          url:"index.php?route=catalog/bahanbaku/autocompleteprod&token=<?php echo $this->request->get['token']; ?>",
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
            console.log($(this).val());
            id=$(this).val();
            coba=$(this).data('id');
            if(id != undefined & id != null){
              customer_group_id=$("#group").val();
              customer_id=$('select[name=\'customer_id\']').val()
              //alert(customer_id);

            $.ajax({
              url: 'index.php?route=catalog/bahanbaku/detail&token=<?php echo $token; ?>&product_id=' + id+'&customer_group_id='+customer_group_id+'&customer_id='+customer_id,
              dataType: 'json',
              success: function(json) {
              console.log(JSON.stringify(json));

              $("select[name='product["+coba+"][product_id]']").remove();

              addhtml='<input type="hidden" name="product['+coba+'][product_id]" value="'+id+'"><input type="text" class="form-control" readonly name="product['+coba+'][name]" value="'+json.name+'">';
              $("#productname-"+coba).html(addhtml);

              $("input[name='product["+coba+"][price]']").val(json.price);
              $("input[name='product["+coba+"][net_cost]']").val(json.net_cost);
              //$("input[name='product["+coba+"][diskon]']").val(json.diskon);
              $("input[name='product["+coba+"][quantity]']").val(1);

              total=Number($("input[name='product["+coba+"][quantity]']").val() * $("input[name='product["+coba+"][price]']").val());
              $("input[name='product["+coba+"][total]']").val(total);
              $("input[name='product["+coba+"][pajak]']").val(total*0.1);

              updatetotal();

              }
            })
          }
      })
    }

})
}
</script>




<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript"><!--
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

  if($("select[name='address_id']").val() == null | $("select[name='address_id']").val() == undefined){
    error=true;
    em +="Alamat harus dipilih <br>";
  }

  if($("select[name='metode_pembayaran']").val() == null | $("select[name='metode_pembayaran']").val() == undefined){
    error=true;
    em +="Metode Pembayaran harus dipilih <br>";
  }

  if($("select[name='customer_id']").val() == null){
    error=true;
    em +="Customer harus diisi <br>";
  }
  /*if($("input[name='usia']").val() == 0){
    error=true;
    em +="Jatuh Tempo harus diisi <br>";
  }*/

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
    tabung_id=$("select[name='product["+i+"][tabung_id]']").val();
    qty=$("input[name='product["+i+"][quantity]']").val();
    jenistabung=$("input[name='product["+i+"][jenistabung]']").val();

    if(qty <= 0){
      error=true;
      errqty=true;
    }

    if(tabung_id != undefined){
      if(tabung_id > 0){
        if(cektb[tabung_id] == undefined){
          cektb[tabung_id]=i;
        }else{
          errduptb = true;
          error=true;
          //alert(product_id+' '+p);
        }

      }
      else{
        if(jenistabung > 0){
          //if(jenistabung == 1){
            error=true;
            errreqtb=true;
        //  }
        }
      }


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
    if(errreqtb){
      em +=" Tabung Gas harus dipilih";
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
<script type="text/javascript"><!--
function country(element, zone_id) {
  $.ajax({
    url: 'index.php?route=sale/customer/country&token=<?php echo $token; ?>&country_id=' + element.value,
    dataType: 'json',
    beforeSend: function() {
      $('select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
    },
    complete: function() {
      $('.wait').remove();
    },
    success: function(json) {

      html = '<option value="0"><?php echo $text_select; ?></option>';

      if (json['zone'] != '') {
        for (i = 0; i < json['zone'].length; i++) {
              html += '<option value="' + json['zone'][i]['zone_id'] + '"';

          if (json['zone'][i]['zone_id'] == zone_id) {
                html += ' selected="selected"';
            }

            html += '>' + json['zone'][i]['name'] + '</option>';
        }
      } else {
        html += '<option value="0"><?php echo $text_none; ?></option>';
      }

      $('select[name=\'zone_id\']').html(html);
    },
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
}

$('select[name$=\'[country_id]\']').trigger('change');

/* tokocepat */

function zone(element, city_id) {
  $.ajax({
    url: 'index.php?route=sale/customer/zone&token=<?php echo $token; ?>&zone_id=' + element.value,
    dataType: 'json',
    beforeSend: function() {
      $('select[name=\'zone_id\']').after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
    },
    complete: function() {
      $('.wait').remove();
    },
    success: function(json) {
      html = '<option value="0"><?php echo $text_select; ?></option>';

      if (json['city'] != '') {
        for (i = 0; i < json['city'].length; i++) {
              html += '<option value="' + json['city'][i]['city_id'] + '"';

          if (json['city'][i]['city_id'] == city_id) {
                html += ' selected="selected"';
            }

            html += '>' + json['city'][i]['name'] + '</option>';
        }
      } else {
        html += '<option value="0"><?php echo $text_none; ?></option>';
      }

      $('select[name=\'city_id\']').html(html);
    },
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
}

$('select[name=\'[zone_id]\']').trigger('change');

function verify(){
  var gudang = $('select[name=\'gudang_id\']').val();
  var productname = $('input[name=\'nama_product\']').val();
  var qty = $('input[name=\'qty\']').val();
  if(gudang=="*"){
    swal("Gudang haus dipilih");
    return false;
  }

  tanggal=$("input[name='tanggal']").val();
  if(tanggal < '<?php echo $locktanggal;?>'){
    swal("Tanggal tidak   boleh kurang dari <?php echo $locktanggal;?>");
    return false;
  }

  if(productname==""){
    swal("Nama produk harus diisi");
    return false;
  }

  if(qty==""){
    swal("Qty produk harus diisi");
    return false;
  }

  if(qty<=0){
    swal("Qty produk harus lebih besar dari 0");
    return false;
  }

  //return true;
}
function simpan(){
  //alert(product_row);
  if(verify()==false){
    return false;
  }

  $("#form").submit();
}
</script>
<script>
  $('input[name=\'nama_product\']').autocomplete({
  delay: 0,
  source: function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term),
      dataType: 'json',
      success: function(json) {
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
    $('input[name=\'nama_product\']').val(ui.item['label']);

    //$('input[name=\'filter_product_id\']').attr('value', ui.item['value']);



    return false;
  },
  focus: function(event, ui) {
        return false;
    }
});
</script>
<?php echo $footer; ?>
