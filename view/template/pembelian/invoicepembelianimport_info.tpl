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
            <h3 class="box-title">Invoice Pembelian Import</h3>
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

            <div class="nav-tabs-custom">
              <ul class="nav nav-tabs">
                <li class="active"><a href="#detail" data-toggle="tab">Daftar Barang</a></li>
                <li ><a href="#pib" data-toggle="tab">Pemberitahuan Import Barang</a></li>
                  <li ><a href="#kursdatang" data-toggle="tab">Kurs Barang Datang</a></li>
                <li><a href="#biaya" data-toggle="tab">Biaya</a></li>
                <li><a href="#pembayaran" data-toggle="tab">Pembayaran</a></li>


              </ul>
              <div class="tab-content">
                <div class="tab-pane active " id="detail">
                  <table class="table">
                      <thead>
                        <th>Nama Produk</th>
                        <th>Quantity</th>
                        <th>Quantity Terima</th>
                        <th>Harga</th>
                        <th>Pajak</th>
                        <th>Total</th>


                      </thead>
                      <tbody>
                        <?php
                        foreach($products as $p){
                        ?>
                        <tr>
                          <td><?php echo $p['product_name']; ?></td>
                          <td><?php echo $p['quantity']; ?></td>
                          <td><?php echo empty($p['quantityterima'])?0:$p['quantityterima']; ?></td>
                          <td>$<?php echo number_format($p['price'],2,'.',','); ?></td>
                          <td>$<?php echo number_format($p['pajak'],2,'.',','); ?></td>
                          <td>$<?php echo number_format(($p['price']*$p['quantity'])+($p['pajak']*$p['quantity']),2,'.',','); ?></td>
                        </tr>
                        <?php
                        }
                        ?>
                      </tbody>
                  </table>
                </div>
                <div class="tab-pane " id="kursdatang">
                  <table class="table">

                      <tr>
                        <th>
                          Kurs
                        </th>
                        <td>
                          <?php echo $this->currency->format($permintaan['kursdatang']); ?>
                        </td>
                      </tr>
                      <tr>
                        <th>
                          Tanggal Kurs
                        </th>
                        <td>
                          <?php echo date('d/m/y',strtotime($permintaan['tglkursdatang'])); ?>
                        </td>
                      </tr>

                  </table>
                </div>
                <div class="tab-pane " id="pib">
                  <table class="table">
                      <tr>
                        <th>
                          No. Billing PIB
                        </th>
                        <td>
                          <?php
                          echo $permintaan['no_pib'] ?>
                        </td>
                      </tr>
                      <tr>
                        <th>
                          PPn
                        </th>
                        <td>
                          <?php echo $this->currency->format($permintaan['ppnpib']); ?>
                        </td>
                      </tr>
                      <tr>
                        <th>
                          PPh
                        </th>
                        <td>
                          <?php echo $this->currency->format($permintaan['pphpib']); ?>
                        </td>
                      </tr>
                      <tr>
                        <th>
                          BM
                        </th>
                        <td>
                          <?php echo $this->currency->format($permintaan['bmpib']); ?>
                        </td>
                      </tr>
                      <tr>
                        <th>
                          Kurs Pajak
                        </th>
                        <td>
                          <?php echo $this->currency->format($permintaan['kurspajakpib']); ?>
                        </td>
                      </tr>
                  </table>
                </div>
                <div class="tab-pane" id="biaya">
                  <table class="table">
                      <thead>
                        <th>Nama Biaya</th>
                        <th>Estimasi Total</th>
                        <th>Total Tagihan</th>
                        <th>Status Pembayaran</th>

                      </thead>
                      <tbody>
                        <?php
                        foreach($biayas as $p){
                        ?>
                        <tr>
                          <td><?php echo $p['name']; ?></td>
                          <td><?php
                            //print_r($p);
                            if($p['currency'] == 1){
                              echo '$'.$p['total'];
                            }else{
                              echo $this->currency->format($p['total']);
                            }
                             ?></td>
                             <td><?php
                               //print_r($p);
                               if($p['statuspembayaran'] > 0){
                                 echo $this->currency->format($p['totalreal']);
                               }else{
                                 echo 'Belum Ada Tagihan';
                               }

                                ?></td>
                            <td>
                              <?php
                              if($p['statuspembayaran'] == 0){
                                echo 'Belum Ada Tagihan';
                              }
                              if($p['statuspembayaran'] == 1){
                                echo 'Ditagih';
                              }
                              if($p['statuspembayaran'] == 2){
                                echo 'Dibayar Sebagian';
                              }
                              if($p['statuspembayaran'] == 3){
                                echo 'Lunas';
                              }
                              if($p['statuspembayaran'] == 4){
                                echo 'Dibatalkan';
                              }
                              ?>
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                      </tbody>
                  </table>
                </div>
                <div class="tab-pane" id="pembayaran">
                  <table class="table">
                      <thead>
                        <th>Tanggal Alokasi</th>
                        <th>Total Alokasi</th>
                        <th>Kurs</th>
                      </thead>
                      <tbody>
                        <?php
                        foreach($pembayarans as $p){
                        ?>
                        <tr>
                          <td><?php echo date('d/m/y',strtotime($p['tglalokasi'])); ?></td>
                          <td><?php echo number_format($p['nominal'],2,'.',','); ?></td>
                          <td><?php echo $this->currency->format($p['kurs']); ?></td>
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
