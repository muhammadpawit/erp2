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
            <h3 class="box-title">User</h3>
            <div class="button pull-right">
                    <a onclick="$('#form').submit();" class="btn btn-info">Simpan</a>
                    <a onclick="location = '<?php echo $cancel; ?>';" class="btn btn-danger">Kembali</a>
						</div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">


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

                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                  <div class="col-md-6">
                  <table class="table">

                    <tr>
                      <td><span class="required">*</span> Nama Pegawai</td>
                      <td><input class="form-control" type="text" name="firstname" value="<?php echo $firstname; ?>" />
                        <?php if ($error_firstname) { ?>
                        <span class="error"><?php echo $error_firstname; ?></span>
                        <?php } ?></td>
                    </tr>
                    <tr>
                      <td><span class="required">*</span> Nomor KTP</td>
                      <td><input class="form-control" type="text" name="ktp" value="<?php echo $ktp; ?>" />
                        </td>
                    </tr>
                    <tr>
                      <td>Akses Aplikasi</td>
                      <td><select class="form-control" name="status">
                          <?php if ($status) { ?>
                          <option value="0">Tidak Aktif</option>
                          <option value="1" selected>Aktif</option>
                          <?php } else { ?>
                          <option value="0" selected>Tidak Aktif</option>
                          <option value="1">Aktif</option>
                          <?php } ?>
                        </select></td>
                    </tr>
                    <tr>
                      <td><span class="required">*</span> Username Aplikasi</td>
                      <td><input class="form-control" type="text" name="username" value="<?php echo $username; ?>" />
                        <?php if ($error_username) { ?>
                        <span class="error"><?php echo $error_username; ?></span>
                        <?php } ?></td>
                    </tr>
                    <tr>
                      <td>Password</td>
                      <td><input class="form-control" type="password" name="password" value="<?php echo $password; ?>"  />
                        <?php if ($error_password) { ?>
                        <span class="error"><?php echo $error_password; ?></span>
                        <?php  } ?></td>
                    </tr>
                    <tr>
                      <td>Konfirmasi Password</td>
                      <td><input class="form-control" type="password" name="confirm" value="" />
                        <?php if ($error_confirm) { ?>
                        <span class="error"><?php echo $error_confirm; ?></span>
                        <?php  } ?></td>
                    </tr>
                    <tr>
                      <td><span class="required">*</span> Nomor NPWP</td>
                      <td><input class="form-control" type="text" name="npwp" value="<?php echo $npwp; ?>" />
                        </td>
                    </tr>
                    <tr>
                      <td>Email</td>
                      <td><input class="form-control" type="text" name="email" value="<?php echo $email; ?>" /></td>
                    </tr>
                    <tr>
                      <td>Alamat</td>
                      <td><input class="form-control" type="text" name="alamat" value="<?php echo $alamat; ?>" />
                        </td>
                    </tr>
                    <tr>
                      <td>Telephone</td>
                      <td><input class="form-control" type="text" name="telephone" value="<?php echo $telephone; ?>" />
                        </td>
                    </tr>
                    <tr>
                      <td>No. HP</td>
                      <td><input class="form-control" type="text" name="hp" value="<?php echo $hp; ?>" />
                        </td>
                    </tr>
                  <tr>
                      <td>Tempat Lahir</td>
                      <td><input class="form-control" type="text" name="tempatlahir" value="<?php echo $tempat_lahir; ?>" />
                        </td>
                    </tr>
                    <tr>
                      <td>Tanggal Lahir</td>
                      <td><input class="form-control date" type="text" name="tgl_lahir" value="<?php echo $tgl_lahir; ?>" readonly />
                        </td>
                    </tr>
                    <tr>
                      <td>Agama</td>
                      <td><select class="form-control" name="agama">
                            <option value="1">Islam</option>
                            <option value="2">Kristen</option>
                            <option value="3">Katolik</option>
                            <option value="4">Hindu</option>
                            <option value="5">Budha</option>
                            <option value="6">Kong Hu Cu</option>
                        </select ></td>
                    </tr>
                    <tr>
                      <td>Jenis Kelamin</td>
                      <td><select class="form-control" name="jeniskelamin">
                            <option value="1">Laki - Laki</option>
                            <option value="2">Perempuan</option>

                        </select ></td>
                    </tr>
                  </table>
                  </div>
                  <div class="col-md-6">
                    <table class="table">
                      <tr>
                        <td>Pendidikan</td>
                        <td><select class="form-control" name="pendidikan">
                              <option value="1">SD</option>
                              <option value="2">SMP</option>
                              <option value="3">SMA</option>
                              <option value="4">S1/D3</option>
                              <option value="5">S2</option>
                              <option value="6">S3</option>
                              <option value="7">Tidak memiliki pendidikan formal</option>

                          </select ></td>
                      </tr>
                      <tr>
                        <td>Kantor</td>
                        <td><select class="form-control" name="gudang_id">
                          <?php
                          foreach($gudangs as $g){
                          ?>
                            <option value="<?php echo $g['gudang_id']; ?>" <?php echo $g['gudang_id'] == $gudang_id?'selected':''; ?> ><?php echo $g['nama']; ?></option>
                          <?php
                          }
                          ?>
                          </select ></td>
                      </tr>
                      <tr>
                        <td>Divisi</td>
                        <td><select class="form-control" name="divisi">
                          <?php
                          foreach($divisis as $g){
                          ?>
                            <option value="<?php echo $g['id']; ?>" <?php echo $g['id'] == $divisi?'selected':''; ?> ><?php echo $g['name']; ?></option>
                          <?php
                          }
                          ?>
                          </select ></td>
                      </tr>
                      <tr>
                        <td>User Group</td>
                        <td><select class="form-control" name="user_group_id">
                            <?php foreach ($user_groups as $user_group) { ?>
                            <?php if ($user_group['user_group_id'] == $user_group_id) { ?>
                            <option value="<?php echo $user_group['user_group_id']; ?>" selected><?php echo $user_group['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $user_group['user_group_id']; ?>"><?php echo $user_group['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                          </select></td>
                      </tr>
                      <tr>
                        <td>Jenis Pegawai</td>
                        <td><select class="form-control" name="jenispegawai">
                              <option value="1">Tetap</option>
                              <option value="2">Kontrak</option>
                              <option value="3">Outsource</option>

                          </select ></td>
                      </tr>
                      <tr>
                        <td>Status Gaji</td>
                        <td><select class="form-control" name="statusgaji">
                              <option value="1">Harian</option>
                              <option value="2">Bulanan</option>

                          </select ></td>
                      </tr>
                      <tr>
                        <td>Bank</td>
                        <td><select class="form-control" name="bank">
                              <option value="1">BCA</option>

                          </select ></td>
                      </tr>
                      <tr>
                        <td>Nomor Rekening</td>
                        <td><input class="form-control" type="text" name="rekening" value="<?php echo $rekening; ?>" />
                          </td>
                      </tr>
                      <tr>
                        <td>Status Kawin</td>
                        <td><select class="form-control" name="statuskawin">
                              <option value="1">Belum Menikah</option>
                              <option value="2">Sudah Menikah</option>
                              <option value="3">Janda/Duda</option>

                          </select ></td>
                      </tr>
                      <tr>
                        <td>Nama Kerabat</td>
                        <td><input class="form-control" type="text" name="namakerabat" value="<?php echo $namakerabat; ?>" />
                          </td>
                      </tr>
                      <tr>
                        <td>Telp Kerabat</td>
                        <td><input class="form-control" type="text" name="telpkerabat" value="<?php echo $telpkerabat; ?>" />
                          </td>
                      </tr>
                      <tr>
                      <td>Foto</td>
                      <td><div class="image"><img src="<?php echo $thumb; ?>" alt="" id="thumb" /><br />
                          <input type="hidden" name="foto" value="<?php echo $foto; ?>" id="image" />
                          <a onclick="image_upload('image', 'thumb');">Cari</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb').attr('src', 'no_image'); $('#image').attr('value', '');">Bersihkan</a></div></td>
                    </tr>
                    </table>
                  </div>
                </form>

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
$('.sidebar-menu').find('#menu-system').addClass('active');
$('.sidebar-menu').find('#menu-user').addClass('active');
$('.sidebar-menu').find('#menu-daftar-user').addClass('active');

//--></script>
<script>
$(function(){
  $('.date').datepicker({dateFormat: 'yy-mm-dd'});
})
</script>
<script type="text/javascript"><!--
$('#form input class="form-control"').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
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
//--></script>

<?php echo $footer; ?>
