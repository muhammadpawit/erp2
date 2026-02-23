<?php echo $header; ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>

    </h1>

  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Persetujuan Penyesuaian Deposit</h3>
            <div class="button pull-right">
              <a onclick="simpan()" ><button type="button" class="btn btn-primary">Simpan</button></a>

                  <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>
								</div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <div class="errordisplay"></div>

              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

                <table class="table">
                  <tr>
                      <td>Nomor Surat:</td>
                      <td><?php echo $permintaan['no_surat'] ?></td>
                  </tr>
                  <tr>
                      <td>Tanggal:</td>
                      <td><?php echo date('d F Y',strtotime($permintaan['tanggal'])) ?></td>
                  </tr>
                  <tr>
                     <td>Customer:</td>
                     <td><?php echo $permintaan['name']; ?></td>
                 </tr>
                 <tr>
                      <td>Nominal Tersimpan:</td>
                      <td><?php echo $this->currency->format($permintaan['nominal_tersimpan']); ?></td>
                  </tr>
                <tr>
                      <td>Nominal Tersedia:</td>
                      <td><?php echo $this->currency->format($permintaan['nominal_tersedia']); ?></td>

                  </tr>
                <tr>
                      <td>Selisih:</td>
                      <td><?php echo $this->currency->format($permintaan['selisih']); ?></td>
                  </tr>
                   <tr>
                      <td>Keterangan:</td>
                      <td><?php echo $permintaan['keterangan'] ?></td>
                  </tr>
                  <?php
                  if($permintaan['status'] == 1){
                  ?>
                  <tr>
                     <td>Tanggal Pemrosesan:</td>
                     <td>
                       <input type="text" class="form-control date" name="tgl_diproses" value="<?php echo date('Y-m-d'); ?>" readonly>
                     </td>
                 </tr>
                   <tr>
                      <td>Proses:</td>
                      <td>
                        <select class="form-control" name="status">
                          <option value="2">Setujui</option>
                          <option value="3">Tolak</option>
                        </select>
                      </td>
                  </tr>
                  <tr id="alasan-tolak">
                    <td>Alasan Ditolak</td>
                    <td><input type="text" class="form-control" name="alasan_dibatalkan" value=""></td>
                  </tr>
                  <tr id="catat-jurnal">
                      <td>Catat di Jurnal</td>
                      <td>
                        <select name="catat_jurnal" class="form-control">
                            <option value="1" >Ya</option>
                            <option value="0" >Tidak</option>

                       </select>
                      </td>
                  </tr>
                  <?php
                  }
                  ?>




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
$(function(){

  $("select[name='status']").on('change',function(){
    if($(this).val() == 2){
        $("#alasan-tolak").hide();
          $("#catat-jurnal").show();
    }else{
      $("#catat-jurnal").hide();
      $("#alasan-tolak").show();
    }


  })
    $('.date').datepicker({dateFormat: 'yy-mm-dd',minDate:'<?php echo $locktanggal; ?>',maxDate:new Date()});
$("select[name='status']").trigger('change');
})
</script>
<script>
function simpan(){
  $(".error").remove();
  error=false;
  errdup=false;
  errqty=false;
  em='';

  cek = [];
  status=$("select[name='status']").val();

  if(status == 3){
    if($("input[name='alasan_dibatalkan']").val() == ''){
      error=true;
      em +="Alasan dibatalkan harus diisi";
    }
    //alert($("input[name='alasan_dibatalkan']").val());
  }
  tanggal=$("input[name='tgl_diproses']").val();
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
</script>

<?php echo $footer; ?>
