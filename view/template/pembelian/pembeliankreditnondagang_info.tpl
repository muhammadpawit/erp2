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
            <h3 class="box-title">Pembelian Lokal Non Produk Dagang</h3>
            <div class="button pull-right">
                  <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>
								</div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <table class="table">
                  <tr>
                      <td>Nomor PO:</td>
                      <td><?php echo $permintaan['no_po'] ?></td>
                  </tr>
                  <tr>
                      <td>Nomor SPPB:</td>
                      <td><?php echo $permintaan['no_surat'] ?></td>
                  </tr>

                  <tr>
                      <td>Tanggal:</td>
                      <td><?php echo date('d F Y',strtotime($permintaan['date_added'])) ?></td>
                  </tr>

                   <tr>
                      <td>Vendor:</td>
                      <td><?php echo $permintaan['name'] ?></td>
                  </tr>

                 <tr>
                    <td>Jenis Barang:</td>
                    <td><?php echo $permintaan['jenis_barang'] == 1?'Bahan Baku':($permintaan['jenis_barang'] == 2?'Produk Dagang':($permintaan['jenis_barang'] == 3?'ATK':'Aset')); ?></td>
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
                    <td>Total Pembelian:</td>
                    <td><?php echo $this->currency->format($permintaan['total_pembelian']) ?></td>
                </tr>

            </table>



              </ul>
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
                          <td><?php echo $p['quantityterima']; ?></td>
                          <td><?php echo $this->currency->format($p['harga']); ?></td>
                          <td><?php echo $this->currency->format($p['ppn']); ?></td>
                          <td><?php echo $this->currency->format(($p['harga']*$p['quantity'])+($p['ppn']*$p['quantity'])); ?></td>
                        </tr>
                        <?php
                        }
                        ?>
                      </tbody>
                  </table>




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
$('.sidebar-menu').find('#menu-pembelian-lokal').addClass('active');

</script>

<?php echo $footer; ?>
