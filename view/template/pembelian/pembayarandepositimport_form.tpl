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
            <h3 class="box-title">Pembayaran Deposit Pembelian Import</h3>
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
                        <td><span class="required">*</span>Vendor</td>
                        <td ><select name="vendor_id" class="form-control lokasi-pameran">

                        </select>
                        </td>
                      </tr>

                    <tr>
                       <td><span class="required">*</span>Tanggal Bayar</td>
                       <td ><input type="text" name="tgl_bayar" class="date form-control" value="<?php echo $tgl_bayar; ?>" readonly >
                       </td>
                     </tr>
                     <!--tr>
                        <td><span class="required">*</span>Status</td>
                        <td ><select name="status" class="form-control status">
                          <option value="1">Disimpan</option>
                          <option value="2">Diterima</option>
                        </select>
                        </td>
                      </tr-->
                      <tr>
                         <td><span class="required">*</span>Tanggal Diterima</td>
                         <td ><input type="text" name="tgl_diterima" class="date form-control" value="<?php echo $tgl_diterima; ?>" readonly >
                         </td>
                       </tr>
                      <tr>
                        <td>No.Kontrak/Invoice Impor</td>
                        <td >
                          <input type="text" name="no_kontrak" class="form-control" value="<?php echo $keterangan; ?>" >
                        </td>
                      </tr>
                     <tr>
                        <td>Keterangan</td>
                        <td >
                          <input type="text" name="keterangan" class="form-control" value="<?php echo $keterangan; ?>" >
                        </td>
                      </tr>
                      <tr>
                         <td><span class="required">*</span>Metode Pembayaran</td>
                         <td ><select name="metode_pembayaran" class="form-control metode">
                           <option value="1">Tunai</option>
                           <option value="2">Transfer Bank</option>
                           <option value="3">Giro</option>
                           <option value="4">Cheque</option>
                           <option value="5">Biaya</option>
                         </select>
                         </td>
                       </tr>
                      <tr>
                        <td><span class="required">*</span>Bank/Kas</td>
                        <td ><select name="bank_id" class="form-control bank">

                    		</select>
                        </td>
                      </tr>
                      <tr id="no-giro">
                          <td>Nomor Giro</td>
                          <td><input type="text" name="no_giro" id="no_giro" class="form-control"></td>
                      </tr>
                      <tr id="no-cheque">
                          <td>Nomor Cheque</td>
                          <td><input type="text" name="no_cheque" id="no_cheque" class="form-control" value="" ></td>
                      </tr>
                     <tr>
                         <td>Jumlah <br> (Dalam USD)</td>
                         <td><input type="text" name="nominal" onchange="updatetotal()" value="<?php echo $nominal; ?>" id="nominal" >&nbsp;<!-- <i><span id="total"></span></i> -->
                          <input type="hidden" name="nominals" id="nominals">
                         </td>
                     </tr>
                     <tr>
                         <td>Kurs</td>
                         <td><input type="text" name="kurs" onchange="updatetotal()" value="<?php echo $kurs; ?>" id="kurs">&nbsp;<!-- <i><span id="kurs"></span></i> -->
                          <input type="hidden" name="nkurs" id="nkurs">
                         </td>
                     </tr>
                     <tr>
                       <td>Total Deposit <br>(Dalam Rupiah)</td>
                       <td><input type="text" name="total" class="form-control" value="" readonly ></td>
                     </tr>
                      <tr>
                       <td>Biaya Lain-lain <br>(Dalam Rupiah)</td>
                       <td><input type="text" name="biaya_lain" class="form-control" value="0" readonly ></td>
                     </tr>
                      <tr>
                       <td>Pendapatan Lain-lain <br>(Dalam Rupiah)</td>
                       <td><input type="text" name="pendapatan_lain" class="form-control" value="0" readonly ></td>
                     </tr>
                     <tr>
                         <td>Total Biaya Provisi & Biaya Administrasi Bank <br> (Dalam Rupiah)</td>
                         <td><input type="text" name="biaya_bank" value="<?php echo $biaya_bank; ?>" onblur="biayabank()" id="biaya_bank">&nbsp;<!-- <i><span id="biaya_bank"></span></i> -->
                          <!-- <input type="text" name="nbiaya_bank" id="nbiaya_bank"> -->
                         </td>
                     </tr>
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
<script src="https://unpkg.com/imask"></script>
<script>
$('.sidebar-menu').find('#menu-pembelian').addClass('active');
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function hidenumberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "");
}

function addCommas(nStr)
{
  nStr += '';
  x = nStr.split('.');
  x1 = x[0];
  x2 = x.length > 1 ? '.' + x[1] : '';
  var rgx = /(\d+)(\d{3})/;
  while (rgx.test(x1)) {
    x1 = x1.replace(rgx, '$1' + ',' + '$2');
  }
  return x1 + x2;
}

function biayabank(){
  var b=$("input[name='biaya_bank']").val();
  //$("#biaya_bank").html(addCommas(b));
  var currencyMask = IMask(
    document.getElementById('biaya_bank'),
    //document.getElementsByTagName('nominal'),
    {
      mask: 'num',
      blocks: {
        num: {
          // nested masks are available!
          mask: Number,
          thousandsSeparator: ','
        }
      }
    });
}

