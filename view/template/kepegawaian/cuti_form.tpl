<?php echo $header; ?>
<div id="content" class="content-wrapper">
  <section class="content-header">
    <h1>

    </h1>

  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Tambah Cuti Pegawai</h3>
            <div class="button pull-right">
									<a onclick="simpan();" class="button"><button type="button" class="btn btn-primary">Simpan</button></a>
                  <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-warning">Kembali</button></a>
								</div>
          </div>
          <div class="box-body">
            <div class="row errordisplay">
              <div class="col-md-12">
                <?php if ($error_warning) { ?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                  <?php
                  foreach($error_warning as $e){
                    echo $e.'<br>';
                  }
                   ?>
                </div>
                <?php
                }
                ?>
                <?php if (isset($success)) { ?>
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-check"></i> Success!</h4>
                  <?php echo $success; ?>
                </div>
                <?php
                }
                ?>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 ">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                  <table class="table table-stripped">
                    <tr>
                      <td><span class="required">*</span> Nama Pegawai</td>
                      <td>
                        <select name="pegawai_id"  class="sales form-control">

                          </select>

                      </td>
                    </tr>
                    <tr>
                      <td>Tanggal Awal Ijin</td>
                      <td><input class="form-control date"  type="text" name="tgl_awal" value="<?php echo $tgl_awal; ?>" readonly />
                        </td>
                    </tr>
                    <tr>
                      <td>Tanggal Akhir Ijin</td>
                      <td><input class="form-control date"  type="text" name="tgl_akhir" value="<?php echo $tgl_akhir; ?>" readonly />
                        </td>
                    </tr>
                    <tr>
                      <td>Keperluan</td>
                      <td><input class="form-control"  type="text" name="keperluan" value="<?php echo $keperluan; ?>" />
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
$('.sidebar-menu').find('#menu-kepegawaian').addClass('active');
$(function(){

  $('.date').datepicker({dateFormat: 'yy-mm-dd'});
  $(".sales").select2({
    ajax: {
    url:"index.php?route=user/user/autocomplete&token=<?php echo $this->request->get['token']; ?>",
    //url: "index.php?route=pamerantoko/toko/autocomplete&token=<?php echo $this->request->get['token']; ?>",
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
});
});
</script>
<script>
function simpan(){
  error=false;
  em="";
  $(".error").remove();
  pegawai=$("select[name='pegawai_id']").val();
  tgl_awal=$("input[name='tgl_awal']").val();
  tgl_akhir=$("input[name='tgl_akhir']").val();
  keperluan=$("input[name='keperluan']").val();
  //alert(pegawai);
  if(pegawai == null){
    error=true;
    em +="Nama Pegawai Harus Dipilih.<br>";
  }
  if(tgl_awal == ""){
    error=true;
    em +="Tanggal awal ijin harus diisi.<br>";
  }
  if(tgl_akhir == ""){
    error=true;
    em +="Tanggal akhir ijin harus diisi.<br>";
  }
  if(keperluan == ""){
    error=true;
    em +="Keperluan harus diisi.<br>";
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
