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
            <h3 class="box-title">Input follow up penagihan customer</h3>
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
                       <td><span class="required">*</span> Tanggal Follow Up</td>
                       <td ><i class="fa fa-clock-o"></i>&nbsp;<?php echo date('Y-m-d H:i:s'); ?><input type="hidden" name="tanggal" class="date form-control" value="<?php echo date('Y-m-d H:i:s'); ?>" readonly >
                       </td>
                     </tr>
                      <tr>
                        <td><span class="required">*</span> Nama Customer</td>
                        <td >
                          <select name="customer_id" class="form-control customer">
                            <option value="*">Pilih</option>
                          </select>
                        </td>
                      </tr>
                      <tr id="no-giro">
                          <td>Media Follow Up</td>
                          <td>
                            <input type="radio" name="media" value="1"> Whatsapp<br>
                            <input type="radio" name="media" value="2"> Telephone<br>
                            <input type="radio" name="media" value="3"> E-mail<br>
                            <input type="radio" name="media" value="4"> Sales<br>
                            <input type="radio" name="media" value="5"> Surat<br>
                            <input type="radio" name="media" value="6"> Kurir<br>
                          </td>
                      </tr>
                     <tr>
                         <td>Hasil Pembicaraan</td>
                         <td>
                          <textarea name="hasil_pembicaraan" id="messageForm" class="form-control textarea" rows="4"></textarea>
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
  var customer_id = $('select[name=\'customer_id\']').val();;
	if (customer_id=='*') {
		swal("Customer Harus dipilih");
    return false;
	}
  if ($('input[name="media"]:checked').length == 0) {
    swal("Media Harus dipilih");
    return false;
  }
  var isi =$("#messageForm").val();
   if (isi=='') {
    swal("Hasil Pembicaraan Harus diisi");
    return false;
  }
  $('#form').submit();
}
</script>

<script type="text/javascript"><!--
$(document).ready(function() {
  //$('.textarea').wysihtml5();
	$('.date').datepicker({dateFormat: 'yy-mm-dd',minDate:'<?php echo $locktanggal; ?>',maxDate:new Date()});
  $(".customer").select2({
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
