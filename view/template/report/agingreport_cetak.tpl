<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $language; ?>" xml:lang="<?php echo $language; ?>">
<head>
<title>Aging Report</title>
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
<body onload="print()">

<div id="content">

  <h2>Aging Report <?php echo $this->config->get('config_name'); ?></h2>

  <h3>Tanggal Cetak</b> <?php echo date('d F Y',time()); ?></h3>

 <table class="store">
	 <table class="table table-bordered">
		 <thead>
			 <tr>
				 <th style="width:20%">
					 Nama Customer
				 </th>
				 <th style="width:10%">
					No. Invoice
				</th>
				<th style="width:5%">
				 Tgl. Invoice
				 </th>
				 <th style="width:5%">
					Jatuh Tempo
				</th>
				 <th style="width:5%">
					 Total Tagihan
				 </th>
         <th style="width:5%">
					 Deposit
				 </th>
				 <th style="width:5%">
					 Sisa Harus Bayar
				 </th>
				 <th style="width:5%">t.Hari</th>
				 <th style="width:5%">0-30</th>
				 <th style="width:5%">31-60</th>
				 <th style="width:5%">61-90</th>
				 <th style="width:5%">91-120</th>
				 <th style="width:5%"> >120</th>
			 </tr>
		 </thead>
		 <tbody>


			 <?php if ($penjualans) { ?>
			 <?php foreach ($penjualans as $product) { ?>
			 <tr class="invoice" data-id="<?php echo $product['customer_id']; ?>" data-faktur="<?php echo $product['name']; ?>" id="list-invoice-<?php echo $product['id']; ?>" >
				 <td style="background-color:#ccc"  class="left"><?php echo $product['name']; ?>
				 </td>
				 <td style="background-color:#ccc" ></td>
				 <td style="background-color:#ccc" ></td>
				 <td style="background-color:#ccc" ></td>
				 <td style="background-color:#ccc" ><?php echo $product['totaltagihan']; ?></td>
         <td style="background-color:#ccc" ><?php echo $product['deposit']; ?></td>
				 <td style="background-color:#ccc" ><?php echo $product['sisabayar']; ?></td>
				 <td style="background-color:#ccc" ></td>
				 <td style="background-color:#ccc" ><?php echo $product['sisabayar30']; ?></td>
				 <td style="background-color:#ccc" ><?php echo $product['sisabayar60']; ?></td>
				 <td style="background-color:#ccc" ><?php echo $product['sisabayar90']; ?></td>
				 <td style="background-color:#ccc" ><?php echo $product['sisabayar120']; ?></td>
				 <td style="background-color:#ccc" ><?php echo $product['sisabayar121']; ?></td>
				 <?php
         $deposit=$product['plaindeposit'];
					 foreach($product['invoice'] as $p){
						 $totalhari=0;
             $dateadd=strtotime($filter_tanggal);
             $tglinvoice=strtotime($p['date_added']);
						 $jatuhtempo=strtotime($p['jatuhtempo']);

						 if($filter_type == 1){
							 $selisih=$dateadd - $jatuhtempo;
							 $totalhari=floor($selisih / (60 * 60 * 24));
						 }else{
							 $selisih=$dateadd - $tglinvoice;
							 $totalhari=floor($selisih / (60 * 60 * 24));
						 }
             $tagihan=$p['totaltagihan']-$p['totalbayar'];
             if($deposit > 0){

               if($tagihan >= $deposit){
                 $tagihan -= $deposit;
                 $deposit=0;
               }else{
                 $deposit -= $tagihan;
                 $tagihan=0;
               }
             }
				 ?>
				 <tr >
					 <td></td>
					 <td><?php echo $p['no_faktur']; ?></td>
					 <td><?php echo date('d/m/y',strtotime($p['date_added'])); ?></td>
					 <td><?php echo date('d/m/y',strtotime($p['jatuhtempo'])); ?></td>
					 <td><?php echo $this->currency->format($p['totaltagihan']); ?></td>
           <td></td>
					 <td><?php echo $this->currency->format($tagihan); ?></td>

					 <td><?php echo $totalhari; ?></td>
					 <td><?php
						 if($totalhari <= 30){
							 echo $this->currency->format($tagihan);
						 }
							?></td>
					<td><?php
						if($totalhari > 30 & $totalhari <=60){
							echo $this->currency->format($tagihan);
						}
						 ?></td>
					 <td><?php
						 if($totalhari > 60 & $totalhari <=90){
							 echo $this->currency->format($tagihan);
						 }
							?></td>
					<td><?php
						if($totalhari > 90 & $totalhari <=120){
							echo $this->currency->format($tagihan);
						}
						 ?></td>
					 <td><?php
						 if($totalhari > 120){
							 echo $this->currency->format($tagihan);
						 }
							?></td>
				 </tr>

			 <?php
			 }
			 ?>

			 </tr>

			 <?php } ?>
			 <tr>
				 <td style="background-color:#ccc" ><b>Total</b></td>
				 <td style="background-color:#ccc" ></td>
				 <td style="background-color:#ccc" ></td>
				 <td style="background-color:#ccc" ></td>
				 <td style="background-color:#ccc" ><b><?php echo $this->currency->format($jumlah['totaltagihan']); ?></b></td>
         <td style="background-color:#ccc" ><b><?php echo $this->currency->format($jumlah['totaldeposit']); ?></b></td>
				 <td style="background-color:#ccc" ><b><?php echo $this->currency->format($jumlah['total']); ?></b></td>
				 <td style="background-color:#ccc" ></td>
				 <td style="background-color:#ccc" ><b><?php echo $jumlah30; ?></b></td>
				 <td style="background-color:#ccc" ><b><?php echo $jumlah60; ?></b></td>
				 <td style="background-color:#ccc" ><b><?php echo $jumlah90; ?></b></td>
				 <td style="background-color:#ccc" ><b><?php echo $jumlah120; ?></b></td>
				 <td style="background-color:#ccc" ><b><?php echo $jumlah121; ?></b></td>
			 </tr>
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
