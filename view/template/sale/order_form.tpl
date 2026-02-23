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
            <h3 class="box-title">Sales Order</h3>
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
                    //if($this->user->getGroupId() != 21){
                    ?>
                    <tr>
                         <td>Sales</td>
                         <td>
                           <select name="sales" onchange="removeproduct()" class="sales form-control">

                           </select>

                         </td>
                     </tr>
                     <?php
                  /* }else{
                     ?>
                     <input type="hidden" name="sales" class="sales" value="<?php echo $this->user->getId(); ?>">
                     <?php
                   }*/
                     ?>
                      <tr>
                       <td>Marketplace</td>
                       <td>
                         <select name="marketplace" class="form-control">
                           <option value="0">Tidak ada</option>
                           <?php if(isset($marketplace)){ ?>
                            <?php
                                foreach($marketplace as $m){
                                  echo "<option value='".$m['id']."'>".$m['nama']."</option>";
                                }
                            ?>
                           <?php } ?>
                         </select>
                       </td>
                     </tr>
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
                     <tr>
                         <td>Jenis Penjualan</td>
                         <td>
                           <select class="form-control penjualan" name="jenispenjualan">
                             <option value="1">Penjualan MP</option>
                             <option value="2">Penjualan MR</option>
                             <!--option value="3">Penjualan Bahan Baku</option-->
                           </select>
                           </td>
                     </tr>
                     <tr id="tttk">
                          <td>Nomor TTTK</td>
                          <td>
                            <select style="width:300px" name="tttk" class="no_so form-control">

                              </select>

                          </td>
                      </tr>
                     <tr>
                         <td>Jenis Stok</td>
                         <td>
                           <select class="form-control" name="jenisstok">
                             <option value="1">Produk Dagang</option>
                             <option value="2">Hasil Produksi</option>

                           </select>
                           </td>
                     </tr>
                     <tr>
                         <td>Metode Pengiriman</td>
                         <td>
                           <select class="form-control pengiriman" name="pengiriman" required="required">
                           	 <option value="*">Pilih</option>
                             <option value="2">Diantar</option>
                             <option value="1">Diambil</option>
                           </select>
                           </td>
                     </tr>
                      <tr>
                        <td>PPn</td>
                        <td>
                           <select class="form-control statuspajak" name="statuspajak" onchange="removeproduct()">
                             <option value="1" selected>Belum Termasuk</option>
                             <option value="2">Sudah Termasuk</option>
                             <option value="3" >Tanpa Pajak</option>
                           </select>
                        </td>
                      </tr>
                      <tr id="columnketeranganpajak">
                        <td>Keterangan Tanpa Ppn*</td>
                        <td><input type="text" name="keteranganpajak" id="keteranganpajak" class="form-control"><small><i>(contoh : area batam,dll.)</i></small></td>
                      </tr>
                     <tr>
                        <td>Metode Pembayaran</td>
                        <td><select name="metode_pembayaran" class="form-control">

                          </select>
                        </td>
                      </tr>

                      <tr>
                          <td>Tanggal Order</td>
                          <td>
                            <input type="text" class="date form-control" name="date_added" value="<?php echo date('Y-m-d'); ?>" readonly>
                          </td>
                      </tr>
                      <tr>
                          <td>Jatuh Tempo (dalam hari)</td>
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
                          </td>
                      </tr>

                   </table>
                </div>
                <div class="col-md-6">
                  <table class="table" id="catatan">
                  	<tr>
                  		<td>Catatan / Note</td>
                  		<td>
                  			<textarea class="form-control" rows="3" cols="50" name="catatan"></textarea>
                  		</td>
                  	</tr>
                  </table>
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
                      <td><input class="form-control" type="text" name="firstname" value="<?php echo $firstname; ?>" />
                        <?php if (isset($error_address_firstname)) { ?>
                        <span class="error"><?php echo $error_address_firstname; ?></span>
                        <?php } ?></td>
                    </tr>
                    <tr>
                      <td><span class="required">*</span> Kontak</td>
                      <td><input class="form-control" type="text" name="lastname" value="<?php echo $lastname; ?>" />
                        <?php if (isset($error_address_lastname)) { ?>
                        <span class="error"><?php echo $error_address_lastname; ?></span>
                        <?php } ?></td>
                    </tr>

                    <tr>
                      <td><span class="required">*</span> Alamat</td>
                      <td><input class="form-control" type="text" name="address_1" value="<?php echo $address_1; ?>" />
                        <?php if (isset($error_address_address_1)) { ?>
                        <span class="error"><?php echo $error_address_address_1; ?></span>
                        <?php } ?></td>
                    </tr>
                    <tr>
                      <td>Alamat (baris 2)</td>
                      <td><input class="form-control" type="text" name="address_2" value="<?php echo $address_2; ?>" /></td>
                    </tr>
                    <tr>
                      <td><span id="postcode-required<?php echo $address_row; ?>" class="required">*</span> Kodepos</td>
                      <td><input class="form-control" type="text" name="postcode" value="<?php echo $postcode; ?>" /></td>
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
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <table class="table" id="list-product" >
                    <thead>
                      <tr>
                        <th></th>
                        <th class="left">Nama Produk</th>
                        <!--th class="left">Tabung Gas</th-->
                        <th class="right">Harga Satuan</th>
                        <th class="right">Ppn</th>
                        <th class="right" style="width:10%">Qty</th>
                        <th class="right">Diskon</th>
                        <th class="right">Total</th>

                      </tr>
                    </thead>
                    <?php $product_row=0;?>


                  <tfoot>
                    <tr>
                      <td colspan="8"></td>
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
      $('#catatan').show();
      $('.alamat').hide();
      $('.alamat input').attr('readonly', 'readonly');
      $('.alamat select').attr('readonly', 'readonly');
    }else{
      $('.alamat input').removeAttr('readonly');
      $('.alamat select').removeAttr('readonly');
      $('.alamat').show();
      // $('#catatan').hide();
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
	$('.date').datepicker({
    dateFormat: 'yy-mm-dd',
    maxDate:new Date(),
    //minDate:0,  
  });
  $("#columnketeranganpajak").hide();

});
//--></script>
<script type="text/javascript">
    var product_row = <?php echo $product_row; ?>;
