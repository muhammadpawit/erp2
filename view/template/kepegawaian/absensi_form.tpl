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
            <h3 class="box-title">Tambah Absensi</h3>
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
                      <td>Tanggal</td>
                      <td><input class="form-control date"  type="text" name="tanggal" value="<?php echo $tanggal; ?>" readonly />
                        </td>
                    </tr>
                    <tr>
                      <td>Jam Datang</td>
                      <td><input class="form-control"  type="text" id="jamdatang" name="jam_datang" size="100" value="<?php echo $jam_datang; ?>"/>
                        </td>
                    </tr>
                    <tr>
                      <td>Jam Pulang</td>
                      <td><input class="form-control"  type="text" id="jampulang" name="jam_pulang" size="100" value="<?php echo $jam_pulang; ?>" />
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
  $('#jamdatang').datetimepicker({
      format: 'LT'
  });
  $('#jampulang').datetimepicker({
      format: 'LT'
  });
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
  jam_datang=$("input[name='jam_datang']").val();
  jam_pulang=$("input[name='jam_pulang']").val();
  tanggal=$("input[name='tanggal']").val();
  if(pegawai == null){
    error=true;
    em +="Nama Pegawai Harus Dipilih.<br>";
  }
  if(jam_datang == null){
    error=true;
    em +="Jam Datang harus diisi.<br>";
  }
  if(jam_pulang == null){
    error=true;
    em +="Jam Pulang harus diisi.<br>";
  }
  if(tanggal == null){
    error=true;
    em +="Tanggal harus diisi.<br>";
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
