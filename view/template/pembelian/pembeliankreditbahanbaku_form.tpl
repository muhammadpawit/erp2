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
            <h3 class="box-title">Pembelian Bahan Baku</h3>
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
                  <?php foreach($error_warning as $e){
                    echo $e.'<br>';
                  } ?>
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
                       <td><span class="required">*</span>No. Surat Permintaan Pembelian</td>
                       <td ><select name="surat_id" class="form-control nosurat">

                   		</select>
                      <input type="hidden" name="jenis_barang" value="">
                      <input type="hidden" name="jenis_aktiva" value="">
                      <input type="hidden" name="gudang_id" value="0">
                       </td>
                     </tr>
                     <tr>
                        <td><span class="required">*</span>Vendor</td>
                        <td ><select name="vendor_id" class="form-control vendor">

                    		</select>
                        </td>
                      </tr>
                      <tr>
                          <td>Tanggal Kirim</td>
                          <td><input type="text" readonly name="tglkirim" class="dates form-control" value="<?php echo date('Y-m-d'); ?>" ></td>
                      </tr>
                      <tr>
                          <td>Metode Pembayaran </td>
                          <td><select name="metode_pembayaran" class="form-control" >
                             <option value="1">CBD</option>
                             <option value="2">COD</option>
                             <option value="3">Kredit</option>
                          </select>
                          </td>
                      </tr>
                      <tr>
                          <td>Jatuh Tempo</td>
                          <td><input type="text" readonly name="jatuhtempo" class="date form-control" value="<?php echo date('Y-m-d'); ?>" ></td>
                      </tr>
                     <tr>
                         <td>Sub Total</td>
                         <td><input type="text" onblur="hitungtotal()"  id="sub_total" readonly name="sub_total" class="form-control" value="0" ></td>
                     </tr>
                     <tr>
                         <td>Status PPN </td>
                         <td><select name="statuspajak" class="form-control" onchange="hitungtotal()" id="status-pajak">
                            <option value="1">Ya</option>
                            <option value="2">Tidak</option>
                         </select>
                         </td>
                     </tr>
                      <tr>
                         <td>Pajak </td>
                         <td><input type="text" id="pajak" onblur="hitungtotal()"  name="pajak" class="form-control" value="0" readonly></td>
                     </tr>
                     <tr>
                         <td>Diskon </td>
                         <td><input type="text" id="diskon" onblur="hitungtotal()" name="diskon" class="form-control" value="0" ></td>
                     </tr>
                     <!--tr>
                         <td>Total Biaya </td>
                         <td><input type="text" id="totalbiaya" name="totalbiaya" class="form-control" value="0" readonly ></td>
                     </tr-->
                     <tr>
                         <td>Total Pembelian </td>
                         <td><input type="text" id="total" onblur="hitungtotal()"  readonly name="total_pembelian" class="form-control" value="0" ></td>
                     </tr>
                     </table>



                        <table class="table" id="list-product" >
                          <thead>
                            <tr>
                              <th class="left">Nama</th>
                               <th class="right">Quantity</th>
                              <th class="right">Harga Beli <br>
                                (per satuan barang)</th>

                            </tr>
                          </thead>
                          <?php $product_row=0;?>
                          <?php $option_row = 0; ?>
                          <?php $download_row = 0; ?>
                          <tbody>
                          </tbody>

                        </table>

                      <!--div class="tab-pane " id="biaya">
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
                              <td colspan="4"></td>
                              <td class="left"><a onclick="addBiaya();" class="btn btn-success">Tambah Biaya</a>  </td>
                            </tr>

                    </tfoot>
                        </table>
                      </div-->


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
$('.sidebar-menu').find('#menu-pembelian').addClass('active');
$('.sidebar-menu').find('#menu-pembelian-kredit').addClass('active');

</script>

