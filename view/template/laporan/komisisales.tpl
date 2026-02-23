<?php echo $header; ?>
<?php
$marginbersih=0;
foreach($penjualans as $product){
                              $biayakirim=0;$profitmarginbersih=0;$biayabungakredit=0; $profitmarginkotor=0;$a=0;$b=0;
                              $totalqty=0;$satuan=null;$hargasatuan=0;$hterendah=0;$profitmarginkotor=0;$biayabungakredit=0;
                              $pmarginbersih=0;$bkirim=0;$totalprofitmagtinkotor=0;
                              $h=0;
                                  if($product['status'] == 3){
                                    $tanggal_lahir  = strtotime($product['tgl']);
                                    $sekarang    = strtotime($product['tglbyr']); // Waktu sekarang
                                    $diff   = $sekarang - $tanggal_lahir;
                                    $h=floor($diff / (60 * 60 * 24)) . ' '; // Umur anda dalam hitungan hari
                                  }else{
                                    $tanggal_lahir  = strtotime($product['tgl']);
                                    $sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
                                    $diff   = $sekarang - $tanggal_lahir;
                                    $h=floor($diff / (60 * 60 * 24)) . ' '; // Umur anda dalam hitungan hari
                                  }
                                $this->load->model('setting/setting');
                                foreach($product['products'] as $p){
                                  $bkirim = $this->model_setting_setting->getbiayakirimdetail($p['penjualan_id']);
                                  //$biayakirim=$p['penjualan_id'];
                                  $totalqty +=$p['quantity'];
                                  $satuan=$p['namasatuan'];
                                  $hargasatuan+=(round($p['price']*0.1+$p['price']));
                                  $this->load->model('gudang/product');
                                  $tglso=$this->model_gudang_product->gettglso($p['no_so']);
                                    if($filter_date_start>='5020-04-01'){
                                      $hargaterendah=$p['harga_terendah'];
                                    }else{
                                      $hargaterendah= $this->model_gudang_product->gethargaterendahdetailkomisi($tglso,$p['product_id'],$product['gudang_id']); 
                                    }
                                  //$hargaterendah= $this->model_gudang_product->gethargaterendahdetailkomisi($tglso,$p['product_id'],$product['gudang_id']); 
                                  $ppnh=0;
                                  $hterendah+=($hargaterendah);
                                  $profitmarginkotor = (round($hargasatuan) - round($hterendah));
                                  $totalprofitmagtinkotor += round($p['price']*0.1+$p['price']-$hargaterendah)*$p['quantity'];
                                  // biaya bunga kredit
                                  $htppn=$hargaterendah;
                                  if($product['status'] == 3){
                                    $tanggal_lahir  = strtotime($product['tgl']);
                                    $sekarang    = strtotime($product['tglbyr']); // Waktu sekarang
                                    $diff   = $sekarang - $tanggal_lahir;
                                    $h=floor($diff / (60 * 60 * 24)) . ' '; // Umur anda dalam hitungan hari
                                  }else{
                                    $tanggal_lahir  = strtotime($product['tgl']);
                                    $sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
                                    $diff   = $sekarang - $tanggal_lahir;
                                    $h=floor($diff / (60 * 60 * 24)) . ' '; // Umur anda dalam hitungan hari
                                  }

                                  if($h>0){
                                  $biayabungakredit += (round($htppn*$p['quantity']*0.025/30*$h));
                                  }else{
                                    $biayabungakredit=0;
                                  }
                                }
                                $marginbersih += round($totalprofitmagtinkotor-$bkirim-$biayabungakredit);
                                //print_r($p);
}


