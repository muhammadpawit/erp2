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
            <h3 class="box-title">Pegawai</h3>
            <div class="button pull-right">
                    <a onclick="location = '<?php echo $insert; ?>'" class="btn btn-info">Tambah</a>
                    <a onclick="$('form').attr('action', '<?php echo $delete; ?>'); $('form').submit();" class="btn btn-danger">Hapus</a>

						</div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">

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
                <form action="" method="post" enctype="multipart/form-data" id="form">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>
                        <th class="left">Nama</th>
                        <th class="left">Status Pegawai</th>
                        <th class="left">Kantor</th>
                        <th class="left">Divisi</th>
                        <th class="left">Kelompok User</th>
                        <th class="left">Kontak</th>
                        <th class="left">Status Aplikasi</th>

                        <th class="right"><?php echo $column_action; ?></td>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td></td>
                        <td><input type="text" class="form-control" name="filter_name" value="<?php echo $filter_name; ?>" /></td>
                        <td>
                          <select class="form-control" name="filter_statuspegawai">
                            <option value="*">Semua Pegawai</option>
                            <option value="1">Pegawai Tetap</option>
                            <option value="2">Pegawai Kontrak</option>
                            <option value="3">Pegawai Outsource</option>
                          </select>
                        </td>
                        <td>
                          <select class="form-control" name="filter_gudang_id">
                            <option value="*">Semua Lokasi</option>
                            <?php
                            foreach($gudangs as $g){
                            ?>
                              <option value="<?php echo $g['gudang_id']; ?>" ><?php echo $g['nama']; ?></option>
                            <?php
                            }
                            ?>
                          </select>
                        </td>
                        <td>
                          <select class="form-control" name="filter_divisi">
                            <option value="*">Semua Divisi</option>
                            <?php
                            foreach($divisis as $g){
                            ?>
                              <option value="<?php echo $g['id']; ?>" ><?php echo $g['name']; ?></option>
                            <?php
                            }
                            ?>
                          </select>
                        </td>
                        <td>
                          <select class="form-control" name="filter_jabatan">
                            <option value="*">Semua</option>
                            <?php foreach ($user_groups as $user_group) { ?>
                            <option value="<?php echo $user_group['user_group_id']; ?>"><?php echo $user_group['name']; ?></option>

                            <?php } ?>
                          </select>
                        </td>
                        <td></td>
                        <td>
                          <select class="form-control" name="filter_status">
                            <option value="*">Semua Status</option>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>

                          </select>
                        </td>
                        <td><a onclick="filter();" class="btn btn-info">Filter</a></td>
                      </tr>
                      <?php if ($users) { ?>
                      <?php foreach ($users as $user) { ?>
                      <tr>
                        <td style="text-align: center;"><?php if ($user['selected']) {
                          //echo $this->user->getId();
                          if($user['user_id'] != $this->user->getId()){
                          ?>
                          <input type="checkbox" name="selected[]" value="<?php echo $user['user_id']; ?>" checked="checked" />
                          <?php } else { ?>
                          <input type="checkbox" name="selected[]" value="<?php echo $user['user_id']; ?>" />
                          <?php }}else{
                            if($user['user_id'] != $this->user->getId()){
                          ?>
                          <input type="checkbox" name="selected[]" value="<?php echo $user['user_id']; ?>" />
                          <?php
                          }
                          } ?></td>
                        <td class="left"><?php echo $user['firstname']; ?></td>
                        <td class="left"><?php echo $user['jenispegawai'] == 1?'Pegawai Tetap':($user['jenispegawai'] == 2?'Pegawai Kontrak':'Pegawai Outsource'); ?>
                          <br>
                          <?php
                          if($user['jenispegawai'] == 2){
                            ?>
                            <small><?php echo $user['tglakhir']; ?></small>
                            <?php
                          }
                          ?>
                        </td>
                        <td class="left"><?php echo $user['namagudang']; ?></td>
                        <td class="left"><?php echo $user['divisi']; ?></td>
                        <td class="left"><?php echo $user['jabatan']; ?></td>
                        <td class="left">
                          <strong>Alamat:</strong> <?php echo $user['alamat']; ?><br>
                          <strong>Email:</strong> <?php echo $user['email']; ?><br>
                          <strong>Telephone:</strong> <?php echo $user['telephone']; ?><br>
                          <strong>HP:</strong> <?php echo $user['hp']; ?><br>

                        </td>
                        <td class="left"><?php echo $user['status']; ?></td>
                        <td class="right"><?php foreach ($user['action'] as $action) { ?>
                          <a href="<?php echo $action['href']; ?>" class="badge bg-yellow"><?php echo $action['text']; ?></a>
                          <?php } ?></td>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="5"><?php echo $text_no_results; ?></td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </form>
              </div>
            </div>

          </div>
          <div class="box-footer">
            <div class="row">
              <div class="col-md-12">
                <div class="pull-right"><?php echo $pagination; ?></div>
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
$('.sidebar-menu').find('#menu-setting').addClass('active');
$('.sidebar-menu').find('#menu-user').addClass('active');
$('.sidebar-menu').find('#menu-daftar-user').addClass('active');

//--></script>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script>
<script type="text/javascript"><!--
function filter() {
		url = 'index.php?route=user/user&token=<?php echo $this->request->get['token']; ?>';

	var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
  var filter_tglakhir = $('input[name=\'filter_tglakhir\']').val();

	if (filter_tglakhir) {
		url += '&filter_tglakhir=' + encodeURIComponent(filter_tglakhir);
	}

  var filter_statuspegawai=$('select[name=\'filter_statuspegawai\']').val();
  if (filter_statuspegawai != '*') {
    url += '&filter_statuspegawai=' + encodeURIComponent(filter_statuspegawai);
  }

  var filter_gudang_id=$('select[name=\'filter_gudang_id\']').val();
  if (filter_gudang_id != '*') {
    url += '&filter_gudang_id=' + encodeURIComponent(filter_gudang_id);
  }

  var filter_divisi=$('select[name=\'filter_divisi\']').val();
  if (filter_divisi != '*') {
    url += '&filter_divisi=' + encodeURIComponent(filter_divisi);
  }

  var filter_jabatan=$('select[name=\'filter_jabatan\']').val();
  if (filter_jabatan != '*') {
    url += '&filter_jabatan=' + encodeURIComponent(filter_jabatan);
  }

  var filter_status=$('select[name=\'filter_status\']').val();
  if (filter_status != '*') {
    url += '&filter_status=' + encodeURIComponent(filter_status);
  }
	location = url;
}
//--></script>
<?php echo $footer; ?>
