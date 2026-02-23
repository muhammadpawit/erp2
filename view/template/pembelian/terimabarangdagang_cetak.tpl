<!DOCTYPE html>
<html>
<head>
  <title>Terima Barang Datang Pembelian Lokal</title>
  <style type="text/css">
    body { font-weight: 100 }
    .h3{ text-align: center; }
    .table { width: 100%; text-align: left; }
  </style>
</head>
<body onload="window.print()">
  <h3>Terima Barang Datang Pembelian Lokal</h3>
          <table class="table" style="width: 50%">
                  <tr>
                      <td>Nomor Surat Jalan</td>
                      <td>:&nbsp;<?php echo $permintaan['no_suratjalan'] ?></td>
                  </tr>
                  <tr>
                      <td>Gudang</td>
                      <td>:&nbsp;<?php echo $permintaan['nama'] ?></td>
                  </tr>
                  <tr>
                      <td>Tanggal Dibuat</td>
                      <td>:&nbsp;<?php echo date('d F Y',strtotime($permintaan['date_added'])) ?></td>
                  </tr>
                  <tr>
                      <td>Tanggal Surat Jalan</td>
                      <td>:&nbsp;<?php echo date('d F Y',strtotime($permintaan['tgl_surat'])) ?></td>
                  </tr>
                  <tr>
                      <td>Tanggal Barang Datang</td>
                      <td>:&nbsp;<?php echo date('d F Y',strtotime($permintaan['tgl_terima'])) ?></td>
                  </tr>
                  <tr>
                     <td>No. Polisi</td>
                     <td>:&nbsp;<?php echo $permintaan['no_pol']?></td>
                 </tr>
                 <tr>
                    <td>Penerima:</td>
                    <td>:&nbsp;<?php echo $penerima?></td>
                  </tr>
                  <tr>
                     <td>Pengangkut:</td>
                     <td>:&nbsp;<?php echo $pengangkut?></td>
                 </tr>

            </table>  
            <hr>
            <table class="table table-responsive" id="list-product-detail" >
              <thead>
                <th>No. PO</th>
                <th>Nama Produk</th>
                <th>Quantity SJ</th>


              </thead>
              <tbody>
                <?php
                foreach($products as $p){

                ?>
                <tr>
                  <td><?php echo $p['no_po']; ?></td>
                  <td><?php echo $p['product_name']; ?></td>
                  <td><?php echo $p['qtyterima']; ?></td>

                </tr>
                <?php
                }
                ?>
              </tbody>
            </table>
</body>
</html>