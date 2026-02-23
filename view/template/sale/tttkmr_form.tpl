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
            <h3 class="box-title">Tanda Terima Tabung Kosong Milik Relasi</h3>
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
                         <td>Nomor TTTK manual</td>
                         <td>
                           <input type="text" class="form-control" name="tttk_manual" id="" value="" >
                         </td>
                     </tr>
                     <tr>
                         <td>Tanggal</td>
                         <td>
                           <input type="text" class="date form-control" name="date_added" value="<?php echo date('Y-m-d'); ?>" readonly>
                         </td>
                     </tr>
                    <tr>
                         <td>Customer</td>
                         <td>
                           <select name="customer_id" onchange="removeproduct()" class="customer form-control">

                             </select>
                          <input type="hidden" name="customer_group_id" id="group" value="">
                         </td>
                     </tr>
                     <tr>
                         <td>Metode Pengambilan</td>
                         <td>
                           <select class="form-control pengiriman" name="pengiriman">
                             <option value="1">Dijemput</option>
                             <option value="2">Diantar</option>

                           </select>
                         </td>
                     </tr>
                     <tr>
                          <td>Total Quantity</td>
                          <td>
                            <input type="text" class="form-control" name="quantity" id="tabungmr" readonly value="" >
                          </td>
                      </tr>


                   </table>
                </div>
                <div class="col-md-6">
                  <table class="table alamat">

                    <tr>
                        <td>Alamat Pengambilan</td>
                        <td>
                          <select name="address_id" class="address  form-control">
                            <option value="-1">Gunakan Alamat Baru</option>

                          </select>
                        </td>
                    </tr>
                    <tr>
                      <td><span class="required">*</span> Nama Toko</td>
                      <td><input class="form-control" type="text" name="firstname" value="<?php echo $firstname; ?>" />
                        <?php if (isset($error_address_firstname)) { ?>
                        <span class="error"><?php echo $error_address_firstname; ?></span>
                        <?php } ?></td>
                    </tr>
                    <tr>
                      <td><span class="required">*</span> Nama Pengelola</td>
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
                            <select name="kernet[1]" class="sales form-control">

                              </select>

                          </td>
                      </tr>
                      <tr>
                           <td>Kernet 2</td>
                           <td>
                             <select name="kernet[2]" class="sales form-control">

                               </select>

                           </td>
                       </tr>
                       <tr>
                            <td>Kernet 3</td>
                            <td>
                              <select name="kernet[3]" class="sales form-control">

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
                        <th class="left">Jenis Gas</th>
                        <th class="right">Qty</th>
                        <th class="right">Tutup</th>

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
<script>
$('.sidebar-menu').find('#menu-penjualan').addClass('active');
$('.sidebar-menu').find('#menu-penjualan-mr').addClass('active');
$(function(){
  $(".pengiriman").on('change',function(){
    p=$(".pengiriman").val();
    if(p==2){
      $('.alamat').hide();
      $('.alamat input').attr('readonly', 'readonly');
      $('.alamat select').attr('readonly', 'readonly');
    }else{
      $('.alamat input').removeAttr('readonly');
      $('.alamat select').removeAttr('readonly');
      $('.alamat').show();
    }
  })
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
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});


});
//--></script>
<script type="text/javascript"><!--
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
function updatetotal(){
  //alert('test');
  //tabungmp=0;
  tabungmr=0;
  error=false;

  i = 0;
  while(i < product_row){
    //alert(i);
    if($("select[name='product["+i+"][product_id]']").val() != undefined){
      tabungmr +=Number($("input[name='product["+i+"][quantity]']").val());

  }
  i++;
  }

  if(!error){
    //$("#tabungmp").val(tabungmp);
    $("#tabungmr").val(tabungmr);
  }

}
function addModule() {
  html  = '<tbody id="product-row' + product_row + '">';
	html += '  <tr>';
  html +='<td class="center" style="width: 3px;"><img src="view/image/delete.png" onclick="hapus('+product_row+')" style="cursor: pointer;" /></td>';
	html += '    <td class="left"><select style="width:300px;" data-id="'+product_row+'" name="product[' + product_row + '][product_id]" class="product form-control"></select><input type="hidden" name="pemilik" value="1"></td>';
	html += '    <td class="right"><input type="text" class="form-control" name="product[' + product_row + '][quantity]" onblur="updatetotal()" value="" /></td>';
  html += '    <td class="right"><select class="form-control" name="product[' + product_row + '][tutup]"><option value="1">Dengan Tutup</option><option value="2">Tanpa Tutup</option></select></td>';
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
          statustabung:2


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
function simpan(){
  updatetotal();
  $(".error").remove();
  error=false;
  errdup=false;
  errqty=false;
  em='';

  //cek error data
  //alert($("select[name='sales']").val() );


  if($("select[name='customer_id']").val() == null){
    error=true;
    em +="Customer harus diisi <br>";
  }


  if(product_row == 0){
    error=true;
    em +="Produk harus dipilih <br>";
  }
  cek = [];
  for(i=0;i<product_row;i++){
    pid=$("select[name='product["+i+"][product_id]']").val();
    tutup=$("select[name='product["+i+"][tutup]']").val();
    if(pid != undefined){
  		if(cek[pid+'-'+tutup] == undefined){

  			cek[pid+'-'+tutup] = i;

  		}
  		else{
  			errdup = true;
  			error=true;
  			//alert(product_id+' '+p);
  		}
		}
  }

  if(error){
    if(errdup){
      em+= "Terdapat duplikasi data produk.<br>";
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
  });

  $(".customer").select2({
    ajax: {
    url:"index.php?route=sale/customer/autocomplete&token=<?php echo $this->request->get['token']; ?>",
      dataType: 'json',
    data: function (params) {
      return {
        q: params.term,
        s:$("select[name='sales']").val()

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
        //  alert(JSON.stringify(json));
          $('input[name=\'customer_group_id\']').val(json.customer_group_id);

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

  $(".jenisorder").select2({
    ajax: {
    url:"index.php?route=catalog/category/autocompletecat&token=<?php echo $this->request->get['token']; ?>",
      dataType: 'json',
    data: function (params) {
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
  });


})
</script>
<?php echo $footer; ?>
