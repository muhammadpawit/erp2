<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Mom & Bab | Surat Jalan Pengiriman BArang PAmeran <?php echo $transfer['detail']['invoice_no']; ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
	<link rel="stylesheet" href="view/newreq/requires/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="view/newreq/main/AdminLTE.min.css">
	<link rel="stylesheet" href="view/newreq/main/skins/blue.css">
	<link rel="stylesheet" href="view/newreq/requires/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="view/stylesheet/ajaxmask.css" />
  <link rel="stylesheet" type="text/css" href="view/stylesheet/select2.min.css" />
  <link rel="stylesheet" type="text/css" href="view/stylesheet/select2bootstrap.css" />
	<link rel="icon" type="image/png" href="view/newreq/main/images/manager-icon.png">
  <script src="view/newreq/requires/jquery/jquery-1.12.1.min.js"></script>
  <script src="view/newreq/requires/bootstrap/js/bootstrap.min.js"></script>
  <script src="view/newreq/main/app.min.js"></script>
  <script src="view/newreq/main/select2.full.min.js"></script>
  <script src="view/newreq/plugins/slimscroll/jquery.slimscroll.min.js"></script>

  <script src="view/javascript/fs/jquery.timer.js"></script>
  <script src="view/javascript/fs/ajaxmask.js"></script>
  <script src="view/javascript/fs/custom.js"></script>
  <script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-1.8.16.custom.min.js"></script>
  <link type="text/css" href="view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
  <script type="text/javascript" src="view/javascript/jquery/tabs.js"></script>

	<style>
	.table {
    border-bottom:0px !important;
	}
	.table th, .table td {
	    border: 1px !important;
			height:10px;
	}
	.fixed-table-container {
	    border:0px !important;
	}
	</style>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body onload="window.print();">
<div class="wrapper">
  <!-- Main content -->
  <section class="invoice">
    <!-- title row -->
    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">
           Mom & Bab
          <small class="pull-right"><?php echo $transfer['detail']['asal']; ?> >> <?php echo $transfer['detail']['gudang_tujuan']; ?></small>
					<br>
					Surat Jalan Pengiriman Barang Pameran <?php echo $transfer['detail']['invoice_no']; ?>
        </h2>
      </div>
      <!-- /.col -->
    </div>


    <div class="row">
      <div class="col-xs-12 table-responsive">
        <table class="table">
          <thead>
          <tr>
            <th>Produk</th>
            <th>Ukuran</th>
            <th>Rak</th>
            <th>Quantity</th>
            <th>Harga</th>
          </tr>
          </thead>
          <tbody>
						<?php
						foreach($transfer['products'] as $p){
						?>
						<tr>
							<td><?php echo $p['name']; ?></td>
							<td><?php echo $p['option']; ?></td>
							<td><?php echo $p['rak']; ?></td>
							<td><?php echo $p['quantity']; ?></td>
							<td><?php echo $this->currency->format($p['harga_jual']); ?></td>
						</tr>
						<?php
						}
						?>

          </tbody>

        </table>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
      <!-- accepted payments column -->

      <!-- /.col -->
      <div class="col-xs-6">
        <div class="table-responsive">
          <table class="table">
            <tr>
              <th style="width:50%">Total:</th>
              <td><?php echo $this->currency->format($transfer['detail']['total']); ?> / <?php echo $transfer['detail']['qtykirim']; ?>pcs</td>
            </tr>
						<tr>
              <th style="width:50%">Tanggal Diterima:</th>
              <td></td>
            </tr>

          </table>
        </div>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
</body>
</html>
