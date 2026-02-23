<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $language; ?>" xml:lang="<?php echo $language; ?>">
<head>
<title>Daftar Produk Gudang</title>
<base href="<?php echo $base; ?>" />
<link rel="stylesheet" type="text/css" href="view/stylesheet/invoice.css" />
<style>
.store > tbody > tr >td{
	//padding:5px;
}
.store > thead > tr >td{
	padding:10px;
	text-align:center;
	font-weight:bold;
}
.qty{
	text-align:center;
}
@page {
        size: auto;
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

  <h2><?php echo $heading_title; ?></h2>

  <h3>Tanggal Cetak</b> <?php echo date('d F Y',time()); ?></h3>

 <table class="store">
        <thead>
          <tr>
            <td class="left">#</td>
            <td class="left"><?php echo $nama; ?></td>
            <td class="left">Nama Produk</td>
            <td class="right">Quantity</td>


          </tr>
        </thead>
        <tbody>
          <?php if ($products) {
		  $tot=0;
		  $i=1;
		  ?>
          <?php foreach ($products as $product) { ?>
          <tr>
            <td class="left"><?php echo $i; ?></td>
            <td class="left"><?php echo $product['nama']; ?></td>
            <td class="left"><?php echo $product['product_name']; ?></td>
            <td class="qty"><?php echo $product['qty']; ?></td>



          </tr>
		  <?php
		  if(!empty($product['options'])){
		  ?>
		  <tr>
            <td class="left"></td>
            <td class="left"></td>
            <td class="left">Ukuran/Warna:
			<?php
			foreach($product['options'] as $ov){
				echo $ov['name']." (".$ov['quantity']."), ";
			}
			?>
			</td>
            <td class="qty"></td>



          </tr>
		  <?php
		  }
		  ?>
          <?php
		  $tot += $product['qty'];
		  $i++;
		  } ?>
		  <tr>
				<td class="left" colspan="3" ><b>Total</b></td>

				<td class="qty"><?php echo $tot; ?></td>

		  </tr>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="6"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>

</div>

</div>
<?php //}} ?>

</body>
</html>
