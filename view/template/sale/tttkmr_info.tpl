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
            <h3 class="box-title">Tanda Terima Tabung Kosong <?php echo $order['no_so']; ?>
            <?php 
            echo $order['status']==3?'<span class="badge bg-red">DIBATALKAN</span>':'';
            ?>
            </h3>
            <div class="button pull-right">
                <a href="<?php echo $invoice; ?>" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
                <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>
						</div>
          </div>
          <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <table class="table">
                <tr>
                  <td>No. TTTK</td>
                  <td><?php echo $order['no_so']; ?></td>
                </tr>
                <tr>
                  <td>TTTK Manual</td>
                  <td><?php echo $order['tttk_manual']; ?></td>
                </tr>
                <tr>
                  <td>Customer</td>
                  <td><?php echo $order['name']; ?></td>
                </tr>
                <tr>
                  <td>Alamat</td>
                  <td>
                    <?php
                    if($order['pengiriman'] == 1){
                    ?>
                    <strong><?php echo $address['firstname']; ?></strong><br>
                    <?php echo $address['address_1'].' '.$address['address_2']; ?><br>
                    <?php echo $address['city'].', '.$address['zone'].', '.$address['country'].', '.$address['postcode']; ?><br>
                    <?php
                    }
                    ?>
                    Phone: <?php echo !empty($address['company_id'])?$address['company_id']:$order['telephone']; ?><br>
                    Email: <?php echo $order['email']; ?>
                  </td>
                </tr>
                <tr>
                  <td>Metode Pengambilan</td>
                  <td><?php echo $order['pengiriman'] == 1?'Diambil':'Diantar'; ?></td>
                </tr>
                <?php
                if($order['pengiriman'] == 1){
                ?>
                <tr>
                  <td>Sopir/Kendaraan</td>
                  <td><?php echo $order['sopir']; ?>/<?php echo $order['no_pol']; ?></td>
                </tr>
                <tr>
                  <td>Kernet</td>
                  <td><?php
                  $i=1;
                   foreach($order['kernets'] as $k){
                     echo $i.". ".$k['firstname']."<br>";
                     $i++;
                   }
                  ?></td>
                </tr>
                <?php
              }
                ?>
              </table>
            </div>
          </div>
        </div>
          <div class="box-footer">
            <div class="row">
              <div class="col-xs-12 table-responsive">
                <table class="table table-striped">
                  <thead>
                  <tr>
                    <th>Tabung ID</th>
                    <th>Produk</th>
                    <th>Ukuran Tabung</th>
                    <th>Qty</th>
                    <th>Tutup</th>
                    <th>Keterangan</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  foreach($products as $p){
                  ?>
                  <tr>
                    <td><?php echo $p['id']; ?></td>
                    <td>
                      <?php echo $p['name']; ?><br>

                    </td>
                  <td><?php echo $p['ukuran']; ?></td>
                  <td><?php echo $p['quantity']; ?></td>
                    <td><?php echo $p['tutup'] == 1?'Dengan Tutup':'Tanpa Tutup'; ?></td>
                    <td><?php echo $p['keterangan']; ?></td>
                  </tr>
                  <?php
                  }
                  ?>

                  </tbody>
                </table>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12 table-responsive">
                <h4>Sales Order</h4>
                <table class="table table-striped">
                  <thead>
                  <tr>
                    <th>Tanggal</th>
                    <th>No. SO</th>
                    <th>Quantity</th>

                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  foreach($order['salesorder'] as $p){
                  ?>
                  <tr>
                    <td><?php echo $p['tanggal']; ?></td>
                    <td><?php echo $p['no_so']; ?></td>
                    <td><?php echo $p['qty']; ?></td>
                  </tr>
                  <?php
                  }
                  ?>

                  </tbody>
                </table>
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
$('.sidebar-menu').find('#menu-penjualan').addClass('active');
$('.sidebar-menu').find('#menu-penjualan-mr').addClass('active');
</script>

<?php echo $footer; ?>
