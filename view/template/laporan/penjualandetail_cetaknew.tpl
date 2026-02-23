<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Penjualan_Detail_Produk_".time().".xls");
?>
<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $language; ?>" xml:lang="<?php echo $language; ?>">
<head>
<title>Penjualan Detail New</title>
<base href="<?php echo $base; ?>" />
<link rel="stylesheet" type="text/css" href="view/stylesheet/invoice.css" />
<style>


table {
    border-collapse: collapse;
}

table, th, td {
    border: 1px solid black;
		padding:5px;
}

@page {
        size: landscape;
				width:100%;
        margin:1cm;
        margin-bottom:2cm;
        margin-top:2cm;
    }

@media print {
        #header, #footer
        {
            display:none
        }
    }

</style>
</head>
<body>

<div id="content">

  <h2>Laporan Penjualan Detail <?php echo $this->config->get('config_name'); ?> </h2>
  <h3>Periode <?php echo date('d/m/y',strtotime($filter_date_start)); ?> - <?php echo date('d/m/y',strtotime($filter_date_end)); ?></h3>

	 <table class="table table-bordered">
		 <thead>
			 <tr>
				 <th width="1" style="text-align: center;">Tanggal</th>
				 <th>Nama Sales</th>
				 <th class="left">Customer ID</th>
				 <th class="left">Nama Customer</th>
				 <th class="left">Kategori</th>
				 <th class="left">Telephone</th>
				 <th class="left">Alamat KTP</th>
				 <th class="left">Alamat NPWP</th>
				 <th class="left">Provinsi</th>
				 <th class="left">Nomor Invoice</th>
				 <th class="left">Nama Barang</th>
				 <th class="left">Jumlah</th>
				 <th class="left">Satuan</th>
				 <th class="left">Harga Satuan</th>
				 <th class="left">DPP</th>
				 <th class="left">Pajak</th>
				 <th class="left">Total</th>
				 <th class="left">Total Bayar</th>
				 <th class="left">Invoice</th>
				 <th class="left">Metode Pembayaran</th>
				 <th class="left">Lama Kredit (Hari)</th>
				 <th class="left">Status</th>
				 
			 </tr>
		 </thead>
		 <tbody>


			 <?php $a=0;$b=0;if ($penjualans) { ?>
			 <?php foreach ($penjualans as $product) { ?>
			 <?php
									 foreach($product['products'] as $p){
								 ?>
								 <tr >
								 	 <td><?php echo $product['tanggal']; ?></td>
									 <td class="left"><?php echo $product['namasales'] ?></td>
				 					 <td class="left"><?php echo $product['customer_id']; ?>
									  <td class="left"><?php echo $product['name']; ?>
									  <td class="left"><?php echo $product['kategori']; ?>
									  <td class="left"><?php echo $product['telephone']; ?>
									  <td class="left"><?php echo $product['alamatktp']; ?>
									  <td class="left"><?php echo $product['alamatnpwp']; ?>
									  <td class="left"><?php echo $product['provinsi']; ?>
									 <td><?php echo $p['name']; ?></td>
									 <td><?php echo $p['quantity'] ?></td>
									 <td><?php echo $p['namasatuan']; ?></td>
									 <td><?php echo $this->currency->format($p['price']); ?></td>
									 <td><?php echo $this->currency->format($p['price']*$p['quantity']); ?></td>
									 <td><?php echo $this->currency->format($p['pajak']); ?></td>
									 <td><?php echo $this->currency->format($p['total']); ?></td>
									 <td><?php echo $product['totalbayar']; ?></td>
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
										<?php echo $product['usia']?>
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

									?></td>
								 </tr>
							 <?php
							 $a++;
							 }
							 ?>
			 <!--
			 <tr class="invoice" data-id="<?php echo $product['id']; ?>" data-faktur="<?php echo $product['no_faktur']; ?>" id="list-invoice-<?php echo $product['id']; ?>" >
				 <td><?php echo $product['tanggal']; ?></td>
				 <td class="left"><?php echo $product['namasales'] ?></td>
				 <td class="left"><?php echo $product['name']; ?>
				 </td>
				 <td><?php echo $product['total']; ?></td>
				 <td><?php echo $product['totalbayar']; ?></td>
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
					<?php echo $product['usia']?>
				</td>
				 <td><?php
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

				 ?></td>


			 </tr>-->
			 <?php } ?>
			 <?php } else { ?>
			 <tr>
				 <td class="center" colspan="8">Data tidak ditemukan</td>
			 </tr>
			 <?php } ?>
		 </tbody>
	</table>

</div>

</div>
</body>
</html>
