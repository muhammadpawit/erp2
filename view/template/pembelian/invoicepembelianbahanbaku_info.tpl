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
            <h3 class="box-title">Invoice Pembelian Bahan Baku</h3>
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
                     <td>Biaya Kirim:</td>
                     <td><?php echo $this->currency->format($permintaan['biayakirim']) ?></td>
                    </tr>
                <tr>
                   <td>Sub Total:</td>
                   <td><?php echo $this->currency->format($permintaan['sub_total']) ?></td>
                  </tr>
                  <tr>
                     <td>Diskon:</td>
                     <td><?php echo $this->currency->format($permintaan['diskon']) ?></td>
                    </tr>
                  <tr>
                     <td>Pajak:</td>
                     <td><?php echo $this->currency->format($permintaan['pajak']) ?></td>
                 </tr>
                 <tr>
                    <td>Total Tagihan:</td>
                    <td><?php echo $this->currency->format($permintaan['totaltagihan']+$permintaan['biayakirim']) ?></td>
                </tr>
                <tr>
                   <td>Total Bayar:</td>
                   <td><?php echo $this->currency->format($permintaan['totalbayar']) ?></td>
               </tr>

            </table>

            <div class="nav-tabs-custom">
              <ul class="nav nav-tabs">
                <li class="active"><a href="#detail" data-toggle="tab">Daftar Barang</a></li>
                <li><a href="#pembayaran" data-toggle="tab">Pembayaran</a></li>


              </ul>
              <div class="tab-content">
                <div class="tab-pane active " id="detail">
                  <table class="table">
                      <thead>
                        <th>No. PO</th>
                        <th>Nama Produk</th>
                        <th>Quantity</th>
                        <!--th>Quantity Telah Terima</th-->
                        <th>Harga</th>
                        <th>Pajak</th>
                        <th>Total</th>


                      </thead>
                      <tbody>
                        <?php
                        foreach($products as $p){
                        ?>
                        <tr>
                          <td>
                            <?php
                            $po=$this->model_pembelian_pembeliankreditdagang->getPermintaanPembelian(array(),array(),array('id'=>$p['po_id']));
                            echo $po['no_po'];
                            ?>
                          </td>
                          <td><?php echo $p['product_name']; ?></td>
                          <td><?php echo $p['quantity']; ?></td>
                          <!--td><?php echo empty($p['quantityterima'])?0:$p['quantityterima']; ?></td-->
                          <td><?php echo $this->currency->format($p['price']); ?></td>
                          <td><?php echo $this->currency->format($p['pajak']); ?></td>
                          <td><?php echo $this->currency->format(($p['price']*$p['quantity'])+($p['pajak']*$p['quantity'])); ?></td>
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

                      </thead>
                      <tbody>
                        <?php
                        foreach($pembayarans as $p){
                        ?>
                        <tr>
                          <td><?php echo date('d/m/y',strtotime($p['tglalokasi'])); ?></td>
                          <td><?php echo number_format($p['nominal'],2,'.',','); ?></td>

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
$('.sidebar-menu').find('#menu-pembelian-kredit').addClass('active');

</script>

<?php echo $footer; ?>
