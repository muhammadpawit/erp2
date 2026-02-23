<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Purchased Order</title>
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
  .ptd { margin-left: 5px; }
  .ptdt { margin-left: 20px; }
  </style>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries 
  
  -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif] --> 
</head>
<body onload="window.print();">
<div class="wrapper">
  <!-- Main content -->
  <section class="invoice">
    <!-- title row -->
    <div class="row page-header">
      <div class="col-xs-2 col-sm-2">
        <!-- <img  class="img-responsive" src="http://erp2.nissonindonesia.com/image/data/logo-nisson-kecil.png.pagespeed.ce.yT-a1GO9Lv.png"> -->
        <img  class="img-responsive" src="<?php echo HTTP_IMAGE . $this->config->get('config_logo'); ?>">
          <!--<small class="pull-right">Date: <?php //echo date('d/m/y',strtotime($order['date_added']))?></small>-->

      </div>
      <div class="col-xs-9 col-sm-10">
        <address>
          <h3><strong><?php echo $this->config->get('config_name'); ?></strong></h3>
          <!-- <?php echo $this->config->get('config_address'); ?><br>
          Email: <?php echo $this->config->get('config_email'); ?> -->
        </address>
      </div>

      <!-- /.col -->
    </div>
    <div class="row">
      <div class="col-md-12 col-xs-12">
        <table border="1" style="border-collapse: collapse !important;width: 100%">
          <tr>
            <td width="50%" valign="top" colspan="2">
                <p class="ptd">
                  <?php echo $this->config->get('config_address'); ?><br>
          Telp.<?php echo $this->config->get('config_telephone'); ?>/<?php echo $this->config->get('config_fax'); ?><br>
          Email: <?php echo $this->config->get('config_email'); ?><br><br>
          NPWP : 31.708.508.2-402.000
                </p>
            </td>
            <td width="50%" valign="top" colspan="3">
              <p class="ptd">
                PO Date   : <?php echo date('d F Y',strtotime($permintaan['date_added'])) ?><br>
                PO Number : <?php echo $permintaan['no_po']; ?><br>
                Tanggal Pengiriman :&nbsp;<?php echo date('d F Y',strtotime($permintaan['tglkirim'])); ?><br>
                Dibuat oleh : 
                <?php echo $namauser['firstname']?>
              </p>
            </td>
          </tr>
          <tr>
            <td colspan="5" align="center"><h4><b>PURCHASE ORDER</b></h4></td>
          </tr>
          <tr>
            <td width="50%" valign="top" colspan="2">
             <p class="ptd">
                Pemasok : <br><?php echo $permintaan['name']; ?><br>
                <?php echo $permintaan['alamatvendor']; ?><br>
             </p>
            </td>
            <td width="50%" valign="top" colspan="3">
              <p class="ptd">
                Kirim Kepada : <br>
                <?php 
                  $gudang = $permintaan['gudang_id']; 
                  if($gudang==1)
                  {
                    echo "PT.Nisson Indonesia<br>Jl. Pembangunan III no. 8 Neglasari Kota Tangerang, Banten 15121";
                  }
                  else if($gudang==3)
                  {
                    echo "PT.Hanson Indonesia<br>Jl. Kenjeran 185-187, Gading, Tambaksari, Kota SBY, Jawa Timur 60134";
                  }
                  else
                  {
                    echo "";
                  }
                ?>
              </p> 
            </td>
          </tr>
          <tr align="center" style="font-weight: bold;">
            <td>NO</td>
            <td>DESCRIPTION</td>
            <td>QUANTITY</td>
            <!--<td>UNIT PRICE</td>
            <td>VALUE</td>-->
          </tr>
          <tr>
            <td colspan="5">
          <?php
            foreach($products as $p){
          ?>              
              <div class="row">
                <div class="col-xs-6">
                  <div class="row">
                    <div class="col-xs-2 text-center">
                      <?php echo $no++ ?>
                    </div>
                    <div class="col-xs-10">
                      <p class="ptdt"><?php echo $p['product_name']; ?></p>
                    </div>
                  </div>
                </div>
                <div class="col-xs-6">
                  <div class="row">
                    <div class="col-xs-4">
                      <center><?php echo $p['quantity']; ?></center>
                    </div>
                    <div class="col-xs-4">
                      <span class="pull-right"><?php //echo $this->currency->format($p['harga']); ?>&nbsp;</span>
                    </div>
                    <div class="col-xs-4">
                      <span class="pull-right"><?php //echo $this->currency->format($p['harga']*$p['quantity']); ?>&nbsp;</span>
                    </div>
                  </div>
                </div>
              </div>

          <?php } ?>
              <br><br><br><br><br><br><br><br><br><br><br><br>
            </td>
          </tr>
          <!-- <?php
            //foreach($products as $p){
          ?>
            <tr>
              <td align="center"></td>
              <td>&nbsp;&nbsp;<?php //echo $p['product_name']; ?></td>
              <td align="center"><?php //echo $p['quantity']; ?></td>
              <td align="right"><?php //echo $this->currency->format($p['harga']); ?>&nbsp;</td>
              <td align="right"><?php //echo $this->currency->format($p['harga']*$p['quantity']); ?>&nbsp;</td>
            </tr>
          <?php
            //}
          ?> -->          
          <!-- <tr>
            <td colspan="5" height="40"></td>
          </tr>
          <tr> -->
            <td colspan="2" rowspan="6" valign="top">&nbsp;No.Permintaan Pembelian<br><b>&nbsp;<?php echo $permintaan['no_surat']?></b><br>
              &nbsp;Disetujui oleh : <?php echo $disetujui['firstname']?>
            </td>
          </tr>
          <tr>
            <td colspan="3">&nbsp;SUB TOTAL VALUE <span class="pull-right"><?php //echo $this->currency->format($permintaan['sub_total']); ?>&nbsp;</span></td>
          </tr>
          <tr>
            <td colspan="3">&nbsp;PPN <span class="pull-right"><?php //echo $this->currency->format($permintaan['pajak']); ?>&nbsp;</span></td>
          </tr>
          <tr>
            <td colspan="3">&nbsp;TOTAL VALUE <span class="pull-right"><?php //echo $this->currency->format($permintaan['total_pembelian']); ?>&nbsp;</span></td>
          </tr>
          <tr>
            <td colspan="3">&nbsp;AMOUNT<br>&nbsp;<i><?php //echo $this->terbilang($permintaan['total_pembelian'])?> rupiah</i></td>
          </tr>
          <tr>
            <td colspan="3">&nbsp;TERM OF PAYMENT<br>&nbsp;<i><?php //echo $permintaan['keterangan_pembayaran']; ?></i></td>
          </tr>        
        </table>
        <p class="ptd">
          <br>
          <b>* PURCHASE ORDER INI TELAH DI SETUJUI SECARA ELEKTRONIK DAN TIDAK DI PERLUKAN TANDA TANGAN</b>
        </p>
      </div>
    </div>
<!--     <div class="row">
      <div class="col-xs-12 table-responsive">
        <table class="table">
          <thead>
          <tr>
            <th>Nama Produk</th>
            <th>Quantity</th>
            <th>Harga</th>
            <th>Pajak</th>
            <th>Total</th>
          </tr>
          </thead>
          <tbody>
            <?php
            //foreach($products as $p){
            ?>
            <tr>
              <td><?php //echo $p['product_name']; ?></td>
              <td><?php //echo $p['quantity']; ?></td>
              <td><?php //echo $this->currency->format($p['harga']); ?></td>
              <td><?php //echo $this->currency->format($p['ppn']); ?></td>
              <td><?php //echo $this->currency->format(($p['harga']*$p['quantity'])+($p['ppn']*$p['quantity'])); ?></td>
            <?php
           // }
            ?>

          </tbody>

        </table>
      </div>
    </div> -->
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
    <div class="row">

      <div class="col-xs-6">
        <div>
          * PO ini tidak memerlukan tanda tangan
        </div>
      </div>

    </div-->
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
</body>
</html>
