<!DOCTYPE html>
<html>
<head>
  <title>Penerimaan Barang Datang Import</title>
  <style type="text/css">
    h2{ text-align: center; }
    .table{width: 100%}
    .table thead th { text-align: center; }
    .table tbody tr td { text-align: center; }
  </style>
</head>
<body onload="window.print()">
  <h2>Penerimaan Barang Pembelian Import</h2>
<table class="tables">
  <tr>
    <td>Nomor Faktur</td>
    <td>:&nbsp;<?php echo $permintaan['no_faktur'] ?></td>
  </tr>
  <tr>
  <td>Vendor</td>
  <td>:&nbsp;<?php echo $permintaan['name'] ?></td>
  </tr>
  <tr>
    <td>Tanggal</td>
    <td>:&nbsp;<?php echo date('d F Y',strtotime($permintaan['date_added'])) ?></td>
  </tr>
</table>
<hr>
<h3 class="box-title">History Penerimaan</h3>
<table class="table">
                <thead>
                  <th>No.SJ</th>
                  <th>Tgl.SJ</th>
                  <th>Tgl.Terima</th>
                  <th>No.Polisi</th>
                  <th>Penerima</th>
                  <th>Pengangkut</th>
                  <th>Item</th>
                  <th>Qty</th>
                  <th>Qty Terima</th>
                </thead>
                <tbody>
                  <?php
                  //print_r($products);
                  $this->load->model('user/user');

                  foreach($products as $p){
                    $penerima='';
                    $pengangkut='';
                    if(!empty($p['penerima_id'])){
                      $pen=$this->model_user_user->getUser($p['penerima_id']);
                      $penerima=$pen['firstname'];
                    }

                    if(!empty($p['pengangkut_id'])){
                      $pen=$this->model_user_user->getUser($p['pengangkut_id']);
                      $pengangkut=$pen['firstname'];
                    }
                  ?>
                  <tr>
                    <td><?php echo $p['no_suratjalan']; ?></td>
                    <td><?php echo !empty($p['tgl_surat'])?date('d/m/y',strtotime($p['tgl_surat'])):date('d/m/y',strtotime($p['date_added'])); ?></td>
                    <td><?php echo !empty($p['tgl_terima'])?date('d/m/y',strtotime($p['tgl_terima'])):date('d/m/y',strtotime($p['date_added'])); ?></td>
                    <td><?php echo $p['no_pol']; ?></td>
                    <td><?php echo $penerima; ?></td>
                    <td><?php echo $pengangkut; ?></td>
                    <td style="text-align: left !important;"><?php echo $p['product_name']; ?></td>
                    <td><?php echo $p['quantity']; ?></td>
                    <td><?php echo $p['qtyterima']; ?></td>
                  </tr>
                  <?php
                  }
                  ?>
                </tbody>
            </table>
</body>
</html>