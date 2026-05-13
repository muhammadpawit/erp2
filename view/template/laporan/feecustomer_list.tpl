<?php echo $header; ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
        Daftar Fee Customer
    </h1>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Data Fee Customer</h3>
          </div>
          <div class="box-body">
            <div class="row" style="margin-bottom: 20px;">
              <div class="col-md-3">
                <div class="form-group">
                  <label>Tanggal Awal</label>
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" class="form-control datepicker" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Tanggal Akhir</label>
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" class="form-control datepicker" />
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label>&nbsp;</label><br />
                  <button type="button" onclick="filter();" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                </div>
              </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="feeCustomerTable">
                    <thead>
                      <tr>
                          <th>ID</th>
                          <th>Cabang</th>
                          <th>Pelanggan</th>
                          <th>Sales Outbound</th>
                          <th>Sales Inbound</th>
                          <th>No Pesanan</th>
                          <th>Tgl Faktur</th>
                          <th>No Faktur</th>
                          <th>Wilayah</th>
                          <th>Kode Barang</th>
                          <th>Nama Barang</th>
                          <th>Qty</th>
                          <th>Harga</th>
                          <th>Total Harga</th>
                          <th>Fee</th>
                          <th>Tgl Pengajuan</th>
                          <th>Keterangan</th>
                          <th>Pembayaran</th>
                          <th>Jatuh Tempo</th>
                          <th>Tgl Bayar</th>
                          <th>No TRX</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php foreach($fee_customers as $f){?>
                          <tr>
                              <td><?php echo $f['id']?></td>
                              <td><?php echo $f['cabang']?></td>
                              <td><?php echo $f['pelanggan']?></td>
                              <td><?php echo $f['sales_outbound']?></td>
                              <td><?php echo $f['sales_inbound']?></td>
                              <td><?php echo $f['no_pesanan_penjualan']?></td>
                              <td><?php echo $f['tgl_faktur_penjualan']?></td>
                              <td><?php echo $f['no_faktur_penjualan']?></td>
                              <td><?php echo $f['wilayah_penjualan']?></td>
                              <td><?php echo $f['kode_barang']?></td>
                              <td><?php echo $f['nama_barang']?></td>
                              <td><?php echo number_format($f['qty'], 2)?></td>
                              <td><?php echo number_format($f['harga'], 0, ',', '.')?></td>
                              <td><?php echo number_format($f['total_harga_jual_excl_ppn'], 0, ',', '.')?></td>
                              <td><?php echo number_format($f['fee_customer'], 0, ',', '.')?></td>
                              <td><?php echo $f['tanggal_pengajuan_fee']?></td>
                              <td><?php echo $f['keterangan_perhitungan_fee_customer']?></td>
                              <td><?php echo number_format($f['pembayaran_faktur_penjualan'], 0, ',', '.')?></td>
                              <td><?php echo $f['tgl_jatuh_tempo']?></td>
                              <td><?php echo $f['tgl_pembayaran_terakhir']?></td>
                              <td><?php echo $f['no_trx_pembayaran']?></td>
                          </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
<script>
$(document).ready( function () {
    $('.datepicker').datepicker({dateFormat: 'yy-mm-dd'});
    $('#feeCustomerTable').DataTable({
        "order": [[ 0, "desc" ]],
        "pageLength": 25
    });
});

function filter() {
    var url = 'index.php?route=laporan/importkomisisales/fee_customer_list&token=<?php echo $token; ?>';

    var filter_date_start = $('input[name=\'filter_date_start\']').val();
    if (filter_date_start) {
        url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
    }

    var filter_date_end = $('input[name=\'filter_date_end\']').val();
    if (filter_date_end) {
        url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
    }

    location = url;
}
</script>
<?php echo $footer; ?>
