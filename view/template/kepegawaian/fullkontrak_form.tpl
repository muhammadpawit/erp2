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
            <h3 class="box-title">Tambah Kontrak Pegawai</h3>
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
                      <td>Nomor Kontrak<br><small>Jika dikosongkan akan digenerate otomatis oleh sistem</small></td>
                      <td><input class="form-control"  type="text" name="no_kontrak" value="<?php echo $no_kontrak; ?>" />
                        </td>
                    </tr>
                    <tr>
                      <td>Tanggal Awal Kontrak</td>
                      <td><input class="form-control date"  type="text" name="tglawal" value="<?php echo $tglawal; ?>" readonly />
                        </td>
                    </tr>
                    <tr>
                      <td>Tanggal Akhir Kontrak</td>
                      <td><input class="form-control date"  type="text" name="tglakhir" value="<?php echo $tglakhir; ?>" readonly />
                        </td>
                    </tr>

                    <tr >
                      <td>Keterangan</td>
                      <td>  <textarea class="form-control " name="keterangan"></textarea>
                        </td>
                    </tr>

                    <tr>
                    <td>Foto Kontrak</td>
                    <td><div class="image"><img src="<?php echo $thumb; ?>" alt="" id="thumb" /><br />
                        <input type="hidden" name="file" value="" id="image" />
                        <a onclick="image_upload('image', 'thumb');">Cari</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb').attr('src', 'no_image'); $('#image').attr('value', '');">Bersihkan</a></div></td>
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
        filter_statuspegawai:2

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
function image_upload(field, thumb) {
	$('#dialog').remove();

	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $this->request->get['token']; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');

	$('#dialog').dialog({
		title: 'Image Manager',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $this->request->get['token']; ?>&image=' + encodeURIComponent($('#' + field).attr('value')),
					dataType: 'text',
					success: function(text) {
						$('#' + thumb).replaceWith('<img src="' + text + '" alt="" id="' + thumb + '" />');
					}
				});
			}
		},
		bgiframe: false,
		width: 800,
		height: 400,
		resizable: false,
		modal: false
	});
};
function simpan(){
  error=false;
  em="";
  $(".error").remove();
  pegawai=$("select[name='pegawai_id']").val();
  tgl_awal=$("input[name='tglawal']").val();
  tgl_akhir=$("input[name='tglakhir']").val();

  //alert(pegawai);
  if(pegawai == null){
    error=true;
    em +="Nama Pegawai Harus Dipilih.<br>";
  }
  if(tgl_awal == ""){
    error=true;
    em +="Tanggal awal kontrak harus diisi.<br>";
  }
  if(tgl_akhir == ""){
    error=true;
    em +="Tanggal akhir kontrak harus diisi.<br>";
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
