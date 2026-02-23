<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Delivery Order</title>
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
        <div class="col-xs-3 col-sm-2 no-print">
          <img class="img-responsive" src="<?php echo HTTP_IMAGE . $this->config->get('config_logo'); ?>">
            <!--<small class="pull-right">Date: <?php echo date('d/m/y',strtotime($order['date_added']))?></small>-->

        </div>
        <div class="col-xs-4 col-sm-4 invoice-col">
          <address>
            <span class="compname"><strong><?php echo $this->config->get('config_name'); ?></strong></span><br>
            <?php echo $this->config->get('config_address'); ?><br>
            Email: <?php echo $this->config->get('config_email'); ?>
          </address>
        </div>
        <div class="col-xs-5 text-right">
          <strong>DELIVERY ORDER</strong><br>
          <small>No: <?php echo $order['no_do']; ?>
          
          </small>
        </div>
        <!-- /.col -->
      </div>
    <div class="row invoice-info">

        <!-- /.col -->
        <div class="col-xs-6 ">

          <b>Metode Pengiriman:</b> <?php echo $order['pengiriman'] == 1?'Diambil':'Diantar'; ?><br>
         
         
           <strong>No. Surat Jalan: </strong><br><?php echo $order['suratjalan']; ?>
        </div>
        <!-- /.col -->
        <div class="col-xs-6 ">
           <b>Tanggal:</b> <?php echo date('d/m/y',strtotime($order['date_added']))?><br>
          <b>Kendaraan:</b> <?php echo $order['no_pol']; ?><br>
          <b>Sopir:</b> <?php echo $order['sopir']; ?><br>
          <b>Kernet:</b><br>
          <?php echo $order['kernet1'].', '.$order['kernet2'].', '.$order['kernet3']; ?>

        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <p></p>

    <div class="row">
      <div class="col-xs-12 table-responsive">
        <table class="table">
          <thead>
          <tr>
            <th>No. Tabung</th>
            <th>Keterangan</th>
            <th>Nama Customer</th>
            
          </tr>
          </thead>
          <tbody>
            <?php
            foreach($tabungs as $p){
            ?>
            <tr>
              <td>
                <?php echo $p['no_tabung']; ?><br>
                
              </td>
              <td>
                <?php echo $p['keterangan']; ?><br>
                
              </td>
            <td>
               
            </td>

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

    <!--div class="row">

      <div class="col-xs-6">
        <div class="table-responsive">
          <table class="table">
            <tr>
              <th style="width:50%">Bagian Pembelian</th>
              <th>Pimpinan</th>
            </tr>

          </table>
        </div>
      </div>

    </div>
    
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
</body>
</html>
