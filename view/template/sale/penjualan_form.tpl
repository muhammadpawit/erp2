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
            <h3 class="box-title"><i class="fa fa-plus"></i> Tambah Surat Jalan Penjualan</h3>
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
                           <input type="hidden" name="cust_id" id="cust_id"/>
                       </td>
                     </tr>
                     <tr>
                         <td>Tanggal Kirim</td>
                         <td>
                           <input type="text" class="date form-control" name="date_added" value="<?php echo date('Y-m-d'); ?>" readonly>
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
                     <tr>
                       <td>Keterangan</td>
                       <td>
                         <input type="text" name="keterangan" class="form-control">
                       </td>
                     </tr>
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
                                  <input type="hidden" class="form-control" name="totalqty" id="totalqty" value="0" readonly>
                                  <input type="hidden" class="form-control" name="sub_total" id="sub_total" value="0" readonly>
                                  <input type="hidden" class="form-control" name="diskon" id="diskon" value="0" readonly>
                                  <input type="hidden" class="form-control" name="pajak" id="pajak" value="0" readonly>
                                  <input type="hidden" class="form-control" name="pembulatan" id="pembulatan" value="0" readonly>
                                  <input type="hidden" class="form-control" name="total" id="total" value="0" readonly>
                                  <input type="hidden" class="form-control" name="net_cost" id="net_cost" value="0" readonly>
                              </td>
                          </tr>
                       <!--tr>
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
                           <td>Pembulatan</td>
                           <td>
                             <input type="text" class="form-control" name="pembulatan" id="pembulatan" value="0" readonly>
                           </td>
                       </tr>
                       <tr>
                           <td>Total</td>
                           <td>
                             <input type="text" class="form-control" name="total" id="total" value="0" readonly>
                             <input type="hidden" class="form-control" name="net_cost" id="net_cost" value="0" readonly>
                           </td>
                       </tr-->

                   </table>
                </div>
                <div class="col-md-6">

                    <table class="table alamat">
                      <tr>
                          <td>Alamat Pengiriman</td>
                          <td>
                            <select name="address_id" class="address form-control">
                              <option value="-1">Gunakan Alamat Baru</option>
                              </select>
                          </td>
                      </tr>
                      <tr>
                        <td><span class="required">*</span> Simpan Alamat Sebagai</td>
                        <td><input class="form-control" type="text" name="firstname" value="" />
                          </td>
                      </tr>
                      <tr>
                        <td><span class="required">*</span> Kontak</td>
                        <td><input class="form-control" type="text" name="lastname" value="" />
                          </td>
                      </tr>

                      <tr>
                        <td><span class="required">*</span> Alamat</td>
                        <td><input class="form-control" type="text" name="address_1" value="" />
                          </td>
                      </tr>
                      <tr>
                        <td>Alamat (baris 2)</td>
                        <td><input class="form-control" type="text" name="address_2" value="" /></td>
                      </tr>
                      <tr>
                        <td><span id="postcode-required<?php echo $address_row; ?>" class="required">*</span> Kodepos</td>
                        <td><input class="form-control" type="text" name="postcode" value="" /></td>
                      </tr>
                      <tr>
                        <td><span class="required">*</span> Propinsi</td>
                        <td><select class="form-control" name="country_id" onchange="country(this, 0);">
                            <option value="">Pilih Propinsi</option>
                            <?php foreach ($countries as $country) { ?>

                           <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                            <?php } ?>
                          </select>
                          </td>
                      </tr>
                      <!-- tokocepat -->
                      <tr>
                        <td><span class="required">*</span> Kota/Kabupaten</td>
                        <td><select class="form-control" name="zone_id" onchange="zone(this, '<?php echo $city_id; ?>');">

                          </select>
                         </td>
                      </tr>
                      <tr>
                        <td><span class="required">*</span> Kecamatan</td>
                        <td><select class="form-control" name="city_id">

                          </select>
                          </td>
                      </tr>

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
                        <th class="left" width="100">Nama Produk</th>
                        <th class="left" width="100">Tabung</th>
                        <th class="right" width="70">Qty Beli</th>
                        <th class="right" width="70">Qty Kirim</th>
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
  $(".pengiriman").trigger('change');
  $('.date').datepicker({
    dateFormat: 'yy-mm-dd',minDate:'<?php echo $locktanggal; ?>',
    //maxDate:new Date()
    maxDate:2,
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
    $("#cust_id").val(id);
    //alert(id);
    if(id != undefined & id != null){
      gudang_id=$("select[name='gudang_id']").val();
    $.ajax({
      url: 'index.php?route=sale/salesorder/detailtanpasj&token=<?php echo $this->request->get['token']; ?>&customer_id=' + id+'&gudang_id='+gudang_id,
      dataType: 'json',
      success: function(json) {
        console.log(JSON.stringify(json));
        html='';
        net_cost=0;
        hargapluspajak=0;
        hargaterendah=0;
        pembulatanharga=0;
        pembulatanhargaterendah=0;
        for(i in json.products){
          if(json.products[i].no_tabung == null){
            json.products[i].no_tabung="";
          }
          hargapluspajak = Number(json.products[i].price*0.1)+Number(json.products[i].price);
          hargaterendah=json.products[i].harga_terendah;
          pembulatanharga=Math.round(hargapluspajak);
          pembulatanhargaterendah=Math.round(hargaterendah);
          console.log(Math.round(hargapluspajak));
          console.log(Math.round(hargaterendah));
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
          //harga=harga.toFixed(2);
          //totalpajak=totalpajak.toFixed(2);
          //if(Number(Math.round(pembulatanhargaterendah)) <= Number(Math.round(pembulatanharga)) ){
          if(json.products[i].persetujuanharga==0){            
            html +='<tr id="product-row"'+product_row+'>';
            html +='<td><input class="pilih" type="checkbox" name="product[' + product_row + '][pilih]" value="1" onchange="updatetotal()"><input type="hidden" name="product[' + product_row + '][diskon]" value="'+json.products[i].diskon+'"></td>';
            html += '    <td class="left">'+json.products[i].no_so+'<input type="hidden" name="product[' + product_row + '][no_so]" value="'+json.products[i].sales_order_id+'" /><input type="hidden" name="product[' + product_row + '][sales_order_product_id]" value="'+json.products[i].id+'" /></td>';
            html += '    <td class="left"><input type="hidden" class="form-control" class="product-name" onblur="updatetotal"  name="product[' + product_row + '][name]" value="'+json.products[i].namaproduct+'" readonly/>'+json.products[i].namaproduct+'<input type="hidden" name="product[' + product_row + '][product_id]" value="'+json.products[i].product_id+'" /><input type="hidden" name="product[' + product_row + '][id]" value="'+json.products[i].id+'" /></td>';
            html += '    <td class="left"><input type="text" class="form-control" class="product-name" onblur="updatetotal()"  name="product[' + product_row + '][no_tabung]" value="'+json.products[i].tabung+'" readonly/><input type="hidden" name="product[' + product_row + '][tabung_id]" value="'+json.products[i].tabung_id+'" /><input type="hidden" name="product[' + product_row + '][quantityterima]" value="'+json.products[i].quantityterima+'" /><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][pajak]" value="'+totalpajak+'"  readonly/><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][pembulatan]" value="'+pembulatan+'"  readonly/><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][net_cost]" value="'+json.products[i].net_cost+'"  /></td>';
            html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_row + '][quantitypesan]" value="'+json.products[i].quantity+'"  readonly/><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][price]" value="'+harga+'"  readonly /></td>';
            html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_row + '][quantity]" value="'+quantitymax+'"  /><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][totalpajak]" value="'+totalpajak+'"  /><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][total]" value="'+json.products[i].total+'"  readonly/><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][quantitymax]" value="'+json.products[i].quantitymax+'"  /><input class="form-control" type="hidden" name="product[' + product_row + '][harga_terendah]" value="'+json.products[i].harga_terendah+'" /></td>';
            html +='</tr>';
          }else{
              html +='<tr id="product-row"'+product_row+'>';
              html +='<td><span class="text-red">Perlu pesetujuan harga</span></td>';
              html += '    <td class="left">'+json.products[i].no_so+'<input type="hidden" name="product[' + product_row + '][no_so]" value="'+json.products[i].sales_order_id+'" /><input type="hidden" name="product[' + product_row + '][sales_order_product_id]" value="'+json.products[i].id+'" /></td>';
              html += '    <td class="left"><input type="hidden" class="form-control" class="product-name" onblur="updatetotal"  name="product[' + product_row + '][name]" value="'+json.products[i].namaproduct+'" readonly/>'+json.products[i].namaproduct+'<input type="hidden" name="product[' + product_row + '][product_id]" value="'+json.products[i].product_id+'" /><input type="hidden" name="product[' + product_row + '][id]" value="'+json.products[i].id+'" /></td>';
              html += '    <td class="left"><input type="text" class="form-control" class="product-name" onblur="updatetotal()"  name="product[' + product_row + '][no_tabung]" value="'+json.products[i].tabung+'" readonly/><input type="hidden" name="product[' + product_row + '][tabung_id]" value="'+json.products[i].tabung_id+'" /><input type="hidden" name="product[' + product_row + '][quantityterima]" value="'+json.products[i].quantityterima+'" /><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][pajak]" value="'+totalpajak+'"  readonly/><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][pembulatan]" value="'+pembulatan+'"  readonly/><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][net_cost]" value="'+json.products[i].net_cost+'"  /></td>';
              html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_row + '][quantitypesan]" value="'+json.products[i].quantity+'"  readonly/><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][price]" value="'+harga+'"  readonly /></td>';
              html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_row + '][quantity]" value="'+quantitymax+'"  /><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][totalpajak]" value="'+totalpajak+'"  /><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][total]" value="'+json.products[i].total+'"  readonly/><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][quantitymax]" value="'+json.products[i].quantitymax+'"  /><input class="form-control" type="hidden" name="product[' + product_row + '][harga_terendah]" value="'+json.products[i].harga_terendah+'" /></td>';
              html +='</tr>';
          }
            net_cost += 0;
            product_row++;
        }
        $("#list-product tbody").html(html);
        $("#totalqty").val(totalqty);
        $("#net_cost").val(net_cost);
        updatetotal();

      }
    })
  }


  });


});
//--></script>
<script type="text/javascript"><!--


