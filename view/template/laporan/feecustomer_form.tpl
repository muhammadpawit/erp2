<?php echo $header; ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
        Edit Fee Customer
    </h1>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-pencil"></i> Form Edit</h3>
            <div class="pull-right">
                <button type="submit" form="form-fee" data-toggle="tooltip" title="Save" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="Cancel" class="btn btn-default"><i class="fa fa-reply"></i> Batal</a>
            </div>
          </div>
          <div class="box-body">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-fee" class="form-horizontal">
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">Cabang</label>
                    <div class="col-sm-10">
                        <input type="text" name="cabang" value="<?php echo isset($fee['cabang']) ? $fee['cabang'] : ''; ?>" class="form-control" />
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">Pelanggan</label>
                    <div class="col-sm-10">
                        <input type="text" name="pelanggan" value="<?php echo isset($fee['pelanggan']) ? $fee['pelanggan'] : ''; ?>" class="form-control" />
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">Sales Outbound</label>
                    <div class="col-sm-10">
                        <input type="text" name="sales_outbound" value="<?php echo isset($fee['sales_outbound']) ? $fee['sales_outbound'] : ''; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Sales Inbound</label>
                    <div class="col-sm-10">
                        <input type="text" name="sales_inbound" value="<?php echo isset($fee['sales_inbound']) ? $fee['sales_inbound'] : ''; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">No Pesanan</label>
                    <div class="col-sm-10">
                        <input type="text" name="no_pesanan_penjualan" value="<?php echo isset($fee['no_pesanan_penjualan']) ? $fee['no_pesanan_penjualan'] : ''; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Tgl Faktur</label>
                    <div class="col-sm-10">
                        <input type="text" name="tgl_faktur_penjualan" value="<?php echo isset($fee['tgl_faktur_penjualan']) ? $fee['tgl_faktur_penjualan'] : ''; ?>" class="form-control datepicker" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">No Faktur</label>
                    <div class="col-sm-10">
                        <input type="text" name="no_faktur_penjualan" value="<?php echo isset($fee['no_faktur_penjualan']) ? $fee['no_faktur_penjualan'] : ''; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Wilayah</label>
                    <div class="col-sm-10">
                        <input type="text" name="wilayah_penjualan" value="<?php echo isset($fee['wilayah_penjualan']) ? $fee['wilayah_penjualan'] : ''; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Kode Barang</label>
                    <div class="col-sm-10">
                        <input type="text" name="kode_barang" value="<?php echo isset($fee['kode_barang']) ? $fee['kode_barang'] : ''; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Nama Barang</label>
                    <div class="col-sm-10">
                        <input type="text" name="nama_barang" value="<?php echo isset($fee['nama_barang']) ? $fee['nama_barang'] : ''; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Qty</label>
                    <div class="col-sm-10">
                        <input type="text" name="qty" value="<?php echo isset($fee['qty']) ? $fee['qty'] : ''; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Harga</label>
                    <div class="col-sm-10">
                        <input type="text" name="harga" value="<?php echo isset($fee['harga']) ? $fee['harga'] : ''; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Total Harga (Excl PPN)</label>
                    <div class="col-sm-10">
                        <input type="text" name="total_harga_jual_excl_ppn" value="<?php echo isset($fee['total_harga_jual_excl_ppn']) ? $fee['total_harga_jual_excl_ppn'] : ''; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Fee Customer</label>
                    <div class="col-sm-10">
                        <input type="text" name="fee_customer" value="<?php echo isset($fee['fee_customer']) ? $fee['fee_customer'] : ''; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Tanggal Pengajuan</label>
                    <div class="col-sm-10">
                        <input type="text" name="tanggal_pengajuan_fee" value="<?php echo isset($fee['tanggal_pengajuan_fee']) ? $fee['tanggal_pengajuan_fee'] : ''; ?>" class="form-control datepicker" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Keterangan</label>
                    <div class="col-sm-10">
                        <textarea name="keterangan_perhitungan_fee_customer" class="form-control" rows="3"><?php echo isset($fee['keterangan_perhitungan_fee_customer']) ? $fee['keterangan_perhitungan_fee_customer'] : ''; ?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Pembayaran Faktur</label>
                    <div class="col-sm-10">
                        <input type="text" name="pembayaran_faktur_penjualan" value="<?php echo isset($fee['pembayaran_faktur_penjualan']) ? $fee['pembayaran_faktur_penjualan'] : ''; ?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Jatuh Tempo</label>
                    <div class="col-sm-10">
                        <input type="text" name="tgl_jatuh_tempo" value="<?php echo isset($fee['tgl_jatuh_tempo']) ? $fee['tgl_jatuh_tempo'] : ''; ?>" class="form-control datepicker" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Tgl Bayar</label>
                    <div class="col-sm-10">
                        <input type="text" name="tgl_pembayaran_terakhir" value="<?php echo isset($fee['tgl_pembayaran_terakhir']) ? $fee['tgl_pembayaran_terakhir'] : ''; ?>" class="form-control datepicker" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">No TRX</label>
                    <div class="col-sm-10">
                        <input type="text" name="no_trx_pembayaran" value="<?php echo isset($fee['no_trx_pembayaran']) ? $fee['no_trx_pembayaran'] : ''; ?>" class="form-control" />
                    </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
$(document).ready(function() {
    $('.datepicker').datepicker({dateFormat: 'yy-mm-dd'});
});
</script>
<?php echo $footer; ?>
