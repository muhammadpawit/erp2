<?php echo $header; ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>

    </h1>

  </section>

  <section class="content">
    <form id="form" method="POST" action="<?php echo $action; ?>">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Invoice Pembelian</h3>
            <div class="button pull-right">
                <a onclick="$('#form').submit();" ><button type="button" class="btn btn-primary">Simpan Invoice</button></a>
                  <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>
								</div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <table class="table">
                  <tr>
                      <td>Nomor PO:</td>
                      <td><?php echo $permintaan['no_po'] ?></td>
                  </tr>
                  <?php
                  if(empty($permintaan['no_faktur'])){
                  ?>
                  <tr>
                      <td>Tanggal Faktur:</td>
                      <td><input type="text" name="tglfaktur" value="" class="date form-control" readonly></td>
                  </tr>
                  <tr>
                      <td>No. Faktur:</td>
                      <td><input type="text" name="no_faktur" value="" class="form-control"></td>
                  </tr>
                  <tr>
                      <td>Jatuh Tempo:</td>
                      <td><input type="text" name="batasbayar" value="" class="date form-control" readonly></td>
                  </tr>
                  <?php
                }else{
                  ?>
                  <tr>
                      <td>Tanggal Faktur:</td>
                      <td><?php echo date('d/m/y',strtotime($permintaan['tglfaktur']))?></td>
                  </tr>
                  <tr>
                      <td>No. Faktur:</td>
                      <td><?php echo $permintaan['no_faktur']; ?></td>
                  </tr>
                  <tr>
                      <td>Jatuh Tempo:</td>
                      <td><?php echo date('d/m/y',strtotime($permintaan['batasbayar']))?></td>
                  </tr>
                  <?php
                }
                  ?>
                   <tr>
                      <td>Vendor:</td>
                      <td><?php echo $permintaan['name'] ?></td>
                  </tr>

                 <tr>
                   <td>Sub Total:</td>
                   <td><?php echo $this->currency->format($permintaan['sub_total']) ?></td>
                  </tr>
                  <tr>
                    <td>Diskon:</td>
                    <td><?php echo $this->currency->format($permintaan['diskon']) ?></td>
                   </tr>
                   <!--tr>
                     <td>Biaya - Biaya:</td>
                     <td><?php echo $this->currency->format($permintaan['biaya']) ?></td>
                   </tr-->
                  <tr>
                     <td>Pajak:</td>
                     <td><?php echo $this->currency->format($permintaan['pajak']) ?></td>
                 </tr>
                 <tr>
                    <td>Total Pembelian:</td>
                    <td><?php echo $this->currency->format($permintaan['sub_total']-$permintaan['diskon']+$permintaan['pajak']) ?></td>
                </tr>

            </table>
                <table class="table">
                    <thead>
                      <th>Nama Produk</th>
                      <th>Quantity</th>
                      <th>Quantity Terima</th>
                      <th>Harga</th>
                      <th>Pajak</th>
                      <th>Total</th>


                    </thead>
                    <tbody>
                      <?php
                      foreach($products as $p){
                      ?>
                      <tr>
                        <td><?php echo $p['product_name']; ?></td>
                        <td><?php echo $p['quantity']; ?></td>
                        <td><?php echo $p['quantityterima']; ?></td>
                        <td><?php echo $this->currency->format($p['harga']); ?></td>
                        <td><?php echo $this->currency->format($p['ppn']); ?></td>
                        <td><?php echo $this->currency->format(($p['harga']*$p['quantity'])+($p['ppn']*$p['quantity'])); ?></td>
                      </tr>
                      <?php
                      }
                      ?>
                    </tbody>
                </table>

              </div>
            </div>

          </div>
          <div class="box-footer">
            <div class="row">
              <div class="col-md-12">

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
  </section>
</div>
</div>
<script>
$('.sidebar-menu').find('#menu-pembelian').addClass('active');
$('.sidebar-menu').find('#menu-pembelian-kredit').addClass('active');

</script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});

	$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>

<?php echo $footer; ?>
