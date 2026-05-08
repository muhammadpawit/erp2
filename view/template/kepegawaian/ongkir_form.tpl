<?php echo $header; ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>Penggantian Biaya Ongkir</h1>
  </section>

  <section class="content">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title"><?php echo isset($this->request->get['id']) ? 'Edit' : 'Tambah'; ?> Data</h3>
      </div>
      <div class="box-body">
        <form action="<?php echo $action; ?>" method="post" id="form-ongkir" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label">Tanggal</label>
            <div class="col-sm-10">
              <input type="text" name="tanggal" value="<?php echo $tanggal; ?>" class="form-control datepicker" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">Nomor #</label>
            <div class="col-sm-10">
              <input type="text" name="nomor" value="<?php echo $nomor; ?>" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">Pelanggan</label>
            <div class="col-sm-10">
              <input type="text" name="pelanggan" value="<?php echo $pelanggan; ?>" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">Jenis Request Ongkir</label>
            <div class="col-sm-10">
              <input type="text" name="jenis_request" value="<?php echo $jenis_request; ?>" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">No. Pembayaran Ongkir</label>
            <div class="col-sm-10">
              <input type="text" name="no_pembayaran" value="<?php echo $no_pembayaran; ?>" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">Penggantian Biaya Kirim</label>
            <div class="col-sm-10">
              <input type="text" name="biaya_pengiriman" value="<?php echo $biaya_pengiriman; ?>" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">Penggantian Biaya Lain</label>
            <div class="col-sm-10">
              <input type="text" name="biaya_lain" value="<?php echo $biaya_lain; ?>" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">Cabang</label>
            <div class="col-sm-10">
              <input type="text" name="cabang" value="<?php echo $cabang; ?>" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">Penjual 1</label>
            <div class="col-sm-10">
              <input type="text" name="penjual1" value="<?php echo $penjual1; ?>" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">Penjual 2</label>
            <div class="col-sm-10">
              <input type="text" name="penjual2" value="<?php echo $penjual2; ?>" class="form-control" />
            </div>
          </div>
          
          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" class="btn btn-primary">Simpan</button>
              <a href="<?php echo $cancel; ?>" class="btn btn-default">Batal</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
</div>
<script type="text/javascript">
$('.datepicker').datepicker({dateFormat: 'yy-mm-dd'});
</script>
<?php echo $footer; ?>
