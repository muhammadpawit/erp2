<?php
//header("Content-type: application/vnd-ms-excel");
//header("Content-Disposition: attachment; filename=Laporan_Penjualan_Detail_Produk_".time().".xls");
?>

<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $language; ?>" xml:lang="<?php echo $language; ?>">
<head>
<title>Penjualan Detail</title>
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
<body onload="window.print()">

<div id="content">

  <h2>Laporan Penjualan Detail <?php echo $this->config->get('config_name'); ?> </h2>
  <h3>Periode <?php echo date('d/m/y',strtotime($filter_date_start)); ?> - <?php echo date('d/m/y',strtotime($filter_date_end)); ?></h3>

 <table class="store">
	 <table class="table table-bordered">
		 <thead>
			 <tr>
				 <th width="1" style="text-align: center;">Tanggal</th>
				 <th>Nama Sales</th>
				 <th class="left">Nama Customer</th>
				 <th class="left">Jumlah</th>
				 <th class="left">Total Bayar</th>
				 <th class="left">Invoice</th>
				 <th class="left">Metode Pembayaran</th>
				 <th class="left">Lama Kredit (Hari)</th>
				 <th class="left">Status</th>

			 </tr>
		 </thead>
		 <tbody>


			 <?php if ($penjualans) { ?>
			 <?php foreach ($penjualans as $product) { ?>
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


			 </tr>
			 <tr>
				 <td colspan="9">
					 <table class="table table-bordered">
						 <thead>
							 <th>Jumlah</th>
							 <th>Nama Barang</th>
							 <th>Harga Satuan</th>
							 <th>Total</th>
							 <th>Pajak</th>
							 <!--<th></th>
							 <th></th>
							 <th></th>
							 <th></th>-->
						 </thead>

						 <tbody class="list-product" id="list<?php echo $product['id']; ?>">
								 <?php
									 foreach($product['products'] as $p){
								 ?>
								 <tr >
									 <td><?php echo $p['quantity'].' '.$p['namasatuan']; ?></td>
									 <td><?php echo $p['name']; ?></td>
									 <td><?php echo $this->currency->format($p['price']); ?></td>
									 <td><?php echo $this->currency->format($p['total']); ?></td>
									 <td><?php echo $this->currency->format($p['pajak']); ?></td>
									 <!--
									 <td></td>
									 <td></td>
									 <td></td>
									 <td></td>
									 -->
								 </tr>
							 <?php
							 }
							 ?>
						 </tbody>
							 
					 </table>
				 </td>
			 </tr>
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
