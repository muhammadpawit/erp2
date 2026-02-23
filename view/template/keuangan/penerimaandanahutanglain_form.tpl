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
            <h3 class="box-title">Input penerimaan dana hutang lain customer</h3>
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
                       <td><span class="required">*</span>Tanggal Bayar</td>
                       <td ><input type="text" name="tanggal" class="date form-control" value="<?php echo $tgl_bayar; ?>" readonly >
                       </td>
                     </tr>
                       <tr>
                        <td>*No.Dokumen Terkait</td>
                        <td >
                          <input type="text" name="linkterkait" class="form-control" value="<?php echo $keterangan; ?>" >
                        </td>
                      </tr>
                      <tr>
                        <td>*Keterangan</td>
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
                          <td><input type="text" name="no_giro" class="form-control" value="<?php echo $no_giro; ?>" ></td>
                      </tr>
                     <tr>
                         <td>Jumlah</td>
                         <td><input type="text" name="nominal" class="form-control" value="<?php echo $nominal; ?>" ></td>
                     </tr>
                     <tr>
                         <td>Biaya Administrasi Bank</td>
                         <td><input type="text" name="biaya_bank" class="form-control" value="<?php echo $biaya_bank; ?>" ></td>
                     </tr>
                     <tr>
                         <td>Biaya Lain-lain</td>
                         <td><input type="text" id="biaya_lain" onblur="cekbiayalain()" name="biaya_lain" class="form-control" value="<?php echo $biaya_lain; ?>" readonly></td>
                     </tr>
                    </table>
                    <input type="hidden" name="ref_akun" class="form-control"/>
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
$('.sidebar-menu').find('#menu-keuangan').addClass('active');
function cekbiayalain(){
	var b = $("input[name='biaya_lain']").val();
	if(b>1000){
		alert("biaya lain-lain maksimal 1000");
		location.reload();
	}
}
function simpan(){
  $(".error").remove();
  error=false;

  em='';
  var b = $("input[name='biaya_lain']").val();
	if(b>1000){
		//alert("biaya lain-lain maksimal 1000");
		error=true;
		em +='Biaya lain-lain maksimal 1000 <br>';
	}
  // if(!($("select[name='jenis']").val())){
  //   error=true;
  //   em +='Jenis Pembayaran Harus Dipilih <br>';
  // }
  // if(!($("select[name='customer_id']").val())){
  //   error=true;
  //   em +='Customer Harus Dipilih <br>';
  // }
  if(!($("select[name='metode_pembayaran']").val())){
    error=true;
    em +='Metode Pembayaran Harus Dipilih <br>';
  }
  if(!($("select[name='bank_id']").val())){
    error=true;
    em +='Bank/Kas Harus Dipilih <br>';
  }
  if(!($("input[name='tanggal']").val())){
    error=true;
    em +='Tanggal bayar tidak boleh kosong <br>';
  }
  if(!($("input[name='keterangan']").val())){
    error=true;
    em +='Keterangan harus diisi<br>';
  }
  if(!($("input[name='nominal']").val())){
    error=true;
    em +='Jumlah pembayaran tidak boleh kosong <br>';
  }
  if(!$.isNumeric( Number($("input[name='nominal']").val())) ) {
    error=true;
    em +='Jumlah pembayaran harus berupa angka <br>';
  }
  if(Number($("input[name='nominal']").val()) <= 0) {
    error=true;
    em +='Jumlah pembayaran harus lebih dari 0 <br>';
  }
  if($("select[name='metode_pembayaran']").val() == 3){
    if(!($("input[name='no_giro']").val())){
      error=true;
      em +='Nomor giro tidak boleh kosong <br>';
    }

  }
  
  tanggal=$("input[name='tanggal']").val();
  if(tanggal < '<?php echo $locktanggal;?>'){
    error=true;
    em+="Tanggal tidak   boleh kurang dari <?php echo $locktanggal;?> <br>";
  }

  if($("select[name='status']").val() == 2){
    if(!($("input[name='tgl_diterima']").val())){
      error=true;
      em +='Tanggal diterima tidak boleh kosong <br>';
    }

  }

  if($("select[name='jenis']").val() == 2){
    if(!($("select[name='ref']").val())){
      error=true;
      em +='Nomor invoice harus dipilih <br>';
    }

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
  $("#no-inv").hide();
  $("#tgl-diterima").hide();
  $("#no-giro").hide();
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
    }else{
      $("#no-giro").hide();
    }
  });
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
}).on("select2:select",function(e){
  console.log(this.value);
  var bank_id=this.value;
      $.ajax({
        url: 'index.php?route=keuangan/penerimaandanahutanglain/rek&token=<?php echo $token; ?>&bank_id=' + bank_id,
        dataType: 'json',
        success: function(json) {
          console.log(json);
          $('input[name=\'ref_akun\']').val(json);
        }
      })
});
});
//--></script>

<?php echo $footer; ?>
