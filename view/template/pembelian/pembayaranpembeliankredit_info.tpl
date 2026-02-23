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
            <h3 class="box-title">Invoice Pembelian Lokal</h3>
            <div class="button pull-right">
                  <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>
								</div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <table class="table">
                  <tr>
                      <td>Nomor Faktur:</td>
                      <td><?php echo $permintaan['no_faktur'] ?></td>
                  </tr>
                  <tr>
                      <td>Tanggal Faktur:</td>
                      <td><?php echo date('d F Y',strtotime($permintaan['tglfaktur'])) ?></td>
                  </tr>
                  <tr>
                      <td>Jatuh Tempo:</td>
                      <td><?php echo date('d F Y',strtotime($permintaan['jatuhtempo'])) ?></td>
                  </tr>
                   <tr>
                      <td>Vendor:</td>
                      <td><?php echo $permintaan['name'] ?></td>
                  </tr>

                 <tr>
                    <td>Jenis Barang:</td>
                    <td><?php echo $permintaan['jenisproduk'] == 1?'Bahan Baku':($permintaan['jenisproduk'] == 2?'Produk Dagang':($permintaan['jenisproduk'] == 3?'ATK':($permintaan['jenisproduk'] == 4?'Aset':'Tabung Gas'))); ?></td>
                </tr>
                <tr>
                   <td>Sub Total:</td>
                   <td><?php echo $this->currency->format($permintaan['sub_total']) ?></td>
                  </tr>
                  <tr>
                     <td>Pajak:</td>
                     <td><?php echo $this->currency->format($permintaan['pajak']) ?></td>
                 </tr>
                 <tr>
                    <td>Total Tagihan:</td>
                    <td><?php echo $this->currency->format($permintaan['totaltagihan']) ?></td>
                </tr>
                <tr>
                   <td>Total Bayar:</td>
                   <td><?php echo $this->currency->format($permintaan['totalbayar']) ?> </td>
               </tr>

            </table>
            <table class="table">
                <thead>
                  <th>Tanggal Alokasi</th>
                  <th>Total Alokasi</th>
                  <th>No. Dokumen</th>
                  <!--th>Kurs</th>
                  <th></th-->
                </thead>
                <tbody>
                  <?php
                  foreach($pembayarans as $p){
                  ?>
                  <tr>
                    <td><?php echo date('d/m/y',strtotime($p['tglalokasi'])); ?></td>
                    <td><?php echo $this->currency->format($p['nominal']); ?></td>
                    <td><?php echo $this->currency->format($p['no_dokumen']); ?></td>

                  </tr>
                  <?php
                  }
                  ?>
                </tbody>
            </table>

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
$('.sidebar-menu').find('#menu-pembelian').addClass('active');
$('.sidebar-menu').find('#menu-pembelian-kredit').addClass('active');

</script>

<?php echo $footer; ?>