<script type="text/javascript"><!--
var biaya_row=<?php echo $biaya_row; ?>;
function addBiaya(){

	html = '  <tr id="biaya-row' + biaya_row + '">';
  html += '    <td class="left"><select style="width:300px" data-id="'+biaya_row+'" name="biaya[' + biaya_row + '][jenisbiaya_id]" class="biaya form-control"></select></td>';
  html += '    <td class="right"><input class="form-control"  type="text" name="biaya[' + biaya_row + '][total]" value="0" onchange="hitungtotal()" /></td>';


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
function hitungtotal(){
    total = 0;
    grandtotal=0;
    totalbiaya=0;
    diskon=Number($("#diskon").val());
    statuspajak=$("#status-pajak").val();
    error=false;

    pajak=0;

    i = 0;
    while(i < product_row){
  		quantity=$("input[name='products["+i+"][quantity]']").val();
      harga=$("input[name='products["+i+"][harga]']").val();
      //error=false;

      if($.isNumeric( Number(quantity) ) & $.isNumeric( Number(harga) )){
          total += Number(quantity) * Number(harga);
      }else{
          error=true;
          alert("Nilai quantity dan harga harus berupa angka.");
      }
      i++;
    }
  /*  y = 0;

      while(y < biaya_row){
        nilaibiaya=$("input[name='biaya["+y+"][total]']").val();
        //error=false;

        if($.isNumeric( Number(nilaibiaya) )){
            totalbiaya += Number(nilaibiaya);
        }else{
            error=true;
            alert("Nominal biaya harus berupa angka.");
        }
        y++;
      }

*/
    if($.isNumeric( Number(diskon) )){
      diskon=Number(diskon);
    }else{
      error=true;
      alert("Nilai diskon harus berupa angka.");
    }
    if(!error){
      if(statuspajak == 1){
        pajak = total*0.1;
      }

      grandtotal=total+pajak-diskon;
    //  $("#totalbiaya").val(totalbiaya);
      $("#sub_total").val(total);
      $("#pajak").val(pajak);
      $("#total").val(grandtotal);
    }
}
function simpan(){
  hitungtotal();
  $('#form').submit();
}
var product_row=<?php echo $product_row?>;
$(document).ready(function() {
	$('.date').datepicker({
    dateFormat: 'yy-mm-dd',
    minDate: new Date()
  });

  $( ".dates" ).datepicker({
    dateFormat: 'yy-mm-dd',
    minDate: new Date()
  });
  $(".nosurat").select2({
    ajax: {
    url:"index.php?route=pembelian/permintaanpembelianbahanbaku/autocomplete&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        q: params.term,
        j: 2,
        s:1// search term

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
      url: 'index.php?route=pembelian/permintaanpembelian/detail&token=<?php echo $token; ?>&surat_id=' + id,
      dataType: 'json',
      success: function(json) {
        //alert(JSON.stringify(json));
        $('input[name=\'jenis_barang\']').val(json.detail.jenis_barang);
        $('input[name=\'jenis_aktiva\']').val(json.detail.jenis_aktiva);
        $('input[name=\'gudang_id\']').val(json.detail.gudang_id);
        html='';
        for(i in json.products){
          html +='<tr id="product-row"'+product_row+'>';

        	html += '    <td class="left"><input type="text" readonly class=" form-control product-name" onblur="hitungtotal()"  name="products[' + product_row + '][name]" value="'+json.products[i].product_name+'" readonly/><input type="hidden" name="products[' + product_row + '][product_id]" value="'+json.products[i].product_id+'" /></td>';
          html += '    <td class="right"><input type="text" class="form-control" onblur="hitungtotal()"  name="products[' + product_row + '][quantity]" value="'+json.products[i].quantity+'"  /></td>';
          html += '    <td class="right"><input type="text" class="form-control" onblur="hitungtotal()"  name="products[' + product_row + '][harga]" value="0"  /></td>';
          html +='</tr>';
          product_row++;
        }
        $("#list-product tbody").html(html);
      }
    })
  }


  });

  $(".vendor").select2({
    ajax: {
    url:"index.php?route=catalog/vendorlokal/autocomplete&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        filter_name: params.term
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

});
//--></script>

<?php echo $footer; ?>
