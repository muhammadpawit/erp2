<?php
// Fungsi header dengan mengirimkan raw data excel
header("Content-type: application/vnd-ms-excel");
 
// Mendefinisikan nama file ekspor "hasil-export.xls"
if($this->user->getUsername()=="pawitx"){

}else{
header("Content-Disposition: attachment; filename=Komisi_Sales.xls");
}

 
?>
<table border="1" style="width:100%">
<thead>
                      <tr>
                        <th>Tanggal Invoice</th>
                        <th>Tanggal Lunas</th>
                        <th>Nama Sales</th>
                        <th class="left">Nama Customer</th>
                        <th class="left">Nama Barang</th>
                        <th class="left">Qty</th>
                        <th class="left">Poin Penjualan</th>
                        <th class="left">Harga Satuan(termasuk PPn)</th>
                        <th class="left">Harga Terendah(termasuk PPn)</th>
                        <th class="left">Total Profit Margin Kotor</th>
                        <th class="left">Biaya Transport</th>
                        <th class="left">Biaya Bunga Kredit</th>
                        <th class="left">Invoice</th>
                        <th class="left">Metode Pembayaran</th>
                        <th class="left">Lama Bayar (Hari)</th>
                        <th class="left">Status</th>
                        <th class="left">Total invoice</th>
                        <th class="left">Total Harga Terendah</th>
                        <th class="left">Profit Margin Bersih(belum termasuk biaya kirim)</th>
                        <th class="left">Total Margin Bersih</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($penjualans as $product) { ?>
                          <?php
                              $biayakirim=0;$profitmarginbersih=0;$biayabungakredit=0; $profitmarginkotor=0;$a=0;$b=0;
                              $totalqty=0;$satuan=null;$hargasatuan=0;$hterendah=0;$profitmarginkotor=0;$biayabungakredit=0;
                              $pmarginbersih=0;$bkirim=0;$totalpoinproduk=0;$promarginkotor=0;$totalmarginkotor=0;
                              $h=0;$totalht=0;
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
                                      $hargaterendah=$this->model_gudang_product->gethargaterendahdetailkomisi($tglso,$p['product_id'],$product['gudang_id']);
                                      $ht= $this->model_gudang_product->gethargaterendahdetailkomisi($tglso,$p['product_id'],$product['gudang_id']);
                                    }
                                    $totalht+=$ht*$p['quantity'];
                                  $totalpoinproduk +=($this->model_gudang_product->getpoinproduct($tglso,$p['product_id'],$product['gudang_id'])*$p['quantity']); 
                                  $ppnh=0;
                                  $hterendah+=($hargaterendah*$p['quantity']);
                                  $profitmarginkotor = (round($hargasatuan) - round($hterendah));
                                  $promarginkotor += round($p['price']*0.1+$p['price']-$hargaterendah)*$p['quantity'];
                                  
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
                                $pmarginbersih=round($promarginkotor-$bkirim-$biayabungakredit); 
                                $totalmarginkotor=$promarginkotor;
                          ?>
                            <?php $i=0;$j=0;$k=0;$l=0;$m=0;$poinproduk=0; $totalp=0;$ht=0;$q=0;?>
                            <?php foreach($product['products'] as $p){ ?>
                            <?php 
                                    $this->load->model('gudang/product');
                                    if($filter_date_start>='5020-04-01'){
                                      $hargaterendah=$p['harga_terendah'];
                                    }else{
                                      $hargaterendah= $this->model_gudang_product->gethargaterendahdetailkomisi($tglso,$p['product_id'],$product['gudang_id'])*$p['quantity'];
                                      
                                    }
                                $poinproduk= $this->model_gudang_product->getpoinproduct($tglso,$p['product_id'],$product['gudang_id']); 
                                $ppnh=0;
                            ?>
                              <tr id="demo<?php echo $product['id'] ?>" class="collapse demo<?php echo $product['id'] ?>" style="background-color:#ebf2ff">
                                <td><?php echo $product['tanggal']; ?></td>
                                <td><?php echo $product['tgllunas']; ?></td>
                                <td><?php echo $product['namasales'] ?></td>
                                <td><?php echo $product['name']; ?></td>
                                <td><?php echo $p['name']; ?></td>
                                <td><?php echo $p['quantity'] ?></td>
                                <td class="left"><?php echo $poinproduk*$p['quantity'] ?></td>
                                <td><?php echo round($p['price']*0.1+$p['price'])*$p['quantity']; ?></td>
                                <td>
                                  <?php 
                                    echo $hargaterendah==null?0:$hargaterendah;
                                  ?>
                                </td>
                                <td class="left">
                                  <?php $profitmarginkotor=round($p['price']*0.1+$p['price']) - round($hargaterendah); ?>
                                  <?php if($profitmarginkotor>0){ ?>
                                      <span class="text-black"><?php echo $profitmarginkotor*$p['quantity']; ?></span>
                                  <?php }else{ ?>
                                      <span class="text-red"><?php echo $profitmarginkotor*$p['quantity']; ?></span>
                                  <?php } ?>
                                </td>
                                <td><?php if(0==$i++) {echo $bkirim;} ?></td>
                                <td>
                                  <?php
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
                                  echo $biayabungakredit;
                                  }else{
                                    $biayabungakredit=0;
                                    echo $biayabungakredit;
                                  }
                                  ?>
                                </td>
                                <td class="left"><?php echo $product['no_faktur']; ?></td>
                                <td>
                                  <?php 
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
                                <td>
                                <?php
                                    if(0==$l++){echo $product['totaliv'];}; 
                                 ?>
                                </td>
                                <td><?php if(0==$q++){echo $totalht;}?></td>
                                <td><?php echo ($profitmarginkotor*$p['quantity'] - $biayabungakredit) ?></td>
                                <td>                      
                                <?php
                                    if(0==$j++){echo $pmarginbersih;};
                                 ?>
                                </td>
                              </tr>
                            <?php } ?>
                      <?php } ?>
                    </tbody>                    
</table>