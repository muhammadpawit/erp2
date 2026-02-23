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
          <div class="box-header with-border no-print">
            <h3 class="box-title">Tanda Terima Pengembalian Tabung Kosong <?php echo $order['no_so']; ?></h3>
            <div class="button pull-right">
                  <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>
								</div>
          </div>
  <section class="invoice">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
          <img src="<?php echo HTTP_IMAGE . $this->config->get('config_logo'); ?>">
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
            <?php
            if(!empty($address['address_1'])){
            ?>
            <?php echo $address['address_1'].' '.$address['address_2']; ?><br>
            <?php echo $address['city'].', '.$address['zone'].', '.$address['country'].', '.$address['postcode']; ?><br>
            <?php
          } 
            ?>
            Phone: <?php echo !empty($address['company_id'])?$address['company_id']:$order['telephone']; ?><br>
            Email: <?php echo $order['email']; ?>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          <b>Metode Pengiriman:</b> <?php echo $order['pengiriman'] == 1?'Diantar':'Diambil'; ?><br>
          <b>No. Return Tabung:</b> <?php echo $order['no_so']; ?><br>
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
                     <th>Produk</th>
                     <th>Qty</th>
                    <th>Tutup</th>
                    <th>Keterangan</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  
                  foreach($products as $p){
                  ?>
                  <tr>
                     <td>
                      <?php echo $p['name']; ?><br>

                    </td>
                   <td><?php echo $p['quantity']; ?></td>
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
          <a onclick="print()" class="btn btn-default"><i class="fa fa-print"></i> Print</a>

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