function removeproduct(){
  $('#list-product tbody').empty();
  // updatepengiriman();
  product_row=0;
  updatetotal();

  id=$(".customer").val();
  //alert(id);
  $("#cust_id").val(id);
  if(id != undefined & id != null){
    gudang_id=$("select[name='gudang_id']").val();
    $.ajax({
      url: 'index.php?route=sale/salesorder/detailtanpasj&token=<?php echo $this->request->get['token']; ?>&customer_id=' + id+'&gudang_id='+gudang_id,
      dataType: 'json',
      success: function(json) {
        console.log(JSON.stringify(json));
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
          html +='<td><input class="pilih" type="checkbox" name="product[' + product_row + '][pilih]" value="1" onchange="updatetotal()"></td>';
          html += '    <td class="left">'+json.products[i].no_so+'<input type="hidden" name="product[' + product_row + '][no_so]" value="'+json.products[i].sales_order_id+'" /><input type="hidden" name="product[' + product_row + '][sales_order_product_id]" value="'+json.products[i].id+'" /></td>';
          html += '    <td class="left"><input type="text" class="form-control" class="product-name" onblur="updatetotal()"  name="product[' + product_row + '][name]" value="'+json.products[i].namaproduct+'" readonly/><input type="hidden" name="product[' + product_row + '][product_id]" value="'+json.products[i].product_id+'" /><input type="hidden" name="product[' + product_row + '][id]" value="'+json.products[i].id+'" /></td>';
          html += '    <td class="left"><input type="text" class="form-control" class="product-name" onblur="updatetotal()"  name="product[' + product_row + '][no_tabung]" value="'+json.products[i].tabung+'" readonly/><input type="hidden" name="product[' + product_row + '][tabung_id]" value="'+json.products[i].tabung_id+'" /><input type="hidden" name="product[' + product_row + '][quantityterima]" value="'+json.products[i].quantityterima+'" /><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][pajak]" value="'+totalpajak+'"  readonly/><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][pembulatan]" value="'+pembulatan+'"  readonly/><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][net_cost]" value="'+json.products[i].net_cost+'"  /></td>';

          //html += '    <td class="right"></td>';
          html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_row + '][quantitypesan]" value="'+json.products[i].quantity+'"  readonly/><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][price]" value="'+harga+'"  readonly /></td>';
          html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_row + '][quantity]" value="'+quantitymax+'"  /><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][totalpajak]" value="'+totalpajak+'"  /><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][total]" value="'+json.products[i].total+'"  readonly/><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][quantitymax]" value="'+json.products[i].quantitymax+'"  /><input class="form-control" type="hidden" name="product[' + product_row + '][harga_terendah]" value="'+json.products[i].harga_terendah+'"/></td>';
          //html += '    <td class="right"></td>';
        //  html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_row + '][total]" value="'+json.products[i].total+'"  readonly/></td>';

          html +='</tr>';
          net_cost += 0;
          product_row++;
        }
        $("#list-product tbody").html(html);
        $("#totalqty").val(totalqty);
        $("#net_cost").val(net_cost);
        updatetotal();

      }
    })
  }



}

