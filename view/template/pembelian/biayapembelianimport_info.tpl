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
            <h3 class="box-title">Biaya Pembelian Import</h3>
            <div class="button pull-right">
                  <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>
								</div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <table class="table">
                  <tr>
                      <td>Referensi Invoice Pembelian Import:</td>
                      <td><?php echo $permintaan['refinvoice'] ?></td>
                  </tr>
                  <tr>
                      <td>Nama Biaya:</td>
                      <td><?php echo $permintaan['name'] ?></td>
                  </tr>
                  <tr>
                      <td>Nomor Faktur:</td>
                      <td><?php echo $permintaan['statuspembayaran']==0?'Belum Ada Tagihan':$permintaan['no_faktur'] ?></td>
                  </tr>
                  <tr>
                      <td>Tanggal Faktur:</td>
                      <td><?php echo $permintaan['statuspembayaran']==0?'Belum Ada Tagihan':date('d F Y',strtotime($permintaan['tglfaktur'])) ?></td>
                  </tr>
                  <tr>
                      <td>Jatuh Tempo:</td>
                      <td><?php echo $permintaan['statuspembayaran']==0?'Belum Ada Tagihan':date('d F Y',strtotime($permintaan['jatuhtempo'])) ?></td>
                  </tr>

                <tr>
                   <td>Biaya Pembelian:</td>
                   <td>$<?php echo number_format($permintaan['sub_total'],2,'.',',') ?></td>
                  </tr>
                  <tr>
                     <td>Pajak:</td>
                     <td>$<?php echo number_format($permintaan['pajak'],2,'.',',') ?></td>
                 </tr>
                 <tr>
                    <td>Total Tagihan:</td>
                    <td>$<?php echo number_format($permintaan['totaltagihan'],2,'.',',') ?></td>
                </tr>
                <tr>
                   <td>Total Bayar:</td>
                   <td>$<?php echo number_format($permintaan['totalbayar'],2,'.',',') ?> (<?php echo $this->currency->format($permintaan['totalbayarrp']); ?>)</td>
               </tr>

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
$('.sidebar-menu').find('#menu-pembelian-import').addClass('active');

</script>

<?php echo $footer; ?>
