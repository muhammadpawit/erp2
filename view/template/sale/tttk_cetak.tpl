<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>TTTK Tabung MP</title>
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
          <img style="width:100px" src="<?php echo HTTP_IMAGE . $this->config->get('config_logo'); ?>">  Tanda Terima Tabung Kosong Milik Perusahaan
            <small class="pull-right">Date: <?php echo date('d/m/y',strtotime($order['date_added']))?></small>
          </h2>

         
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
          <address>
            <strong><?php echo $this->config->get('config_name'); ?></strong><br>
            <?php echo $this->config->get('config_address'); ?><br>
            Email: <?php echo $this->config->get('config_email'); ?>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          Customer
          <address>
            <strong><?php echo $order['name']; ?></strong><br>
            <strong><?php echo $address['firstname']; ?></strong><br>
            <?php echo $address['address_1'].' '.$address['address_2']; ?><br>
            <?php echo $address['city'].', '.$address['zone'].', '.$address['country'].', '.$address['postcode']; ?><br>
            Phone: <?php echo !empty($address['company_id'])?$address['company_id']:$order['telephone']; ?><br>
            Email: <?php echo $order['email']; ?>
          </address>
        </div>
        <div class="col-sm-4 invoice-col">
          <b>Metode Pengiriman:</b> <?php echo $order['pengiriman'] == 1?'Diambil':'Diantar'; ?><br>
          <b>No. TTTK:</b> <?php echo $order['no_so']; ?>

        </div>
        <!-- /.col -->
        
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- Table row -->
      <div class="row">
        <div class="col-xs-12 table-responsive">
          <table class="table table-striped">
            <thead>
            <tr>
              <th>Tabung ID</th>
              <th>No. Tabung</th>
              <th>Pemilik</th>
              <th>Ukuran Tabung</th>
              <th>Tutup</th>
              <th>Keterangan</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($products as $p){
            ?>
            <tr>
              <td><?php echo $p['tabung_id']; ?></td>
              <td>
                <?php echo $p['no_tabung']; ?><br>

              </td>
              <td><?php echo $p['pemilik'] == 1?'MP':'MR'; ?></td>
              <td><?php echo $p['name']; ?></td>
              <td><?php echo $p['tutup'] == 1?'Dengan Tutup':'Tanpa Tutup'; ?></td>
              <td><?php echo $p['keterangan']; ?></td>
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
      <p></p>
      <p></p>
      <div class="row">
      <!-- accepted payments column -->

      <!-- /.col -->
      <div class="col-xs-6">
        <div class="table-responsive">
          <table class="table">
            <tr>
              <th style="width:50%">Pengirim</th>
              <th>Penerima</th>
            </tr>
            <tr>
              <td>
              <p></p>
              <p></p>
              <p></p>
              <p>(............)</p>
              </td>
              <td>
              <p></p>
              <p></p>
              <p></p>
              <p>(............)</p>
              </td>
            </tr>

          </table>
        </div>
      </div>
      <!-- /.col -->
    </div>

      
    </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
</body>
</html>
