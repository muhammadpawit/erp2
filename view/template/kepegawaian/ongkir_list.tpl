<?php echo $header; ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>Penggantian Biaya Ongkir</h1>
  </section>

  <section class="content">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Daftar Penggantian Biaya Ongkir</h3>
        <div class="pull-right">
          <a href="<?php echo $insert; ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah</a>
          <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-import"><i class="fa fa-upload"></i> Import Excel</button>
          <button type="button" class="btn btn-danger" onclick="confirm('Yakin ingin menghapus?') ? $('#form-list').submit() : false;"><i class="fa fa-trash"></i> Hapus Terpilih</button>
        </div>
      </div>
      <div class="box-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label">Tanggal Awal</label>
                <input type="text" name="filter_tanggal_start" value="<?php echo $filter_tanggal_start; ?>" class="form-control datepicker" />
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label">Tanggal Akhir</label>
                <input type="text" name="filter_tanggal_end" value="<?php echo $filter_tanggal_end; ?>" class="form-control datepicker" />
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label">Nomor #</label>
                <input type="text" name="filter_nomor" value="<?php echo $filter_nomor; ?>" class="form-control" />
              </div>
            </div>
            <div class="col-sm-3">
              <label class="control-label">&nbsp;</label><br>
              <button type="button" onclick="filter();" class="btn btn-warning"><i class="fa fa-search"></i> Filter</button>
            </div>
          </div>
        </div>

        <form action="<?php echo $delete; ?>" method="post" id="form-list">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>
                  <th>Tanggal</th>
                  <th>Nomor #</th>
                  <th>Pelanggan</th>
                  <th>Jenis Request Ongkir</th>
                  <th>No. Pembayaran Ongkir</th>
                  <th>Penggantian Biaya Kirim</th>
                  <th>Penggantian Biaya Lain</th>
                  <th>Cabang</th>
                  <th>Penjual 1</th>
                  <th>Penjual 2</th>
                  <th class="text-right">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($ongkirs) { ?>
                <?php foreach ($ongkirs as $ongkir) { ?>
                <tr>
                  <td class="text-center"><input type="checkbox" name="selected[]" value="<?php echo $ongkir['id']; ?>" /></td>
                  <td><?php echo $ongkir['tanggal']; ?></td>
                  <td><?php echo $ongkir['nomor']; ?></td>
                  <td><?php echo $ongkir['pelanggan']; ?></td>
                  <td><?php echo $ongkir['jenis_request']; ?></td>
                  <td><?php echo $ongkir['no_pembayaran']; ?></td>
                  <td><?php echo $ongkir['biaya_pengiriman']; ?></td>
                  <td><?php echo $ongkir['biaya_lain']; ?></td>
                  <td><?php echo $ongkir['cabang']; ?></td>
                  <td><?php echo $ongkir['penjual1']; ?></td>
                  <td><?php echo $ongkir['penjual2']; ?></td>
                  <td class="text-right">
                    <a href="<?php echo $ongkir['edit']; ?>" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
                    <a href="<?php echo $ongkir['delete']; ?>" class="btn btn-danger btn-xs" onclick="return confirm('Yakin?')"><i class="fa fa-trash"></i></a>
                  </td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="12">Data tidak ditemukan</td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- Modal Import -->
<div id="modal-import" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Import Data dari Excel</h4>
      </div>
      <form action="<?php echo $import; ?>" method="post" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="form-group">
            <label>Pilih File Excel (.xls, .xlsx)</label>
            <input type="file" name="file" class="form-control" required accept=".xls,.xlsx">
          </div>
          <div class="help-block">
            Format Kolom: Tanggal, Nomor, Pelanggan, Jenis Request, No Pembayaran, Biaya Kirim, Biaya Lain, Cabang, Penjual 1, Penjual 2
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Import</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">
$('.datepicker').datepicker({dateFormat: 'yy-mm-dd'});

function filter() {
	url = 'index.php?route=kepegawaian/ongkir&token=<?php echo $token; ?>';
	
	var filter_tanggal_start = $('input[name=\'filter_tanggal_start\']').val();
	if (filter_tanggal_start) {
		url += '&filter_tanggal_start=' + encodeURIComponent(filter_tanggal_start);
	}
	
	var filter_tanggal_end = $('input[name=\'filter_tanggal_end\']').val();
	if (filter_tanggal_end) {
		url += '&filter_tanggal_end=' + encodeURIComponent(filter_tanggal_end);
	}
	
	var filter_nomor = $('input[name=\'filter_nomor\']').val();
	if (filter_nomor) {
		url += '&filter_nomor=' + encodeURIComponent(filter_nomor);
	}
	
	location = url;
}
</script>
<?php echo $footer; ?>
