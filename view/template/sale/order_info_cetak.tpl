<!DOCTYPE html>
<html>
<head>
  <title>Sales Order Penjualan MP <?php echo $order['no_so']; ?></title>
  <style type="text/css">
    .judul{ width: 200px; text-align: center; }
    #p1 { font-weight: 800;font-size: 30px; height: auto; border-bottom: 1px solid black;margin-bottom: -15px}
  </style>
</head>
<body onload="window.print()">
  <!-- <body> -->
              <center>
              <div class="judul">
                <p id="p1">Sales Order</p>
                <p id="p2"><?php echo $order['no_so']; ?></p>
              </div>
              </center>
              <table class="table" style="width: 100%">
                <!-- <tr>
                  <td>No.SO</td>
                  <td><?php echo $order['no_so']; ?></td>
                </tr> -->
                <tr>
                  <td>Dicetak Oleh</td>
                  <td><?php echo $pencetak; ?></td>
                </tr>
                <tr>
                  <td>Tanggal</td>
                  <td><?php echo date('d/m/Y',strtotime($order['date_added'])); ?></td>
                </tr>
                <tr>
                  <td>No. TTTK</td>
                  <td><?php echo $order['no_tttk']; ?></td>
                </tr>
                <tr>
                  <td>Gudang</td>
                  <td><?php echo $order['nama']; ?></td>
                </tr>
                 <tr>
                  <td>Marketplace</td>
                  <td><?php echo $marketplace; ?></td>
                </tr>
                <tr>
                  <td>Customer</td>
                  <td><?php echo $order['name']; ?></td>
                </tr>
                <tr>
                  <td valign="top">Alamat</td>
                  <td>
                    <?php
                    if($order['pengiriman'] == 2){
                    ?>
                    <strong><?php echo $address['firstname'] ." / ".$address['lastname'];  ?></strong><br>
                    <?php echo $address['address_1'].' '.$address['address_2']; ?><br>
                    <?php echo $address['city'].', '.$address['zone'].', '.$address['country'].', '.$address['postcode']; ?><br>
                    <?php
                    }
                    ?>
                    <?php if($order['pengiriman']==1){ ?>
                      <!-- <strong><?php echo $order['name']; ?></strong><br> -->
                      <?php echo $order['alamat'] ?><br>
                    <?php } ?>
                    Phone: <?php echo !empty($address['company_id'])?$address['company_id']:$order['telephone']; ?><br>
                    Email: <?php echo $order['email']; ?>

                  </td>
                </tr>
                <?php //if($order['pengiriman']==1){ ?>
                <tr>
                  <td>Catatan</td>
                  <td><b><?php echo ($order['catatan']!=null)?$order['catatan']:$order['alamat']; ?></b></td>
                </tr>
                <?php //} ?>
                <tr>
                  <td>Metode Pengiriman</td>
                  <td><?php echo $order['pengiriman'] == 1?'Diambil':'Diantar'; ?></td>
                </tr>
                <tr>
                  <td>Metode Pembayaran</td>
                  <td><?php // echo $order['metode_pembayaran'] == 1?'Tunai':($order['metode_pembayaran'] == 2?'COD':($order['metode_pembayaran'] == 4?'CBD':'Kredit')); ?>
                  <?php 
                      // 1 tunai, 2 cod, 3 kredit, 4 CBD

                      $metode = $order['metode_pembayaran'];
                      
                      if($metode==1){
                        echo "Tunai";
                      }

                      if($metode==2){
                        echo "COD";
                      }

                      if($metode==3){
                        echo "Kredit";
                      }

                      if($metode==4){
                        echo "CBD";
                      }
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>Sales</td>
                  <td><?php echo $order['sales']; ?></td>
                </tr>
                <tr>
                  <td>Subtotal:</td>
                  <td><?php echo $this->currency->format($order['sub_total']); ?></td>
                </tr>
                <tr>
                  <td>Diskon</td>
                  <td><?php echo $this->currency->format($order['diskon']); ?></td>
                </tr>
                <tr>
                  <td>Pajak:</td>
                  <td><?php echo $this->currency->format($order['pajak']); ?></td>
                </tr>
                <?php if($order['pajak']==0){?>
                <tr>
                  <td>Keterangan Tanpa Ppn:</td>
                  <td><?php echo $order['keteranganpajak']; ?></td>
                </tr>
                <?php } ?>
                <!--tr>
                  <td>Pembulatan:</td>
                  <td><?php echo $this->currency->format($order['pembulatan']); ?></td>
                </tr-->
                <tr>
                  <td>Total:</td>
                  <td><?php echo $this->currency->format($order['total']); ?></td>
                </tr>
              </table>
            </div>
          </div>
        </div><hr>
          <div class="box-footer">
            <div class="row">
              <div class="col-xs-12 table-responsive">
                <table class="table table-striped" style="width: 100%">
                  <thead>
                  <tr>
                    <th>Qty</th>
                    <th>Produk ID</th>
                    <th>Nama Produk</th>
                    <th>Harga Satuan</th>
                    <th>Pajak Satuan</th>
                    <th>Harga Satuan<br>(Setelah Pajak)</th>
                    <th>Pajak Total</th>
                    <th>Total</th>
                    <!-- <th>Referensi</th> -->

                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  foreach($products as $p){
                    $pajak=!empty($p['totalpajak'])?floor($p['totalpajak']/$p['quantity']):$p['pajak'];
                    $hargasatuan=floor($p['total']/$p['quantity']);
                  ?>
                  <tr>
                    <td><?php echo $p['quantity']; ?></td>
                    <td><?php echo $p['product_id']; ?></td>
                    <td>
                      <?php echo $p['name']; ?><br>
                      <small><?php echo $p['no_tabung']; ?></small>
                    </td>
                    <td><?php echo $this->currency->format($p['price']); ?></td>
                    <td><?php echo $this->currency->format($pajak); ?></td>
                    <td><?php echo $this->currency->format($hargasatuan); ?></td>
                  <td><?php echo $this->currency->format(!empty($p['totalpajak'])?$p['totalpajak']:($p['pajak']*$p['quantity'])); ?></td>

                    <td><?php echo $this->currency->format($p['total']); ?></td>
                    

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

</body>
</html>