function limit(id){
  $.ajax({
      url: 'index.php?route=sale/customer/getstatuslimit&token=<?php echo $this->request->get['token']; ?>&customer_id=' + id,
      dataType: 'json',
      success: function(json) {
        if(json==1){
          $('#list-product tbody').empty();
          swal("Tidak bisa membuat surat jalan. Limit tagihan untuk customer ini telah diaktifkan.");
        }
      }
  });
}


function updatetotal(){
  //alert('test');
  limit($("#cust_id").val());
  total=0;
  diskonp=0;
  grandtotal=0;
  totalqty=0;
  net=0;
  totalpembulatan=0;
  error=false;
  discprod=0;

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
      totalpajak=$("input[name='product["+i+"][totalpajak]']").val();
      net_cost=$("input[name='product["+i+"][net_cost]']").val();
      diskonproduk=$("input[name='product["+i+"][pajak]']").val();
      pembulatan=$("input[name='product["+i+"][pembulatan]']").val();
      diskonprods=$("input[name='product["+i+"][diskon]']").val();
      //alert($("input[name='product["+i+"][weight]']").val());
      error=false;
      if($.isNumeric( Number(quantity) ) & $.isNumeric( Number(harga) )){
        totalpjk=0;
        //total +=Math.floor(Number(quantity) * (Number(harga)));
        net += Number(quantity) * (Number(net_cost));
        diskonp +=Math.floor((Number(totalpajak)/Number(quantitypesan))*Number(quantity));
        totalqty += Number(quantity);
        subtanpapajak=Math.floor(Number(quantity) * (Number(harga)));
        discprod += Number(quantity) * (Number(diskonprods));
        if(diskonproduk > 0){
          subpajak=Math.floor(subtanpapajak*0.1);
        }else{
          subpajak=0;
        }
        sub=subtanpapajak+subpajak;
        $("input[name='product["+i+"][total]']").val(sub);
        total +=subtanpapajak;

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
    grandtotal=Math.floor(Number(total)+Number(pajak)+Number(totalpembulatan)-Number(discprod));

    $("#sub_total").val(Math.round(total));
    $("#pajak").val(pajak);
    $("#totalqty").val(totalqty);
    $("#net_cost").val(net);
    $("#pembulatan").val(totalpembulatan);
    //$("#diskon").val(diskon);
    $("#diskon").val(discprod);
    $("#total").val(grandtotal);
  }
}

