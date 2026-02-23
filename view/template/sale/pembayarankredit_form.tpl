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
            <h3 class="box-title">Pembayaran Penjualan Kredit</h3>
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
                        <td>Tangal Bayar</td>
                        <td><input type="text" name="date_added" class="date form-control" value="<?php echo date('Y-m-d'); ?>" readonly ></td>
                    </tr>
                    <tr>
                       <td><span class="required">*</span>Customer ID</td>
                       <td ><select name="customer_id" class="form-control lokasi-pameran">

                       </select>
                       </td>
                     </tr>

                   </tr>
                   <tr>
                       <td>Saldo Deposit</td>
                       <td><input type="hidden"  id=deposit name="deposit" class="form-control" value="0" readonly><span id="nilaideposit"></span></td>
                   </tr>
                   <tr>
                      <td>Total Pembayaran</td>
                      <td><input type="text" onblur="updatetotal()" id="total" name="total" class="form-control" value="0" readonly></td>
                   </tr>
                   <tr>
                     <td>Keterangan</td>
                     <td><input type="text" name="linkterkait" class="form-control" value="-"></td>
                   </tr>


                    </table>
                    <table class="table table-bordered" id="list-product">
                      <thead>
                        <tr>
                            <th>No. Invoice</th>
                            <th>Sisa Tagihan</th>
                            <th>Alokasi Pembayaran</th>
                        </tr>
                      </thead>
                      <tbody>

                    </tbody>

                  <tfoot>

                    <tr>
                      <td colspan="2"></td>
                      <td class="left"><a onclick="addModule();" class="btn btn-success">Tambah Pembayaran</a>  </td>
                    </tr>

                  </tfoot>
                    </table>
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
$('.sidebar-menu').find('#menu-penjualan').addClass('active');
$('.sidebar-menu').find('#menu-pembayaran').addClass('active');
$('.sidebar-menu').find('#menu-pembayaran-invoice').addClass('active');
$('.sidebar-menu').find('#menu-pembayaran-kredit').addClass('active');
var product_row = 0;
function updatetotal(){
  total=0;
  i=0;
  error=false;
  while(i < product_row){
    if($("select[name='orders["+i+"][invoice_id]']").val() != undefined){
      t=$("input[name='orders["+i+"][total]']").val();
      if($.isNumeric(t)){
        total += Number(t);
      }else{
        error=true;
        $("input[name='orders["+i+"][total]']").val(0);
      }
    }
    i++;
  }
  if(!error){

  $("#total").val(total);
}else{
  alert("Nilai pembayaran harus berupa angka");
}
}
function simpan(){
  //updatetotal();
  error=false;
  errdup=false;
  errtotal=false;

  updatetotal();
  $(".error").remove();
  em='';

  if($("select[name='customer_id']").val() == null){
    error=true;
    em +="Customer harus diisi <br>";
    // swal("customer harus  diisi");
  }
  if($("input[name='linkterkait']").val() == ''){
    error=true;
    em +="Keterangan harus diisi <br>";
  }

  if(Number($("input[name='total']").val()) > Number($("input[name='deposit']").val())){
    error=true;
    em +="Total alokasi melebihi total deposit <br>";
  }

  tanggal=$("input[name='date_added']").val();
  if(tanggal < '<?php echo $locktanggal;?>'){
    error=true;
    em+="Tanggal tidak   boleh kurang dari <?php echo $locktanggal;?> <br>";
  }

  if(product_row == 0){
    error=true;
    em +="Invoice harus dipilih <br>";
  }
  cek = [];
  cektb=[];
  for(i=0;i<product_row;i++){
  //  pid=$("select[name='product["+i+"][product_id]']").val();
    invoice_id=$("select[name='orders["+i+"][invoice_id]']").val();
    qty=$("input[name='orders["+i+"][total]']").val();
    //tagihan=Math.round($("input[name='orders["+i+"][totaltagihan]']").val());
    tagihan=Number($("input[name='orders["+i+"][totaltagihan]']").val());
    console.log(tagihan);
    if(qty <= 0){
      error=true;
      errtotal=true;
    }

    if(qty > Number(tagihan.toFixed(2))){
      error=true;
      errtotal=true;
    }

    if(invoice_id != undefined){
      if(cek[invoice_id] == undefined){
        cek[invoice_id]=i;
      }else{
        errdup = true;
        error=true;
        //alert(product_id+' '+p);
      }


    }
  }
  if(error){
    if(errdup){
      em+= "Terdapat duplikasi data invoice.<br>";
    }
  if(errtotal){
      em +=" Total pembayaran invoice tidak valid";
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


function hapus(row){
  $('#product-row'+row).remove();
  updatetotal();
}
function addModule() {
	html = '  <tr id="product-row' + product_row + '">';
  html += '    <td class="left" style="width:300px"><select data-row="'+product_row+'" name="orders['+product_row+'][invoice_id]" class="nosurat form-control"></select></td>';
  html += '    <td class="right"><input class="form-control" readonly type="text" name="orders[' + product_row + '][totaltagihan]" /></td>';
html += '    <td class="right"><input class="form-control" onblur="updatetotal()" type="text" name="orders[' + product_row + '][total]" /></td>';


  html += '    <td class="right"><a class="btn btn-warning" onclick="hapus('+product_row+')" class="button">Hapus</a> <span></span></td>';

	html += '  </tr>';


	$('#list-product tbody').append(html);
  $(document).ready(function() {
    $(".nosurat").select2({
      ajax: {
      url:"index.php?route=sale/invoice/autocomplete&token=<?php echo $this->request->get['token']; ?>",
      dataType: 'json',
      data: function (params) {
        return {
          q: params.term,
          //p:3,
          customer_id:$("select[name='customer_id']").val()
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
      row=$(this).data('row');
      //alert(row);
      //alert(id);
      if(id != undefined & id != null){
      $.ajax({
        url: 'index.php?route=sale/invoice/detailinvoice&token=<?php echo $this->request->get['token']; ?>&id=' + id,
        dataType: 'json',
        success: function(json) {


            sisa=Number(json.totaltagihan) - Number(json.totalbayar);
            $("input[name='orders["+row+"][totaltagihan]']").val(sisa.toFixed(2));
        }
      })
    }


    });



  });

	product_row++;
}

</script>

<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd',minDate:'<?php echo $locktanggal; ?>',maxDate:new Date()});


$(".lokasi-pameran").select2({
  ajax: {
  url:"index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>",
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
    //alert(id);
    if(id != undefined & id != null){
    $.ajax({
      url: 'index.php?route=sale/customer/detail&token=<?php echo $this->request->get['token']; ?>&customer_id=' + id,
      dataType: 'json',
      success: function(json) {
        //alert(JSON.stringify(json));
          //$("#deposit").after("");
          console.log(json);
        $("#deposit").val(json.deposit);
        $("#nilaideposit").html(json.pdeposit);
      }
    })
  }


  });
});
//--></script>

<?php echo $footer; ?>
