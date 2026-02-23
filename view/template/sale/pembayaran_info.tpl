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
            <h3 class="box-title">Alokasi Pembayaran Penjualan</h3>
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
                      <td><?php echo $order['no_dokumen'] ?></td>
                  </tr>
                  <tr>
                      <td>Tanggal:</td>
                      <td><?php echo date('d F Y',strtotime($order['date_added'])) ?></td>
                  </tr>
                  
                   <tr>
                      <td>Customer:</td>
                      <td><?php echo $order['name'] ?></td>
                  </tr>

                
                 <tr>
                    <td>Total Alokasi:</td>
                    <td><?php echo $this->currency->format($order['total']) ?></td>
                </tr>
               

            </table>
            <table class="table">
                <thead>
                  <th>No. Invoice</th>
                  <th>Total Alokasi</th>
                 
                 
                </thead>
                <tbody>
                  <?php
                  foreach($products as $p){
                  ?>
                  <tr>
                    <td><?php echo $p['invoice']; ?></td>
                    <td><?php echo $p['totalbayar']; ?></td>
                   
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