//--></script>




<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript"><!--
$('.dates').datepicker({dateFormat: 'yy-mm-dd',minDate:'<?php echo $locktanggal; ?>',maxDate:new Date()});
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

          html += '    <td class="left"><input type="text" class="form-control" class="product-name" onblur="updatetotal"  name="product[' + product_row + '][name]" value="'+json.products[i].name+'" readonly/><input type="hidden" name="product[' + product_row + '][product_id]" value="'+json.products[i].product_id+'" /><input type="hidden" name="product[' + product_row + '][id]" value="'+json.products[i].id+'" /></td>';
          html += '    <td class="left"><input type="text" class="form-control" class="product-name" onblur="updatetotal()"  name="product[' + product_row + '][no_tabung]" value="'+json.products[i].no_tabung+'" readonly/><input type="hidden" name="product[' + product_row + '][tabung_id]" value="'+json.products[i].tabung_id+'" /><input type="hidden" name="product[' + product_row + '][quantityterima]" value="'+json.products[i].quantityterima+'" /><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][pajak]" value="'+totalpajak+'"  readonly/><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][pembulatan]" value="'+pembulatan+'"  readonly/><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][net_cost]" value="'+json.products[i].net_cost+'"  /></td>';

          //html += '    <td class="right"></td>';
          html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_row + '][quantitypesan]" value="'+json.products[i].quantity+'"  readonly/><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][price]" value="'+harga+'"  readonly /></td>';
          html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_row + '][quantity]" value="'+quantitymax+'"  /><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][totalpajak]" value="'+totalpajak+'"  /><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][total]" value="'+json.products[i].total+'"  readonly/><input class="form-control" type="hidden" onblur="updatetotal()"  name="product[' + product_row + '][quantitymax]" value="'+json.products[i].quantitymax+'"  /></td>';
          //html += '    <td class="right"></td>';
        //  html += '    <td class="right"><input class="form-control" type="text" onblur="updatetotal()"  name="product[' + product_row + '][total]" value="'+json.products[i].total+'"  readonly/></td>';

          html +='</tr>';
          net_cost += 0;
          product_row++;
        }
        $("#list-product tbody").html(html);
        $("#totalqty").val(totalqty);
        $("#net_cost").val(net_cost);
        updatetotal();
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
  errmax=false;
  errqty=false;
  em='';
  if($("select[name='customer_id']").val() == null){
    error=true;
    em +="Customer harus dipilih <br>";
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

  tanggal=$("input[name='date_added']").val();
  if(tanggal < '<?php echo $locktanggal;?>'){
    error=true;
    em+="Tanggal tidak   boleh kurang dari <?php echo $locktanggal;?> <br>";
  }

  if($("input[name='jenisorder']").val() == null){
    error=true;
    em +="Jenis Order harus diisi <br>";
  }
  */
  if($(".pengiriman").val() == 2){

    if($(".address").val() == -1){
      //alert($("input[name='firstname']").val());
      if($("input[name='firstname']").val() == ""){
        error=true;
        em +="Nama alamat harus diisi <br>";
      }
      if($("input[name='lastname']").val() == ""){
        error=true;
        em +="Kontak alamat harus diisi <br>";
      }
      if($("input[name='address_1']").val() == ""){
        error=true;
        em +="Alamat harus diisi <br>";
      }
      if($("input[name='postcode']").val() == ""){
        error=true;
        em +="Kodepos harus diisi <br>";
      }

      if($("select[name='country_id']").val() == null){
        error=true;
        em +="Provinsi harus diisi <br>";
      }

      if($("select[name='zone_id']").val() == null){
        error=true;
        em +="Kota/Kabupaten harus diisi <br>";
      }

      if($("select[name='city_id']").val() == null){
        error=true;
        em +="Kecamatan harus diisi <br>";
      }

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
    qtypesan=$("input[name='product["+i+"][quantitypesan]']").val();
      qty=$("input[name='product["+i+"][quantity]']").val();
      qtyterima=$("input[name='product["+i+"][quantityterima]']").val();
      qtymax=$("input[name='product["+i+"][quantitymax]']").val();

      totalqty=Number(qty)+Number(qtyterima);

    /*if(qty <= 0){
      error=true;
      errqty=true;
    }*/
    if(totalqty > Number(qtypesan)){
      error=true;
      errdup=true;
    }

    if(Number(qty) > Number(qtymax)){
      error=true;
      errmax=true;
    }


  }


  }

  if(error){
    if(errdup){
      em+= "Quantity Kirim melebihi quantity dibeli.<br>";
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

//--></script>
<script>
<?php echo $footer; ?>