function updatetotal(){
  kurs=$("input[name='kurs']").val();
  nominal=$("input[name='nominal']").val();

  var currencyMask = IMask(
    document.getElementById('kurs'),
    //document.getElementsByTagName('nominal'),
    {
      mask: 'num',
      blocks: {
        num: {
          // nested masks are available!
          mask: Number,
          thousandsSeparator: ','
        }
      }
    });
  nkurs=$("#kurs").val();
  var cm = nkurs;
  var cma = cm.replace(/,/g, '');
  $("input[name='nkurs']").val(cma);

  var currencyMask = IMask(
    document.getElementById('nominal'),
    //document.getElementsByTagName('nominal'),
    {
      mask: 'num',
      blocks: {
        num: {
          // nested masks are available!
          mask: Number,
          thousandsSeparator: ','
        }
      }
    });
  nnominal=$("#nominal").val();
  var cm = nnominal;
  var cmb = cm.replace(/,/g, '');
  $("input[name='nominals']").val(cmb);

  total=Number(cma)*Number(cmb);
  $("input[name='total']").val(total);
  $("input[name='total']").val(addCommas(total));
  $("#total").html(addCommas(nominal));

}
function simpan(){
  $(".error").remove();
  error=false;

  em='';
  if(!($("select[name='vendor_id']").val())){
    error=true;
    em +='Vendor Harus Dipilih <br>';
  }
  if(!($("select[name='metode_pembayaran']").val())){
    error=true;
    em +='Metode Pembayaran Harus Dipilih <br>';
  }
  if(!($("select[name='bank_id']").val())){
    error=true;
    em +='Bank/Kas Harus Dipilih <br>';
  }
  if(!($("input[name='tgl_bayar']").val())){
    error=true;
    em +='Tanggal bayar tidak boleh kosong <br>';
  }
  if(!($("input[name='nominal']").val())){
    error=true;
    em +='Jumlah pembayaran tidak boleh kosong <br>';
  }

  if(!$.isNumeric( Number($("input[name='nominals']").val())) ) {
    error=true;
    em +='Jumlah pembayaran harus berupa angka <br>';
  }
  if(Number($("input[name='nominals']").val()) <= 0) {
    error=true;
    em +='Jumlah pembayaran harus lebih dari 0 <br>';
  }

  if(!($("input[name='kurs']").val())){
    error=true;
    em +='Kurs tidak boleh kosong <br>';
  }
  if(!$.isNumeric( Number($("input[name='nkurs']").val())) ) {
    error=true;
    em +='Nilai Kurs harus berupa angka <br>';
  }
  if(Number($("input[name='kurs']").val()) <= 0) {
    error=true;
    em +='Kurs harus lebih dari 0 <br>';
  }

  if($("select[name='metode_pembayaran']").val() == 3){
    if(!($("input[name='no_giro']").val())){
      error=true;
      em +='Nomor giro tidak boleh kosong <br>';
    }
  }

  if($("select[name='metode_pembayaran']").val() == 4){
    if(!($("input[name='no_cheque']").val())){
      error=true;
      em +='Nomor Cheque tidak boleh kosong <br>';
    }
  }

  if(!($("input[name='tgl_diterima']").val())){
      error=true;
      em +='Tanggal diterima tidak boleh kosong <br>';
    }



  if(error){
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
$(document).ready(function() {
  updatetotal();
  $("#no-inv").hide();
  $("#tgl-diterima").hide();
  $("#no-giro").hide();
  $("#no-cheque").hide();
  $(".coa").on('change',function(){
    jenis=$(".coa").val();
    if(jenis == 1){
      $("#no-inv").hide();
    }else{
      $("#no-inv").show();
    }
  });
  $(".status").on('change',function(){
    status=$(".status").val();
    if(status == 1){
      $("#tgl-diterima").hide();
    }else{
      $("#tgl-diterima").show();
    }
  });
  $(".metode").on('change',function(){
    metode=$(".metode").val();
    if(metode == 3){
      $("#no-giro").show();
      $("#no_cheque").attr("value","");
    }else{
      $("#no-giro").hide();
    }
    if(metode == 4){
      $("#no-cheque").show();
      $("#no_giro").attr("value","");
    }else{
      $("#no-cheque").hide();
    }
  });
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
  $(".lokasi-pameran").select2({
    ajax: {
    url:"index.php?route=catalog/vendorimport/autocomplete&token=<?php echo $token; ?>",
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
  })
  $(".nosurat").select2({
    ajax: {
    url:"index.php?route=sale/invoice/autocomplete&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        q: params.term,
        p:4,
        customer_id:$(".lokasi-pameran").val()


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
$(".bank").select2({
  ajax: {
  url:"index.php?route=keuangan/bank/autocomplete&token=<?php echo $this->request->get['token']; ?>",
  dataType: 'json',
  data: function (params) {
    return {
      q: params.term,
      c:1

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
});
//--></script>

<?php echo $footer; ?>