?>
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
            <h3 class="box-title">Laporan Komisi Sales</h3>
            <div class="button pull-right">

            </div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <?php if ($error_warning) { ?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                  <?php echo $error_warning; ?>
                </div>
                <?php
                }
                ?>
                <?php if ($success) { ?>
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-check"></i> Success!</h4>
                  <?php echo $success; ?>
                </div>
                <?php
                }
                ?>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-5">
                <table class="table table-stripped">
                  <tr>
                    <td>Tanggal Awal</td>
                    <td> <input type="text" class="form-control date" readonly name="filter_date_start" value="<?php echo $filter_date_start; ?>" /></td>
                  </tr>
                    <tr>
                      <td>Tanggal Akhir</td>
                      <td> <input type="text" class="form-control date" readonly name="filter_date_end" value="<?php echo $filter_date_end; ?>" /></td>
                    </tr>
                  <tr>
                    <td>Customer</td>
                    <td>
                    <select name="filter_customer_id" class="form-control lokasi-pameran">
                      <option value="*" <?php echo empty($filter_customer_id)?'selected':'';?>>Semua Customer</option>
                    </select>
                  </td>
                  </tr>
                  <tr>
                    <td>Provinsi</td>
                    <td>
                      <select name="filter_provinsi" multiple="multiple" class="form-control select" id="filter_provinsi">
                        <option value="*" <?php echo $filter_provinsi=='*'?'':'selected'?>>Semua</option>
                        <?php foreach($countries as $c){ ?>
                        <option value="<?php echo $c['country_id'] ?>" <?php echo $c['country_id']==$filter_provinsi?'selected':'' ?>><?php echo $c['name'] ?></option>
                        <?php } ?>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <td>Gudang</td>
                    <td>
                      <select class="form-control" name="filter_gudang_id">
                        <option value="*">Semua Lokasi</option>
                        <option value="1" >Tangerang</option>
                        <option value="3" >Surabaya</option>
                        <!--
                        <?php
                        foreach($gudangs as $g){
                        ?>
                          <option value="<?php echo $g['gudang_id']; ?>" ><?php echo $g['nama']; ?></option>
                        <?php
                        }
                        ?>-->
                        
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <td>Nama Sales</td>
                    <td>
                      <select name="sales" class="sales form-control">
                        <option value="*">Semua</option>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <td>Status Pembayaran</td>
                    <td id="status">
                      <input type="checkbox" name="filter_status[]" <?php echo in_array(1,$filter_status)?'checked':''; ?> value="1"> Ditagih<br>
                      <input type="checkbox" name="filter_status[]" <?php echo in_array(2,$filter_status)?'checked':''; ?> value="2"> Belum Lunas<br>
                      <input type="checkbox" name="filter_status[]" <?php echo in_array(3,$filter_status)?'checked':''; ?> value="3"> Lunas<br>
                      <!--<input type="checkbox" name="filter_status[]" <?php echo in_array(4,$filter_status)?'checked':''; ?> value="4"> Dibatalkan<br>-->
                      <!--select name="filter_status" class="form-control">
                          <option value="*">Semua Status</option>
                          <option value="1" >Ditagih</option>
                          <option value="2" >Belum Lunas</option>
                          <option value="3" >Lunas</option>
                          <option value="4" >Dibatalkan</option>
                      </select-->
                    </td>
                  </tr>
                  <tr>
                    <td><a onclick="filter();" class="btn btn-info">Filter</a></td>
                    <td><!--<a onclick="cetak();" class="btn btn-default">Cetak</a>-->
                      <!--<a href="<?php echo $cetak ?>" class="btn btn-default" target="_blank">Cetak</a>-->
                    </td>
                    <!-- <td></td> -->
                  </tr>
                  <tr>
                    <td><b>Jumlah Invoice: <?php echo $this->currency->format($jumlah); ?></b><br>
                      Jumlah Tanpa Pajak: <?php echo $this->currency->format($jumlahtanpapajak); ?>
                    </td>
                    <td><b><small>Jumlah Komisi: <?php echo $this->currency->format($marginbersih);?></b></small></td>
                  </tr>

                </table>

              </div>
              <div class="col-xs-7 table-responsive" style="max-height:300px;overflow-y:scroll">
                <div class="callout callout-success lead">
                  <h4>Rincian Barang <span id="display-faktur"></span></h4>

                </div>
                <table class="table table-bordered">
                  <thead>
                    <th>Jumlah</th>
                    <th>Nama Barang</th>
                    <th>Harga Satuan</th>
                    <th>PPN</th>
                    <th>Total Pajak</th>
                    <th>Total</th>
                  </thead>
                  <?php if ($penjualans) { ?>
                  <?php foreach ($penjualans as $p) {
                    ?>
                  <tbody class="list-product" id="list<?php echo $p['id']; ?>">
                      <?php
                        foreach($p['products'] as $product){
                      ?>
                      <tr >
                        <td><?php echo $product['quantity'].' '.$product['namasatuan']; ?></td>
                        <td><?php echo $product['name']; ?></td>
                        <td><?php echo $this->currency->format($product['price']); ?></td>
                        <td><?php echo $this->currency->format(round(($product['price']/10))); ?></td>
                        <td><?php echo $this->currency->format($product['pajak']); ?></td>
                        <td><?php echo $this->currency->format($product['total']); ?></td>
                      </tr>
                    <?php
                    }
                    ?>
                  </tbody>
                    <tfoot class="total-transaksi" id="total<?php echo $p['id']; ?>">
                      <tr >
                        <td class="text-right" colspan="5">Harga Jual/Penggantian/Uang Muka</td>
                        <td><?php echo $p['sub_total']; ?></td>
                      </tr>
                      <tr >
                        <td class="text-right" colspan="5">Potongan Harga</td>
                        <td><?php echo $this->currency->format($p['diskon']); ?></td>
                      </tr>

                      <tr >
                        <td class="text-right" colspan="5">Dasar Pengenaan Pajak</td>
                        <td><?php echo $p['dasar']; ?></td>
                      </tr>
                      <tr >
                        <td class="text-right" colspan="5">PPN 10%</td>
                        <td><?php echo $p['pajak']; ?></td>
                      </tr>
                      <tr >
                        <td class="text-right" colspan="5">Uang Muka yang Telah Diterima</td>
                        <td><?php echo $p['dp']; ?></td>
                      </tr>
                      <?php
                      if($p['jenisinvoice'] == 1 | $p['jenisinvoice'] == 3){
                      ?>
                      <tr >
                        <td class="text-right" colspan="5">Jumlah yang Harus Dibayar</td>
                        <td><?php echo $p['totaltagihan']; ?></td>
                      </tr>
                      <?php
                      }
                      ?>
                      <?php
                      if($p['jenisinvoice'] == 2){
                      ?>
                      <tr >
                        <td class="text-right" colspan="5">Total Tagihan</td>
                        <td><?php echo $p['totaltagihan']; ?></td>
                      </tr>
                      <tr >
                        <td class="text-right" colspan="5">Uang Muka yang Harus Dibayar</td>
                        <td><?php echo $p['total']; ?></td>
                      </tr>
                      <?php
                      }
                      ?>
                    </tfoot>
                    <?php
                    }
                    }
                    ?>

                </table>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
               <a href="<?php echo $exporttoexcel ?>" class="btn btn-success" target="_blank">Export to Excel</a>
                <div style="height:550px !important; overflow:auto">              
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
                  <table class="table table-hover" id="myTable">
                    <thead>
                      <tr>
                        <th></th>
                        <th>Tanggal Invoice</th>
                        <th>Tanggal Lunas</th>
                        <th>Nama Sales</th>
                        <th class="left">Nama Customer</th>
                        <th class="left">Nama Barang</th>
                        <th class="left">Qty</th>
                        <th class="left">Poin Penjualan</th>
                        <th class="left">Nilai Invoice</th>
                        <th class="left">Harga Terendah(termasuk PPn)</th>
                        <th class="left">Total Profit Margin Kotor</th>
                        <th class="left">Biaya Transport</th>
                        <th class="left">Biaya Bunga Kredit</th>
                        <th class="left">Invoice</th>
                        <th class="left">Metode Pembayaran</th>
                        <th class="left">Lama Bayar (Hari)</th>
                        <th class="left">Status</th>
                        <th class="left">Profit Margin Bersih</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($penjualans as $product) { ?>
                          <?php
                              $biayakirim=0;$profitmarginbersih=0;$biayabungakredit=0; $profitmarginkotor=0;$a=0;$b=0;
                              $totalqty=0;$satuan=null;$hargasatuan=0;$hterendah=0;$profitmarginkotor=0;$biayabungakredit=0;
                              $pmarginbersih=0;$bkirim=0;$totalpoinproduk=0;$promarginkotor=0;$totalmarginkotor=0;
                              $h=0;
                                  if($product['status'] == 3){
                                    $tanggal_lahir  = strtotime($product['tgl']);
                                    $sekarang    = strtotime($product['tglbyr']); // Waktu sekarang
                                    $diff   = $sekarang - $tanggal_lahir;
                                    $h=floor($diff / (60 * 60 * 24)) . ' '; // Umur anda dalam hitungan hari
                                  }else{
                                    $tanggal_lahir  = strtotime($product['tgl']);
                                    $sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
                                    $diff   = $sekarang - $tanggal_lahir;
                                    $h=floor($diff / (60 * 60 * 24)) . ' '; // Umur anda dalam hitungan hari
                                  }
                                $this->load->model('setting/setting');
                                foreach($product['products'] as $p){
                                  $bkirim = $this->model_setting_setting->getbiayakirimdetail($p['penjualan_id']);
                                  $totalqty +=$p['quantity'];
                                  $satuan=$p['namasatuan'];
                                  $hargasatuan+=(round($p['price']*0.1+$p['price'])*$p['quantity']);
                                  $this->load->model('gudang/product');
                                  $tglso=$this->model_gudang_product->gettglso($p['no_so']);
                                    if($filter_date_start>='5020-04-01'){
                                      $hargaterendah=$p['harga_terendah'];
                                    }else{
                                      $hargaterendah= $this->model_gudang_product->gethargaterendahdetailkomisi($tglso,$p['product_id'],$product['gudang_id']); 
                                    }
                                  $totalpoinproduk +=($this->model_gudang_product->getpoinproduct($tglso,$p['product_id'],$product['gudang_id'])*$p['quantity']); 
                                  $ppnh=0;
                                  $hterendah+=($hargaterendah)*$p['quantity'];
                                  $profitmarginkotor = (round($hargasatuan) - round($hterendah));
                                  $promarginkotor += round($p['price']*0.1+$p['price']-$hargaterendah)*$p['quantity'];

                                  // biaya bunga kredit
                                  $htppn=$hargaterendah;
                                  if($product['status'] == 3){
                                    $tanggal_lahir  = strtotime($product['tgl']);
                                    $sekarang    = strtotime($product['tglbyr']); // Waktu sekarang
                                    $diff   = $sekarang - $tanggal_lahir;
                                    $h=floor($diff / (60 * 60 * 24)) . ' '; // Umur anda dalam hitungan hari
                                  }else{
                                    $tanggal_lahir  = strtotime($product['tgl']);
                                    $sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
                                    $diff   = $sekarang - $tanggal_lahir;
                                    $h=floor($diff / (60 * 60 * 24)) . ' '; // Umur anda dalam hitungan hari
                                  }

                                  if($h>0){
                                  $biayabungakredit += (round($htppn*$p['quantity']*0.025/30*$h));
                                  }else{
                                    $biayabungakredit=0;
                                  }
                                }

                                $totalmarginkotor=$promarginkotor;
                               
                                
                          ?>
                          <tr class="invoice" data-id="<?php echo $product['id']; ?>" data-faktur="<?php echo $product['no_faktur']; ?>"  id="list-invoice-<?php echo $product['id']; ?>"  data-toggle="collapse" data-target=".demo<?php echo $product['id'] ?>" data-parent="#myTable">
                            <td ></td>
                            <td><?php echo $product['tanggal']; ?></td>
                            <td><?php echo $product['tgllunas']; ?></td>
                            <td><?php echo $product['namasales'] ?></td>
                            <td><?php echo $product['name']; ?></td>
                            <td></td>
                            <td><?php echo $totalqty." ".$satuan ?></td>
                            <td><?php echo $totalpoinproduk ?></td>
                            <td><?php echo $this->currency->format($hargasatuan) ?></td>
                            <td><?php echo $this->currency->format($hterendah) ?></td>
                            <td>
                              <?php 
                               echo $this->currency->format($totalmarginkotor) ;
                              ?>
                            </td>
                            <td>
                              <?php 
                                  echo $this->currency->format($bkirim);
                                
                               ?>
                            </td>
                            <td>
                              <?php echo $this->currency->format($biayabungakredit); ?>
                            </td>
                            <td><?php echo $product['no_faktur'] ?></td>
                            <td>
                                <?php 
                                  // 1 tunai, 2 cod, 3 kredit, 4 CBD
                                  $metode = $product['metode_pembayaran'];
                                  if($metode==1){
                                    echo "Tunai";
                                  }else if($metode==2){
                                    echo "COD";
                                  }else if($metode==3){
                                    echo "Kredit";
                                  }else{
                                    echo "CBD";
                                  }
                                ?>
                            </td>
                            <td>
                                <?php 
                                  echo $h;
                                ?>
                            </td>
                            <td>
                              <?php
                                if($product['status'] == 1){
                                  echo 'Ditagih';
                                }
                                if($product['status'] == 2){
                                  echo 'Belum Lunas';
                                }
                                if($product['status'] == 3){
                                  echo 'Lunas';
                                }
                                if($product['status'] == 4){
                                  echo 'Dibatalkan';
                                }
                              ?>
                            </td>
                            <td>
                              <?php 
                              $pmarginbersih=round($totalmarginkotor-$bkirim-$biayabungakredit); 
                              ?>
                              <?php if($pmarginbersih>0){ ?>
                                <span><?php echo $this->currency->format($pmarginbersih); ?></span>
                              <?php }else{ ?>
                                <span class="text-red"><?php echo $this->currency->format($pmarginbersih); ?></span>
                              <?php } ?>
                            </td>
                            <?php $poinproduk=0; foreach($product['products'] as $p){ ?>
                              <?php 
                                $this->load->model('gudang/product');
                                $tglsos=$this->model_gudang_product->gettglso($p['no_so']);
                                if($filter_date_start>='5020-04-01'){
                                  $hargaterendah=$p['harga_terendah'];
                                }else{
                                  $hargaterendah= $this->model_gudang_product->gethargaterendahdetailkomisi($tglsos,$p['product_id'],$product['gudang_id']); 
                                }
                                $poinproduk= $this->model_gudang_product->getpoinproduct($tglsos,$p['product_id'],$product['gudang_id']); 
                                $ppnh=0;
                              ?>
                              <tr id="demo<?php echo $product['id'] ?>" class="collapse demo<?php echo $product['id'] ?>" style="background-color:/*#c9c0bd*/#ebf2ff">
                                <td></td>
                                <td><?php echo $product['tanggal']; ?></td>
                                <td><?php echo $product['tgllunas']; ?></td>
                                <td class="left"></td>
                                <td class="left"></td>
                                <td><?php echo $p['name']; ?></td>
                                <td><?php echo $p['quantity']." ".$p['namasatuan'] ?></td>
                                <td class="left"><?php echo $poinproduk*$p['quantity'] ?></td>
                                <td><?php echo $this->currency->format(round($p['price']*0.1+$p['price'])*$p['quantity']); ?></td>
                                <td>
                                  <?php 
                                    echo $this->currency->format($hargaterendah*$p['quantity']);
                                  ?>
                                </td>
                                <td class="left">
                                  <?php $profitmarginkotor=round($p['price']*0.1+$p['price']) - round($hargaterendah); ?>
                                  <?php if($profitmarginkotor>0){ ?>
                                      <span class="text-black"><?php echo $this->currency->format($profitmarginkotor*$p['quantity']); ?></span>
                                  <?php }else{ ?>
                                      <span class="text-red"><?php echo $this->currency->format($profitmarginkotor*$p['quantity']); ?></span>
                                  <?php } ?>
                                  <?php 
                                    /*
                                    if($this->user->getUsername()=="pawit"){
                                      echo $this->currency->format($profitmarginkotor*$p['quantity']) ;
                                    }else{
                                      //echo $this->currency->format($profitmarginkotor);
                                    }
                                    */
                                  ?>
                                </td>
                                <td>0</td>
                                <td>
                                  <?php
                                  // biaya bunga kredit
                                  $htppn=$hargaterendah;
                                  if($product['status'] == 3){
                                    $tanggal_lahir  = strtotime($product['tgl']);
                                    $sekarang    = strtotime($product['tglbyr']); // Waktu sekarang
                                    $diff   = $sekarang - $tanggal_lahir;
                                    $h=floor($diff / (60 * 60 * 24)) . ' '; // Umur anda dalam hitungan hari
                                  }else{
                                    $tanggal_lahir  = strtotime($product['tgl']);
                                    $sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
                                    $diff   = $sekarang - $tanggal_lahir;
                                    $h=floor($diff / (60 * 60 * 24)) . ' '; // Umur anda dalam hitungan hari
                                  }

                                  if($h>0){
                                  $biayabungakredit = round($htppn*$p['quantity']*0.025/30*$h);
                                  echo $this->currency->format($biayabungakredit);
                                  }else{
                                    $biayabungakredit=0;
                                    echo $biayabungakredit;
                                  }
                                  ?>
                                </td>
                                <td class="left"><?php echo $product['no_faktur']; ?></td>
                                <td>
                                  <?php 
                                    // 1 tunai, 2 cod, 3 kredit, 4 CBD
                                    $metode = $product['metode_pembayaran'];
                                    if($metode==1){
                                      echo "Tunai";
                                    }else if($metode==2){
                                      echo "COD";
                                    }else if($metode==3){
                                      echo "Kredit";
                                    }else{
                                      echo "CBD";
                                    }
                                  ?>
                                </td>
                                <td>
                                  <?php 
                                    $tanggal_lahir  = strtotime($product['tgl']);
                                    $sekarang    = strtotime($product['tglbyr']); // Waktu sekarang
                                    $diff   = $sekarang - $tanggal_lahir;
                                    if($product['status'] == 3){
                                      echo ' ' . floor($diff / (60 * 60 * 24)) . ' '; // Umur anda dalam hitungan hari
                                    }else{
                                      echo 'belum lunas';
                                    }
                                  ?>
                                </td>
                                <td>
                                  <?php
                                          if($product['status'] == 1){
                                            echo 'Ditagih';
                                          }
                                          if($product['status'] == 2){
                                            echo 'Belum Lunas';
                                          }
                                          if($product['status'] == 3){
                                            echo 'Lunas';
                                          }
                                          if($product['status'] == 4){
                                            echo 'Dibatalkan';
                                          }
                                  ?>
                                </td>
                                <td><?php echo $this->currency->format(round($profitmarginkotor*$p['quantity']-0-$biayabungakredit)) ?></td>
                              </tr>
                            <?php } ?>
                          </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </form>
              </div>
            </div>

          </div>
          <div class="box-footer">
            <div class="row">
              <div class="col-md-12">
              <div class="pull-right"><?php //echo $pagination; ?></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
</div>
<?php if (count($penjualans)>0) { ?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
<script>
$(document).ready( function () {
    $('#myTable').DataTable( {
        "lengthChange": false,
        "bPaginate": false,
        "bFilter": false,
    } );
} );
</script>
<?php } ?>
<script>
$('.sidebar-menu').find('#menu-laporan').addClass('active');
function show(id){
  //alert(id);
   $('.collapse'+id).show();
   $('#plus'+id).hide();
   $('#minus'+id).show();
}
function hide(id){
  //alert(id);
   $('.collapse'+id).hide();
   $('#minus'+id).hide();
   $('#plus'+id).show();
}
$(function(){
  $('.date').datepicker({dateFormat: 'yy-mm-dd'});
  $('.list-product').hide();
  $('.total-transaksi').hide();

  $(".invoice").on('click',function(){
    id=$(this).data('id');
    faktur=$(this).data('faktur');

    $("#display-faktur").html(faktur);
    $(".invoice td").css('background-color','#fff');
    $(".invoice td").css('font-weight','normal');
    //$("#list-invoice-"+id+" td").css('background-color','#ccc');
    $("#list-invoice-"+id+" td").css('font-weight','bold');

    $('.list-product').hide();
    $('.total-transaksi').hide();

    $("#list"+id).show();
    $("#total"+id).show();
  });
});
$(".select").select2({
    //width: 'resolve' // need to override the changed default
    theme:"bootstrap"
});
$(".sales").select2({
    ajax: {
    url:"index.php?route=user/user/autocomplete&token=<?php echo $this->request->get['token']; ?>",
      dataType: 'json',
    data: function (params) {
      return {
        q: params.term,
        j:21

      };
    },
    delay: 250,
    processResults: function (data) {
      return {
        results: data
      };
    },
    //cache: true
  },
  theme:"bootstrap"
  });
</script>
<script type="text/javascript"><!--
function filter() {
	url = "index.php?route=laporan/komisisales&token=<?php echo $token; ?>";
  
  var filter_provinsi = $('select[name=\'filter_provinsi\']').val();
	if (filter_provinsi != '*') {
		url += '&filter_provinsi=' + encodeURIComponent(filter_provinsi);
	}
  
	
  var filter_customer_id = $('select[name=\'filter_customer_id\']').val();

	if (filter_customer_id != '*') {
		url += '&filter_customer_id=' + encodeURIComponent(filter_customer_id);
	}

  var filter_gudang_id = $('select[name=\'filter_gudang_id\']').val();

	if (filter_gudang_id != '*') {
		url += '&filter_gudang_id=' + encodeURIComponent(filter_gudang_id);
	}

  var filter_sales= $('select[name=\'sales\']').val();

	if (filter_sales != '*') {
		url += '&filter_sales=' + encodeURIComponent(filter_sales);
	}

  var filter_date_start = $('input[name=\'filter_date_start\']').val();

	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

  var filter_date_end = $('input[name=\'filter_date_end\']').val();

	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}

  var filter_register_start = $('input[name=\'filter_register_start\']').val();

	if (filter_register_start) {
		url += '&filter_register_start=' + encodeURIComponent(filter_register_start);
	}

  var filter_register_end = $('input[name=\'filter_register_end\']').val();

	if (filter_register_end) {
		url += '&filter_register_end=' + encodeURIComponent(filter_register_end);
	}

  var filter_statuss = $("#status input:checkbox:checked").map(function(){
      return $(this).val();
    }).get(); // <----

    //alert(JSON.stringify(filter_statuss));
    url+='&filter_status=' +filter_statuss;

  /*var filter_shipping_method = $('select[name=\'filter_shipping_method\']').val();

	if (filter_shipping_method != '*') {
		url += '&filter_shipping_method=' + encodeURIComponent(filter_shipping_method);
	}
*/


	location = url;
}
function cetak() {
	//url = "index.php?route=laporan/penjualandetail&print=1&token=<?php echo $token; ?>";
  url ="<?php echo $cetak ?>";

	var filter_customer_id = $('select[name=\'filter_customer_id\']').val();

	if (filter_customer_id != '*') {
		url += '&filter_customer_id=' + encodeURIComponent(filter_customer_id);
	}

  var filter_gudang_id = $('select[name=\'filter_gudang_id\']').val();

	if (filter_gudang_id != '*') {
		url += '&filter_gudang_id=' + encodeURIComponent(filter_gudang_id);
	}

  /*  var filter_status= $('select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}*/

  var filter_date_start = $('input[name=\'filter_date_start\']').val();

	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

  var filter_date_end = $('input[name=\'filter_date_end\']').val();

	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}

  var filter_statuss = $("#status input:checkbox:checked").map(function(){
      return $(this).val();
    }).get(); // <----

    //alert(JSON.stringify(filter_statuss));
    url+='&filter_status=' +filter_statuss;

  /*var filter_shipping_method = $('select[name=\'filter_shipping_method\']').val();

	if (filter_shipping_method != '*') {
		url += '&filter_shipping_method=' + encodeURIComponent(filter_shipping_method);
	}
*/


  window.open(
  url,
  '_blank' // <- This is what makes it open in a new window.
  );
}
$(function(){
  $(".select-ads").select2({


      theme:"bootstrap"
    });
  $(".lokasi-pameran").select2({
    ajax: {
    url:"index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>",
    //url: "index.php?route=pamerantoko/toko/autocomplete&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        q: params.term, // search term

      };
    },
    delay: 250,
    processResults: function (data) {
      return {
        results: data
      };
    },
    //cache: true
  },
  theme:"bootstrap"
  });

  $(".salesorder").select2({
    ajax: {
    url:"index.php?route=sale/invoice/autocomplete&token=<?php echo $token; ?>",
    //url: "index.php?route=pamerantoko/toko/autocomplete&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        q: params.term, // search term

      };
    },
    delay: 250,
    processResults: function (data) {
      return {
        results: data
      };
    },
    //cache: true
  },
  theme:"bootstrap"
  });

  $(".jenisorder").select2({
    ajax: {
    url:"index.php?route=catalog/product/autocompleteprod&token=<?php echo $this->request->get['token']; ?>",
      dataType: 'json',
    data: function (params) {
      return {
        q: params.term

      };
    },
    delay: 250,
    processResults: function (data) {
      return {
        results: data
      };
    },
    //cache: true
  },
  theme:"bootstrap"
  });
})
//--></script>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script>
<script type="text/javascript"><!--
$('input[name=\'filter_name\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/atk/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item.nama,
						value: item.atk_id
					}
				}));
			}
		});
	},
	select: function(event, ui) {
		$('input[name=\'filter_name\']').val(ui.item.label);

		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});


//--></script>
<script>
function detail(id,faktur){
  //alert(id);
  $("#display-faktur").html(faktur);
  $(".invoice td").css('background-color','#fff');
  $(".invoice td").css('font-weight','normal');
  $("#list-invoice-"+id+" td").css('background-color','#ccc');
  $("#list-invoice-"+id+" td").css('font-weight','bold');

  $('.list-product').hide();
  $('.total-transaksi').hide();

  $("#list"+id).show();
  $("#total"+id).show();
}
</script>
<?php echo $footer; ?>
