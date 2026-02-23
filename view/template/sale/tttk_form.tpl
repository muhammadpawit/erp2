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
            <h3 class="box-title">Tanda Terima Tabung Kosong MP</h3>
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
                           <select class="form-control" name="pengiriman">
                             <option value="1">Dijemput</option>
                             <option value="2">Diantar</option>

                           </select>
                         </td>
                     </tr>



                   </table>
                </div>
                <div class="col-md-6">
                  <table class="table">
                    <tr>
                        <td>Alamat Pengambilan</td>
                        <td>
                          <select name="address_id" class="address form-control">

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
                        <th class="left">No. Tabung</th>
                        <th class="right">Ukuran</th>
                        <th class="right">Tutup</th>
                        <th class="right">Keterangan</th>

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
$('.sidebar-menu').find('#menu-tttk').addClass('active');

</script>

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
  tabungmp=0;
  tabungmr=0;
  error=false;

  i = 0;
  while(i < product_row){
    //alert(i);
    if($("select[name='product["+i+"][product_id]']").val() != undefined){
      if($("select[name='product["+i+"][pemilik]']").val() == 1){
        tabungmp +=1;
      }else{
        tabungmr +=1;
      }


  }
  i++;
  }

  if(!error){
    $("#tabungmp").val(tabungmp);
    $("#tabungmr").val(tabungmr);
  }

}
function addModule() {
  html  = '<tbody id="product-row' + product_row + '">';
	html += '  <tr>';
  html +='<td class="center" style="width: 3px;"><img src="view/image/delete.png" onclick="hapus('+product_row+')" style="cursor: pointer;" /></td>';
	html += '    <td class="left"><select data-id="'+product_row+'" name="product[' + product_row + '][product_id]" class="product form-control"></select><input type="hidden" name="pemilik" value="1"></td>';
	html += '    <td class="right" id="ukuran'+product_row+'"></td>';
  html += '    <td class="right"><select class="form-control" name="product[' + product_row + '][tutup]"><option value="1">Dengan Tutup</option><option value="2">Tanpa Tutup</option></select></td>';
  html += '    <td class="right"><input type="text" class="form-control" name="product[' + product_row + '][keterangan]" value="" /></td>';
	html += '  </tr>';
	html += '</tbody>';

	$('#list-product tfoot').before(html);

	product_row++;

  $(function(){
    $(".product").select2({
      ajax: {
      url:"index.php?route=catalog/tabungmp/autocomplete&token=<?php echo $this->request->get['token']; ?>&status=6",
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
            $("#ukuran"+coba).text(json.namaukuran);
            if(json.status != 6){
                alert("Tabung tersedia di gudang.");
                hapus(coba);
              }


            }
          })
      }
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
  if($("select[name='sales']").val() == null){
    if($("input[name='sales']").val() == "" | $("input[name='undefined']").val() == ""){
      error=true;
      em +="Sales harus diisi <br>";
    }

  }

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
    if(pid != undefined){
  		if(cek[pid] == undefined){

  			cek[pid] = i;

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
