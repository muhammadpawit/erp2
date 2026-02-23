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
            <h3 class="box-title">Biaya Iklan</h3>
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
                       <td><span class="required">*</span>Tanggal Tagihan</td>
                       <td ><input type="text" name="tgl_tagihan" class="date form-control" value="<?php echo $tgl_tagihan; ?>" readonly >
                       </td>
                     </tr>
                     <tr>
                      <td><span class="required">*</span>Tanggal Jatuh Tempo</td>
                      <td ><input type="text" name="jatuhtempo" class="date form-control" value="<?php echo $jatuhtempo; ?>" readonly >
                      </td>
                    </tr>
                    <tr>
                       <td><span class="required">*</span>No. Faktur</td>
                       <td >
                         <input type="text" name="no_faktur" class="form-control" value="<?php echo $no_faktur; ?>" >
                       </td>
                     </tr>
                     <tr>
                       <td><span class="required">*</span>Jenis Tagihan</td>
                       <td colspan="2">
                         <select name="jenis" class="form-control">
                           <option value="1">Biaya Iklan</option>
                           <option value="2">Iklan Dibayar Dimuka</option>

                         </select>
                       </td>
                     </tr>
                     <tr id="sewa">
                       <td>Referensi Iklan Dibayar Dimuka</td>
                       <td >
                         <select name="ref" class="form-control">
                           <option value="0" >Tanpa Referensi</option>
                           <?php
                           foreach($refs as $r){
                           ?>
                             <option value="<?php echo $r['id']; ?>" ><?php echo $r['name'].' ('.$r['keterangan'].' '.$this->currency->format($r['total']).') '; ?></option>
                           <?php
                           }
                           ?>
                         </select>
                       </td>
                     </tr>
                     <tr>
                        <td>Keterangan</td>
                        <td >
                          <input type="text" name="keterangan" class="form-control" value="<?php echo $keterangan; ?>" >
                        </td>
                      </tr>
                     <tr>
                         <td>Jumlah</td>
                         <td><input type="text" name="nominal" class="form-control" value="<?php echo $nominal; ?>" ></td>
                     </tr>
                     <tr>
                       <td>Pajak</td>
                       <td><select name="pajak" class="form-control">
                         <option value="0">Tanpa Pajak</option>
                         <option value="1">PPh 21</option>
                         <option value="2">PPh 23</option>
                         <option value="3">PPh 4 (2) PP 46</option>
                         <option value="4">PPh 29</option>
                         <option value="5">PPh 4 (2) atas Sewa</option>
                       </select></td>
                     </tr>
                     <tr>
                       <td>Status Pajak</td>
                       <td><select name="statuspajak" class="form-control">
                         <option value="0">Tanpa Pajak</option>
                         <option value="1">Potong Total</option>
                         <option value="2">Tidak Potong Total</option>
                        
                       </select></td>
                     </tr>
                     <tr>
                         <td>Total Pajak</td>
                         <td><input type="text" name="nilaipajak" class="form-control" value="<?php echo $nilaipajak; ?>" ></td>
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
function simpan(){
  $(".error").remove();
  error=false;

  em='';
  if(!$("input[name='no_faktur']").val()){
    error=true;
    em +="Nomor Faktur harus diisi <br>";
  }

  if(!$("input[name='nominal']").val()){
    error=true;
    em +="Jumlah tagihan harus diisi <br>";
  }
  
  tanggal=$("input[name='tgl_tagihan']").val();
  if(tanggal < '<?php echo $locktanggal;?>'){
    error=true;
    em+="Tanggal tidak   boleh kurang dari <?php echo $locktanggal;?> <br>";
  }

  if($("select[name='jenis']").val() == 2){
    if($("select[name='ref']").val() == 0){
      error=true;
      em +="Referensi tagihan harus diisi <br>";
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
  $("#sewa").hide();
	$('.date').datepicker({dateFormat: 'yy-mm-dd',minDate:'<?php echo $locktanggal; ?>',maxDate:new Date()});

  $("select[name='jenis']").on('change',function(){
    if($(this).val() == 2){
      $("#sewa").show();
    }else{
      $("#sewa").hide();
    }
  });

});
//--></script>

<?php echo $footer; ?>
