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
            <h3 class="box-title">Input Jurnal</h3>
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
                       <td style="width:30%"><span class="required">*</span>Tanggal</td>
                       <td colspan="2"><input type="text" name="tanggal" class="date form-control" value="" readonly >
                       </td>
                     </tr>

                      <tr>
                         <td>Akun Debet</td>
                         <td colspan="2" >
                           <select name="ref_debet" class="form-control coa">

                           </select>
                         </td>
                       </tr>
                       <tr>
                          <td>Akun Kredit</td>
                          <td colspan="2" >
                            <select name="ref_kredit" class="form-control coa">

                            </select>
                          </td>
                        </tr>
                        <tr>
                         <td>Referensi</td>
                         <td colspan="2" >
                           <input type="text" name="referensi" class="form-control" value="0" >
                         </td>
                        </tr>
                        <tr>
                         <td>Dokumen Terkait</td>
                         <td colspan="2" >
                           <input type="text" name="linkterkait" class="form-control" placeholder="No.Dokumen" required="required">
                         </td>
                        </tr>
                       <tr>
                        <td>Keterangan</td>
                        <td colspan="2" >
                          <input type="text" name="keterangan" class="form-control" value="" >
                        </td>
                      </tr>
                     <tr>
                         <td>Jumlah</td>
                         <td colspan="2"><input type="text" name="nominal" class="form-control" value="" ></td>
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
$('.sidebar-menu').find('#menu-akuntansi').addClass('active');
$('.sidebar-menu').find('#menu-jurnal').addClass('active');
</script>

<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
  $(".coa").select2({
    ajax: {
    url:"index.php?route=keuangan/coa/autocompletes&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        filter_name: params.term,

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
function simpan(){
  $(".error").remove();
  error=false;

  em='';
  /*if($("select[name='akun_hutang']").val() == null | $("select[name='akun_hutang']").val() == undefined){
    error=true;
    em +="Jenis Hutang harus dipilih <br>";
  }*/



  if($("input[name='nominal']").val() == null){
    error=true;
    em +="Jumlah tagihan harus diisi <br>";
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
