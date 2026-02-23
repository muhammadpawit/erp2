<?php echo $header;
//print_r($fulldetail);
?>
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
             <h3 class="box-title">Return Penjualan No <?php echo $order['no_return']; ?></h3>
            
            <div class="button pull-right no-print">

                  <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>
								</div>
          </div>
          <p></p>
          <section class="invoice">
      <!-- title row -->
     
      <!-- info row -->
      <div class="row">

        <!-- /.col -->
        <div class="col-xs-8 ">

         <table class="table">
                <tr>
                  <td>No. Retur</td>
                  <td><?php echo $order['no_return']; ?></td>
                </tr>
                <tr>
                  <td>Tanggal</td>
                  <td><?php echo date('d/m/Y',strtotime($order['date_added'])); ?></td>
                </tr>
                <tr>
                  <td>Gudang</td>
                  <td><?php echo $order['nama']; ?></td>
                </tr>
                <tr>
                  <td>Customer</td>
                  <td><?php echo $order['name']; ?></td>
                </tr>
                
                <tr>
                  <td>Keterangan:</td>
                  <td><?php echo $order['keterangan']; ?></td>
                </tr>
                <tr>
                  <td>Sub Total</td>
                  <td><?php echo $this->currency->format($order['sub_total']); ?></td>
                </tr>
                <tr>
                  <td>Pajak:</td>
                  <td><?php echo $this->currency->format($order['pajak']); ?></td>
                </tr>
                
                <tr>
                  <td>Total:</td>
                  <td><?php echo $this->currency->format($order['total']); ?></td>
                </tr>
                <tr>
                  <td>Total Refund:</td>
                  <td><?php echo $this->currency->format($order['totalrefund']); ?></td>
                </tr>
              </table>
          
        </div>
        <!-- /.col -->
       
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <p></p>
      <div class="row">
        <div class="col-xs-12 table-responsive">
          <table class="table table-bordered">
            <thead>
            <tr>
              <th>Qty Return</th>
              <th>Nama Barang</th>
              <th>No. Sales Order</th>
              
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($products as $p){
            ?>
            <tr>
              <td><?php echo $p['quantity']; ?> <?php echo $p['namasatuan']; ?></td>
              <!--<td><?php echo $p['product_id']; ?></td>-->

              <td>
                <?php echo $p['name']; ?><br>
                <small><?php echo $p['no_tabung']; ?></small>
              </td>
              
                <td><?php echo $p['nomor_so']; ?></td>
            </tr>
            <?php
            }
            ?>

            </tbody>
          </table>
        </div>
        <!-- /.col -->
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
$('.sidebar-menu').find('#menu-return-penjualan').addClass('active');

</script>

<?php echo $footer; ?>
