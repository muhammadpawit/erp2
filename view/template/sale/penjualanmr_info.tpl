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
            <h3 class="box-title no-print">Surat Jalan Penjualan <?php echo $order['no_sj']; ?>
              <br>
              <?php
              if(isset($order['nama'])){
              ?>
              <small>Gudang: <?php echo $order['nama']; ?></small>
              <?php
              }
              ?>
            </h3>
            <div class="button pull-right no-print">

                  <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>
								</div>
          </div>
          <p></p>
          <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <table class="table">
                <tr>
                  <td>Tanggal</td>
                  <td><?php echo date('d/m/y',strtotime($order['date_added']))?></td>
                </tr>
                <tr>
                  <td>No. Surat Jalan</td>
                  <td><?php echo $order['salesorder']; ?></td>
                </tr>
                <tr>
                  <td>Referensi</td>
                  <td><?php echo $order['no_sj']; ?></td>
                </tr>
                <tr>
                  <td>Customer</td>
                  <td><?php echo $order['name']; ?></td>
                </tr>
                <tr>
                  <td>Alamat</td>
                  <td>
                    <?php
                    if(!empty($address['firstname'])){
                    ?>
                    <strong><?php echo $address['firstname']; ?></strong><br>
                    <?php echo $address['address_1'].' '.$address['address_2']; ?><br>
                    <?php echo $address['city'].', '.$address['zone'].', '.$address['country'].', '.$address['postcode']; ?><br>
                    <?php
                    }
                    ?>
                    Phone: <?php echo !empty($address['company_id'])?$address['company_id']:$order['telephone']; ?><br>
                    Email: <?php echo $order['email']; ?>

                  </td>
                </tr>
                <tr>
                  <td>Metode Pengiriman</td>
                  <td><?php echo $order['pengiriman'] == 1?'Diambil':'Diantar'; ?></td>
                </tr>
                <tr>
                  <td>Sales</td>
                  <td><?php echo $order['sales']; ?></td>
                </tr>
                <tr>
                  <td>Sopir/Kendaraan</td>
                  <td><?php echo $order['sopir']; ?>/<?php echo $order['no_pol']; ?></td>
                </tr>
                <tr>
                  <td>Kernet</td>
                  <td><?php
                  $i=1;
                   foreach($order['kernets'] as $k){
                     echo $i.". ".$k['firstname']."<br>";
                     $i++;
                   }
                  ?></td>
                </tr>
                <tr>
                  <td>Subtotal:</td>
                  <td><?php echo $this->currency->format($order['sub_total']); ?></td>
                </tr>
                <tr>
                  <td>Diskon</td>
                  <td><?php echo $this->currency->format($order['diskon']); ?></td>
                </tr>
                <tr>
                  <td>Pajak:</td>
                  <td><?php echo $this->currency->format($order['pajak']); ?></td>
                </tr>
                <tr>
                  <td>Total:</td>
                  <td><?php echo $this->currency->format($order['total']); ?></td>
                </tr>
              </table>
            </div>
            <div class="col-xs-12 table-responsive">
            <table class="table table-bordered">
              <thead>
              <tr>
                <th>Qty + Satuan</th>
                <th>Nama Barang</th>
                <!--<th>Tabung</th>
                <th>Harga Satuan</th>
                <th>Diskon</th>
                <th>Pajak</th>
                <th>Total</th>-->
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

                </td>
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
  </section>
</div>
</div>
<script>
$('.sidebar-menu').find('#menu-penjualan').addClass('active');
$('.sidebar-menu').find('#menu-salesorder').addClass('active');
$(document).on('keydown', function(e) {
    if((e.ctrlKey || e.metaKey) && (e.key == "p" || e.charCode == 16 || e.charCode == 112 || e.keyCode == 80) ){
        alert("Gunakan tombol cetak untuk mencetak surat jalan");
        e.cancelBubble = true;
        e.preventDefault();

        e.stopImmediatePropagation();
    }
});
function cetak(){
  window.print();
}

function cetakjkt(detail){
  ukuran=$("#kertas").val();
  if(ukuran != 1){
    $.ajax({
      url: '<?php echo PRINTER_JKT; ?>printsjfull',
      dataType: 'json',
      method:'POST',
      data:JSON.stringify(detail),
      success: function(json) {
        alert("Surat jalan berhasil dicetak.");


      }
    });
  }else{
    $.ajax({
      url: '<?php echo PRINTER_JKT; ?>printsj',
      dataType: 'json',
      method:'POST',
      data:JSON.stringify(detail),
      success: function(json) {
        alert("Surat jalan berhasil dicetak.");


      }
    });
  }
}

function cetaksby(detail){
  ukuran=$("#kertas").val();
  if(ukuran != 1){
    $.ajax({
      url: '<?php echo PRINTER_SBY; ?>printsjfull',
      dataType: 'json',
      method:'POST',
      data:JSON.stringify(detail),
      success: function(json) {
        alert("Surat jalan berhasil dicetak.");


      }
    });
  }else{
    $.ajax({
      url: '<?php echo PRINTER_SBY; ?>printsj',
      dataType: 'json',
      method:'POST',
      data:JSON.stringify(detail),
      success: function(json) {
        alert("Surat jalan berhasil dicetak.");


      }
    });
  }
}
</script>

<?php echo $footer; ?>
