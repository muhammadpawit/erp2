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
            <h3 class="box-title">Input Kontrak Pegawai</h3>
            <div class="button pull-right">
                    <a onclick="simpan()" class="btn btn-info">Simpan</a>
                    <a onclick="location = '<?php echo $cancel; ?>';" class="btn btn-danger">Kembali</a>
						</div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <div class="errordisplay"></div>
                <?php if ($success) { ?>
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-check"></i> Success!</h4>
                  <?php echo $success; ?>
                </div>
                <?php
                }
                ?>

                <?php if ($error_warning) { ?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-check"></i> Alert!</h4>
                  <?php echo $error_warning; ?>
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
                      <td style="width:40%">Nama Customer</td>
                      <td>
                        <?php
                        echo $user['firstname'].' '.$user['lastname'];
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <td>Nomor Kontrak<br><small>Jika dikosongkan akan digenerate otomatis oleh sistem</small></td>
                      <td><input class="form-control date" type="text" name="tglakhir" value="<?php echo date('Y-m-d',strtotime('+1 month')); ?>" readonly />
                        </td>
                    </tr>

                    <tr>
                      <td><span class="required">*</span> Tanggal awal Kontrak</td>
                      <td><input class="form-control date" type="text" name="tglawal" value="<?php echo date('Y-m-d'); ?>" readonly />
                        </td>
                    </tr>
                    <tr>
                      <td><span class="required">*</span> Tanggal akhir Kontrak</td>
                      <td><input class="form-control date" type="text" name="tglakhir" value="<?php echo date('Y-m-d',strtotime('+1 month')); ?>" readonly />
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
$('.sidebar-menu').find('#menu-pegawai').addClass('active');


//--></script>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});


});
//--></script>
<script type="text/javascript"><!--
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
  em='';
  $(".error").remove();
  if($("input[name='tglawal']").val() == ""){
    error=true;
    em +="Tanggal awal kontrak harus diisi <br>";
  }
  if($("input[name='tglakhir']").val() == ""){
    error=true;
    em +="Tanggal akhir kontrak harus diisi <br>";
  }


  if($("input[name='file']").val() == ""){
    error=true;
    em +="Foto kontrak harus diisi <br>";
  }

  if(!error){

    $('#form').submit();
  }else{
    html='<div class="alert alert-danger alert-dismissible">';
    html +='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
    html +='<h4><i class="icon fa fa-ban"></i> Alert!</h4>';
    html +=em;
    html +='</div>';

    $('.errordisplay').html(html);
  }
}
//--></script>
<?php echo $footer; ?>