function removeproduct(){
  $('#list-product tbody').remove();
  // updatepengiriman();

  product_row=0;
  updatetotal();
   var statuspajak=$("select[name='statuspajak']").val();
  if(statuspajak==3){
    $("#keteranganpajak").val("");
    $("#columnketeranganpajak").show();
    $("#keteranganpajak").attr("required", true);
  }else{
    $("#keteranganpajak").val();
    $("#columnketeranganpajak").hide();
  }

}

function hapus(row){
  $('#product-row'+row).remove();
  $('#list-product tbody').each(function(){
    parent=$(this).data('parent');
    if(parent == row){
      $(this).remove();
    }
  });
  updatetotal();
}

function notif(angka){
  if(angka==1){
    $(".notif").hide();
  }else{
    //$("").show();
  }
}

function updatetotal(row){
  if(row != undefined){

    batasbawah=$("input[name='product["+row+"][batasbawah]']").val();
    harga_terendah=$("input[name='product["+row+"][harga_terendah]']").val();
    harga=$("input[name='product["+row+"][price]']").val();
    nilaipajak=$("select[name='product["+row+"][nilaipajak]']").val();
    //console.log(harga);
    hargapluspajak=Number(harga*0.1) + Number(harga);
    hargatanpapajak=Number(harga);
    // nilai toleransi 2, jika selisih masih sama dengan atau dibawah 2, tidak perlu memerlukan persetujuan 
    toleransi=Number(Math.round(harga_terendah))-Number(Math.round(hargapluspajak));
    toleransitp=Number(Math.round(harga_terendah))-Number(Math.round(hargatanpapajak));
    //console.log(Math.round(hargapluspajak))
    if(Number(batasbawah) > Number(harga)){
      $("input[name='product["+row+"][price]']").after('<br><small style="color:red">Harga kurang dari batasbawah</small>');
    }
    if(nilaipajak==1){
      console.log(harga_terendah);
      //if(Number(harga_terendah) > Number(hargapluspajak)){
      if(Number(toleransi)>2){
        $("input[name='product["+row+"][price]']").after('<div class="alert alert-danger alert-dismissible notif"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Perlu persetujuan harga SO.</div>');
      }else{
        notif(1);
        //$("input[name='product["+row+"][price]']").after('<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong>harga jual sama/lebih tinggi dari harga terendah.</div>');
      }
    }

    if(nilaipajak==2){
      //console.log(harga_terendah);
      //if(Number(harga_terendah) > Number(hargapluspajak)){
      if(Number(toleransitp)>2){
        $("input[name='product["+row+"][price]']").after('<div class="alert alert-danger alert-dismissible notif"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Perlu persetujuan harga SO.</div>');
      }else{
        notif(1);
        //$("input[name='product["+row+"][price]']").after('<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong>harga jual sama/lebih tinggi dari harga terendah.</div>');
      }
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
    diskonprods=0;

    if($("input[name='product["+i+"][product_id]']").val() != undefined){
  		quantity=$("input[name='product["+i+"][quantity]']").val();
      harga=$("input[name='product["+i+"][price]']").val();
      diskonprods=$("input[name='product["+i+"][diskonprods]']").val();
    //  diskonproduk=$("input[name='product["+i+"][diskon]']").val();
      //alert($("input[name='product["+i+"][weight]']").val());
      error=false;
      if($.isNumeric( Number(quantity) ) & $.isNumeric( Number(harga) )){
          if(nilaipajak == 1){
            //subtotal=(110/100)*(Number(quantity) * (Number(harga)));

            //  pajak=Math.round((0.1 * Number(harga)))*Number(quantity);
            pajak = Math.floor(0.1*(Number(harga)*Number(quantity)));
            subtotal=Math.floor( (Number(harga)*Number(quantity))+Number(pajak) - (Number(diskonprods)*Number(quantity)) );
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

            subtotal=Math.floor((Number(quantity) * (Number(hargasatuan))) + ((10/100)*(hargasatuan*Number(quantity))) - (Number(diskonprods)*Number(quantity)) );
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
          diskonp += Number(quantity) * (Number(diskonprods));
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
    grandtotal=totalsub+totalpajak-diskonp;

    $("#sub_total").val(totalsub);
    $("#pajak").val(totalpajak);
    //$("#pembulatan").val(totalpembulatan);
    //$("#diskon").val(diskon);
    $("#diskon").val(diskonp);
    $("#total").val(total);
  }
}
function addModule() {
  var statuspajak=$("select[name='statuspajak']").val();
  var keteranganpajak=$("#keteranganpajak").val();
  html  = '<tbody data-parent="0" id="product-row' + product_row + '">';
	html += '  <tr>';
  html +='<td class="center" style="width: 3px;"><img src="view/image/delete.png" onclick="hapus('+product_row+')" style="cursor: pointer;" /></td>';
  html += '    <td class="left" id="productname-'+product_row+'"><select style="width:250px" data-id="'+product_row+'" name="product[' + product_row + '][product_id]" class="product form-control"></select></td>';
  	html += '    <td class="right"><input type="text" class="form-control" name="product[' + product_row + '][price]" onblur="updatetotal('+product_row+')" value="" /><input type="hidden" name="product[' + product_row + '][net_cost]" value="" readonly/><input type="hidden" name="product[' + product_row + '][pricelist]" value="" readonly/><input type="hidden" name="product[' + product_row + '][batasbawah]" value="" readonly/>';
  html +='<input type="hidden" class="form-control" name="product[' + product_row + '][pajak]"  value="" /></td>';
  html += '<td><select class="form-control" name="product[' + product_row + '][nilaipajak]" onchange="updatetotal('+product_row+')">';
  //html += '<option value="1" >Belum Termasuk</option>';
  //html += '<option value="2" >Sudah Termasuk</option>';
  //html += '<option value="3" >Tanpa Pajak</option>';
  if(statuspajak==1){
    html += '<option value="1" >Belum Termasuk</option>';
  }else if(statuspajak==2){
    html += '<option value="2" selected>Sudah Termasuk</option>';
  }else{
    html += '<option value="3" >Tanpa Pajak</option>';
  }
  html += '</select></td>';
//  htm\ += '    <td class="right"><input type="text" class="form-control" name="product[' + product_row + '][diskon]" onblur="updatetotal()" value="" /></td>';
  html += '    <td class="right"><input type="text" class="form-control" name="product[' + product_row + '][quantity]" onblur="updatetotal('+product_row+')" value="1" /></td>';
  html += '    <td class="right"><input type="text" class="form-control" name="product[' + product_row + '][diskonprods]" onblur="updatetotal('+product_row+')" value="0" /></td>';
  html += '    <td class="right"><input type="text" class="form-control" name="product[' + product_row + '][total]" value="" readonly/><input type="hidden" class="form-control" name="product[' + product_row + '][harga_terendah]" value="" readonly/><input type="hidden" name="product[' + product_row + '][jenistabung]" value="0" readonly/><input type="hidden" name="product[' + product_row + '][tabung_id]" value="0" readonly/></td>';
  html += '</tbody>';

	$('#list-product tfoot').before(html);

	product_row++;
  penjualan=$(".penjualan").val();
  //alert(penjualan);
  var statuspajak=$("select[name='statuspajak']").val();
  var keteranganpajak=$("#keteranganpajak").val();
  if(statuspajak==3){
    if(keteranganpajak==""){
      alert("Keterangan tanpa Ppn harus diisi");
      $("#keteranganpajak").focus();
      removeproduct();
    }
  }

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
              if(json.detail.name !=undefined){
              child=$("select[name='product["+coba+"][tabung_id]']").data('child');
              if(child > 0){
                hapus(child);
              }
              if(json.harga_terendah==0){
                swal("Tidak bisa buat SO untuk produk ini karena harga terendah untuk produk tersebut belum di set");
                hapus(coba);
                return false;
              }
              /*addhtml='<select data-id="'+coba+'"  data-row="'+coba+'" data-child="0" name="product[' + coba + '][tabung_id]" class="tabung form-control"><option value="0">Tanpa Tabung</option></select><input type="hidden" name="product[' + coba + '][jenistabung]" value="0" readonly/>';
              $("#tabung-"+coba).html('');
              */
              $("select[name='product["+coba+"][product_id]']").remove();

              addhtml='<input type="hidden" name="product['+coba+'][product_id]" value="'+id+'"><input type="text" class="form-control" readonly name="product['+coba+'][name]" value="'+json.detail.name+'">';
              $("#productname-"+coba).html(addhtml);
              $("input[name='product["+coba+"][price]']").val(json.lastprice);
              $("input[name='product["+coba+"][pricelist]']").val(json.pricelist);
              $("input[name='product["+coba+"][batasbawah]']").val(json.batasbawah);
              $("input[name='product["+coba+"][net_cost]']").val(json.detail.net_cost);
              $("input[name='product["+coba+"][harga_terendah]']").val(json.harga_terendah);
              //$("input[name='product["+coba+"][diskon]']").val(json.diskon);
              //$("input[name='product["+coba+"][quantity]']").val(1);

              total=Number($("input[name='product["+coba+"][quantity]']").val() * $("input[name='product["+coba+"][price]']").val());
              $("input[name='product["+coba+"][total]']").val(total);
              $("input[name='product["+coba+"][pajak]']").val(total*0.1);
              if(json.detail.jenistabung > 0){
                if(json.detail.jenistabung == 1){
                  //$("input[name='product["+coba+"][quantity]']").prop('readonly',true);
                  $("input[name='product["+coba+"][jenistabung]']").val(json.detail.jenistabung);
                }else{
                    $("input[name='product["+coba+"][jenistabung]']").val(json.detail.jenistabung);
                }

              }
              updatetotal();

            }else{
              hapus(coba);
              swal("Produk tidak ditemukan");
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
          url:"index.php?route=catalog/tabungmr/autocompleteprodso&token=<?php echo $this->request->get['token']; ?>",
            dataType: 'json',
          data: function (params) {
            return {
              q: params.term,
              filter_customer_id:$('select[name=\'customer_id\']').val(),
              tttk: $('select[name=\'tttk\']').val()
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

  if($("select[name='pengiriman']").val() == null | $("select[name='pengiriman']").val() == undefined | $("select[name='pengiriman']").val() =="*"){
    error=true;
    em +="Metode pengiriman harus dipilih <br>";
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
   // tabung_id=$("select[name='product["+i+"][tabung_id]']").val();
    qty=$("input[name='product["+i+"][quantity]']").val();
    jenistabung=$("input[name='product["+i+"][jenistabung]']").val();

    if(qty <= 0){
      error=true;
      errqty=true;
    }

    /*if(tabung_id != undefined){
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


    }*/
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
          //console.log(JSON.stringify(json));
          $('input[name=\'customer_group_id\']').val(json.customer_group_id);
          $('textarea[name=\'catatan\']').val(json.alamat);
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

//--></script>
<script>
<?php echo $footer; ?>
