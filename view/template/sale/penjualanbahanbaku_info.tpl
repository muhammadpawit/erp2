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
            <!--<h3 class="box-title no-print">Surat Jalan Penjualan <?php echo $order['no_sj']; ?>
              <br>
              <?php
              if(isset($order['nama'])){
              ?>
              <small>Gudang: <?php echo $order['nama']; ?></small>
              <?php
              }
              ?>
            </h3>-->
            <div class="button pull-right no-print">

                  <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>
								</div>
          </div>
          <p></p>
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
          <strong>SURAT JALAN</strong><br>
          <small>No: <?php echo $order['no_sj']; ?></small>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">

        <!-- /.col -->
        <div class="col-xs-8 ">
          <strong>Kepada: <?php echo $order['name']; ?></strong><br>
          <address>
            <!--
            <strong><?php echo $address['firstname']; ?></strong><br>-->
            <strong>Alamat:</strong><br>
            <?php echo $address['address_1'].' '.$address['address_2']; ?><br>
            <?php echo $address['city'].', '.$address['zone'].', '.$address['country'].', '.$address['postcode']; ?><br>
            Phone: <?php echo !empty($address['company_id'])?$address['company_id']:$order['telephone']; ?><br>
            Email: <?php echo $order['email']; ?>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-xs-4 ">
          <!--<b>No. Surat Jalan:</b> <?php echo $order['no_sj']; ?><br>
          <b>Metode Pengiriman:</b> <?php echo $order['pengiriman'] == 1?'Diambil':'Diantar'; ?><br>-->
          <b>Tanggal:</b> <?php echo date('d/m/y',strtotime($order['date_added']))?><br>
          <b>Sales:</b> <?php echo $order['sales']; ?><br>
          <b>Kendaraan:</b> <?php echo $order['no_pol']; ?><br>
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
      <p></p>
      <div class="row">
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
                <small><?php echo $p['no_tabung']; ?></small>
              </td>
              <!--<td><?php echo $p['no_tabung']; ?></td>
              <td><?php echo $this->currency->format($p['price']); ?></td>
              <td><?php echo $this->currency->format($p['diskon']); ?></td>
              <td><?php echo $this->currency->format($p['pajak']); ?></td>
              <td><?php echo $this->currency->format($p['total']); ?></td>-->
            </tr>
            <?php
            }
            ?>

            </tbody>
          </table>
        </div>
        <!-- /.col -->
      </div>
      <div class="row">
        <div class="col-xs-6">

          <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
            Keterangan <br>
            Metode Pengiriman: <?php echo $order['pengiriman'] == 1?'Diambil':'Diantar'; ?><br>
            Referensi: <?php echo $order['salesorder']; ?>
          </p>
        </div>
      </div>

      <div class="row print-only">
        <div class="col-xs-4">
          Diterima Oleh
          <br><br><br><br>
          (_______________)
        </div>
        <div class="col-xs-4">
          Gudang
          <br><br><br><br>
          (_______________)
        </div>
        <div class="col-xs-4">
          Distribusi
          <br><br><br><br>
          (_______________)
        </div>
      </div>
      <p></p>
      <div class="row print-only">
        <div class="col-xs-12">

          <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
            Klaim Hanya dapat dilayani dalam waktu 2x24 jam setelah barang diterima<br>
            Tgl. Cetak: <?php echo date('d/m/y H:i:s'); ?>
          </p>
        </div>
      </div>


      <!-- this row will not appear when printing -->
      <?php
      if($order['cetak'] < 1){
      ?>
      <div class="row no-print">
        <div class="col-xs-12">
            <table class="table">
              <tr>
                <td>Ukuran Kertas</td>
                <td>
                  <select id="kertas" class="form-control">
                    <option value="1">1/2 A4</option>
                    <option value="2">Full A4</option>
                  </select>
                </td>
              </tr>
            </table>
        </div>
      </div>
      <div class="row no-print">
        <div class="col-xs-12 text-right">
            <a onclick='cetakjkt(<?php echo json_encode($fulldetail); ?>)' class="btn btn-default"><i class="fa fa-print"></i> Cetak Tangerang</a>
            <a onclick='cetaksby(<?php echo json_encode($fulldetail); ?>)' class="btn btn-default"><i class="fa fa-print"></i> Cetak Surabaya</a>

        </div>
      </div>
      <?php
      }
      ?>
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
