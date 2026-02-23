<?php echo $header; ?>
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
            <h3 class="box-title">Tanda Terima Tabung Kosong <?php echo $order['no_so']; ?>
            <?php 
            echo $order['status']==3?'<span class="badge bg-red">DIBATALKAN</span>':'';
            ?>
            </h3>
            
            <div class="button pull-right">
                  <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>
								</div>
          </div>
          <section class="invoice">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
          <img src="<?php echo HTTP_IMAGE . $this->config->get('config_logo'); ?>" height="250">
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
          To
          <address>
            <strong><?php echo $order['name']; ?></strong><br>
            <strong><?php echo $address['firstname']; ?></strong><br>
            <?php echo $address['address_1'].' '.$address['address_2']; ?><br>
            <?php echo $address['city'].', '.$address['zone'].', '.$address['country'].', '.$address['postcode']; ?><br>
            Phone: <?php echo !empty($address['company_id'])?$address['company_id']:$order['telephone']; ?><br>
            Email: <?php echo $order['email']; ?>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          <b>Metode Pengiriman:</b> <?php echo $order['pengiriman'] == 1?'Diambil':'Diantar'; ?><br>
          <b>Sales:</b> <?php echo $order['sales']; ?><br>
          <b>Sopir:</b> <?php echo $order['sopir']; ?><br>
          <b>Kernet:</b><br>
          <?php
          $i=1;
           foreach($order['kernets'] as $k){
             echo $i.". ".$k['firstname']."<br>";
             $i++;
           }
          ?>

        </div>
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

      <div class="row">
        <!-- accepted payments column -->
        <div class="col-xs-6">

        </div>
        <!-- /.col -->
        <div class="col-xs-6">
          <p class="lead"></p>

          <!--div class="table-responsive">
            <table class="table">
              <tr>
                <th style="width:50%">Total Qty MP:</th>
                <td><td><?php echo $order['tabungmp']; ?></td></td>
              </tr>
              <tr>
                <th style="width:50%">Total Qty MR:</th>
                <td><td><?php echo $order['tabungmr']; ?></td></td>
              </tr>

            </table>
          </div>
        </div-->
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- this row will not appear when printing -->
      <div class="row no-print">
        <div class="col-xs-12">
          <a href="<?php echo $invoice; ?>" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
        </div>
      </div>
    </section>
          <div class="box-footer">
            <div class="row">
              <div class="col-md-12">

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
</div>
<script>
$('.sidebar-menu').find('#menu-penjualan').addClass('active');
$('.sidebar-menu').find('#menu-tttk').addClass('active');

</script>

<?php echo $footer; ?>
