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
            <h3 class="box-title">Biaya</h3>
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
                     <td >
                       <select name="vendor_id" class="form-control lokasi-pameran">

                       </select>
                     </td>
                   </tr>
                      <tr>
                       <td><span class="required">*</span>Tanggal Awal</td>
                       <td ><input type="text" name="tglawal" class="date form-control" value="<?php echo $tglawal; ?>" readonly >
                       </td>
                     </tr>
                     <tr>
                      <td>Jenis Biaya</td>
                      <td >
                        <select name="jenisbiaya" class="form-control">
                          <?php
                          /*
                          6217 sewa kantor dan gudang
                          6220 perjalanan dinas
                          6261 profesional
                          6262 asuransi
                          */
                          ?>
                          <option value="1">Sewa Kantor dan Gudang</option>
                          <option value="2">Perjalanan Dinas</option>
                          <option value="3">Profesional</option>
                          <option value="4">Asuransi</option>
                          <!--option value="5">Renovasi Bangunan</option-->
                          <option value="6">Pembuatan Software</option>
                          <option value="7">Lain-lain</option>
                        </select>
                      </td>
                    </tr>
                     <tr>
                      <td><span class="required">*</span>Masa Berlaku/Durasi (Dalam satuan bulan)</td>
                      <td ><input type="text" name="masaberlaku" class="form-control" value="<?php echo $masaberlaku; ?>" >
                      </td>
                    </tr>
                    <tr>
                        <td>Keterangan</td>
                        <td >
                          <input type="text" name="keterangan" class="form-control" value="<?php echo $keterangan; ?>" >
                        </td>
                      </tr>
                     <tr>
                         <td>Nominal</td>
                         <td><input type="text" name="nilaisewa" class="form-control" value="<?php echo $nilaisewa; ?>" ></td>
                     </tr>

                     <tr>
                         <td>PPn</td>
                         <td><input type="text" name="ppn" class="form-control" value="<?php echo $ppn; ?>" ></td>
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
$('.sidebar-menu').find('#menu-biaya').addClass('active');

</script>

<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd',minDate:'<?php echo $locktanggal; ?>',maxDate:new Date()});
  $(".lokasi-pameran").select2({
    ajax: {
    url: 'index.php?route=catalog/vendorlokal/autocomplete&token=<?php echo $token; ?>',
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
  });

});
function simpan(){
  $(".error").remove();
  error=false;
  errdup=false;
  em='';
  if($("select[name='vendor_id']").val() == null){
    error = true;
    em += "Vendor harus dipilih.<br>";
  }
  nilaisewa=$("input[name='nilaisewa']").val();
  masaberlaku=$("input[name='masaberlaku']").val();
  ppn=$("input[name='ppn']").val();
  if(!$.isNumeric( Number(nilaisewa) )){
    error = true;
    em += "Nominal harus berupa angka.<br>";
  }else{
    if(Number(nilaisewa) < 0){
      error = true;
      em += "Nominal harus lebih dari sama dengan 0.<br>";
    }
  }

  if(!$.isNumeric( Number(masaberlaku) )){
    error = true;
    em += "Masa berlaku/Durasi harus berupa angka.<br>";
  }else{
    if(Number(masaberlaku) < 0){
      error = true;
      em += "Masa berlaku/Durasi harus lebih dari sama dengan 0.<br>";
    }
  }

  if(!$.isNumeric( Number(ppn) )){
    error = true;
    em += "PPn harus berupa angka.<br>";
  }else{
    if(Number(ppn) < 0){
      error = true;
      em += "PPn harus lebih dari sama dengan 0.<br>";
    }
  }
  tanggal=$("input[name='tglawal']").val();
  if(tanggal < '<?php echo $locktanggal;?>'){
    error=true;
    em+="Tanggal tidak   boleh kurang dari <?php echo $locktanggal;?> <br>";
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
//--></script>

<?php echo $footer; ?>
